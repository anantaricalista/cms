<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Download_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'Downloads',
            'page_header' => 'Downloads',
            'breadcrumb' => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="' . site_url('admin/dashboard') . '">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Downloads
                    </li>
                </ol>',
            'downloads' => $this->Download_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/downloads/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Download',
            'page_header' => 'Create Download',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/downloads/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        if (empty($_FILES['media']['name'])) {

            $this->session->set_flashdata(
                'error',
                'File wajib dipilih.'
            );

            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $mediaId = $this->Media_model->upload('media');

            if (!$mediaId) {
                throw new Exception('Gagal mengunggah file.');
            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'title' => trim(
                    $this->input->post('title', TRUE)
                ),
                'description' => $this->input->post(
                    'description',
                    FALSE
                ),
                'media_id' => $mediaId,
                'status' => $status,
                'published_at' => ($status === 'published')
                    ? date('Y-m-d H:i:s')
                    : null
            ];

            $this->Download_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception(
                    'Gagal menyimpan Download.'
                );
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Download berhasil ditambahkan.'
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

        redirect('admin/downloads');
    }

    public function edit($id)
    {
        $download = $this->Download_model->get_by_id($id);

        if (!$download) {
            show_404();
        }

        $data = [
            'title' => 'Edit Download',
            'page_header' => 'Edit Download',
            'download' => $download,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/downloads/edit', $data);
    }

    public function update($id)
    {
        $download = $this->Download_model->get_by_id($id);

        if (!$download) {
            show_404();
        }

        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $status = $this->input->post(
                'status',
                TRUE
            );

            $data = [
                'title' => trim(
                    $this->input->post('title', TRUE)
                ),
                'description' => $this->input->post(
                    'description',
                    FALSE
                ),
                'status' => $status
            ];

            if (
                $download['published_at'] === NULL &&
                $status === 'published'
            ) {

                $data['published_at'] = date(
                    'Y-m-d H:i:s'
                );
            }

            if (!empty($_FILES['media']['name'])) {

                $newMediaId = $this->Media_model->upload(
                    'media'
                );

                if (!$newMediaId) {
                    throw new Exception(
                        'Gagal mengunggah file baru.'
                    );
                }

                $data['media_id'] = $newMediaId;

                if (!empty($download['media_id'])) {

                    $this->Media_model->delete(
                        $download['media_id']
                    );
                }
            }

            $this->Download_model->update(
                $id,
                $data
            );

            if ($this->db->trans_status() === FALSE) {
                throw new Exception(
                    'Gagal memperbarui Download.'
                );
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Download berhasil diperbarui.'
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

        redirect('admin/downloads');
    }

    public function delete($id)
    {
        $download = $this->Download_model->get_by_id($id);

        if (!$download) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Download_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception(
                    'Gagal menghapus Download.'
                );
            }

            $this->db->trans_commit();

            /*
             |---------------------------------------------------------
             | Hapus media setelah transaksi database berhasil.
             |---------------------------------------------------------
             */

            if (!empty($download['media_id'])) {

                $this->Media_model->delete(
                    $download['media_id']
                );
            }

            $this->session->set_flashdata(
                'success',
                'Download berhasil dihapus.'
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

        redirect('admin/downloads');
    }

    public function toggle_status($id)
    {
        $this->Download_model->toggle_status($id);

        redirect('admin/downloads');
    }
}