<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller for all backend modules.
 */
class Admin_Controller extends CI_Controller
{
    protected $current_user = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['url', 'form']);
        $this->load->library(['session']);

        // User must be authenticated
        if (!$this->session->userdata('user_id')) {
            redirect('admin/authentication');
            exit;
        }

        $this->current_user = [
            'id'      => $this->session->userdata('user_id'),
            'role_id' => $this->session->userdata('role_id'),
            'name'    => $this->session->userdata('name'),
            'email'   => $this->session->userdata('email'),
        ];

        // Available in every backend view
        $this->load->vars([
            'current_user' => $this->current_user
        ]);
    }

    /**
     * Render backend layout.
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

    protected function currentUser()
    {
        return $this->current_user;
    }

    protected function requireRole($role)
    {
        // Reserved for next phase.
    }

    protected function requirePermission($permission)
    {
        // Reserved for next phase.
    }
}
