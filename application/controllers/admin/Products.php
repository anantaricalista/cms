<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Product_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }
    public function index()
    {
        $data = [
            'title' => 'Products',
            'page_header' => 'Products',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Products</li>
        </ol>',
            'products' => $this->Product_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/Products/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Products',
            'page_header' => 'Create Products',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/Products') . '">Products</a>
            </li>
            <li class="breadcrumb-item active">Create Products</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/Products/create', $data);
    }
    public function store()
    {
        $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
        $this->form_validation->set_rules('sku', 'SKU', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('specification', 'Specification', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = null;

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'products_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title($this->input->post('name', TRUE), '-', TRUE),
                'sku' => trim($this->input->post('sku', TRUE)),
                'summary' => trim($this->input->post('summary', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'specification' => $this->input->post('specification', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'sort_order' => $this->Product_model->get_next_order(),
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status,
                'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null
            ];

            $this->Product_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Product.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Product berhasil ditambahkan.');

            redirect('admin/products');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/products/create');

        }
    }
    public function edit($id)
    {
        $product = $this->Product_model->get_by_id($id);

        if (!$product) {
            show_404();
        }

        $data = [
            'title' => 'Edit Products',
            'page_header' => 'Edit Products',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/Products') . '">Products</a>
            </li>
            <li class="breadcrumb-item active">Edit Products</li>
        </ol>',
            'product' => $product,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/products/edit', $data);
    }
    public function update($id)
    {
        $product = $this->Product_model->get_by_id($id);

        if (!$product) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
        $this->form_validation->set_rules('sku', 'SKU', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('specification', 'Specification', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = $product['featured_image_media_id'];

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'products_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

                if (!empty($product['featured_image_media_id'])) {
                    $this->Media_model->delete($product['featured_image_media_id']);
                }
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title($this->input->post('name', TRUE), '-', TRUE),
                'sku' => trim($this->input->post('sku', TRUE)),
                'summary' => trim($this->input->post('summary', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'specification' => $this->input->post('specification', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status
            ];

            if (
                $product['published_at'] === NULL &&
                $status === 'published'
            ) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->Product_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Product.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Product berhasil diperbarui.'
            );

            redirect('admin/products');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

            redirect('admin/products/edit/' . $id);

        }
    }
    public function delete($id)
    {
        $product = $this->Product_model->get_by_id($id);

        if (!$product) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($product['featured_image_media_id'])) {
                $this->Media_model->delete($product['featured_image_media_id']);
            }

            $this->Product_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Product.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Product berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/products');
    }
}
