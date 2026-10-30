<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card card-outline card-success">

    <div class="card-header">

        <h3 class="card-title" id="form-title">

            Tambah Menu

        </h3>

    </div>

    <form id="menu-form" action="<?= site_url('admin/menus/store'); ?>" method="post">

        <input type="hidden" name="id" id="menu_id">

        <div class="card-body">

            <!-- Title -->

            <div class="form-group">

                <label>Judul Menu <span class="text-danger">*</span></label>

                <input type="text" name="title" id="title" class="form-control" required>

            </div>

            <!-- Parent -->

            <div class="form-group">

                <label>Parent Menu</label>

                <select name="parent_id" id="parent_id" class="form-control">

                    <option value="">-- Root Menu --</option>

                    <?php foreach ($parents as $parent): ?>

                        <option value="<?= $parent['id']; ?>">

                            <?= html_escape($parent['title']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Page -->

            <div class="form-group">

                <label>Halaman</label>

                <select name="page_id" id="page_id" class="form-control">

                    <option value="">-- Pilih Halaman --</option>

                    <?php foreach ($pages as $page): ?>

                        <option value="<?= $page['id']; ?>">

                            <?= html_escape($page['title']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Service -->

            <div class="form-group">

                <label>Layanan</label>

                <select name="service_id" id="service_id" class="form-control">

                    <option value="">-- Pilih Layanan --</option>

                    <?php foreach ($services as $service): ?>

                        <option value="<?= $service['id']; ?>">

                            <?= html_escape($service['name']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Product -->

            <div class="form-group">

                <label>Produk</label>

                <select name="product_id" id="product_id" class="form-control">

                    <option value="">-- Pilih Produk --</option>

                    <?php foreach ($products as $product): ?>

                        <option value="<?= $product['id']; ?>">

                            <?= html_escape($product['name']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Custom URL -->

            <div class="form-group">

                <label>Custom URL</label>

                <input
                    type="text"
                    name="custom_url"
                    id="custom_url"
                    class="form-control"
                    placeholder="https://example.com">

                <small class="text-muted">

                    Digunakan hanya untuk URL eksternal.

                </small>

            </div>

            <!-- Icon -->

            <div class="form-group">

                <label>Icon (Font Awesome)</label>

                <input
                    type="text"
                    name="icon"
                    id="icon"
                    class="form-control"
                    placeholder="fas fa-home">

            </div>

            <!-- Target -->

            <div class="form-group">

                <label>Target</label>

                <select name="target" id="target" class="form-control">

                    <option value="_self">

                        Buka di Tab Saat Ini

                    </option>

                    <option value="_blank">

                        Buka Tab Baru

                    </option>

                </select>

            </div>

            <!-- Status -->

            <div class="form-group">

                <div class="custom-control custom-switch">

                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="is_active"
                        name="is_active"
                        value="1"
                        checked>

                    <label class="custom-control-label" for="is_active">

                        Aktif

                    </label>

                </div>

            </div>

        </div>

        <div class="card-footer text-right">

            <button type="button" class="btn btn-secondary" id="btn-reset">

                <i class="fas fa-sync"></i>

                Reset

            </button>

            <button type="submit" class="btn btn-success">

                <i class="fas fa-save"></i>

                Simpan

            </button>

        </div>

    </form>

</div>

<script>

$(function () {

    function syncMenuSource(source) {

        switch (source) {

            case 'page':
                $('#service_id').val('');
                $('#product_id').val('');
                $('#custom_url').val('').prop('readonly', true);
                break;

            case 'service':
                $('#page_id').val('');
                $('#product_id').val('');
                $('#custom_url').val('').prop('readonly', true);
                break;

            case 'product':
                $('#page_id').val('');
                $('#service_id').val('');
                $('#custom_url').val('').prop('readonly', true);
                break;

            default:

                if (
                    $('#page_id').val() === '' &&
                    $('#service_id').val() === '' &&
                    $('#product_id').val() === ''
                ) {
                    $('#custom_url').prop('readonly', false);
                }

        }

    }

    $('#page_id').change(function () {

        if ($(this).val() !== '') {
            syncMenuSource('page');
        } else {
            syncMenuSource();
        }

    });

    $('#service_id').change(function () {

        if ($(this).val() !== '') {
            syncMenuSource('service');
        } else {
            syncMenuSource();
        }

    });

    $('#product_id').change(function () {

        if ($(this).val() !== '') {
            syncMenuSource('product');
        } else {
            syncMenuSource();
        }

    });

    $('#custom_url').on('input', function () {

        if ($(this).val().trim() !== '') {

            $('#page_id').val('');
            $('#service_id').val('');
            $('#product_id').val('');

        }

    });

    $('#btn-reset').click(function () {

        $('#menu-form')[0].reset();

        $('#menu_id').val('');

        $('#form-title').text('Tambah Menu');

        $('#menu-form').attr(
            'action',
            '<?= site_url("admin/menus/store"); ?>'
        );

        $('#custom_url').prop('readonly', false);

    });

    $('.btn-edit').click(function () {

        $('#form-title').text('Edit Menu');

        $('#menu_id').val($(this).data('id'));

        $('#title').val($(this).data('title'));

        $('#parent_id').val($(this).data('parent'));

        $('#page_id').val($(this).data('page'));

        $('#service_id').val($(this).data('service'));

        $('#product_id').val($(this).data('product'));

        $('#custom_url').val($(this).data('url'));

        $('#icon').val($(this).data('icon'));

        $('#target').val($(this).data('target'));

        $('#is_active').prop('checked', $(this).data('active') == 1);

        $('#menu-form').attr(
            'action',
            '<?= site_url("admin/menus/update"); ?>'
        );

        if ($('#page_id').val() !== '') {
            syncMenuSource('page');
        } else if ($('#service_id').val() !== '') {
            syncMenuSource('service');
        } else if ($('#product_id').val() !== '') {
            syncMenuSource('product');
        } else {
            syncMenuSource();
        }

    });

    syncMenuSource();

});

</script>