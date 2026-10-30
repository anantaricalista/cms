<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Heroes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Hero_model');
        $this->load->model('Media_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title' => 'Hero',
            'page_header' => 'Hero',
            'breadcrumb' => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="' . site_url('admin/dashboard') . '">Dashboard</a></li>
                    <li class="breadcrumb-item active">Hero</li>
                </ol>',
            'heroes' => $this->Hero_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/heroes/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Hero',
            'page_header' => 'Create Hero',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/heroes/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $data = [
                'badge' => $this->input->post('badge', TRUE),
                'title' => $this->input->post('title', TRUE),
                'subtitle' => $this->input->post('subtitle', TRUE),
                'description' => $this->input->post('description'),
                'primary_button_text' => $this->input->post('primary_button_text', TRUE),
                'primary_button_url' => $this->input->post('primary_button_url', TRUE),
                'secondary_button_text' => $this->input->post('secondary_button_text', TRUE),
                'secondary_button_url' => $this->input->post('secondary_button_url', TRUE),
                'video_url' => $this->input->post('video_url', TRUE),
                'display_order' => $this->Hero_model->get_next_order(),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if (!empty($_FILES['background']['name'])) {
                $data['background_media_id'] = $this->Media_model->upload('background');
            }

            if (!empty($_FILES['hero_image']['name'])) {
                $data['hero_media_id'] = $this->Media_model->upload('hero_image');
            }

            $this->Hero_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Hero.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Hero berhasil ditambahkan.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/heroes');
    }

    public function edit($id)
    {
        $hero = $this->Hero_model->get_by_id($id);

        if (!$hero) {
            show_404();
        }

        $data = [
            'title' => 'Edit Hero',
            'page_header' => 'Edit Hero',
            'hero' => $hero,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/heroes/edit', $data);
    }

    public function update($id)
    {
        $hero = $this->Hero_model->get_by_id($id);

        if (!$hero) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $data = [
                'badge' => $this->input->post('badge', TRUE),
                'title' => $this->input->post('title', TRUE),
                'subtitle' => $this->input->post('subtitle', TRUE),
                'description' => $this->input->post('description'),
                'primary_button_text' => $this->input->post('primary_button_text', TRUE),
                'primary_button_url' => $this->input->post('primary_button_url', TRUE),
                'secondary_button_text' => $this->input->post('secondary_button_text', TRUE),
                'secondary_button_url' => $this->input->post('secondary_button_url', TRUE),
                'video_url' => $this->input->post('video_url', TRUE),
                'display_order' => (int) $this->input->post('display_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $oldBackground = $hero['background_media_id'];
            $oldHeroImage = $hero['hero_media_id'];

            if (!empty($_FILES['background']['name'])) {

                $data['background_media_id'] = $this->Media_model->upload('background');

                if ($oldBackground) {
                    $this->Media_model->delete($oldBackground);
                }
            }

            if (!empty($_FILES['hero_image']['name'])) {

                $data['hero_media_id'] = $this->Media_model->upload('hero_image');

                if ($oldHeroImage) {
                    $this->Media_model->delete($oldHeroImage);
                }
            }

            $this->Hero_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Hero.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('success', 'Hero berhasil diperbarui.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/heroes');
    }

    public function delete($id)
    {
        $hero = $this->Hero_model->get_by_id($id);

        if (!$hero) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            $this->Hero_model->delete($id);

            // Reorder display_order setelah delete
            $this->Hero_model->reorder_display_order();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus Hero.');
            }

            $this->db->trans_commit();

            /*
             |---------------------------------------------------------
             | Hapus media setelah transaksi database berhasil.
             |---------------------------------------------------------
             */
            if (!empty($hero['background_media_id'])) {
                $this->Media_model->delete($hero['background_media_id']);
            }

            if (!empty($hero['hero_media_id'])) {
                $this->Media_model->delete($hero['hero_media_id']);
            }

            $this->session->set_flashdata('success', 'Hero berhasil dihapus.');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            $this->session->set_flashdata('error', $e->getMessage());
        }

        redirect('admin/heroes');
    }

    public function toggle_status($id)
    {
        $this->Hero_model->toggle_status($id);
        redirect('admin/heroes');
    }

    public function update_order()
    {
        $items = $this->input->post('items');

        if (is_array($items)) {
            foreach ($items as $order => $id) {
                $this->Hero_model->update_order($id, $order + 1);
            }
        }

        echo json_encode(['status' => true]);
    }

    public function move_up($id)
    {
        $this->Hero_model->move_up($id);
        redirect('admin/heroes');
    }

    public function move_down($id)
    {
        $this->Hero_model->move_down($id);
        redirect('admin/heroes');
    }
}
