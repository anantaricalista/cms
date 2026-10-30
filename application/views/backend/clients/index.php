<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/clients/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Client
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Website</th>
                    <th width="20%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($clients)): ?>
                    <?php $no = 1; ?>
                    <?php $total = count($clients); ?>

                    <?php foreach ($clients as $client): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?php if (!empty($client['logo_media_id'])): ?>
                                    <img src="<?= site_url('media/show/' . $client['logo_media_id']) ?>"
                                         alt="<?= html_escape($client['name']) ?>"
                                         style="height: 50px; width: auto; max-width: 150px;">
                                <?php else: ?>
                                    <span class="text-muted">No Logo</span>
                                <?php endif; ?>
                            </td>

                            <td><?= html_escape($client['name']) ?></td>

                            <td>
                                <?php if (!empty($client['website'])): ?>
                                    <a href="<?= html_escape($client['website']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-globe"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <?php if ($client['sort_order'] > 1): ?>
                                    <a href="<?= site_url('admin/clients/move_up/' . $client['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Up">

                                        <i class="fas fa-arrow-up"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at top">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($client['sort_order'] < $total): ?>
                                    <a href="<?= site_url('admin/clients/move_down/' . $client['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Down">

                                        <i class="fas fa-arrow-down"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at bottom">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= site_url('admin/clients/edit/' . $client['id']) ?>"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="<?= site_url('admin/clients/delete/' . $client['id']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus client ini?');">

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
