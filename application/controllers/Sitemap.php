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
        $this->load->model('Client_model');
        $this->load->model('Career_model');
        $this->load->model('Download_model');
    }

    /**
     * Generate XML Sitemap
     */
    public function index()
    {
        header('Content-Type: application/xml; charset=UTF-8');
        header('Cache-Control: public, max-age=86400'); // Cache 24 jam

        $urls = [];

        $urls[] = $this->entry(base_url(), date('Y-m-d'), 'weekly', '1.0');

        // Halaman daftar (listing) yang tersedia di frontend
        $listings = [
            ['service',     $this->Service_model->get_published(), 'weekly',  '0.8'],
            ['product',     $this->Product_model->get_published(), 'weekly',  '0.8'],
            ['portfolio',   $this->Portfolio_model->get_published(), 'weekly', '0.8'],
            ['news',        $this->News_model->get_published(), 'weekly',  '0.8'],
            ['careers',     $this->Career_model->get_all(), 'weekly', '0.6'],
            ['downloads',   $this->Download_model->get_published(), 'monthly', '0.6'],
            ['team',        $this->Team_model->get_published(), 'monthly', '0.7'],
            ['client',      $this->Client_model->get_published(), 'monthly', '0.6'],
            ['testimonial', $this->Testimonial_model->get_published(), 'monthly', '0.6'],
        ];

        foreach ($listings as $listing) {
            list($path, $items, $freq, $priority) = $listing;

            // Listing kosong tidak dimasukkan agar tidak ada halaman tanpa konten
            if (empty($items)) {
                continue;
            }

            $urls[] = $this->entry(base_url($path), $this->latest($items), $freq, $priority);
        }

        // Halaman detail yang memiliki slug dan route detail
        $details = [
            ['page',    $this->Page_model->get_published(),    'monthly', '0.8'],
            ['service', $this->Service_model->get_published(), 'monthly', '0.8'],
            ['product', $this->Product_model->get_published(), 'monthly', '0.8'],
            ['news',    $this->News_model->get_published(),    'weekly',  '0.7'],
        ];

        foreach ($details as $detail) {
            list($path, $items, $freq, $priority) = $detail;

            foreach ((array) $items as $item) {
                if (empty($item['slug'])) {
                    continue;
                }

                $urls[] = $this->entry(
                    base_url($path . '/' . rawurlencode($item['slug'])),
                    $this->item_date($item),
                    $freq,
                    $priority
                );
            }
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            echo '  <url>' . "\n";
            echo '    <loc>' . htmlspecialchars($url['url'], ENT_XML1) . '</loc>' . "\n";
            echo '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            echo '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            echo '    <priority>' . $url['priority'] . '</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        echo '</urlset>' . "\n";
    }

    private function entry($url, $lastmod, $changefreq, $priority)
    {
        return [
            'url' => $url,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function item_date($item)
    {
        foreach (['updated_at', 'published_at', 'created_at'] as $field) {
            if (!empty($item[$field]) && strtotime($item[$field])) {
                return date('Y-m-d', strtotime($item[$field]));
            }
        }

        return date('Y-m-d');
    }

    // Tanggal terbaru dari sekumpulan item, untuk lastmod halaman listing
    private function latest($items)
    {
        $latest = 0;

        foreach ($items as $item) {
            $time = strtotime($this->item_date($item));
            $latest = max($latest, $time);
        }

        return $latest ? date('Y-m-d', $latest) : date('Y-m-d');
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
