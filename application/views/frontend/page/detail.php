<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="page-header">

    <div class="container">

        <h1 class="display-4 fw-bold mb-3">

            <?= html_escape($page['title']); ?>

        </h1>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb justify-content-center">

                <li class="breadcrumb-item">
                    <a href="<?= base_url(); ?>">Beranda</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page">
                    <?= html_escape($page['title']); ?>
                </li>

            </ol>

        </nav>

    </div>

</section>

<section class="section-padding">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <?php if (!empty($page['featured_image_media_id'])) : ?>

                    <div class="page-featured-image mb-5">

                        <img
                            src="<?= site_url('media/show/' . $page['featured_image_media_id']); ?>"
                            alt="<?= html_escape($page['title']); ?>">

                    </div>

                <?php endif; ?>

                <div class="page-content">

                    <?= $page['content']; ?>

                </div>

            </div>

        </div>

    </div>

</section>

<style>

.page-header{

    padding-top:100px;
    padding-bottom:60px;

    text-align:center;

    background:
        linear-gradient(
            180deg,
            rgba(255,255,255,.04),
            transparent
        );
}

.page-header h1{

    color:var(--tx);

    margin-bottom:18px;
}

.breadcrumb{

    justify-content:center;

    background:transparent;

    margin:0;

    padding:0;
}

.breadcrumb-item a{

    color:var(--tx2);

    text-decoration:none;
}

.breadcrumb-item.active{

    color:var(--pri);
}

.breadcrumb-item + .breadcrumb-item::before{

    color:var(--tx3);
}

.page-featured-image{

    width:100%;

    height:420px;

    overflow:hidden;

    border-radius:22px;

    box-shadow:
        0 20px 45px rgba(0,0,0,.12);
}

.page-featured-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    object-position:center;
}

.page-content{

    background:var(--sf);

    border:1px solid var(--bd);

    border-radius:22px;

    padding:48px;

    color:var(--tx);

    font-size:1rem;

    line-height:1.9;
}

.page-content h1,
.page-content h2,
.page-content h3,
.page-content h4,
.page-content h5{

    margin-top:2rem;

    margin-bottom:1rem;

    color:var(--tx);
}

.page-content p{

    margin-bottom:1.25rem;
}

.page-content ul,
.page-content ol{

    margin-bottom:1.25rem;

    padding-left:1.5rem;
}

.page-content blockquote{

    margin:2rem 0;

    padding:1rem 1.5rem;

    border-left:4px solid var(--pri);

    background:rgba(0,0,0,.03);

    border-radius:0 12px 12px 0;
}

.page-content img{

    max-width:100%;

    height:auto;

    border-radius:16px;

    margin:2rem 0;
}

.page-content table{

    width:100%;

    margin:2rem 0;
}

.page-content iframe{

    max-width:100%;
}

@media (max-width:991px){

    .page-header{

        padding-top:120px;

        padding-bottom:55px;
    }

    .page-featured-image{

        height:300px;
    }

    .page-content{

        padding:32px 24px;
    }

}

</style>