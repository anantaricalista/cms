<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    protected $table = 'menus';
    public function __construct()
    {
        parent::__construct();

        $this->load->library('uuid');
        $this->load->model('Page_model');
        $this->load->model('Service_model');
        $this->load->model('Product_model');
    }
    public function get_all()
    {
        return $this->db
            ->order_by('display_order', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    public function insert($data)
    {
        $data['uuid'] = $this->uuid->v4();

        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table);
    }

    public function reorder_display_order()
    {
        $menus = $this->db
            ->select('id')
            ->from($this->table)
            ->order_by('display_order', 'ASC')
            ->get()
            ->result_array();

        $this->db->trans_start();

        foreach ($menus as $index => $menu) {
            $this->db
                ->where('id', $menu['id'])
                ->update($this->table, ['display_order' => $index + 1]);
        }

        $this->db->trans_complete();
    }

    public function toggle_status($id)
    {
        $menu = $this->get_by_id($id);

        if (!$menu) {
            return false;
        }

        return $this->update($id, [
            'is_active' => $menu['is_active'] ? 0 : 1
        ]);
    }

    public function generate_next_order()
    {
        $row = $this->db
            ->select_max('display_order')
            ->get($this->table)
            ->row_array();

        return ((int) $row['display_order']) + 1;
    }

    public function get_parent_options()
    {
        return $this->db
            ->where('parent_id IS NULL', null, false)
            ->order_by('display_order', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function update_order($id, $order)
    {
        return $this->update($id, [
            'display_order' => $order
        ]);
    }

    public function move_up($id)
    {
        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $this->db->where('display_order', $current['display_order'] - 1);

        if (empty($current['parent_id'])) {
            $this->db->where('parent_id IS NULL', NULL, FALSE);
        } else {
            $this->db->where('parent_id', $current['parent_id']);
        }

        $other = $this->db
            ->get($this->table)
            ->row_array();

        if (!$other) {
            return false;
        }

        $this->db->trans_start();

        $this->update($other['id'], [
            'display_order' => $current['display_order']
        ]);

        $this->update($current['id'], [
            'display_order' => $current['display_order'] - 1
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function move_down($id)
    {
        $current = $this->get_by_id($id);

        if (!$current) {
            return false;
        }

        $this->db->where('display_order', $current['display_order'] + 1);

        if (empty($current['parent_id'])) {
            $this->db->where('parent_id IS NULL', NULL, FALSE);
        } else {
            $this->db->where('parent_id', $current['parent_id']);
        }

        $other = $this->db
            ->get($this->table)
            ->row_array();

        if (!$other) {
            return false;
        }

        $this->db->trans_start();

        $this->update($other['id'], [
            'display_order' => $current['display_order']
        ]);

        $this->update($current['id'], [
            'display_order' => $current['display_order'] + 1
        ]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function getMenus()
    {
        $menus = $this->db
            ->where('is_active', 1)
            ->order_by('display_order', 'ASC')
            ->get($this->table)
            ->result_array();

        return $this->buildMenuTree($menus);
    }

    private function buildMenuTree(array $menus, $parent_id = NULL)
    {
        $tree = [];

        foreach ($menus as $menu) {
            if ((string) $menu['parent_id'] === (string) $parent_id) {
                $menu['url'] = $this->buildMenuUrl($menu);

                $menu['children'] = $this->buildMenuTree(
                    $menus,
                    $menu['id']
                );

                $tree[] = $menu;
            }
        }

        return $tree;
    }

    private function buildMenuUrl(array $menu)
    {
        // Prioritas 1 : Custom URL (External)
        if (!empty($menu['custom_url'])) {
            return $menu['custom_url'];
        }

        // Prioritas 2 : Page
        if (!empty($menu['page_id'])) {

            $page = $this->Page_model->get_by_id($menu['page_id']);

            if ($page) {
                return base_url('page/' . $page['slug']);
            }

        }

        // Prioritas 3 : Service
        if (!empty($menu['service_id'])) {

            $service = $this->Service_model->get_by_id($menu['service_id']);

            if ($service) {
                return base_url('service/' . $service['slug']);
            }

        }

        // Prioritas 4 : Product
        if (!empty($menu['product_id'])) {

            $product = $this->Product_model->get_by_id($menu['product_id']);

            if ($product) {
                return base_url('product/' . $product['slug']);
            }

        }

        // Prioritas 5 : Placeholder
        return '#';
    }

    public function get_next_order($parent_id = NULL)
    {
        $this->db->select_max('display_order');

        if ($parent_id === NULL || $parent_id == '') {
            $this->db->where('parent_id IS NULL', NULL, FALSE);
        } else {
            $this->db->where('parent_id', $parent_id);
        }

        $row = $this->db->get($this->table)->row_array();

        return ((int) $row['display_order']) + 1;
    }
}
