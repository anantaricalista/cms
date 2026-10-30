<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-header">

        <div class="card-tools">

            <button type="button" class="btn btn-success btn-sm" id="btn-manage-category">

                <i class="fas fa-tags"></i>
                Manage Categories

            </button>

            <div class="modal fade" id="category-modal" tabindex="-1">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">

                        <div class="modal-body p-0" id="category-container">

                        </div>

                    </div>

                </div>

            </div>

            <a href="<?= site_url('admin/news/create'); ?>" class="btn btn-primary btn-sm">

                <i class="fas fa-plus"></i>
                Add News

            </a>

        </div>

    </div>

    <div class="card-body">

        <table id="datatable" class="table table-bordered table-hover">

            <thead>

                <tr>

                    <th width="5%">#</th>

                    <th width="90">Image</th>

                    <th>Title</th>

                    <th width="180">Category</th>

                    <th width="110">Status</th>

                    <th width="170">Published</th>

                    <th width="140" class="text-center">Action</th>

                </tr>

            </thead>

        </table>

    </div>

</div>

<script>

    $(function () {

        $('#datatable').DataTable({

            processing: true,

            serverSide: true,

            responsive: true,

            autoWidth: false,

            order: [[5, 'desc']],

            ajax: {

                url: "<?= site_url('admin/news/datatable'); ?>",

                type: "POST"

            },

            columnDefs: [

                {
                    targets: [0, 1, 3, 4, 5, 6],
                    orderable: false
                },

                {
                    targets: [0, 1, 3, 4, 5, 6],
                    className: 'text-center'
                }

            ]

        });

        $('#btn-manage-category').on('click', function () {

            $('#category-container').load(
                "<?= site_url('admin/news/categories'); ?>",
                function () {

                    $('#category-modal').modal('show');

                }
            );

        });

    });

</script>