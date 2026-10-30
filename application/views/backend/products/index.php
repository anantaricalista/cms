<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-header">

        <div class="card-tools">

            <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Product
            </a>

        </div>

    </div>

    <div class="card-body">

        <table id="datatable" class="table table-bordered table-hover">

            <thead>

                <tr>

                    <th width="5%">#</th>

                    <th>Name</th>

                    <th>SKU</th>

                    <th>Slug</th>

                    <th>Status</th>

                    <th>Published</th>

                    <th width="15%" class="text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                <?php if (!empty($products)): ?>
                    <?php $no = 1; ?>

                    <?php foreach ($products as $product): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= html_escape($product['name']) ?></td>

                            <td><?= html_escape($product['sku']) ?></td>

                            <td>
                                <code><?= html_escape($product['slug']) ?></code>
                            </td>

                            <td>

                                <?php if ($product['status'] == 'published'): ?>

                                    <span class="badge bg-success">
                                        Published
                                    </span>

                                <?php elseif ($product['status'] == 'draft'): ?>

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-warning">
                                        <?= ucfirst(html_escape($product['status'])) ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= !empty($product['published_at'])
                                    ? date('d M Y H:i', strtotime($product['published_at']))
                                    : '-' ?>

                            </td>

                            <td class="text-center">

                                <a href="<?= site_url('product/' . $product['slug']); ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-info"
                                   title="View Product">

                                    <i class="fas fa-eye"></i>

                                </a>

                                <a href="<?= site_url('admin/products/edit/' . $product['id']) ?>"
                                   class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="<?= site_url('admin/products/delete/' . $product['id']) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus product ini?');">

                                    <i class="fas fa-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script>

$(function () {

    $('#datatable').DataTable({
        responsive: true,
        autoWidth: false
    });

});

</script>