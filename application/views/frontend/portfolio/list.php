<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.portfolio-card {
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
.portfolio-card:hover {
    transform: translateY(-8px);
    border-color: var(--pri);
    box-shadow: 0 20px 45px rgba(0, 0, 0, .12);
}
.portfolio-image {
    position: relative;
    height: 240px;
    overflow: hidden;
    background: #ececec;
}
.portfolio-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: .45s;
}
.portfolio-card:hover .portfolio-image img {
    transform: scale(1.08);
}
.portfolio-image-placeholder {
    width: 100%;
    height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bd);
    color: var(--tx2);
    font-size: 4rem;
}
.portfolio-meta {
    position: absolute;
    left: 18px;
    bottom: 18px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.portfolio-year {
    background: rgba(255, 255, 255, .94);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 7px 14px;
    font-size: .82rem;
    font-weight: 600;
    color: #222;
}
.portfolio-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 24px;
}
.portfolio-title {
    color: var(--tx);
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1.45;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.portfolio-client {
    color: var(--pri);
    font-weight: 600;
    font-size: .875rem;
    margin-bottom: 12px;
}
.portfolio-description {
    color: var(--tx2);
    line-height: 1.8;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: .9rem;
}
.portfolio-pagination {
    margin-top: 50px;
}
.portfolio-pagination nav {
    display: flex;
    justify-content: center;
}
.portfolio-pagination .pagination {
    gap: 10px;
    margin: 0;
    padding: 0;
}
.portfolio-pagination .page-item {
    list-style: none;
}
.portfolio-pagination .page-link {
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
.portfolio-pagination .page-link:hover {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.portfolio-pagination .page-item.active .page-link {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.portfolio-pagination .page-item.disabled .page-link {
    background: var(--sf);
    border-color: var(--bd);
    color: var(--tx2) !important;
    opacity: .45;
    pointer-events: none;
}
@media(max-width:991px) {
    .portfolio-image,
    .portfolio-image-placeholder {
        height: 210px;
    }
}
@media(max-width:576px) {
    .portfolio-image,
    .portfolio-image-placeholder {
        height: 200px;
    }
    .portfolio-pagination .page-link {
        width: 42px;
        height: 42px;
        font-size: .9rem;
    }
}
</style>

<?php if (!empty($portfolio)): ?>
    <div class="row g-4">
        <?php foreach ($portfolio as $item): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="portfolio-card">
                    <?php if (!empty($item['featured_image_media_id'])): ?>
                        <div class="portfolio-image">
                            <img src="<?= site_url('media/show/' . $item['featured_image_media_id']); ?>" alt="<?= html_escape($item['title']); ?>">
                            <?php if (!empty($item['project_year'])): ?>
                                <div class="portfolio-meta">
                                    <span class="portfolio-year">
                                        <i class="fa-regular fa-calendar me-1"></i><?= html_escape($item['project_year']); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="portfolio-image-placeholder">
                            <i class="fa-regular fa-folder-open"></i>
                        </div>
                    <?php endif; ?>
                    <div class="portfolio-body">
                        <h3 class="portfolio-title"><?= html_escape($item['title']); ?></h3>
                        <?php if (!empty($item['client'])): ?>
                            <div class="portfolio-client">
                                <i class="fa-regular fa-building me-1"></i><?= html_escape($item['client']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($item['description'])): ?>
                            <div class="portfolio-description"><?= character_limiter(strip_tags($item['description']), 180); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($pagination)): ?>
        <div class="portfolio-pagination">
            <?= $pagination; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fa-regular fa-folder-open fa-5x text-muted mb-4"></i>
        <h3 class="mb-3">Belum Ada Portfolio</h3>
        <p class="text-muted">Kami akan segera menampilkan proyek-proyek terbaik yang telah kami kerjakan.</p>
    </div>
<?php endif; ?>
