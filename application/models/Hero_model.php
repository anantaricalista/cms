<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hero_model extends CI_Model
{
    /**
     * Nama tabel
     *
     * @var string
     */
    protected $table = 'hero_slides';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh Hero yang aktif.
     *
     * @return array
     */
    public function get_active_slides()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('is_active', 1)
            ->order_by('display_order', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil Hero pertama yang aktif.
     *
     * Digunakan apabila frontend hanya
     * menampilkan satu Hero.
     *
     * @return array|null
     */
    public function get_first_slide()
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
     * Mengambil Hero berdasarkan ID.
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
     * Menghitung jumlah Hero aktif.
     *
     * @return int
     */
    public function count_active()
    {
        return $this->db
            ->where('is_active', 1)
            ->count_all_results($this->table);
    }

    /**
     * Backend: Mengambil seluruh hero.
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('display_order', 'ASC')
            ->get()
            ->result_array();
    }

    public function insert(array $data)
    {
        $now = date('Y-m-d H:i:s');
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

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

    public function reorder_display_order()
    {
        $heroes = $this->db
            ->select('id')
            ->from($this->table)
            ->order_by('display_order', 'ASC')
            ->get()
            ->result_array();

        $this->db->trans_start();

        foreach ($heroes as $index => $hero) {
            $this->db
                ->where('id', $hero['id'])
                ->update($this->table, ['display_order' => $index + 1]);
        }

        $this->db->trans_complete();
    }

    public function toggle_status($id)
    {
        $hero = $this->get_by_id($id);

        if (!$hero) {
            return false;
        }

        return $this->update($id, [
            'is_active' => $hero['is_active'] ? 0 : 1
        ]);
    }

    public function update_order($id, $display_order)
    {
        return $this->update($id, [
            'display_order' => (int) $display_order
        ]);
    }

    public function get_next_order()
    {
        $row = $this->db
            ->select_max('display_order')
            ->get($this->table)
            ->row_array();

        return empty($row['display_order'])
            ? 1
            : ((int) $row['display_order']) + 1;
    }

    public function get_media_ids($id)
    {
        return $this->db
            ->select('background_media_id, hero_media_id')
            ->from($this->table)
            ->where('id', (int) $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    public function move_up($id)
    {
        $this->db->trans_start();

        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $targetOrder = $current['display_order'] - 1;

        $other = $this->db
            ->where('display_order', $targetOrder)
            ->get($this->table)
            ->row_array();

        // Sudah berada di urutan paling atas
        if (!$other) {
            $this->db->trans_complete();
            return false;
        }

        // Tukar order
        $this->update($other['id'], [
            'display_order' => $current['display_order']
        ]);

        $this->update($current['id'], [
            'display_order' => $targetOrder
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function move_down($id)
    {
        $this->db->trans_start();

        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $targetOrder = $current['display_order'] + 1;

        $other = $this->db
            ->where('display_order', $targetOrder)
            ->get($this->table)
            ->row_array();

        // Sudah berada di urutan paling bawah
        if (!$other) {
            $this->db->trans_complete();
            return false;
        }

        // Tukar order
        $this->update($other['id'], [
            'display_order' => $current['display_order']
        ]);

        $this->update($current['id'], [
            'display_order' => $targetOrder
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
