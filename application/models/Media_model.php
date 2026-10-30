<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model
{
    protected $table = 'media';

    public function upload($field, $prefix = "")
    {
        if (!isset($_FILES[$field])) {
            throw new Exception('Upload field tidak ditemukan: ' . $field);
        }

        $file = $_FILES[$field];

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception('Tidak ada file yang dipilih.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Invalid uploaded file.');
        }

        $this->validateUpload($file);

        $mime = $this->detectMimeType($file['tmp_name']);
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $dimension = $this->detectDimensions($file['tmp_name']);
        $checksum = $this->generateChecksum($file['tmp_name']);

        $duplicate = $this->existsChecksum($checksum);

        if ($duplicate) {

            $this->db
                ->where('id', $duplicate->id)
                ->update('media', [
                    'used_count' => ((int) $duplicate->used_count) + 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return (int) $duplicate->id;
        }

        $blob = file_get_contents($file['tmp_name']);

        if ($blob === false) {
            throw new Exception('Gagal membaca file upload.');
        }
        $perusahaan = url_title($_SESSION['perusahaan'], '_', TRUE);
        $data = [
            'uuid' => function_exists('uuid_create') ? uuid_create(UUID_TYPE_RANDOM) : bin2hex(random_bytes(16)),
            'original_filename' => $file['name'],
            'stored_filename' => uniqid('media_' . $perusahaan . "_" . $prefix . '_', true),
            'mime_type' => $mime,
            'extension' => $extension,
            'file_size' => filesize($file['tmp_name']),
            'file_content' => $blob,
            'width' => $dimension['width'],
            'height' => $dimension['height'],
            'checksum' => $checksum,
            'used_count' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('media', $data);

        if (!$this->db->affected_rows()) {
            throw new Exception('Gagal menyimpan media.');
        }

        return (int) $this->db->insert_id();
    }

    protected function validateUpload(array $file)
    {
        $maxSize = 10 * 1024 * 1024;

        if ($file['size'] <= 0) {
            throw new Exception('Ukuran file tidak valid.');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('Ukuran file melebihi 10 MB.');
        }

        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp',
            'svg',
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'ppt',
            'pptx',
            'zip',
            'rar'
        ];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new Exception('Extension file tidak diizinkan.');
        }
    }

    protected function detectMimeType($tmp)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        return $mime;
    }

    protected function detectDimensions($tmp)
    {
        $size = @getimagesize($tmp);

        if (!$size) {
            return ['width' => 0, 'height' => 0];
        }

        return [
            'width' => $size[0],
            'height' => $size[1]
        ];
    }

    protected function generateChecksum($tmp)
    {
        return hash_file('sha256', $tmp);
    }

    protected function existsChecksum($checksum)
    {
        return $this->db
            ->where('checksum', $checksum)
            ->get('media')
            ->row();
    }

    protected function getUploadErrorMessage($code)
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi upload_max_filesize.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi MAX_FILE_SIZE.',
            UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file dipilih.',
            UPLOAD_ERR_NO_TMP_DIR => 'Temporary directory tidak ditemukan.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension PHP.'
        ];

        return $errors[$code] ?? 'Upload gagal.';
    }

    public function find($key)
    {
        // Berdasarkan ID
        if (is_numeric($key)) {
            $media = $this->db
                ->where('id', (int) $key)
                ->get($this->table)
                ->row();

            if ($media) {
                return $media;
            }
        }

        // Berdasarkan stored_filename
        $stored_filename = pathinfo(urldecode($key), PATHINFO_FILENAME);

        return $this->db
            ->where('stored_filename', $stored_filename)
            ->get($this->table)
            ->row();
    }

    public function delete($mediaId)
    {
        $media = $this->find($mediaId);

        if (!$media) {
            return false;
        }

        if ((int) $media->used_count > 1) {

            return $this->db
                ->where('id', $mediaId)
                ->update('media', [
                    'used_count' => ((int) $media->used_count) - 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }

        return $this->db
            ->where('id', $mediaId)
            ->delete('media');
    }

    public function getFilenameMap(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $rows = $this->db
            ->select('id, stored_filename, extension')
            ->where_in('id', $ids)
            ->get($this->table)
            ->result();

        $map = [];

        foreach ($rows as $row) {
            $map[$row->id] = $row->stored_filename . '.' . $row->extension;
        }

        return $map;
    }

    /**
     * ========================================
     * MEDIA LIBRARY FUNCTIONS (NEW)
     * ========================================
     */

    /**
     * Mengambil media dengan pagination dan search.
     *
     * @param int $page
     * @param int $per_page
     * @param string|null $search
     * @return array
     */
    public function get_paginated($page = 1, $per_page = 9, $search = null)
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $per_page;

        $this->db->select('id, uuid, original_filename, mime_type, file_size, width, height, used_count, created_at');
        $this->db->from($this->table);

        if (!empty($search)) {
            $this->db->like('original_filename', $search, 'both');
        }

        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($per_page, $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Menghitung total media dengan optional search filter.
     *
     * @param string|null $search
     * @return int
     */
    public function count_total($search = null)
    {
        $this->db->from($this->table);

        if (!empty($search)) {
            $this->db->like('original_filename', $search, 'both');
        }

        return $this->db->count_all_results();
    }

    /**
     * Mengambil media berdasarkan ID (with file_content).
     *
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('id', (int) $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Increment used_count untuk tracking.
     *
     * @param int $id
     * @return bool
     */
    public function increment_used_count($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->set('used_count', 'used_count+1', FALSE)
            ->update($this->table);
    }

    /**
     * Decrement used_count untuk tracking.
     *
     * @param int $id
     * @return bool
     */
    public function decrement_used_count($id)
    {
        $this->db->where('id', (int) $id);
        $this->db->where('used_count > 0');

        return $this->db
            ->set('used_count', 'used_count-1', FALSE)
            ->update($this->table);
    }
}
