<section id="cta" class="position-relative overflow-hidden">

    <div class="aur aur-a" style="top:-120px;left:-150px;"></div>
    <div class="aur aur-b" style="bottom:-120px;right:-150px;"></div>

    <div class="container position-relative" style="z-index:2;">

        <!-- Services Overview -->
        <div class="row g-4 mb-6">

            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-scale-balanced" style="font-size:2.5rem;color:var(--pt);"></i>
                    </div>
                    <h5>Konsultan Legalitas Hukum</h5>
                    <p class="text-secondary small">Perlindungan hukum & perizinan perusahaan Anda</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-calculator" style="font-size:2.5rem;color:var(--pt);"></i>
                    </div>
                    <h5>Pajak & Akuntansi</h5>
                    <p class="text-secondary small">Manajemen keuangan & perpajakan yang efisien</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-building" style="font-size:2.5rem;color:var(--pt);"></i>
                    </div>
                    <h5>Virtual Working Space</h5>
                    <p class="text-secondary small">Ruang kerja virtual untuk tim yang fleksibel</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fa-solid fa-laptop" style="font-size:2.5rem;color:var(--pt);"></i>
                    </div>
                    <h5>Konsultan IT</h5>
                    <p class="text-secondary small">Solusi teknologi & infrastruktur digital</p>
                </div>
            </div>

        </div>

        <!-- CTA Section -->
        <div class="gc p-5 p-lg-6 text-center">

            <span class="slbl justify-content-center">
                Hubungi Kami
            </span>

            <h2 class="stitle mb-4">
                Butuh Solusi Bisnis
                <span class="gt">untuk Perusahaan Anda?</span>
            </h2>

            <p class="ssub mx-auto mb-5">

                Tim profesional <strong><?php echo html_escape($site['company_name']); ?></strong>
                siap memberikan konsultasi terbaik di bidang legalitas, pajak, ruang kerja virtual,
                dan teknologi informasi untuk mengembangkan bisnis Anda.

            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">

                <?php if (!empty($site['phone'])) : ?>

                    <a
                        href="tel:<?php echo html_escape($site['phone']); ?>"
                        class="bgrd btn btn-lg px-4">

                        <i class="fa-solid fa-phone me-2"></i>

                        Hubungi Kami

                    </a>

                <?php endif; ?>

                <?php if (!empty($site['email'])) : ?>

                    <a
                        href="mailto:<?php echo html_escape($site['email']); ?>"
                        class="boc btn btn-lg px-4">

                        <i class="fa-regular fa-envelope me-2"></i>

                        Kirim Email

                    </a>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>