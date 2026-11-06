<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('whatsapp_contact_url')) {
    function whatsapp_contact_url($phone, $message = '')
    {
        $input = trim((string) $phone);
        $phone = preg_replace('/\D/', '', $input);

        if ($phone === '') {
            return site_url() . '#contact';
        }

        if (strpos($input, '+') !== 0 && strpos($phone, '00') === 0) {
            $phone = substr($phone, 2);
        } elseif (strpos($input, '+') !== 0 && strpos($phone, '62') !== 0) {
            $phone = (strpos($phone, '0') === 0)
                ? '62' . substr($phone, 1)
                : '62' . $phone;
        }

        if ($phone === '') {
            return site_url() . '#contact';
        }

        return 'https://api.whatsapp.com/send?' . http_build_query([
            'phone' => $phone,
            'text' => $message
        ]);
    }
}
