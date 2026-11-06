<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="contact-messages" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Perihal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= html_escape($message['created_at']); ?></td>
                            <td><?= html_escape($message['name']); ?></td>
                            <td><?= html_escape($message['email']); ?></td>
                            <td><?= html_escape($message['subject']); ?></td>
                            <td>
                                <?php if ((int) $message['is_read'] === 1): ?>
                                    <span class="badge text-bg-secondary">Dibaca</span>
                                <?php else: ?>
                                    <span class="badge text-bg-primary">Belum dibaca</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="<?= site_url('admin/contact_messages/detail/' . (int) $message['id']); ?>">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(function () {
    $('#contact-messages').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, targets: 5 }],
        language: { emptyTable: 'Belum ada pesan kontak.' }
    });
});
</script>
