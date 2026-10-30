<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('convert_media_fields')) {
    /**
     * Mengubah seluruh field yang berakhiran "media_id"
     * menjadi "stored_filename.extension".
     *
     * Contoh:
     * logo_media_id => logo-company.webp
     *
     * @param mixed $data
     * @return mixed
     */
    function convert_media_fields($data)
    {
        $CI =& get_instance();

        if (!isset($CI->Media_model)) {
            $CI->load->model('Media_model');
        }

        $ids = [];

        /*
        |--------------------------------------------------------------------------
        | Collect Media IDs
        |--------------------------------------------------------------------------
        */
        collect_media_ids($data, $ids);

        $ids = array_unique(array_filter($ids));

        if (empty($ids)) {
            return $data;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Media Map
        |--------------------------------------------------------------------------
        */
        $media_map = $CI->Media_model->getFilenameMap($ids);

        /*
        |--------------------------------------------------------------------------
        | Replace Media IDs
        |--------------------------------------------------------------------------
        */
        replace_media_ids($data, $media_map);

        return $data;
    }
}

if (!function_exists('collect_media_ids')) {
    /**
     * Mengumpulkan seluruh Media ID dari array multidimensi.
     *
     * @param mixed $data
     * @param array $ids
     */
    function collect_media_ids($data, &$ids)
    {
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                collect_media_ids($value, $ids);
                continue;
            }

            if (substr($key, -8) === 'media_id' && !empty($value)) {
                $ids[] = $value;
            }
        }
    }
}

if (!function_exists('replace_media_ids')) {
    /**
     * Mengganti seluruh media_id menjadi filename.
     *
     * @param mixed $data
     * @param array $media_map
     */
    function replace_media_ids(&$data, array $media_map)
    {
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                replace_media_ids($value, $media_map);
                continue;
            }

            if (substr($key, -8) === 'media_id') {
                if (isset($media_map[$value])) {
                    $value = $media_map[$value];
                }
            }
        }
    }
}