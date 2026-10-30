<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-header float-right">

        <a href="<?= site_url('admin/portfolios/create'); ?>" class="btn btn-primary">

            <i class="fas fa-plus"></i>

            Create Portfolio

        </a>

    </div>

    <div class="card-body">

        <table id="portfolio-table" class="table table-bordered table-hover">

            <thead>

                <tr>

                    <th width="50">#</th>

                    <th width="90">Image</th>

                    <th>Title</th>

                    <th width="180">Client</th>

                    <th width="120">Year</th>

                    <th width="120">Status</th>

                    <th width="180">Published</th>

                    <th width="120">Action</th>

                </tr>

            </thead>

            <tbody></tbody>

        </table>

    </div>

</div>

<script>

    $(function () {

        $('#portfolio-table').DataTable({

            processing: true,

            serverSide: true,

            responsive: true,

            autoWidth: false,

            order: [[6, 'desc']],

            ajax: {

                url: "<?= site_url('admin/portfolios/datatable'); ?>",

                type: "POST"

            },

            columnDefs: [

                {
                    targets: [0, 1, 5, 6, 7],

                    className: "text-center"
                },

                {
                    targets: [1, 7],

                    orderable: false
                },

                {
                    targets: [1],

                    searchable: false
                }

            ]

        });

    });

</script>