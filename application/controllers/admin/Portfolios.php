<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolios extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Site_model');
        $this->load->model('Portfolio_model');
        $this->load->model('Media_model');

        $this->load->library('form_validation');
        $this->load->library('uuid');
    }
    public function index()
    {
        $data = [
            'title' => 'Portfolio',
            'page_header' => 'Portfolio',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                Portfolio
            </li>
        </ol>',
            'site' => $this->Site_model->get()
        ];

        $this->render('backend/portfolios/index', $data);
    }
    public function datatable()
    {
        $list = $this->Portfolio_model->get_datatables();

        $data = [];

        $no = (int) $this->input->post('start');

        foreach ($list as $portfolio) {

            $no++;

            $image = '-';

            if (!empty($portfolio['featured_image_media_id'])) {

                $image = '<img src="' .
                    site_url('media/show/' . $portfolio['featured_image_media_id']) .
                    '" class="img-thumbnail" style="width:80px;height:60px;object-fit:cover;">';

            }

            $statusBadge =
                $portfolio['status'] === 'published'
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $publishedAt = '-';

            if (!empty($portfolio['published_at'])) {
                $publishedAt = date(
                    'd M Y H:i',
                    strtotime($portfolio['published_at'])
                );
            }

            $row = [];

            $row[] = $no;

            $row[] = $image;

            $row[] =
                '<strong>' .
                html_escape($portfolio['title']) .
                '</strong>';

            $row[] = html_escape($portfolio['client']);

            $row[] = html_escape($portfolio['project_year']);

            $row[] = $statusBadge;

            $row[] = $publishedAt;

            $row[] =
                '<div class="btn-group">
                <a href="' . site_url('portfolio/' . $portfolio['slug']) . '"
                   target="_blank"
                   class="btn btn-info btn-sm"
                   title="View News">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="' .
                site_url('admin/portfolios/edit/' . $portfolio['id']) .
                '" class="btn btn-warning btn-sm">

                    <i class="fas fa-edit"></i>

                </a>

                <a href="' .
                site_url('admin/portfolios/delete/' . $portfolio['id']) .
                '" class="btn btn-danger btn-sm"
                    onclick="return confirm(\'Yakin ingin menghapus portfolio ini?\')">

                    <i class="fas fa-trash"></i>

                </a>

            </div>';

            $data[] = $row;

        }

        echo json_encode([
            "draw" => (int) $this->input->post('draw'),
            "recordsTotal" => $this->Portfolio_model->count_all(),
            "recordsFiltered" => $this->Portfolio_model->count_filtered(),
            "data" => $data
        ]);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Portfolio',
            'page_header' => 'Create Portfolio',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/portfolio') . '">Portfolio</a>
            </li>
            <li class="breadcrumb-item active">
                Create
            </li>
        </ol>',
            'site' => $this->Site_model->get(),
            'portfolio' => [
                'title' => '',
                'slug' => '',
                'client' => '',
                'project_year' => '',
                'description' => '',
                'featured_image_media_id' => null,
                'seo_title' => '',
                'seo_description' => '',
                'seo_keywords' => '',
                'status' => 'draft',
                'published_at' => ''
            ]
        ];

        $this->render('backend/portfolios/create', $data);
    }
    public function store()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->create();
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = null;

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'portfolio_' . url_title($this->input->post('title', TRUE), '-', TRUE)
                );

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'uuid' => $this->uuid->v4(),
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'client' => trim($this->input->post('client', TRUE)),
                'project_year' => trim($this->input->post('project_year', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status,
                'published_at' => ($status === 'published')
                    ? date('Y-m-d H:i:s')
                    : null
            ];

            $this->Portfolio_model->insert($data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal menyimpan Portfolio.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Portfolio berhasil ditambahkan.'
            );

            redirect('admin/portfolios');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

            redirect('admin/portfolios/create');

        }
    }

    public function edit($id)
    {
        $portfolio = $this->Portfolio_model->get_by_id($id);

        if (!$portfolio) {

            show_404();

        }

        $data = [
            'title' => 'Edit Portfolio',
            'page_header' => 'Edit Portfolio',
            'breadcrumb' => '
        <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/dashboard') . '">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="' . site_url('admin/portfolio') . '">Portfolio</a>
            </li>
            <li class="breadcrumb-item active">
                Edit
            </li>
        </ol>',
            'site' => $this->Site_model->get(),
            'portfolio' => $portfolio
        ];

        $this->render('backend/portfolios/edit', $data);
    }

    public function update($id)
    {
        $portfolio = $this->Portfolio_model->get_by_id($id);

        if (!$portfolio) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            return $this->edit($id);
        }

        $this->db->trans_begin();

        try {

            $featuredImageMediaId = $portfolio['featured_image_media_id'];

            if (!empty($_FILES['featured_image']['name'])) {

                $featuredImageMediaId = $this->Media_model->upload(
                    'featured_image',
                    'portfolio_' . url_title($this->input->post('title', TRUE), '-', TRUE)
                );

                if (!empty($portfolio['featured_image_media_id'])) {
                    $this->Media_model->delete(
                        $portfolio['featured_image_media_id']
                    );
                }

            }

            $status = $this->input->post('status', TRUE);

            $data = [
                'title' => trim($this->input->post('title', TRUE)),
                'slug' => url_title($this->input->post('title', TRUE), '-', TRUE),
                'client' => trim($this->input->post('client', TRUE)),
                'project_year' => trim($this->input->post('project_year', TRUE)),
                'description' => $this->input->post('description', FALSE),
                'featured_image_media_id' => $featuredImageMediaId,
                'seo_title' => trim($this->input->post('seo_title', TRUE)),
                'seo_description' => trim($this->input->post('seo_description', TRUE)),
                'seo_keywords' => trim($this->input->post('seo_keywords', TRUE)),
                'status' => $status
            ];

            if (
                $portfolio['published_at'] === NULL &&
                $status === 'published'
            ) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            $this->Portfolio_model->update($id, $data);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Gagal memperbarui Portfolio.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Portfolio berhasil diperbarui.'
            );

            redirect('admin/portfolios');

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

            redirect('admin/portfolios/edit/' . $id);

        }
    }

    public function delete($id)
    {
        $portfolio = $this->Portfolio_model->get_by_id($id);

        if (!$portfolio) {

            show_404();

        }

        $this->db->trans_begin();

        try {

            if (!empty($portfolio['featured_image_media_id'])) {

                $this->Media_model->delete(
                    $portfolio['featured_image_media_id']
                );

            }

            $this->Portfolio_model->delete($id);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Failed to delete portfolio.');
            }

            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Portfolio deleted successfully.'
            );

        } catch (Exception $e) {

            $this->db->trans_rollback();

            log_message('error', $e->getMessage());

            $this->session->set_flashdata(
                'error',
                $e->getMessage()
            );

        }

        redirect('admin/portfolios');
    }
}
