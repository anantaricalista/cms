<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Core
        $this->load->model('Site_model');

        // Modules
        $this->load->model('Service_model');
        $this->load->model('Menu_model');
    }

    /**
     * Services
     */
    public function index()
    {
        $data = [];

        /*
        |--------------------------------------------------------------------------
        | Site Information
        |--------------------------------------------------------------------------
        */

        $data['site'] = $this->Site_model->get_site();
        $data['site'] = convert_media_fields($data['site']);

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $data['title'] = 'Layanan';

        $data['description'] = 'Daftar layanan yang kami sediakan.';

        $data['keywords'] = '';

        $data['image'] = '';

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $data['services'] = $this->Service_model->get_published();
        $data['services'] = convert_media_fields($data['services']);

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/service/index';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }

    /**
     * Service Detail
     */
    public function detail($slug = NULL)
    {
        if (empty($slug)) {
            show_404();
        }

        $service = $this->Service_model->get_by_slug($slug);
        $service = convert_media_fields($service);

        if (!$service) {
            show_404();
        }

        $data = [];

        /*
        |--------------------------------------------------------------------------
        | Site Information
        |--------------------------------------------------------------------------
        */

        $data['site'] = $this->Site_model->get_site();
        $data['site'] = convert_media_fields($data['site']);

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $data['title'] = !empty($service['seo_title'])
            ? $service['seo_title']
            : $service['name'];

        $data['description'] = !empty($service['seo_description'])
            ? $service['seo_description']
            : '';

        $data['keywords'] = !empty($service['seo_keywords'])
            ? $service['seo_keywords']
            : '';

        $data['image'] = !empty($service['featured_image_media_id'])
            ? site_url('media/show/' . $service['featured_image_media_id'])
            : '';

        /*
        |--------------------------------------------------------------------------
        | Service
        |--------------------------------------------------------------------------
        */

        $data['service'] = $service;

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/service/detail';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }
}