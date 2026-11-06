<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$site_name = !empty($site['site_name']) ? $site['site_name'] : 'Company';
$company_name = !empty($site['company_name']) ? $site['company_name'] : $site_name;
$summary = !empty($site['company_summary']) ? $site['company_summary'] : '';
$email = !empty($site['email']) ? $site['email'] : '';
$phone = !empty($site['phone']) ? $site['phone'] : '';
$whatsapp_url = whatsapp_contact_url(
    $phone,
    'Halo ' . $company_name . ', saya ingin menghubungi Anda.'
);
$address = !empty($site['address']) ? $site['address'] : '';
$social_links = [
    'whatsapp_url' => ['label' => 'WhatsApp', 'icon' => 'fa-brands fa-whatsapp'],
    'tiktok_url' => ['label' => 'TikTok', 'icon' => 'fa-brands fa-tiktok'],
    'facebook_url' => ['label' => 'Facebook', 'icon' => 'fa-brands fa-facebook-f'],
    'instagram_url' => ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram'],
    'youtube_url' => ['label' => 'YouTube', 'icon' => 'fa-brands fa-youtube'],
    'x_url' => ['label' => 'X', 'icon' => 'fa-brands fa-twitter'],
    'threads_url' => ['label' => 'Threads', 'icon' => 'fa-solid fa-at'],
    'linkedin_url' => ['label' => 'LinkedIn', 'icon' => 'fa-brands fa-linkedin-in'],
    'telegram_url' => ['label' => 'Telegram', 'icon' => 'fa-brands fa-telegram']
];
?>

<footer id="foot">

    <div class="container">

        <div class="row g-5 mb-5">

            <!-- Company -->

            <div class="col-lg-6">

                <a href="<?php echo base_url(); ?>"
                   class="d-flex align-items-center gap-2 mb-3 text-decoration-none">

                    <div class="logo-i">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>

                    <span style="font-size:1.15rem;font-weight:700;color:var(--tx);">
                        <?php echo html_escape($site_name); ?>
                    </span>

                </a>

                <p style="font-size:.875rem;color:var(--tx3);line-height:1.7;">

                    <?php echo html_escape($summary); ?>

                </p>

                <?php if ($email != '') : ?>

                    <div class="mt-3">

                        <div class="mb-2">

                            <i class="fa-regular fa-envelope me-2"></i>

                            <?php echo html_escape($email); ?>

                        </div>

                        <?php if ($phone != '') : ?>

                            <div class="mb-2">

                                <i class="fa-solid fa-phone me-2"></i>

                                <?php echo html_escape($phone); ?>

                            </div>

                        <?php endif; ?>

                        <?php if ($address != '') : ?>

                            <div>

                                <i class="fa-solid fa-location-dot me-2"></i>

                                <?php echo html_escape($address); ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>

            <!-- Company -->

            <div class="col-6 col-md-2 fcol">

                <h5>Company</h5>

                <?php foreach ($footer_pages as $footer_page): ?>
                    <a href="<?= html_escape(site_url('page/' . $footer_page['slug'])); ?>">
                        <?= html_escape($footer_page['title']); ?>
                    </a>
                <?php endforeach; ?>

            </div>

            <!-- Services -->

            <div class="col-6 col-md-2 fcol">

                <h5>Services</h5>

                <?php foreach ($footer_services as $footer_service): ?>
                    <a href="<?= html_escape(site_url('service/' . $footer_service['slug'])); ?>">
                        <?= html_escape($footer_service['name']); ?>
                    </a>
                <?php endforeach; ?>

            </div>

            <!-- Support -->

            <div class="col-6 col-md-2 fcol">

                <h5>Information</h5>

                <a href="<?= site_url('careers'); ?>">Career</a>

                <a href="<?= site_url('downloads'); ?>">Downloads</a>

                <a href="<?php echo html_escape($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">Support</a>

            </div>

        </div>

        <div
            class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-4"
            style="border-top:1px solid var(--bd);">

            <p style="font-size:.82rem;color:var(--tx3);margin:0;">

                &copy; <?php echo date('Y'); ?>

                <?php echo html_escape($company_name); ?>

                . All Rights Reserved.

            </p>

            <div class="d-flex gap-2">
                <?php foreach ($social_links as $field => $social): ?>
                    <?php if (!empty($site[$field])): ?>
                        <a
                            href="<?= html_escape($site[$field]); ?>"
                            class="sico"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?= html_escape($social['label']); ?>"
                            title="<?= html_escape($social['label']); ?>">
                            <i class="<?= html_escape($social['icon']); ?>" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

</footer>