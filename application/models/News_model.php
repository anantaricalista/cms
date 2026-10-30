<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'news';
    protected $column_order = [
        null,
        null,
        'news.title',
        'category_names',
        'news.status',
        'news.published_at',
        null
    ];

    protected $column_search = [
        'news.title',
        'news.slug',
        'news.summary',
        'news_categories.name'
    ];

    protected $order = [
        'news.published_at' => 'DESC'
    ];
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh berita yang dipublikasikan.
     *
     * @return array
     */
    public function get_all()
    {
        $news = $this->db
            ->select('news.*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('published_at', 'DESC')
            ->get()
            ->result_array();

        foreach ($news as &$item) {
            $item['categories'] = $this->get_categories($item['id']);
        }

        return $news;
    }

    /**
     * Mengambil berita terbaru.
     *
     * @param int $limit
     * @return array
     */
    public function get_latest($limit = 3)
    {
        $news = $this->db
            ->select('news.*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('published_at', 'DESC')
            ->limit((int) $limit)
            ->get()
            ->result_array();

        foreach ($news as &$item) {
            $item['categories'] = $this->get_categories($item['id']);
        }

        return $news;
    }

    /**
     * Mengambil seluruh berita yang dipublikasikan.
     *
     * @return array
     */
    public function get_published($limit = NULL, $offset = 0)
    {
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }

        return $this->db
            ->where('status', 'published')
            ->order_by('published_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    public function count_published()
    {
        return $this->db
            ->where('status', 'published')
            ->count_all_results($this->table);
    }

    /**
     * Mengambil kategori News berdasarkan slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function get_category_by_slug($slug)
    {
        return $this->db
            ->where('slug', $slug)
            ->get('news_categories')
            ->row_array();
    }

    /**
     * Mengambil News published berdasarkan kategori.
     *
     * @param int $category_id
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function get_published_by_category($category_id, $limit = NULL, $offset = 0)
    {
        $this->db
            ->select('n.*')
            ->from($this->table . ' n')
            ->join(
                'news_category_relations ncr',
                'ncr.news_id = n.id',
                'inner'
            )
            ->where('ncr.category_id', $category_id)
            ->where('n.status', 'published')
            ->order_by('n.published_at', 'DESC')
            ->order_by('n.id', 'DESC');

        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }

        return $this->db
            ->get()
            ->result_array();
    }

    /**
     * Menghitung jumlah News published berdasarkan kategori.
     *
     * @param int $category_id
     * @return int
     */
    public function count_published_by_category($category_id)
    {
        return $this->db
            ->select('COUNT(DISTINCT n.id) AS total')
            ->from($this->table . ' n')
            ->join(
                'news_category_relations ncr',
                'ncr.news_id = n.id',
                'inner'
            )
            ->where('ncr.category_id', $category_id)
            ->where('n.status', 'published')
            ->get()
            ->row()
            ->total;
    }

    public function get_categories_by_news($news_id)
    {
        return $this->db
            ->select('nc.id, nc.name, nc.slug')
            ->from('news_categories nc')
            ->join(
                'news_category_relations ncr',
                'ncr.category_id = nc.id',
                'inner'
            )
            ->where('ncr.news_id', $news_id)
            ->order_by('nc.name', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil seluruh kategori News
     * beserta jumlah berita published
     *
     * @return array
     */
    public function get_news_categories()
    {
        return $this->db
            ->select('
            nc.id,
            nc.name,
            nc.slug,
            COUNT(DISTINCT n.id) AS total_news
        ')
            ->from('news_categories nc')
            ->join(
                'news_category_relations ncr',
                'ncr.category_id = nc.id',
                'inner'
            )
            ->join(
                'news n',
                'n.id = ncr.news_id',
                'inner'
            )
            ->where('n.status', 'published')
            ->group_by([
                'nc.id',
                'nc.name',
                'nc.slug'
            ])
            ->order_by('nc.name', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil berita berdasarkan slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function get_by_slug($slug)
    {
        $news = $this->db
            ->select('*')
            ->from($this->table)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->limit(1)
            ->get()
            ->row_array();

        if ($news) {
            $news['categories'] = $this->get_categories($news['id']);
        }

        return $news;
    }

    public function get_categories($newsId)
    {
        return $this->db
            ->select('c.id, c.name, c.slug')
            ->from('news_category_relations r')
            ->join(
                'news_categories c',
                'c.id = r.category_id'
            )
            ->where('r.news_id', $newsId)
            ->order_by('c.name', 'ASC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil berita berdasarkan ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        $news = $this->db
            ->select('*')
            ->from($this->table)
            ->where('id', (int) $id)
            ->limit(1)
            ->get()
            ->row_array();

        if ($news) {
            $news['category_ids'] = $this->get_category_ids($news['id']);
        }

        return $news;
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }
    public function update($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }
    public function delete($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table);
    }
    public function get_category_ids($newsId)
    {
        return array_column(
            $this->db
                ->select('category_id')
                ->from('news_category_relations')
                ->where('news_id', $newsId)
                ->get()
                ->result_array(),
            'category_id'
        );
    }

    public function delete_categories($newsId)
    {
        return $this->db
            ->where('news_id', $newsId)
            ->delete('news_category_relations');
    }

    public function save_categories($newsId, array $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {

            $this->db->insert(
                'news_category_relations',
                [
                    'news_id' => $newsId,
                    'category_id' => $categoryId
                ]
            );

        }
    }

    private function _get_datatables_query()
    {
        $this->db
            ->select("
            news.*,
            GROUP_CONCAT(
                DISTINCT news_categories.name
                ORDER BY news_categories.name
                SEPARATOR ', '
            ) AS category_names
        ")
            ->from($this->table)
            ->join(
                'news_category_relations',
                'news_category_relations.news_id = news.id',
                'left'
            )
            ->join(
                'news_categories',
                'news_categories.id = news_category_relations.category_id',
                'left'
            )
            ->group_by('news.id');

        $i = 0;

        foreach ($this->column_search as $item) {

            if (!empty($_POST['search']['value'])) {

                if ($i === 0) {

                    $this->db->group_start();

                    $this->db->like(
                        $item,
                        $_POST['search']['value']
                    );

                } else {

                    $this->db->or_like(
                        $item,
                        $_POST['search']['value']
                    );

                }

                if ($i === count($this->column_search) - 1) {
                    $this->db->group_end();
                }
            }

            $i++;
        }

        if (isset($_POST['order'])) {

            $column = $this->column_order[$_POST['order'][0]['column']];

            $dir = $_POST['order'][0]['dir'];

            if ($column !== null) {
                $this->db->order_by($column, $dir);
            }

        } else {

            foreach ($this->order as $key => $val) {
                $this->db->order_by($key, $val);
            }

        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();

        if ($_POST['length'] != -1) {

            $this->db->limit(
                $_POST['length'],
                $_POST['start']
            );

        }

        return $this->db
            ->get()
            ->result_array();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();

        return $this->db
            ->get()
            ->num_rows();
    }

    public function count_all()
    {
        return $this->db
            ->count_all($this->table);
    }
}