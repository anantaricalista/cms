<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>

.news-detail-layout{

    padding-top:70px;

    padding-bottom:80px;

}

.news-detail{

    background:var(--sf);

    border:1px solid var(--bd);

    border-radius:22px;

    overflow:hidden;

}

.news-detail-header{

    padding:34px 38px 28px;

}

.news-detail-categories{

    display:flex;

    flex-wrap:wrap;

    align-items:center;

    gap:8px;

    margin-bottom:20px;

}

.news-detail-category{

    display:inline-flex;

    align-items:center;

    padding:7px 13px;

    border-radius:50px;

    background:rgba(0,0,0,.05);

    border:1px solid var(--bd);

    color:var(--pri);

    text-decoration:none;

    font-size:.8rem;

    font-weight:600;

    transition:.25s;

}

.news-detail-category:hover{

    background:var(--pri);

    border-color:var(--pri);

    color:#fff;

}

.news-detail-title{

    color:var(--tx);

    font-size:2.35rem;

    line-height:1.3;

    font-weight:750;

    margin:0 0 18px;

}

.news-detail-meta{

    display:flex;

    flex-wrap:wrap;

    align-items:center;

    gap:10px 18px;

    color:var(--tx2);

    font-size:.88rem;

}

.news-detail-meta-item{

    display:inline-flex;

    align-items:center;

    gap:7px;

}

.news-detail-meta-item i{

    color:var(--pri);

}

.news-detail-image{

    width:100%;

    max-height:520px;

    overflow:hidden;

    background:#eee;

}

.news-detail-image img{

    display:block;

    width:100%;

    height:auto;

    max-height:520px;

    object-fit:cover;

    object-position:center;

}

.news-detail-content{

    padding:38px;

    color:var(--tx);

    font-size:1.05rem;

    line-height:1.9;

    overflow-wrap:anywhere;

}

.news-detail-content > *:first-child{

    margin-top:0;

}

.news-detail-content > *:last-child{

    margin-bottom:0;

}

.news-detail-content p{

    margin-bottom:1.4rem;

}

.news-detail-content h2{

    color:var(--tx);

    font-size:1.65rem;

    font-weight:700;

    line-height:1.4;

    margin-top:2.5rem;

    margin-bottom:1rem;

}

.news-detail-content h3{

    color:var(--tx);

    font-size:1.35rem;

    font-weight:700;

    line-height:1.4;

    margin-top:2rem;

    margin-bottom:.9rem;

}

.news-detail-content a{

    color:var(--pri);

}

.news-detail-content img{

    max-width:100%;

    height:auto;

    border-radius:12px;

}

.news-detail-content blockquote{

    margin:2rem 0;

    padding:18px 22px;

    border-left:4px solid var(--pri);

    background:rgba(0,0,0,.035);

    border-radius:0 12px 12px 0;

    color:var(--tx2);

}

.news-detail-content ul,
.news-detail-content ol{

    margin-bottom:1.5rem;

    padding-left:1.5rem;

}

.news-detail-content li{

    margin-bottom:.45rem;

}

.news-detail-sidebar{

    position:sticky;

    top:100px;

}

.news-detail-back{

    margin-top:26px;

}

.news-detail-back a{

    display:inline-flex;

    align-items:center;

    gap:8px;

    color:var(--tx2);

    text-decoration:none;

    font-size:.9rem;

    transition:.25s;

}

.news-detail-back a:hover{

    color:var(--pri);

}

@media(max-width:991px){

    .news-detail-layout{

        padding-top:35px;

        padding-bottom:60px;

    }

    .news-detail-header{

        padding:26px 24px 22px;

    }

    .news-detail-title{

        font-size:1.85rem;

    }

    .news-detail-image{

        max-height:420px;

    }

    .news-detail-content{

        padding:28px 24px;

        font-size:1rem;

    }

    .news-detail-sidebar{

        position:static;

        margin-top:10px;

    }

}

@media(max-width:575px){

    .news-detail-header{

        padding:22px 18px;

    }

    .news-detail-title{

        font-size:1.55rem;

    }

    .news-detail-meta{

        gap:8px 14px;

        font-size:.82rem;

    }

    .news-detail-content{

        padding:24px 18px;

    }

}

</style>

<section class="news-detail-layout">

    <div class="container">

        <div class="row g-5">

            <!-- ARTICLE -->

            <div class="col-lg-9">

                <article class="news-detail">

                    <header class="news-detail-header">

                        <?php if (!empty($news_categories)) : ?>

                            <div class="news-detail-categories">

                                <?php foreach ($news_categories as $category) : ?>

                                    <a
                                        href="<?= site_url('news?category=' . urlencode($category['slug'])); ?>"
                                        class="news-detail-category">

                                        <i class="fa-solid fa-folder-open me-1"></i>

                                        <?= html_escape($category['name']); ?>

                                    </a>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                        <h1 class="news-detail-title">

                            <?= html_escape($news['title']); ?>

                        </h1>

                        <div class="news-detail-meta">

                            <?php if (!empty($news['published_at'])) : ?>

                                <span class="news-detail-meta-item">

                                    <i class="fa-regular fa-calendar"></i>

                                    <?= date('d M Y', strtotime($news['published_at'])); ?>

                                </span>

                            <?php endif; ?>

                            <?php if (!empty($news['content'])) : ?>

                                <span class="news-detail-meta-item">

                                    <i class="fa-regular fa-clock"></i>

                                    <?= max(
                                        1,
                                        ceil(
                                            str_word_count(
                                                strip_tags($news['content'])
                                            ) / 200
                                        )
                                    ); ?>

                                    menit baca

                                </span>

                            <?php endif; ?>

                        </div>

                    </header>

                    <?php if (!empty($news['featured_image_media_id'])) : ?>

                        <div class="news-detail-image">

                            <img
                                src="<?= site_url('media/show/' . $news['featured_image_media_id']); ?>"
                                alt="<?= html_escape($news['title']); ?>">

                        </div>

                    <?php endif; ?>

                    <div class="news-detail-content">

                        <?= $news['content']; ?>

                    </div>

                </article>

                <div class="news-detail-back">

                    <a href="<?= site_url('news'); ?>">

                        <i class="fa-solid fa-arrow-left"></i>

                        Kembali ke Berita

                    </a>

                </div>

            </div>

            <!-- SIDEBAR -->

            <div class="col-lg-3">

                <div class="news-detail-sidebar">

                    <?php $this->load->view(
                        'frontend/news/_sidebar',
                        [
                            'categories' => $categories
                        ]
                    ); ?>

                </div>

            </div>

        </div>

    </div>

</section>