<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Company CMS</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- OverlayScrollbars -->
    <link href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.12.0/styles/overlayscrollbars.min.css" rel="stylesheet">

    <!-- AdminLTE 4 -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" rel="stylesheet">
</head>

<body class="login-page bg-body-secondary">

<div class="login-box">

    <div class="login-logo">
        <a href="<?= site_url(); ?>">
            <strong>Company</strong> CMS
        </a>
    </div>

    <div class="card card-outline card-primary">

        <div class="card-header text-center">
            <h4 class="mb-0">Administrator Login</h4>
        </div>

        <div class="card-body">

            <p class="login-box-msg">
                Sign in to start your session
            </p>

            <?php if (validation_errors()) : ?>
                <div class="alert alert-danger">
                    <?= validation_errors(); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('admin/authentication/login'); ?>" method="post">

                <div class="input-group mb-3">

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Email"
                        value="<?= set_value('email'); ?>"
                        required>

                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>

                </div>

                <div class="input-group mb-3">

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Password"
                        required>

                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                </div>

                <div class="row">

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- OverlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.12.0/browser/overlayscrollbars.browser.es6.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof OverlayScrollbarsGlobal !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(document.body, {});
    }

});
</script>

</body>
</html>