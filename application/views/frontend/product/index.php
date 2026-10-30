<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="page-header">

    <div class="container">

        <h1 class="display-4 fw-bold mb-3">
            Produk Kami
        </h1>

        <?php if (!empty($description)): ?>

            <p class="lead mb-4">

                <?= nl2br(html_escape($description)); ?>

            </p>

        <?php endif; ?>

    </div>

</section>

<section class="section-padding">

    <div class="container">

        <?php if (!empty($products)) : ?>

            <div class="row g-4">

                <?php foreach ($products as $product) : ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="product-card h-100">

                            <?php if (!empty($product['featured_image_media_id'])) : ?>

                                <div class="product-thumb">

                                    <img
                                        src="<?= site_url('media/show/' . $product['featured_image_media_id']); ?>"
                                        alt="<?= html_escape($product['name']); ?>">

                                </div>

                            <?php endif; ?>

                            <div class="product-body">

                                <h3>

                                    <?= html_escape($product['name']); ?>

                                </h3>

                                <?php if (!empty($product['summary'])) : ?>

                                    <div class="product-summary">

                                        <?= html_escape($product['summary']); ?>

                                    </div>

                                <?php endif; ?>

                                <?php if (!empty($product['description'])) : ?>

                                    <div class="product-description">

                                        <?= character_limiter(strip_tags($product['description']), 160); ?>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <div class="product-footer">

                                <a
                                    href="<?= base_url('product/' . $product['slug']); ?>"
                                    class="service-link">

                                    Selengkapnya

                                    <i class="fa-solid fa-arrow-right ms-2"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else : ?>

            <div class="text-center py-5">

                <h4>

                    Belum ada produk yang dipublikasikan.

                </h4>

            </div>

        <?php endif; ?>

    </div>

</section>

<style>

.page-header{

    padding-top:90px;
    padding-bottom:38px;

    text-align:center;

    background:
        linear-gradient(
            180deg,
            rgba(255,255,255,.04),
            transparent
        );
}

.page-header h1{

    font-size:2rem;

    font-weight:700;

    margin-bottom:12px;

    color:var(--tx);
}

.page-header .lead{

    max-width:700px;

    margin:auto;

    font-size:1rem;

    color:var(--tx2);

    line-height:1.8;
}

.product-card{

    display:flex;

    flex-direction:column;

    height:100%;

    background:var(--sf);

    border:1px solid var(--bd);

    border-radius:18px;

    overflow:hidden;

    transition:.3s;
}

.product-card:hover{

    transform:translateY(-3px);

    border-color:var(--pri);

    box-shadow:0 12px 30px rgba(0,0,0,.08);
}

.product-thumb{

    height:220px;

    overflow:hidden;

    background:#f5f5f5;
}

.product-thumb img{

    width:100%;

    height:100%;

    object-fit:cover;

    object-position:center;

    transition:.45s;
}

.product-card:hover .product-thumb img{

    transform:scale(1.04);
}

.product-body{

    flex:1;

    display:flex;

    flex-direction:column;

    padding:22px 24px 18px;
}

.product-body h3{

    margin-bottom:10px;

    font-size:1.35rem;

    font-weight:700;

    color:var(--tx);

    line-height:1.4;
}

.product-summary{

    font-size:1rem;

    color:var(--pri);

    font-weight:600;

    margin-bottom:12px;

    line-height:1.6;
}

.product-description{

    color:var(--tx2);

    line-height:1.7;

    display:-webkit-box;

    -webkit-line-clamp:3;

    -webkit-box-orient:vertical;

    overflow:hidden;
}

.product-footer{

    padding:0 24px 22px;

    margin-top:auto;
}

.product-link{

    display:inline-flex;

    align-items:center;

    gap:8px;

    color:var(--pri);

    text-decoration:none;

    font-weight:600;

    transition:.3s;
}

.product-link i{

    transition:.3s;
}

.product-link:hover{

    letter-spacing:.3px;
}

.product-link:hover i{

    transform:translateX(5px);
}

@media (max-width:991px){

    .page-header{

        padding-top:90px;

        padding-bottom:30px;
    }

    .page-header h1{

        font-size:1.7rem;
    }

    .page-header .lead{

        font-size:.95rem;
    }

    .product-thumb{

        height:200px;
    }

    .product-body{

        padding:20px;
    }

    .product-body h3{

        font-size:1.25rem;
    }

    .product-footer{

        padding:0 20px 20px;
    }

}

</style>