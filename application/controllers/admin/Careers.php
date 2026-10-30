<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Career_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'Careers',
            'page_header' => 'Careers',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Careers</li>
        </ol>',
            'careers' => $this->Career_model->get_backend_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/careers/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Career',
            'page_header' => 'Create Career',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/careers') . '">Careers</a>
            </li>
            <li class="breadcrumb-item active">Create Career</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/careers/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('position', 'Position', 'required|trim');
        $this->form_validation->set_rules('location', 'Location', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $position = trim($this->input->post('position', TRUE));
            $slug = url_title($position, '-', TRUE);

            // Check if slug already exists
            if ($this->Career_model->slug_exists($slug)) {
                $this->session->set_flashdata(
                    'error',
                    'Slug "' . $slug . '" sudah digunakan. Ubah posisi untuk menghasilkan slug yang unik.'
                );

                return $this->create();
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'position' => $position,
                'slug' => $slug,
                'location' => trim($this->input->post('location', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'status' => $status,
                'published_at' => ($status === 'published')
                    ? date('Y-m-d H:i:s')
                    : null
            ];

            $this->Career_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Career.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Career berhasil ditambahkan.');

            redirect('admin/careers');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/careers/create');

        }
    }

    public function edit($id)
    {
        $career = $this->Career_model->get_by_id($id);

        if (!$career) {
            show_404();
        }

        $data = [
            'title' => 'Edit Career',
            'page_header' => 'Edit Career',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/careers') . '">Careers</a>
            </li>
            <li class="breadcrumb-item active">Edit Career</li>
        </ol>',
            'career' => $career,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/careers/edit', $data);
    }

    public function update($id)
    {
        $career = $this->Career_model->get_by_id($id);

        if (!$career) {
            show_404();
        }

        $this->form_validation->set_rules('position', 'Position', 'required|trim');
        $this->form_validation->set_rules('location', 'Location', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $position = trim($this->input->post('position', TRUE));
            $slug = url_title($position, '-', TRUE);

            // Check if slug already exists (exclude current career)
            if ($this->Career_model->slug_exists($slug, $id)) {
                $this->session->set_flashdata(
                    'error',
                    'Slug "' . $slug . '" sudah digunakan. Ubah posisi untuk menghasilkan slug yang unik.'
                );

                return $this->edit($id);
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'position' => $position,
                'slug' => $slug,
                'location' => trim($this->input->post('location', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'status' => $status
            ];

            if (
                $career['published_at'] === NULL &&
                $status === 'published'
            ) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->Career_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Career.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Career berhasil diperbarui.');

            redirect('admin/careers');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/careers/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $career = $this->Career_model->get_by_id($id);

        if (!$career) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Career_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Career.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Career berhasil dihapus.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

        }

        redirect('admin/careers');
    }

    public function toggle_status($id)
    {
        $career = $this->Career_model->get_by_id($id);

        if (!$career) {
            show_404();
        }

        $this->Career_model->toggle_status($id);

        $this->session->set_flashdata('success', 'Status career berhasil diubah.');

        redirect('admin/careers');
    }
}
