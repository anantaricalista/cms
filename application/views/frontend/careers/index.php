<?php defined('BASEPATH') OR exit('No direct script access allowed');

$company = !empty($site['company_name']) ? $site['company_name'] : 'Kami';
$phone = !empty($site['phone']) ? $site['phone'] : '';
?>

<section class="page-header">

    <div class="container">

        <span class="hbadge">
            <span class="bdot"></span>
            Karier
        </span>

        <h1 class="display-4 fw-bold mt-3 mb-3">
            Bergabung Bersama <span class="gt"><?= html_escape($company); ?></span>
        </h1>

        <p class="lead mb-0">
            Temukan peluang karier dan kembangkan potensi Anda bersama tim profesional kami.
        </p>

    </div>

</section>

<section class="section-padding">

    <div class="container">

        <?php if (!empty($careers)): ?>

            <div class="row justify-content-center">

                <div class="col-lg-10">

                    <div class="career-count mb-4">
                        <?= count($careers); ?> lowongan tersedia
                    </div>

                    <?php foreach ($careers as $i => $career): ?>

                        <?php
                        $collapse_id = 'career-' . (int) $career['id'];
                        $apply_url = whatsapp_contact_url(
                            $phone,
                            'Halo ' . $company . ', saya tertarik melamar posisi ' . $career['position'] . '.'
                        );
                        ?>

                        <article class="career-card mb-4">

                            <div class="career-main">

                                <div class="career-icon">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>

                                <div class="career-info">

                                    <h2><?= html_escape($career['position']); ?></h2>

                                    <div class="career-meta">

                                        <span>
                                            <i class="fa-solid fa-location-dot"></i>
                                            <?= html_escape($career['location']); ?>
                                        </span>

                                        <?php if (!empty($career['published_at'])): ?>
                                            <span>
                                                <i class="fa-regular fa-calendar"></i>
                                                <?= date('d M Y', strtotime($career['published_at'])); ?>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                </div>

                                <div class="career-actions">

                                    <button
                                        class="boc btn px-3 py-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?= $collapse_id; ?>"
                                        aria-expanded="false"
                                        aria-controls="<?= $collapse_id; ?>">
                                        Detail
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </button>

                                    <a
                                        href="<?= html_escape($apply_url); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="bgrd btn px-3 py-2">
                                        <i class="fa-brands fa-whatsapp me-2"></i>
                                        Lamar
                                    </a>

                                </div>

                            </div>

                            <div class="collapse" id="<?= $collapse_id; ?>">
                                <div class="career-detail">
                                    <?= $career['description']; ?>
                                </div>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </div>

        <?php else: ?>

            <div class="empty-state text-center">
                <div class="empty-icon"><i class="fa-solid fa-briefcase"></i></div>
                <h4>Belum ada lowongan saat ini</h4>
                <p>Silakan kunjungi kembali halaman ini untuk informasi lowongan terbaru.</p>
            </div>

        <?php endif; ?>

    </div>

</section>

<style>
    .page-header {
        padding-top: 100px;
        padding-bottom: 40px;
        text-align: center;
        background: linear-gradient(180deg, rgba(20, 184, 166, .08), transparent);
    }

    .page-header h1 {
        font-size: 2.4rem;
        color: var(--tx);
    }

    .page-header .lead {
        max-width: 640px;
        margin: auto;
        color: var(--tx2);
        line-height: 1.8;
    }

    .career-count {
        color: var(--tx3);
        font-size: .9rem;
        font-weight: 600;
    }

    .career-card {
        background: var(--sf);
        border: 1px solid var(--bd);
        border-radius: 18px;
        overflow: hidden;
        transition: .3s;
    }

    .career-card:hover {
        transform: translateY(-3px);
        border-color: rgba(20, 184, 166, .35);
        box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
    }

    .career-main {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 24px 28px;
    }

    .career-icon {
        flex-shrink: 0;
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(20, 184, 166, .12);
        color: var(--pur);
        font-size: 1.25rem;
    }

    .career-info {
        flex: 1;
        min-width: 0;
    }

    .career-info h2 {
        margin: 0 0 8px;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--tx);
    }

    .career-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 18px;
        font-size: .88rem;
        color: var(--tx2);
    }

    .career-meta i {
        margin-right: 6px;
        color: var(--pur);
    }

    .career-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .career-actions .btn {
        white-space: nowrap;
    }

    .career-actions [aria-expanded="true"] .fa-chevron-down {
        transform: rotate(180deg);
    }

    .career-actions .fa-chevron-down {
        transition: .25s;
    }

    .career-detail {
        padding: 24px 28px 28px;
        border-top: 1px solid var(--bd);
        color: var(--tx2);
        line-height: 1.8;
    }

    .career-detail h1,
    .career-detail h2,
    .career-detail h3,
    .career-detail h4 {
        color: var(--tx);
        margin: 1.2rem 0 .6rem;
        font-size: 1.1rem;
    }

    .career-detail ul,
    .career-detail ol {
        padding-left: 1.4rem;
    }

    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(20, 184, 166, .12);
        color: var(--pur);
        font-size: 1.8rem;
    }

    .empty-state p {
        color: var(--tx2);
    }

    @media (max-width: 767px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .career-main {
            flex-wrap: wrap;
            padding: 20px;
        }

        .career-actions {
            width: 100%;
        }

        .career-actions .btn {
            flex: 1;
        }
    }
</style>
