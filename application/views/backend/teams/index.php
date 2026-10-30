<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/teams/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Team Member
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>LinkedIn</th>
                    <th width="20%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($teams)): ?>
                    <?php $no = 1; ?>
                    <?php $total = count($teams); ?>

                    <?php foreach ($teams as $team): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= html_escape($team['name']) ?></td>

                            <td><?= html_escape($team['position']) ?></td>

                            <td>
                                <?php if (!empty($team['linkedin'])): ?>
                                    <a href="<?= html_escape($team['linkedin']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <?php if ($team['sort_order'] > 1): ?>
                                    <a href="<?= site_url('admin/teams/move_up/' . $team['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Up">

                                        <i class="fas fa-arrow-up"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at top">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($team['sort_order'] < $total): ?>
                                    <a href="<?= site_url('admin/teams/move_down/' . $team['id']) ?>"
                                        class="btn btn-info btn-sm"
                                        title="Move Down">

                                        <i class="fas fa-arrow-down"></i>

                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-info btn-sm" disabled title="Already at bottom">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= site_url('admin/teams/edit/' . $team['id']) ?>"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="<?= site_url('admin/teams/delete/' . $team['id']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus team member ini?');">

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
