<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="section-padding" style="padding-top:120px;">
    <div class="container">
        <h1 class="stitle mb-4">Downloads</h1>
        <?php if (empty($downloads)): ?>
            <p>Belum ada dokumen yang tersedia untuk diunduh.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($downloads as $download): ?>
                    <div class="col-12 col-md-6">
                        <article class="gc p-4 h-100">
                            <h2><?= html_escape($download['title']); ?></h2>
                            <?php if (!empty($download['description'])): ?>
                                <p><?= nl2br(html_escape($download['description'])); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($download['media_id'])): ?>
                                <a class="bgrd btn" href="<?= site_url('media/show/' . $download['media_id']); ?>" download>
                                    Unduh
                                </a>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
