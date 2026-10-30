<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_model extends CI_Model
{
    protected $table = 'site_settings';

    public function get()
    {
        return $this->db
            ->select('
                ss.*,
                logo.original_filename AS logo_name,
                logo.stored_filename AS logo_file,
                favicon.original_filename AS favicon_name,
                favicon.stored_filename AS favicon_file
            ')
            ->from($this->table.' ss')
            ->join('media logo','logo.id = ss.logo_media_id','left')
            ->join('media favicon','favicon.id = ss.favicon_media_id','left')
            ->limit(1)
            ->get()
            ->row();
    }

    public function update(array $data)
    {
        $row = $this->db->select('id')->from($this->table)->limit(1)->get()->row();

        if (!$row) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db
            ->where('id', $row->id)
            ->update($this->table, $data);
    }

    public function get_site()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->limit(1)
            ->get()
            ->row_array();
    }
}
