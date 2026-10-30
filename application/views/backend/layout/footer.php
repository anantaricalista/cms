<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<footer class="app-footer">

    <div class="float-end d-none d-sm-inline">
        CMS Version 1.0
    </div>

    <strong>
        &copy; <?= date('Y'); ?>
        <?= isset($company_name) ? html_escape($company_name) : 'Company'; ?>
    </strong>

    All rights reserved.

</footer>
