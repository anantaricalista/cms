<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.team-card {
    display: flex;
    flex-direction: column;
    background: var(--sf);
    border: 1px solid var(--bd);
    border-radius: 20px;
    overflow: hidden;
    transition: .35s;
    box-shadow: 0 8px 22px rgba(0, 0, 0, .05);
}
.team-card:hover {
    transform: translateY(-8px);
    border-color: var(--pri);
    box-shadow: 0 20px 45px rgba(0, 0, 0, .12);
}
.team-image {
    position: relative;
    height: 280px;
    overflow: hidden;
    background: #ececec;
}
.team-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: .45s;
}
.team-card:hover .team-image img {
    transform: scale(1.06);
}
.team-image-placeholder {
    width: 100%;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bd);
    color: var(--tx2);
    font-size: 4rem;
}
.team-body {
    padding: 24px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.team-name {
    color: var(--tx);
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 4px;
}
.team-position {
    color: var(--pri);
    font-weight: 600;
    font-size: .9rem;
    margin-bottom: 12px;
}
.team-description {
    color: var(--tx2);
    font-size: .9rem;
    line-height: 1.7;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.team-pagination {
    margin-top: 50px;
}
.team-pagination nav {
    display: flex;
    justify-content: center;
}
.team-pagination .pagination {
    gap: 10px;
    margin: 0;
    padding: 0;
}
.team-pagination .page-item {
    list-style: none;
}
.team-pagination .page-link {
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
.team-pagination .page-link:hover {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.team-pagination .page-item.active .page-link {
    background: var(--pri);
    border-color: var(--pri);
    color: #fff !important;
}
.team-pagination .page-item.disabled .page-link {
    background: var(--sf);
    border-color: var(--bd);
    color: var(--tx2) !important;
    opacity: .45;
    pointer-events: none;
}
@media(max-width:576px) {
    .team-pagination .page-link {
        width: 42px;
        height: 42px;
        font-size: .9rem;
    }
}
</style>

<?php if (!empty($team)): ?>
    <div class="row g-4">
        <?php foreach ($team as $member): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-card">
                    <?php if (!empty($member['photo_media_id'])): ?>
                        <div class="team-image">
                            <img src="<?= site_url('media/show/' . $member['photo_media_id']); ?>" alt="<?= html_escape($member['name']); ?>">
                        </div>
                    <?php else: ?>
                        <div class="team-image-placeholder">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    <?php endif; ?>
                    <div class="team-body">
                        <h3 class="team-name"><?= html_escape($member['name']); ?></h3>
                        <?php if (!empty($member['position'])): ?>
                            <div class="team-position"><?= html_escape($member['position']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($member['bio'])): ?>
                            <p class="team-description"><?= html_escape($member['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($pagination)): ?>
        <div class="team-pagination">
            <?= $pagination; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fa-solid fa-users fa-5x text-muted mb-4"></i>
        <h3 class="mb-3">Belum Ada Tim</h3>
        <p class="text-muted">Kami akan segera menampilkan profil tim profesional kami.</p>
    </div>
<?php endif; ?>
