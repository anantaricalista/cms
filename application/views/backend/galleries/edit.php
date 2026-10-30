<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <?php if (validation_errors()): ?>

                <div class="alert alert-danger">
                    <?= validation_errors(); ?>
                </div>

            <?php endif; ?>

            <form action="<?= site_url('admin/galleries/update/' . $gallery['id']); ?>"
                  method="post"
                  enctype="multipart/form-data">

                <?php $this->load->view('backend/galleries/_form', [
                    'gallery' => $gallery
                ]); ?>

                <div class="card">

                    <div class="card-footer text-right">

                        <a href="<?= site_url('admin/galleries'); ?>"
                           class="btn btn-secondary">

                            Batal

                        </a>

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="fas fa-save"></i>

                            Update

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </section>

</div>

<script>

$(function () {

    $('.custom-file-input').on('change', function () {

        let fileName = $(this).val().split('\\').pop();

        $(this)
            .next('.custom-file-label')
            .html(fileName);

        if (this.files && this.files[0]) {

            let reader = new FileReader();

            reader.onload = function (e) {

                $('#gallery-image-preview')
                    .attr('src', e.target.result);

            };

            reader.readAsDataURL(this.files[0]);

        }

    });

});

</script>