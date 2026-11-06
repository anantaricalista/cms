(function () {
    'use strict';

    function initContactForm() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const details = document.getElementById('modalDetails');
        const status = document.getElementById('whatsappStatus');
        const whatsappBtn = document.getElementById('whatsappBtn');
        const modal = new bootstrap.Modal(document.getElementById('successContactModal'));

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            if (submitBtn.disabled) return;

            // Reserve the tab during the user gesture, before the asynchronous save.
            const tab = form.dataset.whatsappUrl ? window.open('', '_blank') : null;
            if (tab) {
                tab.opener = null;
                tab.document.title = 'Mengirim pesan';
                tab.document.body.textContent = 'Mohon tunggu, pesan Anda sedang disimpan...';
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Mengirim...';

            let result;
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                result = await response.json();
                if (!response.ok || !result.success) {
                    if (tab && !tab.closed) tab.close();
                    alert('Error: ' + (result.message || 'Gagal menyimpan pesan'));
                    return;
                }
            } catch (error) {
                if (tab && !tab.closed) tab.close();
                console.error('Gagal mengirim pesan:', error);
                alert('Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
                return;
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Kirim Pesan';
            }

            const data = result.data;
            const escapeHtml = function (value) {
                return String(value || '').replace(/[&<>"']/g, function (character) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[character];
                });
            };
            details.innerHTML = `
                <h6 class="fw-bold mb-3">Detail Pesan Anda:</h6>
                <div class="mb-2"><span class="text-secondary small">Nama:</span><p class="mb-0 fw-semibold">${escapeHtml(data.name)}</p></div>
                <div class="mb-2"><span class="text-secondary small">Email:</span><p class="mb-0 fw-semibold">${escapeHtml(data.email)}</p></div>
                ${data.phone ? `<div class="mb-2"><span class="text-secondary small">Telepon:</span><p class="mb-0 fw-semibold">${escapeHtml(data.phone)}</p></div>` : ''}
                <div class="mb-2"><span class="text-secondary small">Perihal:</span><p class="mb-0 fw-semibold">${escapeHtml(data.subject)}</p></div>
                <div><span class="text-secondary small">Pesan:</span><p class="mb-0 fw-semibold" style="word-break: break-word;">${escapeHtml(data.message).replace(/\n/g, '<br>')}</p></div>
            `;

            whatsappBtn.hidden = true;
            whatsappBtn.removeAttribute('href');
            if (form.dataset.whatsappUrl) {
                const message = `Halo ${form.dataset.companyName}, saya ${data.name}\nEmail: ${data.email}\nTelepon: ${data.phone || '-'}\nPerihal: ${data.subject}\nPesan: ${data.message}`;
                const url = new URL(form.dataset.whatsappUrl);
                url.searchParams.set('text', message);
                whatsappBtn.href = url.toString();
                whatsappBtn.hidden = false;

                if (tab && !tab.closed) {
                    tab.location.href = url.toString();
                    status.textContent = 'WhatsApp dibuka di tab baru. Gunakan tombol di bawah jika perlu membukanya kembali.';
                } else {
                    status.textContent = 'Tab WhatsApp tidak dapat dibuka otomatis. Klik tombol di bawah untuk melanjutkan.';
                }
            } else {
                status.textContent = 'Pesan tersimpan, tetapi nomor WhatsApp belum dikonfigurasi.';
            }

            form.reset();
            modal.show();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initContactForm);
    } else {
        initContactForm();
    }
})();
