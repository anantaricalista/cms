<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Media_model');
    }

    /**
     * Stream media ke browser.
     *
     * URL:
     * /media/show/{id}
     */
    public function show($key = null)
    {
        if (empty($key)) {
            show_404();
        }

        $media = $this->Media_model->find($key);

        if (!$media) {
            show_404();
        }

        header('Content-Type: ' . $media->mime_type);
        header('Content-Length: ' . $media->file_size);
        header('Cache-Control: public, max-age=86400');
        header(
            'Content-Disposition: inline; filename="' .
            $media->stored_filename . '.' . $media->extension .
            '"'
        );
        echo $media->file_content;
        exit;
    }
}