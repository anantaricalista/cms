<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tambah Hero</h1>

            <a href="<?= site_url('admin/heroes'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

<?php if (validation_errors()) : ?>
<div class="alert alert-danger">
    <?= validation_errors(); ?>
</div>
<?php endif; ?>

<form action="<?= site_url('admin/heroes/store'); ?>"
      method="post"
      enctype="multipart/form-data">

    <?php $this->load->view('backend/heroes/_form', [
        'hero' => []
    ]); ?>

    <div class="card">
        <div class="card-footer text-right">

            <a href="<?= site_url('admin/heroes'); ?>"
               class="btn btn-secondary">
                Batal
            </a>

            <button type="submit"
                    class="btn btn-primary">
                <i class="fas fa-save"></i>
                Simpan
            </button>

        </div>
    </div>

</form>

</div>
</section>

</div>
