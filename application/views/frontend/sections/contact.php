<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (empty($site))
{
    return;
}

// Load models untuk ambil data Layanan dan Produk
$this->load->model('Service_model');
$this->load->model('Product_model');

$services = $this->Service_model->get_published();
$products = $this->Product_model->get_published();
?>

<!-- Set Site Settings untuk JavaScript -->
<script>
    window.siteSettings = {
        company_name: '<?= isset($site['company_name']) ? addslashes($site['company_name']) : 'Kami'; ?>',
        phone: '<?= isset($site['phone']) ? addslashes($site['phone']) : ''; ?>'
    };
</script>

<section id="contact" class="section-padding position-relative overflow-hidden">

    <!-- Background Decorative Elements -->
    <div class="position-absolute" style="top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(var(--pt-rgb),.05) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>
    <div class="position-absolute" style="bottom: -80px; left: -100px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(var(--pt-rgb),.05) 0%, transparent 70%); border-radius: 50%; z-index: 0;"></div>

    <div class="container position-relative" style="z-index: 1;">

        <!-- Header -->
        <div class="text-center mb-5">

            <span class="hbadge">
                <span class="bdot"></span>
                Hubungi Kami
            </span>

            <h2 class="stitle mt-3">
                Mari Berdiskusi <span class="gt">Bersama Kami</span>
            </h2>

            <p class="ssub mt-3 mx-auto" style="max-width: 500px;">
                Kami siap membantu menjawab pertanyaan maupun kebutuhan konsultasi Anda.
            </p>

        </div>

        <!-- Main Contact Section -->
        <div class="row g-4 align-items-stretch">

            <!-- Left Column - Contact Information -->
            <div class="col-lg-4">

                <div class="h-100 p-4 p-lg-5 rounded-3" style="background: linear-gradient(135deg, rgba(var(--pt-rgb),.08) 0%, rgba(var(--pt-rgb),.02) 100%); border: 1px solid rgba(var(--pt-rgb),.1); backdrop-filter: blur(10px);">

                    <h5 class="mb-4 fw-bold">Informasi Kontak</h5>

                    <!-- Company Info -->
                    <?php if (!empty($site['company_name'])) : ?>

                        <div class="mb-4 pb-4 border-bottom" style="border-color: rgba(var(--pt-rgb),.1);">

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    <div class="rounded-2 p-3" style="background: rgba(var(--pt-rgb),.1);">
                                        <i class="fa-solid fa-building" style="font-size:1.2rem;color:var(--pt);"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <p class="small text-secondary mb-1">Perusahaan</p>
                                    <p class="fw-semibold mb-0"><?php echo html_escape($site['company_name']); ?></p>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- Address Info -->
                    <?php if (!empty($site['address'])) : ?>

                        <div class="mb-4 pb-4 border-bottom" style="border-color: rgba(var(--pt-rgb),.1);">

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    <div class="rounded-2 p-3" style="background: rgba(var(--pt-rgb),.1);">
                                        <i class="fa-solid fa-map-location-dot" style="font-size:1.2rem;color:var(--pt);"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <p class="small text-secondary mb-1">Alamat</p>
                                    <p class="fw-semibold small mb-0"><?php echo nl2br(html_escape($site['address'])); ?></p>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- Phone Info -->
                    <?php if (!empty($site['phone'])) : ?>

                        <div class="mb-4 pb-4 border-bottom" style="border-color: rgba(var(--pt-rgb),.1);">

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    <div class="rounded-2 p-3" style="background: rgba(var(--pt-rgb),.1);">
                                        <i class="fa-solid fa-phone" style="font-size:1.2rem;color:var(--pt);"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <p class="small text-secondary mb-1">Telepon</p>
                                    <?php 
                                        $phone = $site['phone'];
                                        $phone_clean = preg_replace('/\D/', '', $phone);
                                        if (!preg_match('/^62/', $phone_clean)) {
                                            if (preg_match('/^0/', $phone_clean)) {
                                                $phone_clean = '62' . substr($phone_clean, 1);
                                            } else {
                                                $phone_clean = '62' . $phone_clean;
                                            }
                                        }
                                        $message = urlencode("Halo " . $site['company_name'] . ", saya ingin berkonsultasi dengan Anda");
                                        $whatsapp_url = "https://wa.me/{$phone_clean}?text={$message}";
                                    ?>
                                    <a href="<?php echo $whatsapp_url; ?>" class="fw-semibold text-decoration-none" style="color:var(--tx);" target="_blank" rel="noopener noreferrer">
                                        <i class="fa-brands fa-whatsapp me-2"></i><?php echo html_escape($phone); ?>
                                    </a>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                    <!-- Email Info -->
                    <?php if (!empty($site['email'])) : ?>

                        <div class="mb-0">

                            <div class="d-flex gap-3">

                                <div class="flex-shrink-0">
                                    <div class="rounded-2 p-3" style="background: rgba(var(--pt-rgb),.1);">
                                        <i class="fa-solid fa-envelope" style="font-size:1.2rem;color:var(--pt);"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <p class="small text-secondary mb-1">Email</p>
                                    <a href="mailto:<?php echo html_escape($site['email']); ?>" class="fw-semibold text-decoration-none" style="color:var(--tx);">
                                        <?php echo html_escape($site['email']); ?>
                                    </a>
                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Right Column - Contact Form -->
            <div class="col-lg-8">

                <div class="h-100 p-4 p-lg-5 rounded-3" style="background: linear-gradient(135deg, rgba(var(--pt-rgb),.04) 0%, rgba(var(--pt-rgb),.01) 100%); border: 1px solid rgba(var(--pt-rgb),.1); backdrop-filter: blur(10px);">

                    <h5 class="mb-4 fw-bold">Kirim Pesan Anda</h5>

                    <form method="post" action="<?= base_url('contact/submit'); ?>" id="contactForm">

                        <!-- Name & Email Row -->
                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label small fw-semibold d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-solid fa-user" style="font-size:0.9rem;color:var(--pt);"></i>
                                        Nama Lengkap
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control form-control-lg rounded-2"
                                        placeholder="Nama Anda"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label small fw-semibold d-flex align-items-center gap-2 mb-2">
                                        <i class="fa-solid fa-envelope" style="font-size:0.9rem;color:var(--pt);"></i>
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control form-control-lg rounded-2"
                                        placeholder="Email Anda"
                                        required>
                                </div>
                            </div>

                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="phone" class="form-label small fw-semibold d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-phone" style="font-size:0.9rem;color:var(--pt);"></i>
                                    Nomor Telepon
                                </label>
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    class="form-control form-control-lg rounded-2"
                                    placeholder="+62 812-3456-7890">
                            </div>
                        </div>

                        <!-- Subject - Dynamic from Services & Products -->
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="subject" class="form-label small fw-semibold d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-list" style="font-size:0.9rem;color:var(--pt);"></i>
                                    Perihal
                                </label>
                                <select
                                    name="subject"
                                    id="subject"
                                    class="form-select form-select-lg rounded-2"
                                    required>
                                    <option value="">-- Pilih Perihal --</option>

                                    <!-- Services Group -->
                                    <?php if (!empty($services)) : ?>

                                        <optgroup label="Layanan">
                                            <?php foreach ($services as $service) : ?>
                                                <option value="<?php echo html_escape($service['name']); ?>">
                                                    <?php echo html_escape($service['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>

                                    <?php endif; ?>

                                    <!-- Products Group -->
                                    <?php if (!empty($products)) : ?>

                                        <optgroup label="Produk">
                                            <?php foreach ($products as $product) : ?>
                                                <option value="<?php echo html_escape($product['name']); ?>">
                                                    <?php echo html_escape($product['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>

                                    <?php endif; ?>

                                    <!-- Other Options -->
                                    <optgroup label="Lainnya">
                                        <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                                        <option value="Keluhan/Masalah">Keluhan/Masalah</option>
                                        <option value="Kemitraan">Kemitraan</option>
                                    </optgroup>

                                </select>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="message" class="form-label small fw-semibold d-flex align-items-center gap-2 mb-2">
                                    <i class="fa-solid fa-pen" style="font-size:0.9rem;color:var(--pt);"></i>
                                    Pesan
                                </label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="5"
                                    class="form-control rounded-2"
                                    placeholder="Ceritakan kebutuhan atau pertanyaan Anda..."
                                    style="resize: none; font-family: inherit;"
                                    required></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 pt-2">

                            <button
                                type="submit"
                                class="bgrd btn btn-lg flex-grow-1 rounded-2 fw-semibold"
                                style="transition: all .3s ease;">

                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim Pesan

                            </button>

                            <button
                                type="reset"
                                class="btn btn-lg rounded-2 fw-semibold"
                                style="background: rgba(var(--pt-rgb),.1); color: var(--tx); border: 1px solid rgba(var(--pt-rgb),.2); transition: all .3s ease;">

                                <i class="fa-solid fa-rotate-right me-2"></i>Reset

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Google Maps -->
        <?php if (!empty($site['google_maps_embed'])) : ?>

            <div class="row mt-5">

                <div class="col-12">

                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden" style="border: 1px solid rgba(var(--pt-rgb),.1);">

                        <?php echo $site['google_maps_embed']; ?>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- Success Modal -->
<div class="modal fade" id="successContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center px-4 py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-3 fw-bold">Pesan Berhasil Dikirim!</h5>
                <p class="text-secondary mb-4">
                    Terima kasih telah menghubungi kami. Kami akan menghubungi Anda melalui kontak yang Anda berikan.
                </p>
                <p class="text-secondary mb-4">
                    Kami akan mengalihkan Anda ke WhatsApp untuk proses konsultasi yang lebih cepat.
                </p>
                
                <div class="bg-light rounded-3 p-4 text-start mb-4" id="modalDetails">
                    <!-- Details akan diisi oleh JavaScript -->
                </div>

                <div class="d-flex flex-column gap-3">
                    <div>
                        <p class="text-secondary small mb-2">
                            Redirecting ke WhatsApp dalam <span id="countdown">5</span> detik...
                        </p>
                        <button type="button" class="btn btn-success w-100 rounded-2 fw-semibold" id="whatsappBtn">
                            <i class="fa-brands fa-whatsapp me-2"></i>Buka WhatsApp Sekarang
                        </button>
                    </div>
                    <p class="text-secondary small mb-0">atau klik tombol di atas untuk memulai konsultasi</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Form Styling - Default Colors */
    #contactForm .form-control,
    #contactForm .form-select {
        font-size: 1rem;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    #contactForm .form-label {
        margin-bottom: 0.5rem;
    }

    #contactForm .btn {
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    #contactForm .bgrd:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(var(--pt-rgb), 0.2);
    }

    #contactForm button[type="reset"]:hover {
        background: rgba(var(--pt-rgb), 0.15) !important;
        border-color: var(--pt) !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        #contactForm .form-control,
        #contactForm .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
</style>

<script>
    /**
     * Contact Form Submission Handler
     * Handles form validation, submission, modal display, and WhatsApp redirect
     */
    (function() {
        'use strict';

        const ContactForm = {
            form: null,
            submitBtn: null,
            resetBtn: null,
            whatsappBtn: null,
            whatsappDelay: 5000, // 5 seconds
            companyName: '', // Will be set from window.siteSettings
            phoneNumber: '', // Will be set from window.siteSettings
            currentData: null, // Store current submission data
            redirectTimeout: null, // Store timeout reference

            init: function() {
                this.form = document.getElementById('contactForm');
                if (!this.form) return;

                this.submitBtn = this.form.querySelector('button[type="submit"]');
                this.resetBtn = this.form.querySelector('button[type="reset"]');
                this.whatsappBtn = document.getElementById('whatsappBtn');
                this.companyName = window.siteSettings?.company_name || 'Kami';
                this.phoneNumber = window.siteSettings?.phone || '';

                this.attachEventListeners();
            },

            attachEventListeners: function() {
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                if (this.whatsappBtn) {
                    this.whatsappBtn.addEventListener('click', (e) => this.handleWhatsappClick(e));
                }
            },

            handleSubmit: function(e) {
                e.preventDefault();

                // Disable submit button
                this.submitBtn.disabled = true;
                this.submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Mengirim...';

                // Get form data
                const formData = new FormData(this.form);

                // Submit via AJAX
                fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    this.submitBtn.disabled = false;
                    this.submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Kirim Pesan';

                    if (result.success) {
                        this.handleSuccess(result.data);
                    } else {
                        this.handleError(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.submitBtn.disabled = false;
                    this.submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Kirim Pesan';
                    this.handleError('Terjadi kesalahan pada server');
                });
            },

            handleSuccess: function(data) {
                // Store current data for WhatsApp redirect
                this.currentData = data;

                // Show success modal
                this.showSuccessModal(data);

                // Reset form
                this.form.reset();

                // Redirect to WhatsApp after delay
                this.redirectTimeout = setTimeout(() => {
                    this.redirectToWhatsApp(data);
                }, this.whatsappDelay);
            },

            handleError: function(message) {
                // Show error modal or alert
                alert('Error: ' + message);
            },

            showSuccessModal: function(data) {
                // Build details HTML
                const detailsHtml = `
                    <h6 class="fw-bold mb-3">Detail Pesan Anda:</h6>
                    <div class="mb-2">
                        <span class="text-secondary small">Nama:</span>
                        <p class="mb-0 fw-semibold">${this.escapeHtml(data.name)}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-secondary small">Email:</span>
                        <p class="mb-0 fw-semibold">${this.escapeHtml(data.email)}</p>
                    </div>
                    ${data.phone ? `
                        <div class="mb-2">
                            <span class="text-secondary small">Telepon:</span>
                            <p class="mb-0 fw-semibold">${this.escapeHtml(data.phone)}</p>
                        </div>
                    ` : ''}
                    <div class="mb-2">
                        <span class="text-secondary small">Perihal:</span>
                        <p class="mb-0 fw-semibold">${this.escapeHtml(data.subject)}</p>
                    </div>
                    <div>
                        <span class="text-secondary small">Pesan:</span>
                        <p class="mb-0 fw-semibold" style="word-break: break-word;">${this.escapeHtml(data.message).replace(/\n/g, '<br>')}</p>
                    </div>
                `;

                // Insert details into modal
                const detailsElement = document.getElementById('modalDetails');
                if (detailsElement) {
                    detailsElement.innerHTML = detailsHtml;
                }

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('successContactModal'));
                modal.show();

                // Countdown timer
                this.startCountdown();
            },

            startCountdown: function() {
                let count = 5;
                const countdownElement = document.getElementById('countdown');

                if (countdownElement) {
                    const interval = setInterval(() => {
                        count--;
                        countdownElement.textContent = count;
                        if (count <= 0) {
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            },

            handleWhatsappClick: function(e) {
                e.preventDefault();

                // Clear the redirect timeout if user clicks button manually
                if (this.redirectTimeout) {
                    clearTimeout(this.redirectTimeout);
                }

                // Redirect immediately
                if (this.currentData) {
                    this.redirectToWhatsApp(this.currentData);
                }
            },

            redirectToWhatsApp: function(data) {
                // Build WhatsApp message
                const message = `Halo ${this.companyName}, saya ${data.name}\nEmail: ${data.email}\nTelepon: ${data.phone || '-'}\nPerihal: ${data.subject}\nPesan: ${data.message}`;

                if (!this.phoneNumber) {
                    console.error('WhatsApp number not configured');
                    alert('Nomor WhatsApp tidak dikonfigurasi');
                    return;
                }

                // Format phone number (remove non-digits, add country code if needed)
                let formattedPhone = this.phoneNumber.replace(/\D/g, '');
                if (!formattedPhone.startsWith('62')) {
                    if (formattedPhone.startsWith('0')) {
                        formattedPhone = '62' + formattedPhone.substring(1);
                    } else {
                        formattedPhone = '62' + formattedPhone;
                    }
                }

                // Create WhatsApp URL
                const encodedMessage = encodeURIComponent(message);
                const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodedMessage}`;

                // Open in new tab
                window.open(whatsappUrl, '_blank');
            },

            escapeHtml: function(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }
        };

        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => ContactForm.init());
        } else {
            ContactForm.init();
        }

        // Expose to window for debugging
        window.ContactForm = ContactForm;
    })();
</script>
