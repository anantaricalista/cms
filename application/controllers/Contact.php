<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contact_message_model');
        $this->load->library('form_validation');
    }

    /**
     * Handle contact form submission
     */
    public function submit()
    {
        // Check if POST request
        if (!$this->input->is_ajax_request() || $this->input->method() !== 'post') {
            return $this->json_response(false, 'Invalid request', null, 400);
        }

        // Set validation rules
        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|max_length[255]');
        $this->form_validation->set_rules('phone', 'Nomor Telepon', 'trim|max_length[100]');
        $this->form_validation->set_rules('subject', 'Perihal', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('message', 'Pesan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            return $this->json_response(false, 'Validation error', $errors, 422);
        }

        // Get input data
        $data = [
            'name' => $this->input->post('name', TRUE),
            'email' => $this->input->post('email', TRUE),
            'phone' => $this->input->post('phone', TRUE),
            'subject' => $this->input->post('subject', TRUE),
            'message' => $this->input->post('message', TRUE),
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Save to database
        $uuid = $this->Contact_message_model->insert($data);

        if ($uuid === false) {
            return $this->json_response(false, 'Gagal menyimpan pesan', null, 500);
        }

        // Prepare response data
        $response_data = [
            'uuid' => $uuid,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message']
        ];

        return $this->json_response(true, 'Pesan berhasil dikirim', $response_data, 200);
    }

    /**
     * Send JSON response
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $status_code
     */
    private function json_response($success, $message, $data = null, $status_code = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode([
                'success' => $success,
                'message' => $message,
                'data' => $data
            ]));
    }
}
