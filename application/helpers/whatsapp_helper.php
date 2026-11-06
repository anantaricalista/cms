<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('whatsapp_contact_url')) {
    function whatsapp_contact_url($phone, $message = '')
    {
        $phone = preg_replace('/\D/', '', (string) $phone);

        if ($phone === '') {
            return site_url() . '#contact';
        }

        if (strpos($phone, '62') !== 0) {
            $phone = (strpos($phone, '0') === 0)
                ? '62' . substr($phone, 1)
                : '62' . $phone;
        }

        return 'https://api.whatsapp.com/send?' . http_build_query([
            'phone' => $phone,
            'text' => $message
        ]);
    }
}
