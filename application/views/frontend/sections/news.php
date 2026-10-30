<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($news)) {
    return;
}
?>

<section id="news" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Berita

            </span>

            <h2 class="mt-3">

                Berita dan Artikel Terbaru

            </h2>

            <p class="mt-3">

                Temukan informasi terbaru, artikel, serta berbagai wawasan mengenai
                layanan, kegiatan, dan perkembangan perusahaan kami.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($news as $article): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card h-100 border-0 shadow-sm product-card">

                        <?php if (!empty($article['featured_image_media_id'])): ?>

                            <img
                                src="<?= site_url('media/show/' . $article['featured_image_media_id']); ?>"
                                alt="<?= html_escape($article['title']); ?>"
                                class="card-img-top product-service-image">

                        <?php endif; ?>

                        <div class="card-body">

                            <?php if (!empty($article['published_at'])): ?>

                                <small class="text-muted d-block mb-2">

                                    <i class="fa-regular fa-calendar me-1"></i>

                                    <?= date('d M Y', strtotime($article['published_at'])); ?>

                                </small>

                            <?php endif; ?>

                            <h4 class="card-title">

                                <?= html_escape($article['title']); ?>

                            </h4>

                            <?php if (!empty($article['summary'])): ?>

                                <p class="card-text mt-3">

                                    <?= nl2br(html_escape($article['summary'])); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                            <a
                                href="<?= base_url('news/' . $article['slug']); ?>"
                                class="btn bgrd">

                                Baca Selengkapnya

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="text-center mt-5">

        <a href="<?= base_url('news'); ?>" class="btn boc">

            Berita Lainnya

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</section>