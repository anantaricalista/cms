<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="alert alert-warning">Data semua hero yang aktif maupun nonaktif.</h5>
                <a href="<?= site_url('admin/heroes/create'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Hero
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php if (empty($heroes)): ?>

                <div class="alert alert-info">
                    Belum ada data Hero.
                </div>

            <?php else: ?>

                <div class="row">

                    <?php foreach ($heroes as $hero): ?>

                        <div class="col-lg-6 col-xl-6 mt-3">

                            <div class="card card-outline card-primary">

                                <?php if (!empty($hero['background_media_id'])): ?>
                                    <img src="<?= site_url('media/show/' . $hero['background_media_id']); ?>" class="card-img-top"
                                        style="height:240px;object-fit:cover;">
                                <?php endif; ?>

                                <div class="card-body">

                                    <div class="text-center mb-3">
                                        <?php if (!empty($hero['hero_media_id'])): ?>
                                            <img src="<?= site_url('media/show/' . $hero['hero_media_id']); ?>"
                                                class="img-thumbnail rounded-circle"
                                                style="width:120px;height:120px;object-fit:cover;">
                                        <?php endif; ?>
                                    </div>

                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th width="35%">Badge</th>
                                            <td><?= html_escape($hero['badge']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td><?= html_escape($hero['title']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Subtitle</th>
                                            <td><?= html_escape($hero['subtitle']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td><?= nl2br(html_escape($hero['description'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Primary Button</th>
                                            <td><?= html_escape($hero['primary_button_text']); ?><br><small><?= html_escape($hero['primary_button_url']); ?></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Secondary Button</th>
                                            <td><?= html_escape($hero['secondary_button_text']); ?><br><small><?= html_escape($hero['secondary_button_url']); ?></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Video</th>
                                            <td><?= $hero['video_url'] ?: '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Order</th>
                                            <td><?= $hero['display_order']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <?php if ($hero['is_active']): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

                                </div>

                                <div class="card-footer d-flex justify-content-between">

                                    <div class="btn-group" role="group">
                                        <a href="<?= site_url('admin/heroes/move_up/' . $hero['id']); ?>"
                                            class="btn btn-light btn-sm" title="Naikkan Urutan">
                                            <i class="fas fa-arrow-up"></i>
                                        </a>

                                        <a href="<?= site_url('admin/heroes/move_down/' . $hero['id']); ?>"
                                            class="btn btn-light btn-sm" title="Turunkan Urutan">
                                            <i class="fas fa-arrow-down"></i>
                                        </a>
                                    </div>

                                    <div>
                                        <a href="<?= site_url('admin/heroes/edit/' . $hero['id']); ?>"
                                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>

                                        <a href="<?= site_url('admin/heroes/delete/' . $hero['id']); ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Hapus hero ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <a href="<?= site_url('admin/heroes/toggle_status/' . $hero['id']); ?>"
                                            class="btn btn-sm <?= $hero['is_active'] ? 'btn-success' : 'btn-secondary'; ?>">
                                            <?= $hero['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>
    </section>

</div>