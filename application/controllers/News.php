<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Core
        $this->load->model('Site_model');

        // Modules
        $this->load->model('News_model');
        $this->load->model('Menu_model');
    }

    /**
     * News
     */
    public function index()
    {
        $this->load->library('pagination');

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
        | Category
        |--------------------------------------------------------------------------
        */

        $category_slug = trim($this->input->get('category'));

        $data['category'] = null;

        if (!empty($category_slug)) {
            $data['category'] = $this->News_model->get_category_by_slug($category_slug);

            if (!$data['category']) {
                show_404();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        if (!empty($data['category'])) {
            $data['title'] = $data['category']['name'];

            $data['description'] =
                'Berita dan artikel dalam kategori ' .
                $data['category']['name'] . '.';

            $data['keywords'] =
                $data['category']['name'];
        } else {
            $data['title'] = 'Berita';

            $data['description'] =
                'Berita, artikel, dan informasi terbaru dari perusahaan kami.';

            $data['keywords'] = '';
        }

        $data['image'] = '';

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $per_page = 9;

        $page = (int) $this->input->get('page');

        if ($page < 1) {
            $page = 1;
        }

        $offset = ($page - 1) * $per_page;

        /*
        |--------------------------------------------------------------------------
        | News
        |--------------------------------------------------------------------------
        */

        if (!empty($data['category'])) {
            $total_rows = $this->News_model
                ->count_published_by_category(
                    $data['category']['id']
                );

            $data['news'] = $this->News_model
                ->get_published_by_category(
                    $data['category']['id'],
                    $per_page,
                    $offset
                );
        } else {
            $total_rows = $this->News_model
                ->count_published();

            $data['news'] = $this->News_model
                ->get_published(
                    $per_page,
                    $offset
                );
        }

        $data['news'] = convert_media_fields($data['news']);

        /*
        |--------------------------------------------------------------------------
        | Pagination Configuration
        |--------------------------------------------------------------------------
        */

        $config['base_url'] = site_url('news');

        $config['page_query_string'] = TRUE;

        $config['query_string_segment'] = 'page';

        $config['reuse_query_string'] = TRUE;

        $config['use_page_numbers'] = TRUE;

        $config['total_rows'] = $total_rows;

        $config['per_page'] = $per_page;

        $config['full_tag_open'] =
            '<nav><ul class="pagination">';

        $config['full_tag_close'] =
            '</ul></nav>';

        $config['num_tag_open'] =
            '<li class="page-item">';

        $config['num_tag_close'] =
            '</li>';

        $config['cur_tag_open'] =
            '<li class="page-item active"><span class="page-link">';

        $config['cur_tag_close'] =
            '</span></li>';

        $config['next_tag_open'] =
            '<li class="page-item">';

        $config['next_tag_close'] =
            '</li>';

        $config['prev_tag_open'] =
            '<li class="page-item">';

        $config['prev_tag_close'] =
            '</li>';

        $config['first_tag_open'] =
            '<li class="page-item">';

        $config['first_tag_close'] =
            '</li>';

        $config['last_tag_open'] =
            '<li class="page-item">';

        $config['last_tag_close'] =
            '</li>';

        $config['attributes'] = [
            'class' => 'page-link'
        ];

        $config['first_link'] = FALSE;

        $config['last_link'] = FALSE;

        $config['prev_link'] =
            '<i class="fa-solid fa-chevron-left"></i>';

        $config['next_link'] =
            '<i class="fa-solid fa-chevron-right"></i>';

        $this->pagination->initialize($config);

        $data['pagination'] =
            $this->pagination->create_links();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $data['categories'] =
            $this->News_model->get_news_categories();

        /*
        |--------------------------------------------------------------------------
        | AJAX Request
        |--------------------------------------------------------------------------
        */

        if ($this->input->is_ajax_request()) {
            $this->load->view(
                'frontend/news/list',
                $data
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] = 'frontend/news/index';

        $data['menus'] = $this->Menu_model->getMenus();

        $this->load->view('frontend/layouts/master', $data);
    }

    /**
     * News Detail
     */
    public function detail($slug = NULL)
    {
        if (empty($slug)) {
            show_404();
        }

        $article = $this->News_model->get_by_slug($slug);

        if (!$article) {
            show_404();
        }

        $article = convert_media_fields($article);

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

        $data['title'] = !empty($article['seo_title'])
            ? $article['seo_title']
            : $article['title'];

        $data['description'] = !empty($article['seo_description'])
            ? $article['seo_description']
            : '';

        $data['keywords'] = !empty($article['seo_keywords'])
            ? $article['seo_keywords']
            : '';

        $data['image'] = !empty($article['featured_image_media_id'])
            ? site_url('media/show/' . $article['featured_image_media_id'])
            : '';

        /*
        |--------------------------------------------------------------------------
        | News
        |--------------------------------------------------------------------------
        */

        $data['news'] = $article;

        /*
        |--------------------------------------------------------------------------
        | News Categories
        |--------------------------------------------------------------------------
        */

        $data['news_categories'] =
            $this->News_model->get_categories_by_news(
                $article['id']
            );

        /*
        |--------------------------------------------------------------------------
        | Sidebar Categories
        |--------------------------------------------------------------------------
        */

        $data['categories'] =
            $this->News_model->get_news_categories();

        /*
        |--------------------------------------------------------------------------
        | Load View
        |--------------------------------------------------------------------------
        */

        $data['content'] =
            'frontend/news/detail';

        $data['menus'] =
            $this->Menu_model->getMenus();

        $this->load->view(
            'frontend/layouts/master',
            $data
        );
    }
}