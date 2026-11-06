<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Site_model');
        $this->load->model('Menu_model');
        $this->load->model('Download_model');
    }

    public function index()
    {
        $data = [
            'site' => convert_media_fields($this->Site_model->get_site()),
            'menus' => $this->Menu_model->getMenus(),
            'title' => 'Downloads',
            'description' => 'Dokumen yang tersedia untuk diunduh.',
            'downloads' => $this->Download_model->get_published(),
            'content' => 'frontend/downloads/index'
        ];

        $this->load->view('frontend/layouts/master', $data);
    }
}
