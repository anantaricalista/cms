<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .news-card {

        display: flex;

        flex-direction: column;

        height: 100%;

        background: var(--sf);

        border: 1px solid var(--bd);

        border-radius: 20px;

        overflow: hidden;

        transition: .35s;

        box-shadow: 0 8px 22px rgba(0, 0, 0, .05);

    }

    .news-card:hover {

        transform: translateY(-8px);

        border-color: var(--pri);

        box-shadow: 0 20px 45px rgba(0, 0, 0, .12);

    }

    .news-image {

        position: relative;

        height: 240px;

        overflow: hidden;

        background: #ececec;

    }

    .news-image img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        object-position: center;

        transition: .45s;

    }

    .news-card:hover .news-image img {

        transform: scale(1.08);

    }

    .news-meta {

        position: absolute;

        left: 18px;

        right: 18px;

        bottom: 18px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 10px;

    }

    .news-date,
    .news-reading {

        background: rgba(255, 255, 255, .94);

        backdrop-filter: blur(10px);

        border-radius: 50px;

        padding: 7px 14px;

        font-size: .82rem;

        font-weight: 600;

        color: #222;

    }

    .news-body {

        display: flex;

        flex-direction: column;

        flex: 1;

        padding: 24px;

    }

    .news-title {

        color: var(--tx);

        font-size: 1.35rem;

        font-weight: 700;

        line-height: 1.45;

        margin-bottom: 14px;

        min-height: 64px;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

    .news-summary {

        color: var(--pri);

        font-weight: 600;

        font-style: italic;

        line-height: 1.7;

        margin-bottom: 16px;

        padding-left: 14px;

        border-left: 3px solid var(--pri);

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

    .news-description {

        color: var(--tx2);

        line-height: 1.8;

        display: -webkit-box;

        -webkit-line-clamp: 4;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

    .news-footer {

        display: flex;

        justify-content: flex-end;

        align-items: center;

        padding: 18px 24px;

        border-top: 1px solid var(--bd);

    }

    .news-link {

        color: var(--pri);

        font-weight: 600;

        text-decoration: none;

        transition: .25s;

    }

    .news-link i {

        transition: .25s;

    }

    .news-link:hover {

        letter-spacing: .3px;

    }

    .news-link:hover i {

        transform: translateX(6px);

    }

    /* ===========================
   Pagination
=========================== */

    .news-pagination {

        margin-top: 50px;

    }

    .news-pagination nav {

        display: flex;

        justify-content: center;

    }

    .news-pagination .pagination {

        gap: 10px;

        margin: 0;

        padding: 0;

    }

    .news-pagination .page-item {

        list-style: none;

    }

    .news-pagination .page-link {

        width: 48px;

        height: 48px;

        display: flex;

        justify-content: center;

        align-items: center;

        border-radius: 12px;

        border: 1px solid var(--bd);

        background: var(--sf);

        color: var(--tx) !important;

        font-weight: 600;

        text-decoration: none;

        transition: .25s;

        box-shadow: none;

    }

    .news-pagination .page-link:hover {

        background: var(--pri);

        border-color: var(--pri);

        color: #fff !important;

    }

    .news-pagination .page-item.active .page-link {

        background: var(--pri);

        border-color: var(--pri);

        color: #fff !important;

    }

    .news-pagination .page-item.disabled .page-link {

        background: var(--sf);

        border-color: var(--bd);

        color: var(--tx2) !important;

        opacity: .45;

        pointer-events: none;

    }

    .news-pagination .page-link:focus {

        box-shadow: none;

    }

    .news-pagination .page-link:focus-visible {

        outline: none;

    }

    @media(max-width:991px) {

        .news-image {

            height: 210px;

        }

    }

    @media(max-width:576px) {

        .news-image {

            height: 200px;

        }

        .news-meta {

            flex-direction: column;

            align-items: flex-start;

        }

        .news-title {

            font-size: 1.2rem;

            min-height: auto;

        }

        .news-pagination .page-link {

            width: 42px;

            height: 42px;

            font-size: .9rem;

        }

    }
</style>

<?php if (!empty($news)): ?>

    <div class="row g-4">

        <div class="row col-lg-9 col-md-12">
            <?php foreach ($news as $article): ?>

                <div class="col-lg-4 col-md-6 mb-4">

                    <article class="news-card">

                        <?php if (!empty($article['featured_image_media_id'])): ?>

                            <div class="news-image">

                                <img src="<?= site_url('media/show/' . $article['featured_image_media_id']); ?>"
                                    alt="<?= html_escape($article['title']); ?>">

                                <div class="news-meta">

                                    <div class="news-date">

                                        <i class="fa-regular fa-calendar me-1"></i>

                                        <?= date('d M Y', strtotime($article['published_at'])); ?>

                                    </div>

                                    <div class="news-reading">

                                        <i class="fa-regular fa-clock me-1"></i>

                                        <?= max(1, ceil(str_word_count(strip_tags($article['content'])) / 200)); ?>

                                        menit baca

                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                        <div class="news-body">

                            <h3 class="news-title">

                                <?= html_escape($article['title']); ?>

                            </h3>

                            <?php if (!empty($article['summary'])): ?>

                                <div class="news-summary">

                                    <?= html_escape($article['summary']); ?>

                                </div>

                            <?php endif; ?>

                            <?php if (!empty($article['content'])): ?>

                                <div class="news-description">

                                    <?= character_limiter(strip_tags($article['content']), 220); ?>

                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="news-footer">

                            <a href="<?= base_url('news/' . $article['slug']); ?>" class="news-link">

                                Baca Artikel

                                <i class="fa-solid fa-arrow-right ms-2"></i>

                            </a>

                        </div>

                    </article>

                </div>

            <?php endforeach; ?>
            <?php if (!empty($pagination)): ?>

                <div class="news-pagination">

                    <?= $pagination; ?>

                </div>

            <?php endif; ?>
        </div>

        <div class="col-lg-3">

            <?php $this->load->view('frontend/news/_sidebar'); ?>

        </div>
    </div>



<?php else: ?>

    <div class="text-center py-5">

        <i class="fa-regular fa-newspaper fa-5x text-muted mb-4"></i>

        <h3 class="mb-3">

            Belum Ada Berita

        </h3>

        <p class="text-muted">

            Kami akan segera membagikan berita dan artikel terbaru.

        </p>

    </div>

<?php endif; ?>