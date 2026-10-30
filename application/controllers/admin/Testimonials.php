<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Testimonial_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'Testimonials',
            'page_header' => 'Testimonials',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Testimonials</li>
        </ol>',
            'testimonials' => $this->Testimonial_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/testimonials/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Testimonial',
            'page_header' => 'Create Testimonial',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/testimonials') . '">Testimonials</a>
            </li>
            <li class="breadcrumb-item active">Create Testimonial</li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/testimonials/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('company', 'Company', 'required|trim');
        $this->form_validation->set_rules('position', 'Position', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $photoMediaId = null;

            if (!empty($_FILES['photo']['name'])) {

                $photoMediaId = $this->Media_model->upload(
                    'photo',
                    'testimonial_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

            }

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'company' => trim($this->input->post('company', TRUE)),
                'position' => trim($this->input->post('position', TRUE)),
                'photo_media_id' => $photoMediaId,
                'content' => $this->input->post('content'),
                'sort_order' => $this->Testimonial_model->get_next_order()
            ];

            $this->Testimonial_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Testimonial.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Testimonial berhasil ditambahkan.');

            redirect('admin/testimonials');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/testimonials/create');

        }
    }

    public function edit($id)
    {
        $testimonial = $this->Testimonial_model->get_by_id($id);

        if (!$testimonial) {
            show_404();
        }

        $data = [
            'title' => 'Edit Testimonial',
            'page_header' => 'Edit Testimonial',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/testimonials') . '">Testimonials</a>
            </li>
            <li class="breadcrumb-item active">Edit Testimonial</li>
        </ol>',
            'testimonial' => $testimonial,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/testimonials/edit', $data);
    }

    public function update($id)
    {

        $testimonial = $this->Testimonial_model->get_by_id($id);

        if (!$testimonial) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('company', 'Company', 'required|trim');
        $this->form_validation->set_rules('position', 'Position', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $photoMediaId = $testimonial['photo_media_id'];

            if (!empty($_FILES['photo']['name'])) {

                $photoMediaId = $this->Media_model->upload(
                    'photo',
                    'testimonial_' . url_title($this->input->post('name', TRUE), '-', TRUE)
                );

                if (!empty($testimonial['photo_media_id'])) {
                    $this->Media_model->delete($testimonial['photo_media_id']);
                }
            }

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'company' => trim($this->input->post('company', TRUE)),
                'position' => trim($this->input->post('position', TRUE)),
                'photo_media_id' => $photoMediaId,
                'content' => $this->input->post('content')
            ];

            $this->Testimonial_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Testimonial.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Testimonial berhasil diperbarui.');

            redirect('admin/testimonials');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata('error', $e->getMessage());

            redirect('admin/testimonials/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $testimonial = $this->Testimonial_model->get_by_id($id);

        if (!$testimonial) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($testimonial['photo_media_id'])) {
                $this->Media_model->delete($testimonial['photo_media_id']);
            }

            $this->Testimonial_model->delete($id);

            // Reorder sort_order setelah delete
            $this->Testimonial_model->reorder_sort_order();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Testimonial.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Testimonial berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/testimonials');
    }

    public function move_up($id)
    {
        $testimonial = $this->Testimonial_model->get_by_id($id);

        if (!$testimonial) {
            show_404();
        }

        $this->Testimonial_model->move_up($id);

        $this->session->set_flashdata('success', 'Testimonial urutan dipindahkan ke atas.');

        redirect('admin/testimonials');
    }

    public function move_down($id)
    {
        $testimonial = $this->Testimonial_model->get_by_id($id);

        if (!$testimonial) {
            show_404();
        }

        $this->Testimonial_model->move_down($id);

        $this->session->set_flashdata('success', 'Testimonial urutan dipindahkan ke bawah.');

        redirect('admin/testimonials');
    }
}
