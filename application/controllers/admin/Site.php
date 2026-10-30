<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Media_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title' => 'Site Settings',
            'page_header' => 'Site Settings',
            'breadcrumb' => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="' . site_url('admin/dashboard') . '">Dashboard</a></li>
                    <li class="breadcrumb-item active">Site Settings</li>
                </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/site/index', $data);
    }

    public function update()
    {
        $this->form_validation->set_rules('site_name', 'Website Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');

        if ($this->form_validation->run() === FALSE) {
            return $this->index();
        }

        $this->db->trans_begin();
        try {
            $site = $this->Site_model->get();
            $oldLogoMediaId = $site->logo_media_id;
            $oldFaviconMediaId = $site->favicon_media_id;
            $data = [
                'site_name' => $this->input->post('site_name', TRUE),
                'company_name' => $this->input->post('company_name', TRUE),
                'tagline' => $this->input->post('tagline', TRUE),
                'company_summary' => $this->input->post('company_summary', TRUE),
                'email' => $this->input->post('email', TRUE),
                'phone' => $this->input->post('phone', TRUE),
                'address' => $this->input->post('address', TRUE),
                'google_maps_embed' => $this->input->post('google_maps_embed'),
                'primary_color' => $this->input->post('primary_color', TRUE),
                'secondary_color' => $this->input->post('secondary_color', TRUE),
                'accent_color' => $this->input->post('accent_color', TRUE),
                'seo_title' => $this->input->post('seo_title', TRUE),
                'seo_description' => $this->input->post('seo_description', TRUE),
                'seo_keywords' => $this->input->post('seo_keywords', TRUE),
                'copyright' => $this->input->post('copyright', TRUE),
            ];

            /*
            |--------------------------------------------------------------------------
            | Upload Logo
            |--------------------------------------------------------------------------
            */

            if (!empty($_FILES['logo']['name'])) {

                $logoMediaId = $this->Media_model->upload('logo');

                if (!$logoMediaId) {
                    throw new Exception('Failed to upload logo.');
                }

                $data['logo_media_id'] = $logoMediaId;

            } else {

                $data['logo_media_id'] = $oldLogoMediaId;

            }

            /*
            |--------------------------------------------------------------------------
            | Upload Favicon
            |--------------------------------------------------------------------------
            */

            if (!empty($_FILES['favicon']['name'])) {

                $faviconMediaId = $this->Media_model->upload('favicon');

                if (!$faviconMediaId) {
                    throw new Exception('Failed to upload favicon.');
                }

                $data['favicon_media_id'] = $faviconMediaId;

            } else {

                $data['favicon_media_id'] = $oldFaviconMediaId;

            }

            if (!$this->Site_model->update($data)) {
                throw new Exception('Failed to update site settings.');
            }
            
            $this->db->trans_commit();
            /*
            |--------------------------------------------------------------------------
            | Delete Old Logo
            |--------------------------------------------------------------------------
            */

            if (
                !empty($_FILES['logo']['name']) &&
                !empty($oldLogoMediaId)
            ) {
                $this->Media_model->delete($oldLogoMediaId);
            }

            /*
            |--------------------------------------------------------------------------
            | Delete Old Favicon
            |--------------------------------------------------------------------------
            */

            if (
                !empty($_FILES['favicon']['name']) &&
                !empty($oldFaviconMediaId)
            ) {
                $this->Media_model->delete($oldFaviconMediaId);
            }
            
            $this->session->set_flashdata(
                'success',
                'Site settings updated successfully.'
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );
        }

        redirect('admin/site');
    }
}
