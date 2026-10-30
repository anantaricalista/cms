<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('backend/layout/head'); ?>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <?php $this->load->view('backend/layout/scripts'); ?>

        <?php $this->load->view('backend/layout/navbar'); ?>

        <?php $this->load->view('backend/layout/sidebar'); ?>

        <main class="app-main">

            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?= isset($title) ? html_escape($title) : ''; ?></h3>
                        </div>

                        <div class="col-sm-6">
                            <?= isset($breadcrumb) ? $breadcrumb : ''; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <?php
                    if (isset($content) && !empty($content)) {
                        $this->load->view($content);
                    }
                    ?>
                </div>
            </div>

        </main>

        <?php $this->load->view('backend/layout/footer'); ?>

    </div>


    <?php
    if (isset($scripts) && !empty($scripts)) {
        echo $scripts;
    }
    ?>

</body>

</html>