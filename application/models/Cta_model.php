<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cta_model extends CI_Model
{
    protected $table = 'cta_sections';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil CTA aktif.
     *
     * @return array|null
     */
    public function get_active()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('is_active', 1)
            ->order_by('display_order', 'ASC')
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Mengambil CTA berdasarkan ID.
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
     * Menghitung CTA aktif.
     *
     * @return int
     */
    public function count_active()
    {
        return $this->db
            ->where('is_active', 1)
            ->count_all_results($this->table);
    }
}