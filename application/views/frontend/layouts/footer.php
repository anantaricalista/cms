<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$site_name = !empty($site['site_name']) ? $site['site_name'] : 'Company';
$company_name = !empty($site['company_name']) ? $site['company_name'] : $site_name;
$summary = !empty($site['company_summary']) ? $site['company_summary'] : '';
$email = !empty($site['email']) ? $site['email'] : '';
$phone = !empty($site['phone']) ? $site['phone'] : '';
$address = !empty($site['address']) ? $site['address'] : '';
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

                <a href="#about">About</a>

                <a href="#team">Team</a>

                <a href="#news">News</a>

                <a href="#contact">Contact</a>

            </div>

            <!-- Services -->

            <div class="col-6 col-md-2 fcol">

                <h5>Services</h5>

                <a href="#services">Our Services</a>

                <a href="#products">Products</a>

                <a href="#portfolio">Portfolio</a>

                <a href="#gallery">Gallery</a>

            </div>

            <!-- Support -->

            <div class="col-6 col-md-2 fcol">

                <h5>Information</h5>

                <a href="#careers">Career</a>

                <a href="#downloads">Downloads</a>

                <a href="#contact">Support</a>

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

                <a href="#"
                   class="sico">

                    <i class="fa-brands fa-facebook-f"></i>

                </a>

                <a href="#"
                   class="sico">

                    <i class="fa-brands fa-instagram"></i>

                </a>

                <a href="#"
                   class="sico">

                    <i class="fa-brands fa-linkedin-in"></i>

                </a>

                <a href="#"
                   class="sico">

                    <i class="fa-brands fa-youtube"></i>

                </a>

            </div>

        </div>

    </div>

</footer>