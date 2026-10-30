<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="card-title mb-1">
                                <i class="fas fa-download mr-2"></i>
                                Kelola dokumen dan file yang dapat diunduh.
                            </h3>

                        </div>

                        <a href="<?= site_url('admin/downloads/create'); ?>" class="btn btn-primary">

                            <i class="fas fa-plus mr-1"></i>
                            Tambah Download

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <?php if (!empty($downloads)): ?>

                        <div class="row">

                            <?php foreach ($downloads as $download): ?>

                                <?php

                                $mimeType = $download['mime_type'] ?? '';

                                $isPdf = ($mimeType === 'application/pdf');

                                $isImage = (strpos($mimeType, 'image/') === 0);

                                $fileUrl = !empty($download['media_id'])
                                    ? site_url('media/show/' . $download['media_id'])
                                    : '';

                                ?>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">

                                    <div class="card download-card h-100">

                                        <div class="download-preview">

                                            <?php if ($isImage && $fileUrl): ?>

                                                <img src="<?= $fileUrl; ?>" alt="<?= html_escape($download['title']); ?>"
                                                    class="download-image">

                                            <?php elseif ($isPdf): ?>

                                                <div class="download-file-icon">

                                                    <i class="fas fa-file-pdf"></i>

                                                    <span>PDF</span>

                                                </div>

                                            <?php else: ?>

                                                <div class="download-file-icon">

                                                    <i class="fas fa-file"></i>

                                                    <span>
                                                        <?= strtoupper(
                                                            html_escape(
                                                                $download['extension'] ?? 'FILE'
                                                            )
                                                        ); ?>
                                                    </span>

                                                </div>

                                            <?php endif; ?>

                                            <div class="download-status">

                                                <?php if ($download['status'] === 'published'): ?>

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

                                        </div>

                                        <div class="card-body">

                                            <h5 class="download-title">
                                                <?= html_escape($download['title']); ?>
                                            </h5>

                                            <?php if (!empty($download['description'])): ?>

                                                <div class="download-description">

                                                    <?= html_escape(
                                                        word_limiter(
                                                            strip_tags(
                                                                $download['description']
                                                            ),
                                                            20
                                                        )
                                                    ); ?>

                                                </div>

                                            <?php else: ?>

                                                <div class="download-description text-muted">
                                                    Tidak ada deskripsi.
                                                </div>

                                            <?php endif; ?>

                                            <?php if (!empty($download['original_filename'])): ?>

                                                <div class="download-file-name">

                                                    <i class="fas fa-paperclip mr-1"></i>

                                                    <?= html_escape(
                                                        $download['original_filename']
                                                    ); ?>

                                                </div>

                                            <?php endif; ?>

                                        </div>

                                        <div class="card-footer bg-white">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div>

                                                    <?php if ($fileUrl): ?>

                                                        <button type="button" class="btn btn-primary btn-sm btn-preview-download"
                                                            data-id="<?= (int) $download['id']; ?>"
                                                            data-title="<?= html_escape($download['title']); ?>"
                                                            data-url="<?= $fileUrl; ?>" data-mime="<?= html_escape($mimeType); ?>">

                                                            <i class="fas fa-eye mr-1"></i>
                                                            Preview

                                                        </button>

                                                        <a href="<?= $fileUrl; ?>" class="btn btn-outline-primary btn-sm" download>

                                                            <i class="fas fa-download"></i>

                                                        </a>

                                                    <?php endif; ?>

                                                </div>

                                                <div class="btn-group">

                                                    <a href="<?= site_url('admin/downloads/edit/' . $download['id']); ?>"
                                                        class="btn btn-warning btn-sm" title="Edit">

                                                        <i class="fas fa-edit"></i>

                                                    </a>

                                                    <a href="<?= site_url('admin/downloads/delete/' . $download['id']); ?>"
                                                        class="btn btn-danger btn-sm" title="Delete"
                                                        onclick="return confirm('Yakin ingin menghapus download ini?');">

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

                                <i class="fas fa-download fa-4x text-muted"></i>

                            </div>

                            <h5>Belum Ada Download</h5>

                            <p class="text-muted mb-4">
                                Belum ada file yang ditambahkan.
                            </p>

                            <a href="<?= site_url('admin/downloads/create'); ?>" class="btn btn-primary">

                                <i class="fas fa-plus mr-1"></i>
                                Tambah Download

                            </a>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

</div>


<!-- Preview Modal -->

<div class="modal fade" id="download-preview-modal" tabindex="-1" role="dialog" aria-labelledby="download-preview-title"
    aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="download-preview-title">
                    Preview
                </h5>

                <button type="button" class="close" aria-label="Close" id="btn-close-download-preview">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body p-0" id="download-preview-content">
            </div>

        </div>

    </div>

</div>


<style>
    #download-preview-modal .modal-header {
        position: relative;
        padding-right: 60px;
    }

    #download-preview-modal .modal-header .close {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        margin: 0;
        padding: 0;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .download-card {
        overflow: hidden;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .download-card:hover {
        box-shadow: 0 5px 18px rgba(0, 0, 0, .10);
        transform: translateY(-2px);
    }

    .download-preview {
        position: relative;
        width: 100%;
        height: 220px;
        overflow: hidden;
        background: #f4f6f9;
    }

    .download-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .download-file-icon {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .download-file-icon i {
        font-size: 64px;
        margin-bottom: 10px;
    }

    .download-file-icon span {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .download-status {
        position: absolute;
        top: 12px;
        right: 12px;
    }

    .download-title {
        margin: 0 0 10px;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.35;

        overflow-wrap: anywhere;
        word-break: break-word;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .download-description {
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

    .download-file-name {
        margin-top: 12px;
        font-size: 12px;
        color: #6c757d;

        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .download-card .card-body {
        min-height: 170px;
    }

    @media (max-width: 767.98px) {

        .download-preview {
            height: 200px;
        }

        .download-card .card-body {
            min-height: auto;
        }

    }
</style>


<script>

    $(document).on('click', '.btn-preview-download', function (e) {

        e.preventDefault();

        const title = $(this).attr('data-title');
        const url = $(this).attr('data-url');
        const mime = $(this).attr('data-mime');

        $('#download-preview-title').text(title);

        let content = '';

        if (mime === 'application/pdf') {

            content = `
            <iframe
                src="${url}"
                width="100%"
                height="700"
                frameborder="0"
                style="display:block;">
            </iframe>
        `;

        } else if (mime.indexOf('image/') === 0) {

            content = `
            <div class="text-center p-3">
                <img
                    src="${url}"
                    alt="${title}"
                    class="img-fluid"
                    style="max-width:100%;max-height:75vh;object-fit:contain;">
            </div>
        `;

        } else {

            content = `
            <div class="text-center py-5">

                <i class="fas fa-file fa-4x text-muted mb-3"></i>

                <p class="text-muted mb-3">
                    Preview tidak tersedia untuk tipe file ini.
                </p>

                <a href="${url}"
                   class="btn btn-primary"
                   download>

                    <i class="fas fa-download mr-1"></i>
                    Download File

                </a>

            </div>
        `;

        }

        $('#download-preview-content').html(content);

        $('#download-preview-modal').modal('show');

    });


    $(document).on('click', '#btn-close-download-preview', function () {

        $('#download-preview-modal').modal('hide');

    });

    $('#download-preview-modal').on('hidden.bs.modal', function () {

        $('#download-preview-content').empty();

        $('#download-preview-title').text('Preview');

    });


</script>