<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="page-header">

    <div class="container">

        <h1 class="display-4 fw-bold mb-3">

            Layanan Kami

        </h1>

        <?php if (!empty($description)): ?>

            <p class="lead mb-4">

                <?= nl2br(html_escape($description)); ?>

            </p>

        <?php endif; ?>

    </div>

</section>

<section class="section-padding">

    <div class="container">

        <?php if (!empty($services)): ?>

            <div class="row g-4">

                <?php foreach ($services as $service): ?>

                    <div class="col-12">

                        <div class="service-card">

                            <div class="row g-0 align-items-center">

                                <div class="col-lg-4">

                                    <div class="service-thumb">

                                        <?php if (!empty($service['featured_image_media_id'])): ?>

                                            <img src="<?= site_url('media/show/' . $service['featured_image_media_id']); ?>"
                                                alt="<?= html_escape($service['name']); ?>">

                                        <?php endif; ?>

                                    </div>

                                </div>

                                <div class="col-lg-8">

                                    <div class="service-body">

                                        <h2>

                                            <?= html_escape($service['name']); ?>

                                        </h2>

                                        <?php if (!empty($service['summary'])): ?>

                                            <div class="service-summary">

                                                <?= nl2br(html_escape($service['summary'])); ?>

                                            </div>

                                        <?php endif; ?>

                                        <?php if (!empty($service['description'])): ?>

                                            <div class="service-description">

                                                <?= character_limiter(strip_tags($service['description']), 280); ?>

                                            </div>

                                        <?php endif; ?>

                                        <a href="<?= base_url('service/' . $service['slug']); ?>" class="service-link">

                                            Selengkapnya

                                            <i class="fa-solid fa-arrow-right ms-2"></i>

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

                <h4>

                    Belum ada layanan yang dipublikasikan.

                </h4>

            </div>

        <?php endif; ?>

    </div>

</section>

<style>
    .page-header {

        padding-top: 90px;
        padding-bottom: 38px;

        text-align: center;

        background:
            linear-gradient(180deg,
                rgba(255, 255, 255, .04),
                transparent);
    }

    .page-header h1 {

        font-size: 2rem;

        font-weight: 700;

        margin-bottom: 12px;

        color: var(--tx);
    }

    .page-header .lead {

        max-width: 700px;

        margin: auto;

        font-size: 1rem;

        color: var(--tx2);

        line-height: 1.8;
    }

    .service-card {

        background: var(--sf);

        border: 1px solid var(--bd);

        border-radius: 18px;

        overflow: hidden;

        transition: .3s;
    }

    .service-card:hover {

        transform: translateY(-3px);

        border-color: var(--pri);

        box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
    }

    .service-thumb {

        height: 180px;

        overflow: hidden;

        background: #f5f5f5;
    }

    .service-thumb img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        object-position: center;

        transition: .45s;
    }

    .service-card:hover .service-thumb img {

        transform: scale(1.04);
    }

    .service-body {

        padding: 22px 26px;
    }

    .service-body h2 {

        margin-bottom: 10px;

        font-size: 1.45rem;

        font-weight: 700;

        color: var(--tx);
    }

    .service-summary {

        font-size: 1rem;

        color: var(--pri);

        font-weight: 600;

        margin-bottom: 12px;

        line-height: 1.6;
    }

    .service-description {

        color: var(--tx2);

        line-height: 1.7;

        margin-bottom: 18px;

        display: -webkit-box;

        -webkit-line-clamp: 3;

        -webkit-box-orient: vertical;

        overflow: hidden;
    }

    .service-link {

        display: inline-flex;

        align-items: center;

        gap: 8px;

        color: var(--pri);

        text-decoration: none;

        font-weight: 600;

        transition: .3s;
    }

    .service-link i {

        transition: .3s;
    }

    .service-link:hover {

        letter-spacing: .3px;
    }

    .service-link:hover i {

        transform: translateX(5px);
    }

    @media (max-width:991px) {

        .page-header {

            padding-top: 90px;

            padding-bottom: 30px;
        }

        .page-header h1 {

            font-size: 1.7rem;
        }

        .page-header .lead {

            font-size: .95rem;
        }

        .service-thumb {

            height: 220px;
        }

        .service-body {

            padding: 20px;
        }

        .service-body h2 {

            font-size: 1.3rem;
        }

    }
</style>