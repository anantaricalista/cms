<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($about_sections))
{
    return;
}

$about = $about_sections[0];
?>

<section id="about" class="section-padding">

    <div class="container">

        <div class="row align-items-center gy-5">

            <!-- ==========================================================
                 IMAGE
            =========================================================== -->

            <div class="col-lg-6">

                <?php if (!empty($about['image_media_id'])) : ?>

                    <!--
                        Image akan menggunakan Media Controller
                        setelah Media Module selesai.
                    -->

                    <img
                        src=""
                        alt="<?php echo html_escape($about['title']); ?>"
                        class="img-fluid rounded-4 shadow">

                <?php endif; ?>

            </div>

            <!-- ==========================================================
                 CONTENT
            =========================================================== -->

            <div class="col-lg-6">

                <?php if (!empty($about['badge'])) : ?>

                    <span class="hbadge">

                        <span class="bdot"></span>

                        <?php echo html_escape($about['badge']); ?>

                    </span>

                <?php endif; ?>

                <h2 class="mt-3">

                    <?php echo html_escape($about['title']); ?>

                </h2>

                <?php if (!empty($about['subtitle'])) : ?>

                    <h5 class="ssub mt-3">

                        <?php echo html_escape($about['subtitle']); ?>

                    </h5>

                <?php endif; ?>

                <?php if (!empty($about['description'])) : ?>

                    <p class="ssub mt-4">

                        <?php echo nl2br(html_escape($about['description'])); ?>

                    </p>

                <?php endif; ?>

                <?php if (!empty($about['button_text'])) : ?>

                    <a
                        href="<?php echo html_escape($about['button_url']); ?>"
                        class="bgrd btn mt-4 px-4 py-3">

                        <?php echo html_escape($about['button_text']); ?>

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>