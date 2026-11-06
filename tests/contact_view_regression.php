<?php

define('BASEPATH', __DIR__);

function base_url($path = '') { return 'https://example.test/' . $path; }
function site_url($path = '') { return base_url($path); }
function html_escape($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }

require_once __DIR__ . '/../application/helpers/whatsapp_helper.php';

class ContactViewModel
{
    public function get_published() { return []; }
}

class ContactViewRenderer
{
    public $load;
    public $Service_model;
    public $Product_model;

    public function __construct()
    {
        $this->load = $this;
        $this->Service_model = new ContactViewModel();
        $this->Product_model = new ContactViewModel();
    }

    public function model($name) {}

    public function render($site)
    {
        ob_start();
        require __DIR__ . '/../application/views/frontend/sections/contact.php';
        return ob_get_clean();
    }
}

function contact_form($html)
{
    $document = new DOMDocument();
    libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8">' . $html);
    libxml_clear_errors();
    return $document->getElementById('contactForm');
}

$renderer = new ContactViewRenderer();
$form = contact_form($renderer->render([
    'company_name' => 'A & "B" </script>',
    'phone' => '0812-3456'
]));
if (!$form || $form->getAttribute('data-company-name') !== 'A & "B" </script>') {
    throw new RuntimeException('Company name must be safely encoded in the form');
}
parse_str(parse_url($form->getAttribute('data-whatsapp-url'), PHP_URL_QUERY), $params);
if ($params['phone'] !== '628123456' || $params['text'] !== '') {
    throw new RuntimeException('WhatsApp number must use the shared helper');
}

$form = contact_form($renderer->render(['company_name' => 'A', 'phone' => '---']));
if ($form->getAttribute('data-whatsapp-url') !== '') {
    throw new RuntimeException('A phone number without digits must not create a WhatsApp link');
}

echo "Contact view regression test passed\n";
