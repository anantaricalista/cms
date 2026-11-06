<?php

define('BASEPATH', __DIR__);

function site_url($path = '') { return 'https://example.test/' . $path; }
function html_escape($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function redirect($path) { $GLOBALS['redirected'] = $path; }
function log_message($level, $message) { $GLOBALS['logged'][] = [$level, $message]; }
function show_404() { throw new RuntimeException('404'); }
function show_error($message, $code) { throw new RuntimeException((string) $code); }
function form_open($path, $attributes = [])
{
    $html = '<form method="post" action="' . html_escape(site_url($path)) . '"';
    foreach ($attributes as $name => $value) {
        $html .= ' ' . html_escape($name) . '="' . html_escape($value) . '"';
    }
    return $html . '>';
}
function form_close() { return '</form>'; }

require __DIR__ . '/../application/helpers/whatsapp_helper.php';

class ContactTestSession
{
    public $flash = [];
    public $data = [];
    public function set_flashdata($key, $value) { $this->flash[$key] = $value; }
    public function flashdata($key) { return isset($this->flash[$key]) ? $this->flash[$key] : null; }
    public function set_userdata($key, $value) { $this->data[$key] = $value; }
    public function userdata($key) { return isset($this->data[$key]) ? $this->data[$key] : null; }
}

class ContactTestInput
{
    public $requestMethod = 'get';
    public $values = [];
    public function method() { return $this->requestMethod; }
    public function post($key) { return isset($this->values[$key]) ? $this->values[$key] : null; }
}

class ContactTestMessages
{
    public $rows = [];
    public $fail = false;
    public function get_all() { return array_values($this->rows); }
    public function get_by_id($id) { return isset($this->rows[$id]) ? $this->rows[$id] : null; }
    public function mark_as_read($id)
    {
        if ($this->fail) return false;
        $this->rows[$id]['is_read'] = 1;
        return true;
    }
    public function delete($id)
    {
        if ($this->fail) return false;
        unset($this->rows[$id]);
        return true;
    }
}

class ContactTestSite
{
    public function get() { return (object) ['site_name' => 'Site']; }
}

class MY_Controller
{
    public $load;
    public $session;
    public $input;
    public $Site_model;
    public $Contact_message_model;
    public $view;
    public $data;

    public function __construct()
    {
        $this->load = $this;
        $this->session = new ContactTestSession();
        $this->input = new ContactTestInput();
        $this->Site_model = new ContactTestSite();
        $this->Contact_message_model = new ContactTestMessages();
    }
    public function model($name) {}
    protected function render($view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
    }
    public function render_view()
    {
        extract($this->data);
        ob_start();
        require __DIR__ . '/../application/views/' . $this->view . '.php';
        return ob_get_clean();
    }
}

function check($condition, $message)
{
    if (!$condition) throw new RuntimeException($message);
}

function expect_404($callback)
{
    try {
        $callback();
    } catch (RuntimeException $exception) {
        if ($exception->getMessage() === '404') return;
        throw $exception;
    }
    throw new RuntimeException('Expected a 404 response');
}

function expect_403($callback)
{
    try {
        $callback();
    } catch (RuntimeException $exception) {
        if ($exception->getMessage() === '403') return;
        throw $exception;
    }
    throw new RuntimeException('Expected a 403 response');
}

require __DIR__ . '/../application/controllers/admin/Contact_messages.php';

$controller = new Contact_messages();
$controller->Contact_message_model->rows[7] = [
    'id' => 7, 'name' => '<script>Ayu</script>', 'email' => 'ayu@example.test',
    'phone' => '0812-3456-7890', 'subject' => 'Jasa & produk',
    'message' => "<b>Halo</b>\nBaris kedua", 'is_read' => 0,
    'created_at' => '2025-01-02 12:00:00'
];

$controller->index();
$html = $controller->render_view();
check(strpos($html, '&lt;script&gt;Ayu&lt;/script&gt;') !== false, 'List must escape names');
check(strpos($html, 'Belum dibaca') !== false, 'List must show unread status');

$controller->detail('7');
$html = $controller->render_view();
check(strpos($html, '&lt;b&gt;Halo&lt;/b&gt;<br') !== false, 'Detail must escape the message and preserve line breaks');
check(strpos($html, 'method="post"') !== false, 'Mutating actions must use POST');
check(strpos($html, 'rel="noopener noreferrer"') !== false, 'WhatsApp must open safely');
parse_str(parse_url($controller->data['reply_url'], PHP_URL_QUERY), $params);
check($params['phone'] === '6281234567890', 'Indonesian number must be normalized');
check(strpos($params['text'], 'Jasa & produk') !== false, 'Reply must include the subject');

$controller->Contact_message_model->rows[7]['phone'] = '+1 415 555 0123';
$controller->detail('7');
parse_str(parse_url($controller->data['reply_url'], PHP_URL_QUERY), $params);
check($params['phone'] === '14155550123', 'International country codes must be preserved');
check(strpos(whatsapp_contact_url('0044 20 1234 5678'), 'phone=442012345678') !== false, '00 international prefix must be normalized');

$controller->Contact_message_model->rows[7]['phone'] = '--';
$controller->detail('7');
check($controller->data['reply_url'] === '', 'Invalid or missing numbers must not produce a reply link');
check(strpos($controller->render_view(), 'tidak tersedia atau tidak valid') !== false, 'Missing phone must be explained');

expect_404(function () use ($controller) { $controller->detail('7abc'); });
expect_404(function () use ($controller) { $controller->detail('8'); });
expect_404(function () use ($controller) { $controller->mark_read('7'); });
expect_404(function () use ($controller) { $controller->delete('7'); });
check($controller->Contact_message_model->rows[7]['is_read'] === 0, 'GET must not mark as read');
check(isset($controller->Contact_message_model->rows[7]), 'GET must not delete');

$controller->input->requestMethod = 'post';
expect_403(function () use ($controller) { $controller->delete('7'); });
expect_403(function () use ($controller) { $controller->mark_read('7'); });
check(isset($controller->Contact_message_model->rows[7]), 'Missing token must not delete');
$controller->input->values['contact_messages_action_token'] = $controller->session->userdata('contact_messages_action_token');
$controller->Contact_message_model->fail = true;
$controller->mark_read('7');
check($controller->Contact_message_model->rows[7]['is_read'] === 0, 'Failed update must not mark as read');
check(isset($controller->session->flash['error']), 'Failed update must report an error');
$controller->delete('7');
check(isset($controller->Contact_message_model->rows[7]), 'Failed delete must preserve the message');
check($GLOBALS['redirected'] === 'admin/contact_messages/detail/7', 'Failed delete must return to detail');

$controller->Contact_message_model->fail = false;
$controller->mark_read('7');
check($controller->Contact_message_model->rows[7]['is_read'] === 1, 'POST must mark as read');
$controller->detail('7');
$html = $controller->render_view();
check(strpos($html, 'Tandai dibaca</button>') === false, 'Read messages must not show the mark-read button');
$controller->delete('7');
check(!isset($controller->Contact_message_model->rows[7]), 'POST must delete the message');
check($GLOBALS['redirected'] === 'admin/contact_messages', 'Successful delete must return to the list');

echo "Admin contact messages regression test passed\n";
