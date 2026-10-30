<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Site_model');
        $this->load->model('Team_model');
        $this->load->model('Menu_model');
    }

    /**
     * Team Listing
     */
    public function index()
    {
        $this->load->library('pagination');
        $data = [];

        $data['site'] = $this->Site_model->get_site();
        $data['site'] = convert_media_fields($data['site']);

        $data['title'] = 'Tim Kami';
        $data['description'] = 'Kenali para profesional berpengalaman yang siap membantu kesuksesan bisnis Anda.';
        $data['keywords'] = 'tim, profesional, team member';
        $data['image'] = '';

        $per_page = 9;
        $page = (int) $this->input->get('page');
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $per_page;

        $total_rows = $this->Team_model->count_published();
        $data['team'] = $this->Team_model->get_published($per_page, $offset);
        $data['team'] = convert_media_fields($data['team']);

        $config['base_url'] = site_url('team');
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['prev_link'] = '<i class="fa-solid fa-chevron-left"></i>';
        $config['next_link'] = '<i class="fa-solid fa-chevron-right"></i>';

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if ($this->input->is_ajax_request()) {
            $this->load->view('frontend/team/list', $data);
            return;
        }

        $data['content'] = 'frontend/team/index';
        $data['menus'] = $this->Menu_model->getMenus();
        $this->load->view('frontend/layouts/master', $data);
    }
}
