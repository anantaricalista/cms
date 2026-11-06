<?php defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('number');

$file_icon = function ($mime, $ext) {
    $mime = (string) $mime;
    $ext = strtolower((string) $ext);

    if ($mime === 'application/pdf' || $ext === 'pdf') {
        return ['fa-file-pdf', 'PDF'];
    }

    if (strpos($mime, 'image/') === 0) {
        return ['fa-file-image', strtoupper($ext ?: 'IMG')];
    }

    return ['fa-file-lines', strtoupper($ext ?: 'FILE')];
};
?>

<section class="page-header">

    <div class="container">

        <span class="hbadge">
            <span class="bdot"></span>
            Downloads
        </span>

        <h1 class="display-4 fw-bold mt-3 mb-3">
            Pusat <span class="gt">Unduhan</span>
        </h1>

        <p class="lead mb-0">
            Unduh brosur, katalog, dan dokumen penting kami dengan mudah.
        </p>

    </div>

</section>

<section class="section-padding">

    <div class="container">

        <?php if (!empty($downloads)): ?>

            <div class="row g-4">

                <?php foreach ($downloads as $download): ?>

                    <?php
                    list($icon, $type) = $file_icon($download['mime_type'] ?? '', $download['extension'] ?? '');
                    $size = !empty($download['file_size']) ? byte_format($download['file_size']) : '';
                    ?>

                    <div class="col-lg-4 col-md-6">

                        <article class="download-card h-100">

                            <div class="download-top">

                                <div class="download-icon">
                                    <i class="fa-solid <?= $icon; ?>"></i>
                                </div>

                                <span class="download-type"><?= html_escape($type); ?></span>

                            </div>

                            <div class="download-body">

                                <h2><?= html_escape($download['title']); ?></h2>

                                <?php if (!empty($download['description'])): ?>
                                    <p><?= html_escape(character_limiter(strip_tags($download['description']), 140)); ?></p>
                                <?php endif; ?>

                            </div>

                            <div class="download-footer">

                                <div class="download-meta">
                                    <?php if ($size): ?>
                                        <span><i class="fa-solid fa-hard-drive"></i> <?= html_escape($size); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($download['published_at'])): ?>
                                        <span><i class="fa-regular fa-calendar"></i> <?= date('d M Y', strtotime($download['published_at'])); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($download['media_id'])): ?>
                                    <a
                                        class="bgrd btn w-100 py-2 mt-3"
                                        href="<?= site_url('media/show/' . $download['media_id']); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        download>
                                        <i class="fa-solid fa-download me-2"></i>
                                        Unduh
                                    </a>
                                <?php endif; ?>

                            </div>

                        </article>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state text-center">
                <div class="empty-icon"><i class="fa-solid fa-folder-open"></i></div>
                <h4>Belum ada dokumen tersedia</h4>
                <p>Dokumen untuk diunduh akan tampil di halaman ini.</p>
            </div>

        <?php endif; ?>

    </div>

</section>

<style>
    .page-header {
        padding-top: 100px;
        padding-bottom: 40px;
        text-align: center;
        background: linear-gradient(180deg, rgba(20, 184, 166, .08), transparent);
    }

    .page-header h1 {
        font-size: 2.4rem;
        color: var(--tx);
    }

    .page-header .lead {
        max-width: 640px;
        margin: auto;
        color: var(--tx2);
        line-height: 1.8;
    }

    .download-card {
        display: flex;
        flex-direction: column;
        background: var(--sf);
        border: 1px solid var(--bd);
        border-radius: 18px;
        overflow: hidden;
        transition: .3s;
    }

    .download-card:hover {
        transform: translateY(-5px);
        border-color: rgba(20, 184, 166, .35);
        box-shadow: 0 16px 36px rgba(0, 0, 0, .1);
    }

    .download-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 22px 24px 0;
    }

    .download-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: rgba(20, 184, 166, .12);
        color: var(--pur);
        font-size: 1.5rem;
    }

    .download-type {
        padding: 4px 12px;
        border-radius: 50px;
        background: rgba(20, 184, 166, .1);
        color: var(--pur);
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .06em;
    }

    .download-body {
        flex: 1;
        padding: 18px 24px;
    }

    .download-body h2 {
        margin-bottom: 10px;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--tx);
        overflow-wrap: anywhere;
    }

    .download-body p {
        margin: 0;
        color: var(--tx2);
        line-height: 1.7;
        font-size: .93rem;
    }

    .download-footer {
        padding: 0 24px 24px;
    }

    .download-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 16px;
        padding-top: 16px;
        border-top: 1px solid var(--bd);
        font-size: .83rem;
        color: var(--tx3);
    }

    .download-meta i {
        margin-right: 5px;
        color: var(--pur);
    }

    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(20, 184, 166, .12);
        color: var(--pur);
        font-size: 1.8rem;
    }

    .empty-state p {
        color: var(--tx2);
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }
    }
</style>
