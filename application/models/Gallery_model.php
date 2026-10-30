<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends CI_Model
{
    protected $table = 'galleries';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_published()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->get()
            ->result_array();
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

    public function get_next_order()
    {
        $row = $this->db
            ->select_max('sort_order')
            ->get($this->table)
            ->row();

        return ((int) ($row->sort_order ?? 0)) + 1;
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

    public function reorder_sort_order()
    {
        $galleries = $this->db
            ->select('id')
            ->from($this->table)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();

        $this->db->trans_start();

        foreach ($galleries as $index => $gallery) {
            $this->db
                ->where('id', $gallery['id'])
                ->update($this->table, ['sort_order' => $index + 1]);
        }

        $this->db->trans_complete();
    }

    public function move_up($id)
    {
        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $previous = $this->db
            ->select('*')
            ->from($this->table)
            ->where('sort_order <', $current['sort_order'])
            ->order_by('sort_order', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$previous) {
            return false;
        }

        $this->db->trans_begin();

        $this->db
            ->where('id', $current['id'])
            ->update($this->table, [
                'sort_order' => $previous['sort_order']
            ]);

        $this->db
            ->where('id', $previous['id'])
            ->update($this->table, [
                'sort_order' => $current['sort_order']
            ]);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_commit();

        return true;
    }

    public function move_down($id)
    {
        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $next = $this->db
            ->select('*')
            ->from($this->table)
            ->where('sort_order >', $current['sort_order'])
            ->order_by('sort_order', 'ASC')
            ->order_by('id', 'ASC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$next) {
            return false;
        }

        $this->db->trans_begin();

        $this->db
            ->where('id', $current['id'])
            ->update($this->table, [
                'sort_order' => $next['sort_order']
            ]);

        $this->db
            ->where('id', $next['id'])
            ->update($this->table, [
                'sort_order' => $current['sort_order']
            ]);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return false;
        }

        $this->db->trans_commit();

        return true;
    }

    public function toggle_status($id)
    {
        $gallery = $this->get_by_id($id);

        if (!$gallery) {
            return false;
        }

        $status = ($gallery['status'] === 'published')
            ? 'draft'
            : 'published';

        $data = [
            'status' => $status
        ];

        if (
            $status === 'published' &&
            empty($gallery['published_at'])
        ) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }
}
