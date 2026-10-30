<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About_model extends CI_Model
{
    protected $table = 'about_sections';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_active()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('is_active', 1)
            ->order_by('display_order', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_first()
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

    public function count_active()
    {
        return $this->db
            ->where('is_active', 1)
            ->count_all_results($this->table);
    }
}