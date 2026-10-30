<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($teams))
{
    return;
}
?>

<section id="team" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Tim Kami

            </span>

            <h2 class="mt-3">

                Kenali Tim Profesional Kami

            </h2>

            <p class="mt-3">

                Didukung oleh tenaga profesional yang berpengalaman di bidangnya.

            </p>

        </div>

        <div class="row g-4">

            <?php foreach ($teams as $member) : ?>

                <div class="col-lg-3 col-md-6">

                    <div class="card border-0 shadow-sm h-100 team-card">

                        <?php if (!empty($member['photo_media_id'])) : ?>

                            <img src="<?= site_url('media/show/' . $member['photo_media_id']); ?>"
                                alt="<?= html_escape($member['name']); ?>"
                                class="card-img-top product-service-image">

                        <?php endif; ?>

                        <div class="card-body text-center">

                            <h5 class="mb-1 card-title">

                                <?= html_escape($member['name']); ?>

                            </h5>

                            <p class="mb-3 card-text text-secondary small">

                                <?= html_escape($member['position']); ?>

                            </p>

                            <?php if (!empty($member['bio'])) : ?>

                                <p class="card-text mb-3 small">

                                    <?= nl2br(html_escape($member['bio'])); ?>

                                </p>

                            <?php endif; ?>

                        </div>

                        <div class="card-footer bg-transparent border-0">

                            <?php if (!empty($member['linkedin'])) : ?>

                                <a href="<?= html_escape($member['linkedin']); ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm bgrd w-100">

                                    <i class="fab fa-linkedin me-1"></i> LinkedIn

                                </a>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="text-center mt-5">

        <a href="<?= base_url('team'); ?>" class="btn boc">

            Lihat Semua Tim

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</section>