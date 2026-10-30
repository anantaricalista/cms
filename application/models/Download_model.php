<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download_model extends CI_Model
{
    protected $table = 'downloads';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db
            ->select('downloads.*, media.mime_type, media.original_filename, media.extension, media.file_size')
            ->from($this->table)
            ->join('media', 'media.id = downloads.media_id', 'left')
            ->order_by('downloads.created_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_published()
    {
        return $this->db
            ->select('downloads.*, media.mime_type, media.original_filename, media.extension, media.file_size')
            ->from($this->table)
            ->join('media', 'media.id = downloads.media_id', 'left')
            ->where('downloads.status', 'published')
            ->order_by('downloads.published_at', 'DESC')
            ->get()
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->select('downloads.*, media.mime_type, media.original_filename, media.extension, media.file_size')
            ->from($this->table)
            ->join('media', 'media.id = downloads.media_id', 'left')
            ->where('downloads.id', (int) $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    public function toggle_status($id)
    {
        $download = $this->get_by_id($id);

        if (!$download) {
            return false;
        }

        $status = ($download['status'] === 'published')
            ? 'draft'
            : 'published';

        $data = [
            'status' => $status
        ];

        if (
            $status === 'published' &&
            empty($download['published_at'])
        ) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }

    public function increment_download_count($id)
    {
        return $this->db
            ->set('download_count', 'download_count + 1', false)
            ->where('id', (int) $id)
            ->update($this->table);
    }
}