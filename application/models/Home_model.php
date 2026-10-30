<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Core
        $this->load->model('Site_model');

        // Homepage Sections
        $this->load->model('Hero_model');
        $this->load->model('About_model');
        $this->load->model('Service_model');
        $this->load->model('Product_model');
        $this->load->model('Portfolio_model');
        $this->load->model('Team_model');
        $this->load->model('Testimonial_model');
        $this->load->model('Client_model');
        $this->load->model('News_model');
        $this->load->model('Menu_model');
    }

    /**
     * Mengambil seluruh data Homepage.
     *
     * @return array
     */
    public function get_homepage_data()
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Site Information
            |--------------------------------------------------------------------------
            */

            'site' => $this->Site_model->get_site(),

            /*
            |--------------------------------------------------------------------------
            | Homepage Sections
            |--------------------------------------------------------------------------
            */

            'hero_slides' => $this->Hero_model->get_active_slides(),

            'about_sections' => $this->About_model->get_active(),

            'services' => $this->Service_model->get_featured(3),

            'products' => $this->Product_model->get_featured(3),

            'portfolios' => $this->Portfolio_model->get_all(),

            'teams' => $this->Team_model->get_all(),

            'testimonials' => $this->Testimonial_model->get_all(),

            'clients' => $this->Client_model->get_all(),

            'news' => $this->News_model->get_latest(3),

            /*
            |--------------------------------------------------------------------------
            | Navigation
            |--------------------------------------------------------------------------
            */

            'menus' => $this->Menu_model->getMenus(),

        ];
    }
}