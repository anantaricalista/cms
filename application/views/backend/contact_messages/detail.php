<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Nama</dt>
            <dd class="col-sm-9"><?= html_escape($message['name']); ?></dd>
            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><?= html_escape($message['email']); ?></dd>
            <dt class="col-sm-3">Telepon</dt>
            <dd class="col-sm-9"><?= html_escape(!empty($message['phone']) ? $message['phone'] : '-'); ?></dd>
            <dt class="col-sm-3">Perihal</dt>
            <dd class="col-sm-9"><?= html_escape($message['subject']); ?></dd>
            <dt class="col-sm-3">Waktu</dt>
            <dd class="col-sm-9"><?= html_escape($message['created_at']); ?></dd>
            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9"><?= (int) $message['is_read'] === 1 ? 'Dibaca' : 'Belum dibaca'; ?></dd>
            <dt class="col-sm-3">Pesan</dt>
            <dd class="col-sm-9" style="overflow-wrap:anywhere;"><?= nl2br(html_escape($message['message'])); ?></dd>
        </dl>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('admin/contact_messages'); ?>" class="btn btn-secondary">Kembali</a>
            <?php if ((int) $message['is_read'] !== 1): ?>
                <?= form_open('admin/contact_messages/mark_read/' . (int) $message['id']); ?>
                    <input type="hidden" name="contact_messages_action_token" value="<?= html_escape($action_token); ?>">
                    <button type="submit" class="btn btn-primary">Tandai dibaca</button>
                <?= form_close(); ?>
            <?php endif; ?>
            <?php if ($reply_url !== ''): ?>
                <a href="<?= html_escape($reply_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success">
                    <i class="fab fa-whatsapp"></i> Balas via WhatsApp
                </a>
            <?php else: ?>
                <span class="text-muted align-self-center">Nomor telepon tidak tersedia atau tidak valid.</span>
            <?php endif; ?>
            <?= form_open('admin/contact_messages/delete/' . (int) $message['id'], ['onsubmit' => "return confirm('Yakin ingin menghapus pesan ini?');"]); ?>
                <input type="hidden" name="contact_messages_action_token" value="<?= html_escape($action_token); ?>">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
            <?= form_close(); ?>
        </div>
    </div>
</div>
