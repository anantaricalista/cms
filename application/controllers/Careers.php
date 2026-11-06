<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Site_model');
        $this->load->model('Menu_model');
        $this->load->model('Career_model');
    }

    public function index()
    {
        $data = [
            'site' => convert_media_fields($this->Site_model->get_site()),
            'menus' => $this->Menu_model->getMenus(),
            'title' => 'Karier',
            'description' => 'Peluang karier yang tersedia.',
            'careers' => $this->Career_model->get_all(),
            'content' => 'frontend/careers/index'
        ];

        $this->load->view('frontend/layouts/master', $data);
    }
}
