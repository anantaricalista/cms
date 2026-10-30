<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_message_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'contact_messages';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh pesan kontak.
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('created_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil pesan kontak berdasarkan ID.
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
     * Mengambil pesan kontak berdasarkan UUID.
     *
     * @param string $uuid
     * @return array|null
     */
    public function get_by_uuid($uuid)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('uuid', $uuid)
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Mengambil pesan kontak yang belum dibaca.
     *
     * @param int $limit
     * @return array
     */
    public function get_unread($limit = 10)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('is_read', 0)
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    /**
     * Menghitung jumlah pesan yang belum dibaca.
     *
     * @return int
     */
    public function count_unread()
    {
        return $this->db
            ->where('is_read', 0)
            ->count_all_results($this->table);
    }

    /**
     * Menyimpan pesan kontak baru.
     *
     * @param array $data
     * @return string|false - UUID jika berhasil, false jika gagal
     */
    public function insert($data)
    {
        // Generate UUID jika belum ada
        if (empty($data['uuid'])) {
            $data['uuid'] = $this->generate_uuid();
        }

        // Set created_at jika belum ada
        if (empty($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if ($this->db->insert($this->table, $data)) {
            return $data['uuid'];
        }

        return false;
    }

    /**
     * Memperbarui data pesan kontak.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    /**
     * Menghapus pesan kontak.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    /**
     * Menandai pesan sebagai sudah dibaca.
     *
     * @param int $id
     * @return bool
     */
    public function mark_as_read($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    /**
     * Generate UUID v4.
     *
     * @return string
     */
    private function generate_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
