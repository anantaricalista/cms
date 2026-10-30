<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/careers/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Career
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Position</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th width="20%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($careers)): ?>
                    <?php $no = 1; ?>

                    <?php foreach ($careers as $career): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= html_escape($career['position']) ?></td>

                            <td><?= html_escape($career['location']) ?></td>

                            <td>
                                <?php if ($career['status'] === 'published'): ?>
                                    <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (!empty($career['published_at'])): ?>
                                    <?= date('d M Y H:i', strtotime($career['published_at'])) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <a href="<?= site_url('career/' . $career['slug']) ?>"
                                   target="_blank"
                                   class="btn btn-info btn-sm"
                                   title="View Career">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="<?= site_url('admin/careers/edit/' . $career['id']) ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="<?= site_url('admin/careers/delete/' . $career['id']) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus career ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada career yang ditambahkan.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

<script>
$(function () {

    $('#datatable').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true
    });

});
</script>
