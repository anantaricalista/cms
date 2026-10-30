<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.client-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: var(--sf);
    border: 1px solid var(--bd);
    border-radius: 20px;
    overflow: hidden;
    transition: .35s;
    box-shadow: 0 8px 22px rgba(0, 0, 0, .05);
    padding: 30px 24px;
    text-align: center;
}
.client-card:hover {
    transform: translateY(-8px);
    border-color: var(--pri);
    box-shadow: 0 20px 45px rgba(0, 0, 0, .12);
}
.client-logo {
    width: 100%;
    max-width: 160px;
    height: 100px;
    object-fit: contain;
    object-position: center;
    margin-bottom: 16px;
    transition: .35s;
}
.client-card:hover .client-logo {
    transform: scale(1.05);
}
.client-name {
    color: var(--tx);
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 8px;
}
.client-website {
    color: var(--pri);
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    transition: .25s;
}
.client-website:hover {
    letter-spacing: .3px;
}
.client-pagination {
    margin-top: 50px;
}
.client-pagination nav {
    display: flex;
    justify-content: center;
}
.client-pagination .pagination {
    gap: 10px;
    margin: 0;
    padding: 0;
}
.client-pagination .page-item {
    list-style: none;
}
.client-pagination .page-link {
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
.client-pagination .page-link:hover {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.client-pagination .page-item.active .page-link {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.client-pagination .page-item.disabled .page-link {
    background: var(--sf);
    border-color: var(--bd);
    color: var(--tx2) !important;
    opacity: .45;
    pointer-events: none;
}
@media(max-width:576px) {
    .client-pagination .page-link {
        width: 42px;
        height: 42px;
        font-size: .9rem;
    }
}
</style>

<?php if (!empty($clients)): ?>
    <div class="row g-4">
        <?php foreach ($clients as $client): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="client-card">
                    <?php if (!empty($client['logo_media_id'])): ?>
                        <img src="<?= site_url('media/show/' . $client['logo_media_id']); ?>" alt="<?= html_escape($client['name']); ?>" class="client-logo">
                    <?php else: ?>
                        <div class="client-logo d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-building fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <h3 class="client-name"><?= html_escape($client['name']); ?></h3>
                    <?php if (!empty($client['website'])): ?>
                        <a href="<?= html_escape($client['website']); ?>" target="_blank" rel="noopener noreferrer" class="client-website">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Kunjungi Website
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($pagination)): ?>
        <div class="client-pagination">
            <?= $pagination; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fa-regular fa-building fa-5x text-muted mb-4"></i>
        <h3 class="mb-3">Belum Ada Klien</h3>
        <p class="text-muted">Kami akan segera menampilkan daftar klien dan mitra bisnis kami.</p>
    </div>
<?php endif; ?>
