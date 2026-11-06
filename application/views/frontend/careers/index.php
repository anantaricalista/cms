<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="section-padding" style="padding-top:120px;">
    <div class="container">
        <h1 class="stitle mb-4">Karier</h1>
        <?php if (empty($careers)): ?>
            <p>Belum ada lowongan yang dipublikasikan.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($careers as $career): ?>
                    <div class="col-12">
                        <article class="gc p-4">
                            <h2><?= html_escape($career['position']); ?></h2>
                            <p><?= html_escape($career['location']); ?></p>
                            <div><?= $career['description']; ?></div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
