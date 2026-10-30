<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh layanan yang dipublikasikan.
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil layanan berdasarkan slug.
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
     * Mengambil layanan berdasarkan ID.
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
     * Menghitung jumlah layanan yang dipublikasikan.
     *
     * @return int
     */
    public function count_published()
    {
        return $this->db
            ->where('status', 'published')
            ->count_all_results($this->table);
    }

    public function get_published()
    {
        return $this->db
            ->select('id, name, slug, featured_image_media_id, summary')
            ->where('status', 'published')
            ->order_by('name', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_featured($limit = 3)
    {
        return $this->db
            ->select('id, name, slug, featured_image_media_id, summary')
            ->where('status', 'published')
            ->order_by('sort_order', 'ASC')
            ->limit($limit)
            ->get($this->table)
            ->result_array();
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

    /**
     * Mengambil urutan berikutnya.
     *
     * @return int
     */
    public function get_next_order()
    {
        $this->db->select_max('sort_order');

        $row = $this->db->get($this->table)->row_array();

        return ((int) $row['sort_order']) + 1;
    }

    /**
     * Mengubah status publish.
     *
     * @param int $id
     * @return bool
     */
    public function toggle_status($id)
    {
        $service = $this->get_by_id($id);

        if (!$service) {
            return false;
        }

        $status = ($service['status'] === 'published') ? 'draft' : 'published';

        $data = [
            'status' => $status
        ];

        if ($service['published_at'] === NULL && $status === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }

    /**
     * Memindahkan urutan ke atas.
     *
     * @param int $id
     * @return bool
     */
    public function move_up($id)
    {
        $current = $this->get_by_id($id);

        if (!$current || $current['sort_order'] <= 1) {
            return false;
        }

        $other = $this->db
            ->where('sort_order', $current['sort_order'] - 1)
            ->get($this->table)
            ->row_array();

        if (!$other) {
            return false;
        }

        $this->db->trans_start();

        $this->update($other['id'], [
            'sort_order' => $current['sort_order']
        ]);

        $this->update($current['id'], [
            'sort_order' => $current['sort_order'] - 1
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Memindahkan urutan ke bawah.
     *
     * @param int $id
     * @return bool
     */
    public function move_down($id)
    {
        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $other = $this->db
            ->where('sort_order', $current['sort_order'] + 1)
            ->get($this->table)
            ->row_array();

        if (!$other) {
            return false;
        }

        $this->db->trans_start();

        $this->update($other['id'], [
            'sort_order' => $current['sort_order']
        ]);

        $this->update($current['id'], [
            'sort_order' => $current['sort_order'] + 1
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}