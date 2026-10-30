<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($services)) {
    return;
}
?>

<section id="services" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Layanan Kami

            </span>

            <h2 class="mt-3">

                Solusi Profesional untuk Mendukung Bisnis Anda

            </h2>

            <p class="mt-3">

                Kami menyediakan berbagai layanan profesional yang dirancang untuk membantu perusahaan Anda berkembang
                melalui solusi yang tepat, efektif, dan berorientasi pada kebutuhan bisnis.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($services as $service): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card h-100 border-0 shadow-sm service-card">

                        <?php if (!empty($service['featured_image_media_id'])): ?>

                            <img src="<?= site_url('media/show/' . $service['featured_image_media_id']); ?>"
                                alt="<?= html_escape($service['name']); ?>" class="card-img-top product-service-image">

                        <?php endif; ?>

                        <div class="card-body">

                            <h4 class="card-title">

                                <?php echo html_escape($service['name']); ?>

                            </h4>

                            <?php if (!empty($service['summary'])): ?>

                                <p class="card-text mt-3">

                                    <?php echo nl2br(html_escape($service['summary'])); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                            <a href="<?php echo site_url('service/' . $service['slug']); ?>" class="btn bgrd">

                                Pelajari Selengkapnya

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
    <div class="text-center mt-5">

        <a href="<?= base_url('service'); ?>" class="btn boc">

            Layanan Lainnya

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>
</section>