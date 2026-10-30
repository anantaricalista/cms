<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_model extends CI_Model
{
    /**
     * Nama tabel.
     *
     * @var string
     */
    protected $table = 'portfolios';
    protected $column_order = [
        null,
        null,
        'title',
        'client',
        'project_year',
        'status',
        'published_at',
        null
    ];

    protected $column_search = [
        'title',
        'slug',
        'client',
        'description'
    ];

    protected $order = [
        'published_at' => 'DESC'
    ];
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh portfolio (semua status).
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('published_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Mengambil portfolio yang dipublikasikan untuk ditampilkan di frontend.
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_published($limit = null, $offset = null)
    {
        $query = $this->db
            ->select('*')
            ->from($this->table)
            ->where('status', 'published')
            ->order_by('published_at', 'DESC');

        if ($limit) {
            $query->limit($limit, $offset);
        }

        return $query->get()->result_array();
    }

    /**
     * Mengambil portfolio berdasarkan slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function get_by_slug($slug)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Mengambil portfolio berdasarkan ID.
     *
     * @param int $id
     * @return array|null
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('*')
            ->from($this->table)
            ->where('id', (int) $id)
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * Menghitung jumlah portfolio yang dipublikasikan.
     *
     * @return int
     */
    public function count_published()
    {
        return $this->db
            ->where('status', 'published')
            ->count_all_results($this->table);
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
    private function _get_datatables_query()
    {
        $this->db
            ->select('*')
            ->from($this->table);

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
