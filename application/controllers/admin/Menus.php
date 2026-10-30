<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Menu_model');
        $this->load->model('Page_model');
        $this->load->model('Service_model');
        $this->load->model('Product_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title' => 'Menu',
            'page_header' => 'Menu',
            'breadcrumb' => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="' . site_url('admin/dashboard') . '">Dashboard</a></li>
                    <li class="breadcrumb-item active">Menu</li>
                </ol>',
            'menus' => $this->Menu_model->get_all(),
            'parents' => $this->Menu_model->get_parent_options(),
            'pages' => $this->Page_model->get_published(),
            'services' => $this->Service_model->get_published(),
            'products' => $this->Product_model->get_published(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/menus/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Menu',
            'page_header' => 'Create Menu',
            'parents' => $this->Menu_model->get_parent_options(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/menus/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $pageId = $this->input->post('page_id') ?: NULL;
        $serviceId = $this->input->post('service_id') ?: NULL;
        $productId = $this->input->post('product_id') ?: NULL;
        $customUrl = trim($this->input->post('custom_url', TRUE));

        $linkCount = 0;

        if ($pageId)
            $linkCount++;
        if ($serviceId)
            $linkCount++;
        if ($productId)
            $linkCount++;
        if ($customUrl !== '')
            $linkCount++;

        if ($linkCount > 1) {
            $this->session->set_flashdata(
                'error',
                'Menu hanya boleh memiliki salah satu tujuan: Halaman, Layanan, Produk atau Custom URL.'
            );

            return redirect('admin/menus');
        }

        $this->db->trans_begin();

        try {
            $parentId = $this->input->post('parent_id') ?: NULL;

            $data = [
                'title' => $this->input->post('title', TRUE),
                'page_id' => $pageId,
                'service_id' => $serviceId,
                'product_id' => $productId,
                'custom_url' => ($customUrl !== '') ? $customUrl : NULL,
                'parent_id' => $parentId,
                'target' => $this->input->post('target', TRUE),
                'icon' => $this->input->post('icon', TRUE),
                'display_order' => $this->Menu_model->get_next_order($parentId),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->Menu_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Menu.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Menu berhasil ditambahkan.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/menus');
    }

    public function edit($id)
    {
        $menu = $this->Menu_model->get_by_id($id);

        if (!$menu) {
            show_404();
        }

        $data = [
            'title' => 'Edit Menu',
            'page_header' => 'Edit Menu',
            'menu' => $menu,
            'parents' => $this->Menu_model->get_parent_options(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/menus/edit', $data);
    }

    public function update()
    {
        $id = $this->input->post('id');

        $menu = $this->Menu_model->get_by_id($id);

        if (!$menu) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $pageId = $this->input->post('page_id') ?: NULL;
        $serviceId = $this->input->post('service_id') ?: NULL;
        $productId = $this->input->post('product_id') ?: NULL;
        $customUrl = trim($this->input->post('custom_url', TRUE));

        $linkCount = 0;

        if ($pageId)
            $linkCount++;
        if ($serviceId)
            $linkCount++;
        if ($productId)
            $linkCount++;
        if ($customUrl !== '')
            $linkCount++;

        if ($linkCount > 1) {
            $this->session->set_flashdata(
                'error',
                'Menu hanya boleh memiliki salah satu tujuan: Halaman, Layanan, Produk atau Custom URL.'
            );

            return redirect('admin/menus');
        }

        $this->db->trans_begin();

        try {

            $data = [
                'title' => $this->input->post('title', TRUE),
                'page_id' => $pageId,
                'service_id' => $serviceId,
                'product_id' => $productId,
                'custom_url' => ($customUrl !== '') ? $customUrl : NULL,
                'parent_id' => $this->input->post('parent_id') ?: NULL,
                'target' => $this->input->post('target', TRUE),
                'icon' => $this->input->post('icon', TRUE),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->Menu_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Menu.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Menu berhasil diperbarui.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/menus');
    }
    public function delete($id)
    {
        $menu = $this->Menu_model->get_by_id($id);

        if (!$menu) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Menu_model->delete($id);

            // Reorder display_order setelah delete
            $this->Menu_model->reorder_display_order();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Menu.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Menu berhasil dihapus.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/menus');
    }

    public function toggle_status($id)
    {
        $this->Menu_model->toggle_status($id);
        redirect('admin/menus');
    }

    public function update_order()
    {
        $items = $this->input->post('items');

        if (is_array($items)) {
            foreach ($items as $order => $id) {
                $this->Menu_model->update_order($id, $order + 1);
            }
        }

        echo json_encode(['status' => true]);
    }

    public function move_up($id)
    {
        $this->Menu_model->move_up($id);
        redirect('admin/menus');
    }

    public function move_down($id)
    {
        $this->Menu_model->move_down($id);
        redirect('admin/menus');
    }
}
