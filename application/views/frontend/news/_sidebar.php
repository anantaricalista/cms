<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    <style>.news-sidebar {

        position: sticky;

        top: 100px;

    }

    .news-widget {

        background: var(--sf);

        border: 1px solid var(--bd);

        border-radius: 18px;

        padding: 22px;

        margin-bottom: 24px;

    }

    .news-widget-title {

        color: var(--tx);

        font-size: 1.05rem;

        font-weight: 700;

        margin-bottom: 18px;

    }

    .news-category {

        list-style: none;

        padding: 0;

        margin: 0;

    }

    .news-category li {

        border-bottom: 1px solid var(--bd);

    }

    .news-category li:last-child {

        border-bottom: 0;

    }

    .news-category a {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 12px;

        padding: 12px 0;

        color: var(--tx);

        text-decoration: none;

        transition: .25s;

    }

    .news-category a:hover {

        color: var(--pri);

        padding-left: 5px;

    }

    .news-category-name {

        flex: 1;

    }

    .news-category-count {

        min-width: 28px;

        height: 28px;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 0 8px;

        border-radius: 50px;

        background: rgba(0, 0, 0, .06);

        color: var(--tx2);

        font-size: .75rem;

        font-weight: 600;

    }

    .news-category a:hover .news-category-count {

        background: var(--pri);

        color: #fff;

    }

    .news-cta {

        background: linear-gradient(135deg,
                var(--pri),
                rgba(13, 110, 253, .85));

    }

    .news-cta .news-widget-title {

        color: var(--pri);

    }

    .news-cta p {

        color: var(--pri);

        line-height: 1.7;

        font-size: .9rem;

        margin-bottom: 20px;

    }

    @media(max-width:991px) {

        .news-sidebar {

            position: static;

            margin-top: 10px;

        }

    }
</style>
</style>
<aside class="news-sidebar">

    <div class="news-widget">

        <h5 class="news-widget-title">

            <i class="fa-solid fa-folder-open me-2"></i>

            Kategori

        </h5>

        <?php if (!empty($categories)): ?>

            <ul class="news-category">

                <?php if (!empty($categories)): ?>

                    <?php foreach ($categories as $category): ?>

                        <li>

                            <a href="<?= site_url('news?category=' . urlencode($category['slug'])); ?>">

                                <span class="news-category-name">

                                    <?= html_escape($category['name']); ?>

                                </span>

                                <span class="news-category-count">

                                    <?= (int) $category['total_news']; ?>

                                </span>

                            </a>

                        </li>

                    <?php endforeach; ?>

                <?php else: ?>

                    <li>

                        <span class="text-muted">

                            Belum ada kategori berita.

                        </span>

                    </li>

                <?php endif; ?>

            </ul>

        <?php else: ?>

            <p class="text-muted mb-0">

                Belum ada kategori berita.

            </p>

        <?php endif; ?>

    </div>

    <div class="news-widget news-cta">

        <h5 class="news-widget-title">

            Butuh Solusi Digital?

        </h5>

        <p class="lead">

            Diskusikan kebutuhan bisnis dan teknologi Anda bersama tim kami.

        </p>

        <a href="<?= base_url('contact'); ?>" class="bgrd flex-fill btn py-2">

            Hubungi Kami

            <i class="fa-solid fa-arrow-right ms-2"></i>

        </a>

    </div>

</aside>