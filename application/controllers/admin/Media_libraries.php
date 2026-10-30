<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Media_libraries extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Media_model');
    }

    public function index()
    {
        $page = 1;
        $per_page = 9;
        $search = null;

        $media = $this->Media_model->get_paginated($page, $per_page, $search);
        $total = $this->Media_model->count_total($search);
        $total_pages = ceil($total / $per_page);

        // Generate HTML cards
        $media_html = '';
        if (!empty($media)) {
            foreach ($media as $item) {
                $media_html .= $this->_render_card($item);
            }
        } else {
            $media_html = '<div class="col-12"><p class="text-muted text-center">No media found</p></div>';
        }

        $data = [
            'title' => 'Media Libraries',
            'page_header' => 'Media Libraries',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Media Libraries</li>
        </ol>',
            'media_html' => $media_html,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_media' => $total,
            'per_page' => $per_page,
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/media_libraries/index', $data);
    }

    public function load_more()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $page = $this->input->post('page', TRUE);
        $search = $this->input->post('search', TRUE);
        $per_page = 9;

        $media = $this->Media_model->get_paginated($page, $per_page, $search);

        if (empty($media)) {
            echo json_encode([
                'success' => FALSE,
                'message' => 'No more media to load'
            ]);
            return;
        }

        $html = '';

        foreach ($media as $item) {
            $html .= $this->_render_card($item);
        }

        echo json_encode([
            'success' => TRUE,
            'html' => $html,
            'page' => (int) $page
        ]);
    }

    public function search()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $search = $this->input->post('search', TRUE);
        $page = 1;
        $per_page = 9;

        $media = $this->Media_model->get_paginated($page, $per_page, $search);
        $total = $this->Media_model->count_total($search);
        $total_pages = ceil($total / $per_page);

        $html = '';

        if (empty($media)) {
            $html = '<div class="col-12"><p class="text-muted text-center">No media found</p></div>';
        } else {
            foreach ($media as $item) {
                $html .= $this->_render_card($item);
            }
        }

        echo json_encode([
            'success' => TRUE,
            'html' => $html,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_media' => $total
        ]);
    }

    public function preview($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $media = $this->Media_model->get_by_id($id);

        if (!$media) {
            echo json_encode([
                'success' => FALSE,
                'message' => 'Media not found'
            ]);
            return;
        }

        // Convert file_content to base64 for embedding
        $file_content_base64 = base64_encode($media['file_content']);
        $data_uri = 'data:' . $media['mime_type'] . ';base64,' . $file_content_base64;

        echo json_encode([
            'success' => TRUE,
            'id' => $media['id'],
            'uuid' => $media['uuid'],
            'original_filename' => html_escape($media['original_filename']),
            'mime_type' => $media['mime_type'],
            'file_size' => $this->_format_bytes($media['file_size']),
            'width' => $media['width'],
            'height' => $media['height'],
            'used_count' => $media['used_count'],
            'created_at' => date('d M Y H:i', strtotime($media['created_at'])),
            'data_uri' => $data_uri,
            'is_image' => strpos($media['mime_type'], 'image/') === 0,
            'is_pdf' => $media['mime_type'] === 'application/pdf'
        ]);
    }

    public function download($id)
    {
        $media = $this->Media_model->get_by_id($id);

        if (!$media) {
            show_404();
        }

        // Set header untuk download
        header('Content-Type: ' . $media['mime_type']);
        header('Content-Disposition: attachment; filename="' . $media['original_filename'] . '"');
        header('Content-Length: ' . $media['file_size']);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $media['file_content'];
        exit;
    }

    /**
     * Private method untuk render single card.
     *
     * @param array $media
     * @return string
     */
    private function _render_card($media)
    {
        $is_image = strpos($media['mime_type'], 'image/') === 0;
        $file_size = $this->_format_bytes($media['file_size']);
        $filename = html_escape($media['original_filename']);
        // Truncate filename for display
        $display_name = strlen($filename) > 28 ? substr($filename, 0, 25) . '...' : $filename;

        $html = '
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card media-card h-100" data-media-id="' . $media['id'] . '" style="border: none; border-radius: 8px; overflow: hidden;">
                <!-- Image Preview Area -->
                <div class="card-img-top" style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
        ';

        if ($is_image) {
            // Fetch full media data including file_content for image preview
            $full_media = $this->Media_model->get_by_id($media['id']);
            if ($full_media) {
                $file_content_base64 = base64_encode($full_media['file_content']);
                $data_uri = 'data:' . $media['mime_type'] . ';base64,' . $file_content_base64;

                $html .= '
                    <img src="' . $data_uri . '" alt="' . $filename . '" class="w-100" style="height: 100%; object-fit: cover;">
                ';
            } else {
                $icon = $this->_get_file_icon($media['mime_type']);
                $html .= '
                    <div class="text-center text-white">
                        <div style="font-size: 60px;">' . $icon . '</div>
                    </div>
                ';
            }
        } else {
            // File icon
            $icon = $this->_get_file_icon($media['mime_type']);
            $html .= '
                    <div class="text-center text-white w-100">
                        <div style="font-size: 60px;">' . $icon . '</div>
                    </div>
            ';
        }

        $html .= '
                </div>

                <!-- Card Body -->
                <div class="card-body p-3">
                    <!-- Filename -->
                    <h6 class="mb-2 text-truncate" title="' . $filename . '" style="font-size: 14px; font-weight: 600; color: #2c3e50; margin-bottom: 12px;">
                        ' . $display_name . '
                    </h6>
                    
                    <!-- File Details -->
                    <div class="row g-2 mb-3" style="font-size: 13px;">
                        <div class="col-6">
                            <div style="background: #f8f9fa; padding: 8px 10px; border-radius: 4px;">
                                <div class="text-muted" style="font-size: 11px; margin-bottom: 2px;">Size</div>
                                <div class="text-dark" style="font-weight: 600;">' . $file_size . '</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background: #f8f9fa; padding: 8px 10px; border-radius: 4px;">
                                <div class="text-muted" style="font-size: 11px; margin-bottom: 2px;">Used</div>
                                <div class="text-dark" style="font-weight: 600;">' . $media['used_count'] . 'x</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-primary btn-preview" data-media-id="' . $media['id'] . '" style="font-size: 12px; padding: 8px; border-radius: 4px;">
                            <i class="fas fa-eye me-1"></i> Preview
                        </button>
                        <a href="' . site_url('admin/media_libraries/download/' . $media['id']) . '" class="btn btn-sm btn-outline-secondary" style="font-size: 12px; padding: 8px; border-radius: 4px;">
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
        ';

        return $html;
    }

    /**
     * Private method untuk format bytes ke human readable.
     *
     * @param int $bytes
     * @return string
     */
    private function _format_bytes($bytes)
    {
        $bytes = (int) $bytes;
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Private method untuk get file icon berdasarkan mime type.
     *
     * @param string $mime_type
     * @return string
     */
    private function _get_file_icon($mime_type)
    {
        if (strpos($mime_type, 'image/') === 0) {
            return '<i class="fas fa-image"></i>';
        } elseif (strpos($mime_type, 'video/') === 0) {
            return '<i class="fas fa-video"></i>';
        } elseif (strpos($mime_type, 'audio/') === 0) {
            return '<i class="fas fa-music"></i>';
        } elseif ($mime_type === 'application/pdf') {
            return '<i class="fas fa-file-pdf"></i>';
        } elseif (strpos($mime_type, 'word') !== FALSE || strpos($mime_type, 'document') !== FALSE) {
            return '<i class="fas fa-file-word"></i>';
        } elseif (strpos($mime_type, 'excel') !== FALSE || strpos($mime_type, 'spreadsheet') !== FALSE) {
            return '<i class="fas fa-file-excel"></i>';
        } elseif (strpos($mime_type, 'powerpoint') !== FALSE || strpos($mime_type, 'presentation') !== FALSE) {
            return '<i class="fas fa-file-powerpoint"></i>';
        } elseif (strpos($mime_type, 'text/') === 0) {
            return '<i class="fas fa-file-alt"></i>';
        } elseif (strpos($mime_type, 'archive') !== FALSE || strpos($mime_type, 'compressed') !== FALSE) {
            return '<i class="fas fa-file-archive"></i>';
        } else {
            return '<i class="fas fa-file"></i>';
        }
    }
}
