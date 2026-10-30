<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="alert alert-warning mb-0">
                    Kelola menu website yang akan ditampilkan pada website.
                </h5>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="row">

                <div class="col-lg-7">

                    <div class="card card-outline card-primary">

                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fas fa-sitemap"></i>

                                Struktur Menu

                            </h3>

                        </div>

                        <div class="card-body">

                            <?php $this->load->view('backend/menus/_tree'); ?>

                        </div>

                    </div>

                </div>

                <div class="col-lg-5">

                    <?php $this->load->view('backend/menus/_form'); ?>

                </div>

            </div>

        </div>
    </section>
<script>

$(function(){

    $('#btn-add-menu').click(function(){

        $('#menu-form')[0].reset();

        $('#menu_id').val('');

        $('#form-title').text('Tambah Menu');

    });

});
</script>
</div>