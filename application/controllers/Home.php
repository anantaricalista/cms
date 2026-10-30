<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Home_model');
    }

    /**
     * Homepage
     */
    public function index()
    {
        $data = $this->Home_model->get_homepage_data();

        $data = convert_media_fields($data);

        $data['content'] = 'frontend/home/index';

        $this->load->view('frontend/layouts/master', $data);
    }
}