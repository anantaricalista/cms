<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    /**
     * Get website information.
     *
     * @return object|null
     */
    public function get_site_information()
    {
        return $this->db
            ->select('ss.*, lm.stored_filename AS logo_file, fm.stored_filename AS favicon_file')
            ->from('site_settings ss')
            ->join('media lm', 'lm.id = ss.logo_media_id', 'left')
            ->join('media fm', 'fm.id = ss.favicon_media_id', 'left')
            ->limit(1)
            ->get()
            ->row();
    }

    /**
     * Dashboard statistics.
     *
     * @return array
     */
    public function get_statistics()
    {
        return [
            'pages'         => $this->db->count_all('pages'),
            'news'          => $this->db->count_all('news'),
            'media'         => $this->db->count_all('media'),
            'portfolios'    => $this->db->count_all('portfolios'),
            'services'      => $this->db->count_all('services'),
            'products'      => $this->db->count_all('products'),
            'galleries'     => $this->db->count_all('galleries'),
            'downloads'     => $this->db->count_all('downloads'),
            'teams'         => $this->db->count_all('teams'),
            'clients'       => $this->db->count_all('clients'),
            'testimonials'  => $this->db->count_all('testimonials'),
            'careers'       => $this->db->count_all('careers'),
            'users'         => $this->db->count_all('users'),
        ];
    }
}
