<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galleries extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Gallery_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title' => 'Galleries',
            'page_header' => 'Galleries',
            'breadcrumb' => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="' . site_url('admin/dashboard') . '">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Galleries
                    </li>
                </ol>',
            'galleries' => $this->Gallery_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/galleries/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Gallery',
            'page_header' => 'Create Gallery',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/galleries/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $data = [
                'title' => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', FALSE),
                'sort_order' => $this->Gallery_model->get_next_order(),
                'status' => $this->input->post('status', TRUE),
                'published_at' => null
            ];

            if (!empty($_FILES['media']['name'])) {

                $data['media_id'] = $this->Media_model->upload('media');

            }

            if ($data['status'] === 'published') {

                $data['published_at'] = date('Y-m-d H:i:s');

            }

            $this->Gallery_model->insert($data);

            if ($this->db->trans_status() === FALSE) {

                throw new Exception(
                    'Gagal menyimpan Gallery.'
                );

            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Gallery berhasil ditambahkan.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message(
                'error',
                $e->getMessage()
            );

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );
        }

        redirect('admin/galleries');
    }

    public function edit($id)
    {
        $gallery = $this->Gallery_model->get_by_id($id);

        if (!$gallery) {
            show_404();
        }

        $data = [
            'title' => 'Edit Gallery',
            'page_header' => 'Edit Gallery',
            'gallery' => $gallery,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/galleries/edit', $data);
    }

    public function update($id)
    {
        $gallery = $this->Gallery_model->get_by_id($id);

        if (!$gallery) {
            show_404();
        }

        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $data = [
                'title' => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', FALSE),
                'sort_order' => (int) $this->input->post('sort_order'),
                'status' => $this->input->post('status', TRUE)
            ];

            if (
                $gallery['published_at'] === NULL &&
                $data['status'] === 'published'
            ) {

                $data['published_at'] = date(
                    'Y-m-d H:i:s'
                );

            }

            $oldMediaId = $gallery['media_id'];

            if (!empty($_FILES['media']['name'])) {

                $data['media_id'] = $this->Media_model->upload(
                    'media'
                );

                if ($oldMediaId) {

                    $this->Media_model->delete(
                        $oldMediaId
                    );

                }
            }

            $this->Gallery_model->update(
                $id,
                $data
            );

            if ($this->db->trans_status() === FALSE) {

                throw new Exception(
                    'Gagal memperbarui Gallery.'
                );

            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Gallery berhasil diperbarui.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message(
                'error',
                $e->getMessage()
            );

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );
        }

        redirect('admin/galleries');
    }

    public function delete($id)
    {
        $gallery = $this->Gallery_model->get_by_id($id);

        if (!$gallery) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Gallery_model->delete($id);

            // Reorder sort_order setelah delete
            $this->Gallery_model->reorder_sort_order();

            if ($this->db->trans_status() === FALSE) {

                throw new Exception(
                    'Gagal menghapus Gallery.'
                );

            }

            $this->db->trans_commit();

            /*
             |---------------------------------------------------------
             | Hapus media setelah transaksi database berhasil.
             |---------------------------------------------------------
             */

            if (!empty($gallery['media_id'])) {

                $this->Media_model->delete(
                    $gallery['media_id']
                );

            }

            $this->session->set_flashdata(
                'success',
                'Gallery berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message(
                'error',
                $e->getMessage()
            );

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );
        }

        redirect('admin/galleries');
    }

    public function toggle_status($id)
    {
        $this->Gallery_model->toggle_status($id);

        redirect('admin/galleries');
    }

    public function move_up($id)
    {
        $this->Gallery_model->move_up($id);

        redirect('admin/galleries');
    }

    public function move_down($id)
    {
        $this->Gallery_model->move_down($id);

        redirect('admin/galleries');
    }
}
