<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($testimonials))
{
    return;
}
?>

<section id="testimonials" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Testimoni

            </span>

            <h2 class="mt-3">

                Apa Kata Klien Kami

            </h2>

            <p class="mt-3">

                Kepercayaan klien merupakan motivasi kami untuk terus memberikan layanan terbaik.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($testimonials as $testimonial) : ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100 testimonial-card">

                        <div class="card-body">

                            <div class="d-flex align-items-center mb-4">

                                <?php if (!empty($testimonial['photo_media_id'])) : ?>

                                    <img src="<?= site_url('media/show/' . $testimonial['photo_media_id']); ?>"
                                        alt="<?= html_escape($testimonial['name']); ?>"
                                        class="rounded-circle me-3 object-fit-cover"
                                        width="72"
                                        height="72">

                                <?php endif; ?>

                                <div>

                                    <h5 class="mb-1 card-title">

                                        <?= html_escape($testimonial['name']); ?>

                                    </h5>

                                    <?php if (!empty($testimonial['position']) || !empty($testimonial['company'])) : ?>

                                        <small class="card-text text-secondary">

                                            <?php
                                            echo html_escape($testimonial['position']);

                                            if (!empty($testimonial['position']) && !empty($testimonial['company']))
                                            {
                                                echo ' · ';
                                            }

                                            echo html_escape($testimonial['company']);
                                            ?>

                                        </small>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <p class="card-text mb-0">

                                "<?= nl2br(html_escape($testimonial['content'])); ?>"

                            </p>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="text-center mt-5">

        <a href="<?= base_url('testimonial'); ?>" class="btn boc">

            Lihat Semua Testimoni

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</section>