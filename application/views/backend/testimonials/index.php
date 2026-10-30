<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/testimonials/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Testimonial
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Photo</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Position</th>
                    <th width="20%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($testimonials)): ?>
                    <?php $no = 1; ?>
                    <?php $total = count($testimonials); ?>

                    <?php foreach ($testimonials as $testimonial): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td>
                                <?php if (!empty($testimonial['photo_media_id'])): ?>
                                    <img src="<?= site_url('media/show/' . $testimonial['photo_media_id']) ?>"
                                         alt="<?= html_escape($testimonial['name']) ?>"
                                         style="height: 50px; width: 50px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">No Photo</span>
                                <?php endif; ?>
                            </td>

                            <td><?= html_escape($testimonial['name']) ?></td>

                            <td><?= html_escape($testimonial['company']) ?></td>

                            <td><?= html_escape($testimonial['position']) ?></td>

                            <td class="text-center">

                                <?php if ($testimonial['sort_order'] > 1): ?>
                                    <a href="<?= site_url('admin/testimonials/move_up/' . $testimonial['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Up">

                                        <i class="fas fa-arrow-up"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at top">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($testimonial['sort_order'] < $total): ?>
                                    <a href="<?= site_url('admin/testimonials/move_down/' . $testimonial['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Down">

                                        <i class="fas fa-arrow-down"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at bottom">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= site_url('admin/testimonials/edit/' . $testimonial['id']) ?>"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="<?= site_url('admin/testimonials/delete/' . $testimonial['id']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus testimonial ini?');">

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
