<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_category_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'news_categories';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh kategori.
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil kategori berdasarkan ID.
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
     * Menyimpan data.
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
     * Memperbarui data.
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
     * Menghapus data.
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

    public function count_news($categoryId)
{
    return $this->db
        ->where('category_id', $categoryId)
        ->count_all_results('news_category_relations');
}
}