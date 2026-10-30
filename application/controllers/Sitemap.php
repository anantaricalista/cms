<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Page_model');
        $this->load->model('Service_model');
        $this->load->model('Product_model');
        $this->load->model('Portfolio_model');
        $this->load->model('News_model');
        $this->load->model('Team_model');
        $this->load->model('Testimonial_model');
    }

    /**
     * Generate XML Sitemap
     */
    public function index()
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=86400'); // Cache 24 jam

        $base_url = base_url();
        $sitemap_urls = [];

        // Home page
        $sitemap_urls[] = [
            'url' => $base_url,
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        ];

        // Pages
        $pages = $this->Page_model->get_published();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $sitemap_urls[] = [
                    'url' => base_url('page/' . $page['slug']),
                    'lastmod' => !empty($page['updated_at']) ? date('Y-m-d', strtotime($page['updated_at'])) : date('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        }

        // Services
        $services = $this->Service_model->get_published();
        if (!empty($services)) {
            foreach ($services as $service) {
                $sitemap_urls[] = [
                    'url' => base_url('service/' . $service['slug']),
                    'lastmod' => !empty($service['updated_at']) ? date('Y-m-d', strtotime($service['updated_at'])) : date('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        }

        // Products
        $products = $this->Product_model->get_published();
        if (!empty($products)) {
            foreach ($products as $product) {
                $sitemap_urls[] = [
                    'url' => base_url('product/' . $product['slug']),
                    'lastmod' => !empty($product['updated_at']) ? date('Y-m-d', strtotime($product['updated_at'])) : date('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.8'
                ];
            }
        }

        // Portfolios
        $portfolios = $this->Portfolio_model->get_published();
        if (!empty($portfolios)) {
            foreach ($portfolios as $portfolio) {
                $sitemap_urls[] = [
                    'url' => base_url('portfolio/' . $portfolio['slug']),
                    'lastmod' => !empty($portfolio['updated_at']) ? date('Y-m-d', strtotime($portfolio['updated_at'])) : date('Y-m-d'),
                    'changefreq' => 'monthly',
                    'priority' => '0.7'
                ];
            }
        }

        // News
        $news_list = $this->News_model->get_published();
        if (!empty($news_list)) {
            foreach ($news_list as $news) {
                $sitemap_urls[] = [
                    'url' => base_url('news/' . $news['slug']),
                    'lastmod' => !empty($news['updated_at']) ? date('Y-m-d', strtotime($news['updated_at'])) : date('Y-m-d'),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        // Team
        $sitemap_urls[] = [
            'url' => base_url('team'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];

        // Testimonials
        $sitemap_urls[] = [
            'url' => base_url('testimonial'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'monthly',
            'priority' => '0.6'
        ];

        // Portfolio listing
        $sitemap_urls[] = [
            'url' => base_url('portfolio'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Services listing
        $sitemap_urls[] = [
            'url' => base_url('service'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Products listing
        $sitemap_urls[] = [
            'url' => base_url('product'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // News listing
        $sitemap_urls[] = [
            'url' => base_url('news'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Generate XML
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($sitemap_urls as $url) {
            echo '  <url>' . "\n";
            echo '    <loc>' . htmlspecialchars($url['url'], ENT_XML1) . '</loc>' . "\n";
            echo '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            echo '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            echo '    <priority>' . $url['priority'] . '</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        echo '</urlset>' . "\n";
    }

    /**
     * Generate Sitemap Index (untuk sitemap besar)
     * Berguna jika Anda memiliki lebih dari 50.000 URL
     */
    public function sitemaps()
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=86400');

        $base_url = base_url();

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        echo '  <sitemap>' . "\n";
        echo '    <loc>' . htmlspecialchars($base_url . 'sitemap.xml', ENT_XML1) . '</loc>' . "\n";
        echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
        echo '  </sitemap>' . "\n";
        echo '</sitemapindex>' . "\n";
    }
}
