<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_messages extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Site_model');
        $this->load->model('Contact_message_model');
    }

    public function index()
    {
        $this->render('backend/contact_messages/index', [
            'title' => 'Pesan Kontak',
            'messages' => $this->Contact_message_model->get_all(),
            'site' => $this->Site_model->get()
        ]);
    }

    public function detail($id = null)
    {
        $message = $this->find_message($id);
        $phone = isset($message['phone']) ? $message['phone'] : '';
        $digits = preg_replace('/\D/', '', $phone);
        $reply_url = strlen($digits) >= 7 && strlen($digits) <= 17 && preg_match('/[1-9]/', $digits)
            ? whatsapp_contact_url($phone, 'Halo ' . $message['name'] . ', terima kasih telah menghubungi kami terkait ' . $message['subject'] . '.')
            : '';

        $this->render('backend/contact_messages/detail', [
            'title' => 'Detail Pesan Kontak',
            'message' => $message,
            'reply_url' => $reply_url,
            'action_token' => $this->action_token(),
            'site' => $this->Site_model->get()
        ]);
    }

    public function mark_read($id = null)
    {
        $this->require_post();
        $message = $this->find_message($id);

        if ($this->Contact_message_model->mark_as_read($message['id']) === false) {
            log_message('error', 'Gagal menandai pesan kontak sebagai dibaca: ' . $message['id']);
            $this->session->set_flashdata('error', 'Gagal menandai pesan sebagai dibaca.');
        } else {
            $this->session->set_flashdata('success', 'Pesan ditandai sebagai dibaca.');
        }

        redirect('admin/contact_messages/detail/' . $message['id']);
    }

    public function delete($id = null)
    {
        $this->require_post();
        $message = $this->find_message($id);

        if ($this->Contact_message_model->delete($message['id']) === false) {
            log_message('error', 'Gagal menghapus pesan kontak: ' . $message['id']);
            $this->session->set_flashdata('error', 'Gagal menghapus pesan.');
            redirect('admin/contact_messages/detail/' . $message['id']);
            return;
        }

        $this->session->set_flashdata('success', 'Pesan berhasil dihapus.');
        redirect('admin/contact_messages');
    }

    private function find_message($id)
    {
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            show_404();
        }

        $message = $this->Contact_message_model->get_by_id($id);
        if (!$message) {
            show_404();
        }

        return $message;
    }

    private function require_post()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $expected = $this->session->userdata('contact_messages_action_token');
        $provided = $this->input->post('contact_messages_action_token');
        if (!is_string($expected) || !is_string($provided) || !hash_equals($expected, $provided)) {
            show_error('Permintaan tidak valid. Muat ulang halaman dan coba lagi.', 403);
        }
    }

    private function action_token()
    {
        $token = $this->session->userdata('contact_messages_action_token');
        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('contact_messages_action_token', $token);
        }

        return $token;
    }
}
