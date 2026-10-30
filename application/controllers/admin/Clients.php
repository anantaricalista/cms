<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Client_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'Clients',
            'page_header' => 'Clients',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Clients</li>
        </ol>',
            'clients' => $this->Client_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/clients/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Client',
            'page_header' => 'Create Client',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/clients') . '">Clients</a>
            </li>
            <li class="breadcrumb-item active">Create Client</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/clients/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Client Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $logoMediaId = null;

            if (!empty($_FILES['logo']['name'])) {

                $logoMediaId = $this->Media_model->upload(
                    'logo',
                    'client_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

            }

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'logo_media_id' => $logoMediaId,
                'website' => trim($this->input->post('website', TRUE)),
                'sort_order' => $this->Client_model->get_next_order()
            ];

            $this->Client_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Client.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Client berhasil ditambahkan.');

            redirect('admin/clients');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/clients/create');

        }
    }

    public function edit($id)
    {
        $client = $this->Client_model->get_by_id($id);

        if (!$client) {
            show_404();
        }

        $data = [
            'title' => 'Edit Client',
            'page_header' => 'Edit Client',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/clients') . '">Clients</a>
            </li>
            <li class="breadcrumb-item active">Edit Client</li>
        </ol>',
            'client' => $client,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/clients/edit', $data);
    }

    public function update($id)
    {

        $client = $this->Client_model->get_by_id($id);

        if (!$client) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Client Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $logoMediaId = $client['logo_media_id'];

            if (!empty($_FILES['logo']['name'])) {

                $logoMediaId = $this->Media_model->upload(
                    'logo',
                    'client_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

                if (!empty($client['logo_media_id'])) {
                    $this->Media_model->delete($client['logo_media_id']);
                }
            }

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'logo_media_id' => $logoMediaId,
                'website' => trim($this->input->post('website', TRUE))
            ];

            $this->Client_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Client.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Client berhasil diperbarui.');

            redirect('admin/clients');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/clients/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $client = $this->Client_model->get_by_id($id);

        if (!$client) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($client['logo_media_id'])) {
                $this->Media_model->delete($client['logo_media_id']);
            }

            $this->Client_model->delete($id);

            // Reorder sort_order setelah delete
            $this->Client_model->reorder_sort_order();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Client.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Client berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/clients');
    }

    public function move_up($id)
    {
        $client = $this->Client_model->get_by_id($id);

        if (!$client) {
            show_404();
        }

        $this->Client_model->move_up($id);

        $this->session->set_flashdata('success', 'Client urutan dipindahkan ke atas.');

        redirect('admin/clients');
    }

    public function move_down($id)
    {
        $client = $this->Client_model->get_by_id($id);

        if (!$client) {
            show_404();
        }

        $this->Client_model->move_down($id);

        $this->session->set_flashdata('success', 'Client urutan dipindahkan ke bawah.');

        redirect('admin/clients');
    }
}
