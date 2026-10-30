<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Page_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }
    public function index()
    {
        $data = [
            'title' => 'Pages',
            'page_header' => 'Pages',
            'breadcrumb' => '
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Pages</li>
            </ol>',
            'pages' => $this->Page_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/pages/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Page',
            'page_header' => 'Create Page',
            'breadcrumb' => '
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="' . site_url('admin/pages') . '">Pages</a>
                </li>
                <li class="breadcrumb-item active">Create Page</li>
            </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/pages/create', $data);
    }
    public function store()
    {
        $this->form_validation->set_rules('page_key', 'Page Key', 'required|trim|is_unique[pages.page_key]');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $pageKey = trim($this->input->post('page_key', TRUE));

            // Upload featured image terlebih dahulu
            $featuredImageMediaId = null;

            if (!empty($_FILES['featured_image']['name'])) {
                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'pages_' . $pageKey
                );
            }
            
            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'page_key' => $pageKey,
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'content' => $this->input->post('content', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status,
                'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null
            ];

            $this->Page_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan halaman.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Halaman berhasil ditambahkan.');

            redirect('admin/pages');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/pages/create');
        }
    }
    public function edit($id)
    {
        $page = $this->Page_model->get_by_id($id);

        if (!$page) {
            show_404();
        }

        $data = [
            'title' => 'Edit Page',
            'page_header' => 'Edit Page',
            'breadcrumb' => '
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">
                    <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="' . site_url('admin/pages') . '">Pages</a>
                </li>
                <li class="breadcrumb-item active">Edit Page</li>
            </ol>',
            'page' => $page,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/pages/edit', $data);
    }
    public function update($id)
    {
        $page = $this->Page_model->get_by_id($id);

        if (!$page) {
            show_404();
        }

        $this->form_validation->set_rules('page_key', 'Page Key', 'required|trim');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $pageKey = trim($this->input->post('page_key', TRUE));

            $featuredImageMediaId = $page['featured_image_media_id'];

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'pages_' . $pageKey
                );

                if (!empty($page['featured_image_media_id'])) {
                    $this->Media_model->delete($page['featured_image_media_id']);
                }
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'page_key' => $pageKey,
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'content' => $this->input->post('content', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status
            ];

            if ($page['published_at'] === null && $status === 'published') {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->Page_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui halaman.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Halaman berhasil diperbarui.');

            redirect('admin/pages');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/pages/edit/' . $id);
        }
    }
    public function delete($id)
    {
        $page = $this->Page_model->get_by_id($id);

        if (!$page) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Page_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Page.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Page berhasil dihapus.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/pages');
    }
}
