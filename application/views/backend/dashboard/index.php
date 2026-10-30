<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row mb-4">

    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="fw-bold mb-1"><?= html_escape($site->site_name); ?></h3>
                        <div class="text-muted mb-2"><?= html_escape($site->tagline); ?></div>
                        <h5 class="mb-3"><?= html_escape($site->company_name); ?></h5>
                    </div>
                    <div class="text-end">
                        <?php if (!empty($site->logo_media_id)): ?>
                            <img src="<?= base_url('media/show/'.$site->logo_media_id); ?>" style="max-height:70px;">
                        <?php else: ?>
                            <i class="bi bi-building fs-1 text-secondary"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <p class="mb-0 text-muted">
                    <?= nl2br(html_escape($site->company_summary)); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="row g-3">

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Email</small>
                        <strong><?= html_escape($site->email); ?></strong>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Phone</small>
                        <strong><?= html_escape($site->phone); ?></strong>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted d-block">Address</small>
                        <div><?= nl2br(html_escape($site->address)); ?></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <strong>Branding</strong>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="rounded p-3 border" style="background:<?= html_escape($site->primary_color); ?>"></div>
                        <small class="d-block mt-2">Primary</small>
                    </div>
                    <div class="col-4">
                        <div class="rounded p-3 border" style="background:<?= html_escape($site->secondary_color); ?>"></div>
                        <small class="d-block mt-2">Secondary</small>
                    </div>
                    <div class="col-4">
                        <div class="rounded p-3 border" style="background:<?= html_escape($site->accent_color); ?>"></div>
                        <small class="d-block mt-2">Accent</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header"><strong>SEO</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">SEO Title</small>
                    <div><?= html_escape($site->seo_title); ?></div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Description</small>
                    <div><?= html_escape($site->seo_description); ?></div>
                </div>

                <div>
                    <small class="text-muted">Keywords</small>
                    <div><?= html_escape($site->seo_keywords); ?></div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <strong>Content Statistics</strong>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php
            $cards=[
                'Pages'=>'pages','News'=>'news','Media'=>'media','Portfolio'=>'portfolios',
                'Services'=>'services','Products'=>'products','Gallery'=>'galleries',
                'Downloads'=>'downloads','Teams'=>'teams','Clients'=>'clients',
                'Testimonials'=>'testimonials','Careers'=>'careers','Users'=>'users'
            ];
            foreach($cards as $label=>$key): ?>
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="border rounded text-center p-3 h-100">
                    <h3 class="fw-bold mb-1"><?= (int)$statistics[$key]; ?></h3>
                    <small class="text-muted"><?= $label; ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
