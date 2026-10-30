<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Service_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }
    public function index()
    {
        $data = [
            'title' => 'Services',
            'page_header' => 'Services',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Services</li>
        </ol>',
            'services' => $this->Service_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/services/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Service',
            'page_header' => 'Create Service',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/services') . '">Services</a>
            </li>
            <li class="breadcrumb-item active">Create Service</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/services/create', $data);
    }
    public function store()
    {
        $this->form_validation->set_rules('name', 'Service Name', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = null;

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'services_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title($this->input->post('name', TRUE), '-', TRUE),
                'summary' => trim($this->input->post('summary', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'sort_order' => $this->Service_model->get_next_order(),
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status,
                'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null
            ];

            $this->Service_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Service.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Service berhasil ditambahkan.');

            redirect('admin/services');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/services/create');

        }
    }
    public function edit($id)
    {
        $service = $this->Service_model->get_by_id($id);

        if (!$service) {
            show_404();
        }

        $data = [
            'title' => 'Edit Service',
            'page_header' => 'Edit Service',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/services') . '">Services</a>
            </li>
            <li class="breadcrumb-item active">Edit Service</li>
        </ol>',
            'service' => $service,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/services/edit', $data);
    }
    public function update($id)
    {

        $service = $this->Service_model->get_by_id($id);

        if (!$service) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Service Name', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = $service['featured_image_media_id'];

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'services_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

                if (!empty($service['featured_image_media_id'])) {
                    $this->Media_model->delete($service['featured_image_media_id']);
                }
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title($this->input->post('name', TRUE), '-', TRUE),
                'summary' => trim($this->input->post('summary', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status
            ];

            if (
                $service['published_at'] === NULL &&
                $status === 'published'
            ) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->Service_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Service.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Service berhasil diperbarui.');

            redirect('admin/services');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/services/edit/' . $id);

        }
    }
    public function delete($id)
    {
        $service = $this->Service_model->get_by_id($id);

        if (!$service) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($service['featured_image_media_id'])) {
                $this->Media_model->delete($service['featured_image_media_id']);
            }

            $this->Service_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Service.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Service berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/services');
    }
}
