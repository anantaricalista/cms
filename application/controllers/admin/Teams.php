<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Team_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'Teams',
            'page_header' => 'Teams',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Teams</li>
        </ol>',
            'teams' => $this->Team_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/teams/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Team Member',
            'page_header' => 'Create Team Member',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/teams') . '">Teams</a>
            </li>
            <li class="breadcrumb-item active">Create Team Member</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/teams/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('position', 'Position', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $photoMediaId = null;

            if (!empty($_FILES['photo']['name'])) {

                $photoMediaId = $this->Media_model->upload(
                    'photo',
                    'team_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

            }

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'position' => trim($this->input->post('position', TRUE)),
                'photo_media_id' => $photoMediaId,
                'linkedin' => trim($this->input->post('linkedin', TRUE)),
                'sort_order' => $this->Team_model->get_next_order()
            ];

            $this->Team_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Team Member.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Team Member berhasil ditambahkan.');

            redirect('admin/teams');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/teams/create');

        }
    }

    public function edit($id)
    {
        $team = $this->Team_model->get_by_id($id);

        if (!$team) {
            show_404();
        }

        $data = [
            'title' => 'Edit Team Member',
            'page_header' => 'Edit Team Member',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/teams') . '">Teams</a>
            </li>
            <li class="breadcrumb-item active">Edit Team Member</li>
        </ol>',
            'team' => $team,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/teams/edit', $data);
    }

    public function update($id)
    {

        $team = $this->Team_model->get_by_id($id);

        if (!$team) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('position', 'Position', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $photoMediaId = $team['photo_media_id'];

            if (!empty($_FILES['photo']['name'])) {

                $photoMediaId = $this->Media_model->upload(
                    'photo',
                    'team_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

                if (!empty($team['photo_media_id'])) {
                    $this->Media_model->delete($team['photo_media_id']);
                }
            }

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'position' => trim($this->input->post('position', TRUE)),
                'photo_media_id' => $photoMediaId,
                'linkedin' => trim($this->input->post('linkedin', TRUE))
            ];

            $this->Team_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Team Member.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Team Member berhasil diperbarui.');

            redirect('admin/teams');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/teams/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $team = $this->Team_model->get_by_id($id);

        if (!$team) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($team['photo_media_id'])) {
                $this->Media_model->delete($team['photo_media_id']);
            }

            $this->Team_model->delete($id);

            // Reorder sort_order setelah delete
            $this->Team_model->reorder_sort_order();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Team Member.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Team Member berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/teams');
    }

    public function move_up($id)
    {
        $team = $this->Team_model->get_by_id($id);

        if (!$team) {
            show_404();
        }

        $this->Team_model->move_up($id);

        $this->session->set_flashdata('success', 'Team Member urutan dipindahkan ke atas.');

        redirect('admin/teams');
    }

    public function move_down($id)
    {
        $team = $this->Team_model->get_by_id($id);

        if (!$team) {
            show_404();
        }

        $this->Team_model->move_down($id);

        $this->session->set_flashdata('success', 'Team Member urutan dipindahkan ke bawah.');

        redirect('admin/teams');
    }
}
