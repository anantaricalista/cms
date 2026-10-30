<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Core
        $this->load->model('Site_model');

        // Modules
        $this->load->model('Page_model');
        $this->load->model('Menu_model');
    }

    /**
     * Static Page
     */
    public function detail($slug = NULL)
    {
        if (empty($slug)) {
            show_404();
        }

        $page = $this->Page_model->get_by_slug($slug);
        $page = convert_media_fields($page);

        if (!$page) {
            show_404();
        }

        $data = [];

        /*
        |--------------------------------------------------------------------------
        | Site Information
        |--------------------------------------------------------------------------
        */

        $data['site'] = $this->Site_model->get_site();

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $data['title'] = !empty($page['seo_title'])
            ? $page['seo_title']
            : $page['title'];

        $data['description'] = !empty($page['seo_description'])
            ? $page['seo_description']
            : '';

        $data['keywords'] = !empty($page['seo_keywords'])
            ? $page['seo_keywords']
            : '';

        $data['image'] = !empty($page['featured_image_media_id'])
            ? site_url('media/show/' . $page['featured_image_media_id'])
            : '';

        /*
        |--------------------------------------------------------------------------
        | Page
        |--------------------------------------------------------------------------
        */

        $data['page'] = $page;

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/page/detail';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }
}