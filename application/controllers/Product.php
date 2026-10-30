<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Core
        $this->load->model('Site_model');

        // Modules
        $this->load->model('Product_model');
        $this->load->model('Menu_model');
    }

    /**
     * Product
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

        $data['title'] = 'Produk';

        $data['description'] = 'Daftar Produk yang siap untuk anda.';

        $data['keywords'] = '';

        $data['image'] = '';

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        $data['products'] = $this->Product_model->get_published();
        $data['products'] = convert_media_fields($data['products']);

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/product/index';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }

    /**
     * Product Detail
     */
    public function detail($slug = NULL)
    {
        if (empty($slug)) {
            show_404();
        }

        $product = $this->Product_model->get_by_slug($slug);
        $product = convert_media_fields($product);

        if (!$product) {
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

        $data['title'] = !empty($product['seo_title'])
            ? $product['seo_title']
            : $product['name'];

        $data['description'] = !empty($product['seo_description'])
            ? $product['seo_description']
            : '';

        $data['keywords'] = !empty($product['seo_keywords'])
            ? $product['seo_keywords']
            : '';

        $data['image'] = !empty($product['featured_image_media_id'])
            ? site_url('media/show/' . $product['featured_image_media_id'])
            : '';

        /*
        |--------------------------------------------------------------------------
        | Product
        |--------------------------------------------------------------------------
        */

        $data['product'] = $product;

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/product/detail';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }
}