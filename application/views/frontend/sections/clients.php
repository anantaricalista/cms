<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($clients))
{
    return;
}
?>

<section id="clients" class="position-relative overflow-hidden">

    <div class="container">

        <div class="text-center mb-5">

            <span class="hbadge">

                <span class="bdot"></span>

                Klien

            </span>

            <h2 class="mt-3">

                Dipercaya Berbagai Perusahaan

            </h2>

            <p class="mt-3">

                Kami bangga menjadi mitra bagi berbagai perusahaan dan organisasi dari berbagai sektor industri.

            </p>

        </div>

        <div class="row justify-content-center align-items-center g-4">

            <?php foreach ($clients as $client) : ?>

                <div class="col-6 col-md-4 col-lg-2">

                    <?php
                    $tag = !empty($client['website']) ? 'a' : 'div';
                    ?>

                    <<?php echo $tag; ?>

                        <?php if ($tag === 'a') : ?>
                            href="<?= html_escape($client['website']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        <?php endif; ?>

                        class="client-card card h-100 border-0 shadow-sm text-center text-decoration-none d-flex align-items-center justify-content-center">

                        <div class="card-body d-flex align-items-center justify-content-center">

                            <?php if (!empty($client['logo_media_id'])) : ?>

                                <img src="<?= site_url('media/show/' . $client['logo_media_id']); ?>"
                                    alt="<?= html_escape($client['name']); ?>"
                                    class="img-fluid object-fit-contain"
                                    style="max-height: 80px;">

                            <?php else : ?>

                                <strong class="text-secondary">

                                    <?= html_escape($client['name']); ?>

                                </strong>

                            <?php endif; ?>

                        </div>

                    </<?php echo $tag; ?>>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <div class="text-center mt-5">

        <a href="<?= base_url('client'); ?>" class="btn boc">

            Lihat Semua Klien

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</section>