<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (empty($hero_slides))
    return;
?>
<style>
    /* ===========================
   HERO CAROUSEL NAVIGATION
=========================== */

    #hero {
        position: relative;
    }

    #hero .carousel-control-prev,
    #hero .carousel-control-next {
        width: 70px;
        opacity: 1;
        z-index: 20;
    }

    #hero .carousel-control-prev {
        justify-content: flex-start;
        left: 20px;
    }

    #hero .carousel-control-next {
        justify-content: flex-end;
        right: 20px;
    }


    #hero .carousel-control-prev:hover .carousel-control-prev-icon,
    #hero .carousel-control-next:hover .carousel-control-next-icon {
        transform: scale(1.12);
        background-color: rgba(255, 255, 255, .30);
    }

    #hero .carousel-item {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
    }

    #hero .hero-bg {

        position: absolute;

        inset: 0;

        z-index: 0;

        background-position: center;

        background-size: cover;

        background-repeat: no-repeat;

        transform: scale(1.08);

    }

    #hero .hero-bg::after {

        content: "";

        position: absolute;

        inset: 0;

        background:
            linear-gradient(90deg,
                rgba(0, 0, 0, .65) 0%,
                rgba(0, 0, 0, .45) 45%,
                rgba(0, 0, 0, .30) 100%);
    }

    #hero .container {

        position: relative;

        z-index: 2;

    }

    /* ===========================
   HERO TYPOGRAPHY
=========================== */

    #hero h1 {

        color: #fff;

        font-weight: 800;

        line-height: 1.15;

        text-shadow:
            0 3px 18px rgba(0, 0, 0, .45);
    }

    #hero h2 {

        color: rgba(255, 255, 255, .92);

        font-weight: 500;

        margin-top: 20px;

        text-shadow:
            0 2px 12px rgba(0, 0, 0, .40);
    }

    #hero p {

        color: rgba(255, 255, 255, .82);

        font-size: 1.1rem;

        line-height: 1.9;

        margin-top: 24px;

        margin-bottom: 36px;

        max-width: 620px;

        text-shadow:
            0 2px 10px rgba(0, 0, 0, .35);
    }

    #hero .hbadge {

        /* background: rgba(255, 255, 255, .12);

        color: #fff;

        border: 1px solid rgba(255, 255, 255, .18); */

        backdrop-filter: blur(10px);
    }

    #hero .bdot {

        background: #fff;
    }

    #hero .col-lg-6 img {

        max-height: 560px;

        width: auto;

        max-width: 100%;

        object-fit: contain;

    }

    /* ===========================
   RESPONSIVE
=========================== */

    @media (max-width:991px) {

        #hero .carousel-control-prev {
            left: 8px;
        }

        #hero .carousel-control-next {
            right: 8px;
        }

        #hero .carousel-control-prev-icon,
        #hero .carousel-control-next-icon {
            width: 44px;
            height: 44px;
            background-size: 16px;
        }

    }

    /* ===========================
   HERO BUTTONS
=========================== */

    #hero .bgrd {

        padding: 14px 32px;

        border-radius: 14px;

        font-weight: 600;

        box-shadow: 0 12px 30px rgba(0, 0, 0, .18);
    }

    #hero .boc {

        padding: 14px 32px;

        border-radius: 14px;

        font-weight: 600;

        color: #fff;

        border: 1px solid rgba(255, 255, 255, .25);

        background: rgba(255, 255, 255, .08);

        backdrop-filter: blur(12px);

        transition: .3s;
    }

    #hero .boc:hover {

        color: #fff;

        background: rgba(255, 255, 255, .18);

        border-color: rgba(255, 255, 255, .45);

        transform: translateY(-2px);
    }

    #hero .boc i {

        margin-left: 8px;
    }
</style>
<section id="hero">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php foreach ($hero_slides as $i => $slide): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i; ?>"
                    class="<?= $i === 0 ? 'active' : ''; ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($hero_slides as $i => $slide): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : ''; ?>">
                    <?php if (!empty($slide['background_media_id'])): ?>
                        <div class="hero-bg"
                            style="background-image:url('<?= site_url('media/show/' . $slide['background_media_id']); ?>');">
                        </div>
                    <?php endif; ?>
                    <div class="container position-relative" style="z-index:2;">
                        <div class="row align-items-center min-vh-100">
                            <div class="col-lg-6">
                                <?php if (!empty($slide['badge'])): ?>
                                    <div class="hbadge"><?= html_escape($slide['badge']); ?></div><?php endif; ?>
                                <h1><?= nl2br(html_escape($slide['title'])); ?></h1>
                                <?php if (!empty($slide['subtitle'])): ?>
                                    <h2><?= html_escape($slide['subtitle']); ?></h2><?php endif; ?>
                                <?php if (!empty($slide['description'])): ?>
                                    <p><?= nl2br(html_escape($slide['description'])); ?></p><?php endif; ?>
                                <div class="d-flex gap-3">
                                    <?php if (!empty($slide['primary_button_text'])): ?><a
                                            href="<?= html_escape($slide['primary_button_url']); ?>"
                                            class="btn bgrd"><?= html_escape($slide['primary_button_text']); ?></a><?php endif; ?>
                                    <?php if (!empty($slide['secondary_button_text'])): ?><a
                                            href="<?= html_escape(!empty($slide['video_url']) ? $slide['video_url'] : $slide['secondary_button_url']); ?>"
                                            class="btn boc<?= !empty($slide['video_url']) ? ' vidpop' : ''; ?>"><?= html_escape($slide['secondary_button_text']); ?></a><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-6 text-center">
                                <?php if (!empty($slide['hero_media_id'])): ?><img class="img-fluid"
                                        src="<?= site_url('media/show/' . $slide['hero_media_id']); ?>"
                                        alt="<?= html_escape($slide['title']); ?>"><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span
                class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span
                class="carousel-control-next-icon"></span></button>
    </div>
</section>