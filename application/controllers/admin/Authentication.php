<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('Authentication_model');
        $this->load->model('Site_model');
    }

    public function index()
    {
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
            return;
        }

        $this->login();
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
            return;
        }

        if ($this->input->method() === 'post') {

            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {

                $user = $this->Authentication_model->login(
                    $this->input->post('email', TRUE),
                    $this->input->post('password', TRUE)
                );
                $perusahaan = $this->Site_model->get()->site_name;
                if ($user) {

                    $this->session->set_userdata([
                        'user_id' => $user->id,
                        'role_id' => $user->role_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'perusahaan'=>$perusahaan
                    ]);

                    redirect('admin/dashboard');
                    return;
                }

                $this->session->set_flashdata(
                    'error',
                    'Email atau password tidak valid.'
                );
            }
        }

        $this->load->view('backend/auth/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/authentication');
    }
}
