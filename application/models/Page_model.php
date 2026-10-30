<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends CI_Model
{
    protected $table = 'pages';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh data halaman.
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Mengambil daftar halaman yang telah dipublish.
     * Digunakan untuk dropdown Menu.
     *
     * @return array
     */
    public function get_published()
    {
        return $this->db
            ->select('id, title, slug')
            ->where('status', 'published')
            ->order_by('title', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Mengambil halaman berdasarkan ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Mengambil halaman berdasarkan slug.
     * Digunakan oleh frontend.
     *
     * @param string $slug
     * @return array|null
     */
    public function get_by_slug($slug)
    {
        return $this->db
            ->where('slug', $slug)
            ->where('status', 'published')
            ->get($this->table)
            ->row_array();
    }

    /**
     * Menyimpan halaman baru.
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
     * Memperbarui halaman.
     *
     * @param int   $id
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
     * Menghapus halaman.
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
}