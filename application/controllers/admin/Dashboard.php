<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        $site       = $this->Dashboard_model->get_site_information();
        $statistics = $this->Dashboard_model->get_statistics();

        $data = [
            'title'       => 'Dashboard',
            'page_header' => 'Dashboard',
            'breadcrumb'  => '
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>',

            'site'        => $site,
            'statistics'  => $statistics,
        ];

        $this->render('backend/dashboard/index', $data);
    }
}