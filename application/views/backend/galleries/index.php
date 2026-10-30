<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h3 class="card-title mb-1">
                                <i class="fas fa-images mr-2"></i>
                                Kelola gambar dan urutan gallery website.
                            </h3>
                        </div>

                        <a href="<?= site_url('admin/galleries/create'); ?>"
                           class="btn btn-primary">

                            <i class="fas fa-plus mr-1"></i>
                            Tambah Gallery

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <?php if (!empty($galleries)): ?>

                        <div class="row">

                            <?php
                            $totalGalleries = count($galleries);
                            ?>

                            <?php foreach ($galleries as $index => $gallery): ?>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">

                                    <div class="card gallery-card h-100">

                                        <!-- Image -->
                                        <div class="gallery-image-wrapper">

                                            <img
                                                src="<?= !empty($gallery['media_id'])
                                                    ? site_url('media/show/' . $gallery['media_id'])
                                                    : 'https://placehold.co/1200x675?text=No+Image'; ?>"
                                                alt="<?= html_escape($gallery['title']); ?>"
                                                class="gallery-image">

                                            <div class="gallery-status">

                                                <?php if ($gallery['status'] === 'published'): ?>

                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Published
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-eye-slash mr-1"></i>
                                                        Draft
                                                    </span>

                                                <?php endif; ?>

                                            </div>

                                            <div class="gallery-order">

                                                <span class="badge badge-dark">
                                                    #<?= (int) $gallery['sort_order']; ?>
                                                </span>

                                            </div>

                                        </div>

                                        <!-- Content -->
                                        <div class="card-body gallery-content">

                                            <h5 class="gallery-title">

                                                <?= html_escape($gallery['title']); ?>

                                            </h5>

                                            <?php if (!empty($gallery['description'])): ?>

                                                <div class="gallery-description">

                                                    <?= html_escape(
                                                        word_limiter(
                                                            strip_tags($gallery['description']),
                                                            20
                                                        )
                                                    ); ?>

                                                </div>

                                            <?php else: ?>

                                                <div class="gallery-description text-muted">
                                                    Tidak ada deskripsi.
                                                </div>

                                            <?php endif; ?>

                                        </div>

                                        <!-- Actions -->
                                        <div class="card-footer gallery-footer">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <!-- Ordering -->
                                                <div class="btn-group">

                                                    <?php if ($index > 0): ?>

                                                        <a href="<?= site_url('admin/galleries/move_up/' . $gallery['id']); ?>"
                                                           class="btn btn-outline-secondary btn-sm"
                                                           title="Move Up">

                                                            <i class="fas fa-arrow-up"></i>

                                                        </a>

                                                    <?php else: ?>

                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm"
                                                                disabled
                                                                title="Move Up">

                                                            <i class="fas fa-arrow-up"></i>

                                                        </button>

                                                    <?php endif; ?>


                                                    <?php if ($index < ($totalGalleries - 1)): ?>

                                                        <a href="<?= site_url('admin/galleries/move_down/' . $gallery['id']); ?>"
                                                           class="btn btn-outline-secondary btn-sm"
                                                           title="Move Down">

                                                            <i class="fas fa-arrow-down"></i>

                                                        </a>

                                                    <?php else: ?>

                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm"
                                                                disabled
                                                                title="Move Down">

                                                            <i class="fas fa-arrow-down"></i>

                                                        </button>

                                                    <?php endif; ?>

                                                </div>


                                                <!-- CRUD -->
                                                <div class="btn-group">

                                                    <a href="<?= site_url('admin/galleries/edit/' . $gallery['id']); ?>"
                                                       class="btn btn-warning btn-sm"
                                                       title="Edit">

                                                        <i class="fas fa-edit"></i>

                                                    </a>

                                                    <a href="<?= site_url('admin/galleries/delete/' . $gallery['id']); ?>"
                                                       class="btn btn-danger btn-sm"
                                                       title="Delete"
                                                       onclick="return confirm('Yakin ingin menghapus gallery ini?');">

                                                        <i class="fas fa-trash"></i>

                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="text-center py-5">

                            <div class="mb-3">

                                <i class="fas fa-images fa-4x text-muted"></i>

                            </div>

                            <h5>Belum Ada Gallery</h5>

                            <p class="text-muted mb-4">
                                Belum ada gambar yang ditambahkan ke gallery.
                            </p>

                            <a href="<?= site_url('admin/galleries/create'); ?>"
                               class="btn btn-primary">

                                <i class="fas fa-plus mr-1"></i>
                                Tambah Gallery

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

</div>


<style>

.gallery-card {
    overflow: hidden;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    transition: box-shadow .2s ease, transform .2s ease;
}

.gallery-card:hover {
    box-shadow: 0 5px 18px rgba(0, 0, 0, .10);
    transform: translateY(-2px);
}

.gallery-image-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #f4f6f9;
}

.gallery-image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-status {
    position: absolute;
    top: 12px;
    right: 12px;
}

.gallery-order {
    position: absolute;
    left: 12px;
    top: 12px;
}

.gallery-content {
    min-height: 145px;
    padding: 18px;
}

.gallery-title {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.35;
    color: #343a40;

    /*
     * Mencegah title terlalu panjang merusak
     * layout card.
     */
    overflow-wrap: anywhere;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.gallery-description {
    font-size: 14px;
    line-height: 1.6;
    color: #6c757d;

    overflow-wrap: anywhere;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.gallery-footer {
    background: #fff;
    padding: 12px 18px;
}

.gallery-footer .btn {
    min-width: 38px;
}

@media (max-width: 767.98px) {

    .gallery-content {
        min-height: auto;
    }

    .gallery-title {
        font-size: 17px;
    }

}

</style>