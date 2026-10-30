<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($portfolios))
{
    return;
}
?>

<section id="portfolio" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Portofolio

            </span>

            <h2 class="mt-3">

                Proyek yang Telah Kami Selesaikan

            </h2>

            <p class="mt-3">

                Berbagai proyek yang telah kami selesaikan menjadi bukti komitmen kami dalam menghadirkan solusi yang berkualitas dan sesuai dengan kebutuhan setiap klien.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($portfolios as $portfolio) : ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card h-100 border-0 shadow-sm portfolio-card">

                        <?php if (!empty($portfolio['featured_image_media_id'])) : ?>

                            <img src="<?= site_url('media/show/' . $portfolio['featured_image_media_id']); ?>"
                                alt="<?= html_escape($portfolio['title']); ?>"
                                class="card-img-top product-service-image">

                        <?php endif; ?>

                        <div class="card-body">

                            <h4 class="card-title">

                                <?= html_escape($portfolio['title']); ?>

                            </h4>

                            <?php if (!empty($portfolio['summary'])) : ?>

                                <p class="card-text mt-3">

                                    <?= nl2br(html_escape($portfolio['summary'])); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                            <a href="<?= site_url('portfolio/' . $portfolio['slug']); ?>" class="btn bgrd">

                                Lihat Proyek

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="text-center mt-5">

        <a href="<?= base_url('portfolio'); ?>" class="btn boc">

            Lihat Semua Portofolio

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</section>