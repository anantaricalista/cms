<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal-header">

    <h5 class="modal-title">

        Manage News Categories

    </h5>

    <button type="button" class="close" data-dismiss="modal">

        <span>&times;</span>

    </button>

</div>

<div class="modal-body">

    <div class="row">

        <div class="col-md-4">

            <div class="card card-outline card-primary">

                <div class="card-header">

                    <h3 class="card-title" id="category-title">

                        Add Category

                    </h3>

                </div>

                <form id="category-form">

                    <input type="hidden" id="category_id">

                    <div class="card-body">

                        <div class="form-group">

                            <label>Category Name</label>

                            <input type="text" class="form-control" id="category_name" name="name" required>

                        </div>

                    </div>

                    <div class="card-footer text-right">

                        <button type="button" class="btn btn-secondary" id="btn-category-reset">

                            Reset

                        </button>

                        <button type="submit" class="btn btn-primary">

                            Save

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <div class="col-md-8">

            <div class="card">

                <div class="card-body p-0">

                    <table class="table table-bordered table-hover mb-0">

                        <thead>

                            <tr>

                                <th width="60">#</th>

                                <th>Name</th>

                                <th>Slug</th>

                                <th width="120" class="text-center">

                                    Action

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (!empty($categories)): ?>

                                <?php $no = 1; ?>

                                <?php foreach ($categories as $category): ?>

                                    <tr>

                                        <td><?= $no++; ?></td>

                                        <td>

                                            <?= html_escape($category['name']); ?>

                                        </td>

                                        <td>

                                            <code><?= html_escape($category['slug']); ?></code>

                                        </td>

                                        <td class="text-center">

                                            <button type="button" class="btn btn-warning btn-sm btn-edit-category"
                                                data-id="<?= $category['id']; ?>"
                                                data-name="<?= html_escape($category['name']); ?>">

                                                <i class="fas fa-edit"></i>

                                            </button>

                                            <button type="button" class="btn btn-danger btn-sm btn-delete-category"
                                                data-id="<?= $category['id']; ?>"
                                                data-name="<?= html_escape($category['name']); ?>">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="4" class="text-center">

                                        Belum ada kategori.

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>
<script>

    let categoryMode = 'create';

    (function () {

        function reloadCategoryModal() {
            $('#category-content').load(
                "<?= site_url('admin/news/categories'); ?>"
            );
        }

        function reloadCategorySelect() {
            $.getJSON(
                "<?= site_url('admin/news/category_list'); ?>",
                function (response) {

                    if (!response.status) {
                        return;
                    }

                    let selected = $('#category_ids').val() || [];

                    $('#category_ids').empty();

                    $.each(response.data, function (_, item) {

                        $('#category_ids').append(
                            $('<option>', {
                                value: item.id,
                                text: item.name,
                                selected: $.inArray(
                                    item.id.toString(),
                                    selected
                                ) !== -1
                            })
                        );

                    });

                    $('#category_ids').trigger('change');

                }
            );
        }

        $(document).on('click', '#btn-category-reset', function () {
            categoryMode = 'create';

            $('#category-form')[0].reset();

            $('#category_id').val('');

            $('#category-title').text('Add Category');

        });

        $(document).on('click', '.btn-edit-category', function () {

            categoryMode = 'update';

            $('#category-title').text('Edit Category');

            $('#category_id').val($(this).data('id'));

            $('#category_name').val($(this).data('name'));

        });

        $(document).on('submit', '#category-form', function (e) {
            e.preventDefault();

            let url =
                categoryMode === 'create'
                    ? "<?= site_url('admin/news/category_store'); ?>"
                    : "<?= site_url('admin/news/category_update'); ?>/" + $('#category_id').val();

            $.ajax({

                url: url,

                type: 'POST',

                data: {
                    name: $('#category_name').val()
                },

                dataType: 'json',

                success: function (response) {

                    if (response.status) {

                        toastr.success(response.message);

                        reloadCategorySelect();

                        $('#category-modal').modal('hide');

                    } else {

                        toastr.error(response.message);

                    }

                },

                error: function () {

                    toastr.error('Terjadi kesalahan pada server.');

                }

            });

        });

        $(document).on('click', '.btn-delete-category', function () {

            let id = $(this).data('id');

            if (!confirm('Yakin ingin menghapus kategori ini?')) {
                return;
            }

            $.ajax({

                url: "<?= site_url('admin/news/category_delete'); ?>/" + id,

                type: 'POST',

                dataType: 'json',

                success: function (response) {

                    if (response.status) {

                        toastr.success(response.message);

                        $('#category-modal').modal('hide');

                        reloadCategoryModal();

                        reloadCategorySelect();

                    } else {

                        toastr.error(response.message);

                    }

                },

                error: function () {

                    toastr.error('Terjadi kesalahan pada server.');

                }

            });

        });

        $('#category-modal').on('hidden.bs.modal', function () {

            reloadCategoryModal();

        });

    })();

</script>