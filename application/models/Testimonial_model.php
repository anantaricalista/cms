<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'testimonials';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil semua testimonial.
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
     * Mengambil testimonial berdasarkan ID.
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
     * Menghitung jumlah testimonial.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * Mengambil testimonial untuk ditampilkan di frontend.
     *
     * @param int $limit
     * @return array
     */
    public function get_published($limit = null)
    {
        $query = $this->db
            ->select('id, name, company, position, photo_media_id, content')
            ->order_by('sort_order', 'ASC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get($this->table)->result_array();
    }

    /**
     * Menghitung jumlah testimonial untuk ditampilkan di frontend.
     *
     * @return int
     */
    public function count_published()
    {
        return $this->db->count_all_results($this->table);
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

    /**
     * Reorder sort_order setelah ada yang dihapus.
     * Memastikan urutan rapi tanpa gap.
     *
     * @return void
     */
    public function reorder_sort_order()
    {
        $testimonials = $this->db
            ->select('id')
            ->from($this->table)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();

        $this->db->trans_start();

        foreach ($testimonials as $index => $testimonial) {
            $this->db
                ->where('id', $testimonial['id'])
                ->update($this->table, ['sort_order' => $index + 1]);
        }

        $this->db->trans_complete();
    }
}
