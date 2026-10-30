<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller
 * Digunakan oleh seluruh controller backend CMS.
 */
class MY_Controller extends CI_Controller
{
    /**
     * Data user yang sedang login.
     *
     * @var array
     */
    protected $current_user = [];

    public function __construct()
    {
        parent::__construct();

        // Helper & Library Global
        $this->load->helper(['url', 'form']);
        $this->load->library(['session']);

        // Validasi Login
        if (!$this->session->userdata('user_id')) {
            redirect('admin/authentication');
            exit;
        }

        // Simpan informasi user aktif
        $this->current_user = [
            'id'      => $this->session->userdata('user_id'),
            'role_id' => $this->session->userdata('role_id'),
            'name'    => $this->session->userdata('name'),
            'email'   => $this->session->userdata('email'),
        ];

        // Share ke semua view backend
        $this->load->vars([
            'current_user' => $this->current_user
        ]);
    }

    /**
     * Render halaman backend menggunakan master layout.
     *
     * @param string $content
     * @param array  $data
     */
    protected function render($content, array $data = [])
    {
        $defaults = [
            'title'       => 'Dashboard',
            'page_header' => '',
            'breadcrumb'  => '',
            'content'     => $content,
            'scripts'     => ''
        ];

        $this->load->view(
            'backend/layout/master',
            array_merge($defaults, $data)
        );
    }

    /**
     * Mengambil data user aktif.
     *
     * @return array
     */
    protected function currentUser()
    {
        return $this->current_user;
    }

    /**
     * Placeholder Role Checking.
     */
    protected function requireRole($role)
    {
        // TODO: Implement Role Checking
    }

    /**
     * Placeholder Permission Checking.
     */
    protected function requirePermission($permission)
    {
        // TODO: Implement Permission Checking
    }
}
