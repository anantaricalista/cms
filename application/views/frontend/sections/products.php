<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($products)) {
    return;
}
?>

<section id="products" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Produk Kami

            </span>

            <h2 class="mt-3">

                Produk yang sudah teruji untuk Mendukung Bisnis Anda

            </h2>

            <p class="mt-3">

                Jelajahi berbagai produk yang kami kembangkan untuk membantu meningkatkan efisiensi, produktivitas, dan
                transformasi digital perusahaan Anda.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($products as $product): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card h-100 border-0 shadow-sm product-card">

                        <?php if (!empty($product['featured_image_media_id'])): ?>

                            <img src="<?= site_url('media/show/' . $product['featured_image_media_id']); ?>"
                                alt="<?= html_escape($product['name']); ?>" class="card-img-top product-service-image">

                        <?php endif; ?>

                        <div class="card-body">

                            <h4 class="card-title">

                                <?= html_escape($product['name']); ?>

                            </h4>

                            <?php if (!empty($product['summary'])): ?>

                                <p class="card-text mt-3">

                                    <?= nl2br(html_escape($product['summary'])); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                            <a href="<?= base_url('product/' . $product['slug']); ?>" class="btn bgrd">

                                Pelajari Selengkapnya

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
    <div class="text-center mt-5">

        <a href="<?= base_url('product'); ?>" class="btn boc">

            Produk Lainnya

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>
</section>