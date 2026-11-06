<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php $this->load->view('frontend/layouts/header'); ?>

<?php $this->load->view('frontend/layouts/navbar'); ?>

<main id="main-content">

    <?php
    /*
    |--------------------------------------------------------------------------
    | Dynamic Content
    |--------------------------------------------------------------------------
    |
    | Controller mengirim nama view melalui variabel $content.
    |
    */

    if (isset($content) && !empty($content)) {
        $this->load->view($content);
    } else {
        echo '<div class="container py-5 text-center">';
        echo '<h3>Content Not Found</h3>';
        echo '</div>';
    }
    ?>

</main>

<?php
$this->load->model('Page_model');
$this->load->model('Service_model');
$this->load->view('frontend/layouts/footer', [
    'footer_pages' => $this->Page_model->get_published(),
    'footer_services' => $this->Service_model->get_published()
]);
?>

<?php $this->load->view('frontend/layouts/scripts'); ?>

</body>

</html>