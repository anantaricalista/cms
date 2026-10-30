<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Career_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'careers';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil semua karir yang dipublikasikan.
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('published_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil karir terbaru.
     *
     * @param int $limit
     * @return array
     */
    public function get_latest($limit = 5)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('published_at', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();
    }

    /**
     * Mengambil karir berdasarkan slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function get_by_slug($slug)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Mengambil karir berdasarkan ID.
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
     * Mengambil semua karir (backend).
     *
     * @return array
     */
    public function get_backend_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('published_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Menghitung jumlah karir.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Menghitung jumlah karir yang dipublikasikan.
     *
     * @return int
     */
    public function count_published()
    {
        return $this->db
            ->where('status', 'published')
            ->count_all_results($this->table);
    }

    /**
     * Menyimpan data karir.
     *
     * @param array $data
     * @return int
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    /**
     * Memperbarui data karir.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }

    /**
     * Menghapus data karir.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table);
    }

    /**
     * Toggle status karir (published/draft).
     *
     * @param int $id
     * @return bool
     */
    public function toggle_status($id)
    {
        $career = $this->get_by_id($id);

        if (!$career) {
            return false;
        }

        $status = ($career['status'] === 'published')
            ? 'draft'
            : 'published';

        $data = [
            'status' => $status
        ];

        if (
            $status === 'published' &&
            empty($career['published_at'])
        ) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }

    /**
     * Check if slug already exists.
     *
     * @param string $slug
     * @param int|null $excludeId
     * @return bool
     */
    public function slug_exists($slug, $excludeId = null)
    {
        $this->db->where('slug', $slug);

        if ($excludeId !== null) {
            $this->db->where('id !=', $excludeId);
        }

        return $this->db->count_all_results($this->table) > 0;
    }
}
