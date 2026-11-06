<?php

define('BASEPATH', __DIR__);

function base_url($path = '')
{
    return 'https://example.test/' . $path;
}

class TestDatabase
{
    public $careers = [];
    private $table;
    private $conditions = [];

    public function select($fields) { return $this; }
    public function from($table) { $this->table = $table; return $this; }
    public function join($table, $condition, $type = '') { return $this; }
    public function order_by($field, $direction = '') { return $this; }
    public function limit($limit, $offset = 0) { return $this; }

    public function where($field, $value)
    {
        $this->conditions[$field] = $value;
        return $this;
    }

    public function get($table = null)
    {
        $table = $table ?: $this->table;
        $rows = $table === 'careers' ? $this->careers : [];
        foreach ($this->conditions as $field => $value) {
            $field = preg_replace('/^.*\./', '', $field);
            $rows = array_filter($rows, function ($row) use ($field, $value) {
                return isset($row[$field]) && $row[$field] === $value;
            });
        }
        $this->conditions = [];
        $this->table = null;
        return new TestResult(array_values($rows));
    }
}

class TestResult
{
    private $rows;
    public function __construct($rows) { $this->rows = $rows; }
    public function result_array() { return $this->rows; }
}

class CI_Model
{
    public $db;
    public function __construct() { $this->db = $GLOBALS['test_db']; }
}

class CI_Controller
{
    public $load;
    public $Page_model;
    public $Service_model;
    public $Product_model;
    public $Portfolio_model;
    public $News_model;
    public $Team_model;
    public $Testimonial_model;
    public $Client_model;
    public $Career_model;
    public $Download_model;

    public function __construct() { $this->load = $this; }
    public function model($name)
    {
        require_once __DIR__ . '/../application/models/' . $name . '.php';
        $this->$name = new $name();
    }
}

require_once __DIR__ . '/../application/controllers/Sitemap.php';

$test_db = new TestDatabase();
$test_db->careers = [
    ['status' => 'published', 'published_at' => '2025-02-01'],
    ['status' => 'draft', 'published_at' => '2025-03-01'],
];
$sitemap = new Sitemap();
ob_start();
$sitemap->index();
$xml = ob_get_clean();
$document = new DOMDocument();
if (!$document->loadXML($xml)) {
    throw new RuntimeException('Sitemap output is not valid XML');
}
$xpath = new DOMXPath($document);
$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
$career = $xpath->query('/s:urlset/s:url[s:loc="https://example.test/careers"]');
if ($career->length !== 1 || $xpath->evaluate('string(s:lastmod)', $career->item(0)) !== '2025-02-01') {
    throw new RuntimeException('Published careers must appear without draft dates');
}

$test_db->careers = [['status' => 'draft', 'published_at' => '2025-03-01']];
ob_start();
$sitemap->index();
$xml = ob_get_clean();
$document->loadXML($xml);
$xpath = new DOMXPath($document);
$xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
if ($xpath->query('/s:urlset/s:url[s:loc="https://example.test/careers"]')->length !== 0) {
    throw new RuntimeException('Draft-only careers must not appear');
}

echo "Sitemap regression test passed\n";
