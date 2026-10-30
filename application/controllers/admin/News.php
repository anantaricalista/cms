<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('News_model');
        $this->load->model('News_category_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }

    public function index()
    {
        $data = [
            'title' => 'News',
            'page_header' => 'News',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">News</li>
        </ol>',
            'categories' => $this->News_category_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/news/index', $data);
    }

    public function datatable()
    {
        $list = $this->News_model->get_datatables();

        $data = [];
        $no = (int) $this->input->post('start');

        foreach ($list as $news) {

            $no++;

            if ($news['status'] == 'published') {
                $status = '<span class="badge bg-success">Published</span>';
            } elseif ($news['status'] == 'draft') {
                $status = '<span class="badge bg-secondary">Draft</span>';
            } else {
                $status = '<span class="badge bg-warning">'
                    . ucfirst(html_escape($news['status']))
                    . '</span>';
            }

            $image = '-';

            if (!empty($news['featured_image_media_id'])) {
                $image = '
                <img src="' . site_url('media/show/' . $news['featured_image_media_id']) . '"
                     class="img-thumbnail"
                     style="width:70px;height:50px;object-fit:cover;">';
            }

            $published = '-';

            if (!empty($news['published_at'])) {
                $published = date(
                    'd M Y H:i',
                    strtotime($news['published_at'])
                );
            }

            $data[] = [
                $no,
                $image,
                html_escape($news['title']),
                html_escape($news['category_names']),
                $status,
                $published,
                '
            <div class="text-center">

                <a href="' . site_url('news/' . $news['slug']) . '"
                   target="_blank"
                   class="btn btn-info btn-sm"
                   title="View News">
                    <i class="fas fa-eye"></i>
                </a>

                <a href="' . site_url('admin/news/edit/' . $news['id']) . '"
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i>
                </a>

                <a href="' . site_url('admin/news/delete/' . $news['id']) . '"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm(\'Yakin ingin menghapus berita ini?\')">
                    <i class="fas fa-trash"></i>
                </a>

            </div>'
            ];
        }

        $output = [
            'draw' => (int) $this->input->post('draw'),
            'recordsTotal' => $this->News_model->count_all(),
            'recordsFiltered' => $this->News_model->count_filtered(),
            'data' => $data
        ];

        echo json_encode($output);
    }

    public function create()
    {
        $data = [
            'title' => 'Create News',
            'page_header' => 'Create News',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/news') . '">News</a>
            </li>
            <li class="breadcrumb-item active">Create News</li>
        </ol>',
            'categories' => $this->News_category_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/news/create', $data);
    }

    public function store()
    {
        $categoryIds = $this->input->post('category_ids');

        if (empty($categoryIds)) {

            $this->session->set_flashdata(
                'error',
                'Minimal pilih satu kategori.'
            );

            return $this->create();

        }
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = null;

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'news_' . url_title($this->input->post('title', TRUE), '-', TRUE)
                );

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'summary' => trim($this->input->post('summary', TRUE)),
                'content' => $this->input->post('content', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status,
                'published_at' => ($status === 'published')
                    ? date('Y-m-d H:i:s')
                    : null
            ];

            $newsId = $this->News_model->insert($data);
            $categoryIds = $this->input->post('category_ids');

            if (!empty($categoryIds)) {
                $this->News_model->save_categories(
                    $newsId,
                    $categoryIds
                );
            }
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan News.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'News berhasil ditambahkan.'
            );

            redirect('admin/news');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

            redirect('admin/news/create');

        }
    }

    public function edit($id)
    {
        $news = $this->News_model->get_by_id($id);

        if (!$news) {
            show_404();
        }

        $data = [
            'title' => 'Edit News',
            'page_header' => 'Edit News',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/news') . '">News</a>
            </li>
            <li class="breadcrumb-item active">Edit News</li>
        </ol>',
            'news' => $news,
            'categories' => $this->News_category_model->get_all(),
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/news/edit', $data);
    }

    public function update($id)
    {
        $news = $this->News_model->get_by_id($id);

        if (!$news) {
            show_404();
        }

        $categoryIds = $this->input->post('category_ids');

        if (empty($categoryIds)) {

            $this->session->set_flashdata(
                'error',
                'Minimal pilih satu kategori.'
            );

            return $this->edit($id);

        }
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = $news['featured_image_media_id'];

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'news_' . url_title($this->input->post('title', TRUE), '-', TRUE)
                );

                if (!empty($news['featured_image_media_id'])) {
                    $this->Media_model->delete($news['featured_image_media_id']);
                }

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'summary' => trim($this->input->post('summary', TRUE)),
                'content' => $this->input->post('content', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status
            ];

            if (
                $news['published_at'] === NULL &&
                $status === 'published'
            ) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->News_model->update($id, $data);
            $this->News_model->delete_categories($id);

            $categoryIds = $this->input->post('category_ids');

            if (!empty($categoryIds)) {

                $this->News_model->save_categories(
                    $id,
                    $categoryIds
                );

            }
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui News.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'News berhasil diperbarui.'
            );

            redirect('admin/news');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

            redirect('admin/news/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $news = $this->News_model->get_by_id($id);

        if (!$news) {
            show_404();
        }

        $this->db->trans_begin();

        try {

            if (!empty($news['featured_image_media_id'])) {
                $this->Media_model->delete($news['featured_image_media_id']);
            }

            $this->News_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus News.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'News berhasil dihapus.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/news');
    }

    public function categories()
    {
        $data = [
            'categories' => $this->News_category_model->get_all()
        ];

        $this->load->view('backend/news/categories', $data);
    }

    public function category_store()
    {
        if (!$this->input->is_ajax_request()) {

            show_404();

        }

        $this->form_validation->set_rules(
            'name',
            'Category Name',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => strip_tags(validation_errors())
                ]));

        }

        $this->db->trans_begin();

        try {

            $data = [
                'uuid' => $this->uuid->v4(),
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title(
                    $this->input->post('name', TRUE),
                    '-',
                    TRUE
                )
            ];

            $this->News_category_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menambahkan kategori.');
            }

            $this->db->trans_commit();

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Kategori berhasil ditambahkan.'
                ]));

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $e->getMessage()
                ]));

        }
    }

    public function category_update($id)
    {
        if (!$this->input->is_ajax_request()) {

            show_404();

        }

        $category = $this->News_category_model->get_by_id($id);

        if (!$category) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan.'
                ]));

        }

        $this->form_validation->set_rules(
            'name',
            'Category Name',
            'required|trim'
        );

        if ($this->form_validation->run() === FALSE) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => strip_tags(validation_errors())
                ]));

        }

        $this->db->trans_begin();

        try {

            $data = [
                'name' => trim($this->input->post('name', TRUE)),
                'slug' => url_title(
                    $this->input->post('name', TRUE),
                    '-',
                    TRUE
                )
            ];

            $this->News_category_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui kategori.');
            }

            $this->db->trans_commit();

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Kategori berhasil diperbarui.'
                ]));

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $e->getMessage()
                ]));

        }
    }

    public function category_delete($id)
    {
        if (!$this->input->is_ajax_request()) {

            show_404();

        }

        $category = $this->News_category_model->get_by_id($id);

        if (!$category) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Kategori tidak ditemukan.'
                ]));

        }

        $newsCount = $this->News_category_model->count_news($id);

        if ($newsCount > 0) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $newsCount . ' artikel.'
                ]));

        }

        $this->db->trans_begin();

        try {

            $this->News_category_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menghapus kategori.');
            }

            $this->db->trans_commit();

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => true,
                    'message' => 'Kategori berhasil dihapus.'
                ]));

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $e->getMessage()
                ]));

        }
    }
}
