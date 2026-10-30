<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <?= validation_errors(); ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('admin/products/store'); ?>" method="post" enctype="multipart/form-data">

                <?php $this->load->view('backend/products/_form', [
                    'service' => []
                ]); ?>

                <div class="card">

                    <div class="card-footer text-right">

                        <a href="<?= site_url('admin/products'); ?>" class="btn btn-secondary">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </section>

</div>


<script>
    $(function () {

        tinymce.init({

            selector: '#description, #specification',

            height: 550,

            menubar: 'file edit view insert format table tools help',

            branding: false,
            promotion: false,

            plugins: [
                'advlist',
                'anchor',
                'autolink',
                'autosave',
                'code',
                'codesample',
                'fullscreen',
                'help',
                'image',
                'insertdatetime',
                'link',
                'lists',
                'preview',
                'searchreplace',
                'table',
                'visualblocks',
                'wordcount'
            ],

            toolbar:
                'undo redo | ' +
                'blocks fontsize | ' +
                'bold italic underline strikethrough | ' +
                'forecolor backcolor | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link image table | ' +
                'removeformat | ' +
                'code preview fullscreen',

            block_formats:
                'Paragraph=p;' +
                'Heading 1=h1;' +
                'Heading 2=h2;' +
                'Heading 3=h3;' +
                'Heading 4=h4;' +
                'Heading 5=h5;' +
                'Heading 6=h6',

            fontsize_formats:
                '10px 12px 14px 16px 18px 20px 24px 28px 32px',

            image_title: true,
            automatic_uploads: false,
            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,
            browser_spellcheck: true,
            contextmenu: 'link image table',
            statusbar: true,
            resize: true,

            content_style: `
            body{
                font-family:Arial,Helvetica,sans-serif;
                font-size:14px;
                line-height:1.7;
                padding:15px;
            }

            img{
                max-width:100%;
                height:auto;
            }

            table{
                border-collapse:collapse;
                width:100%;
            }

            table td,
            table th{
                border:1px solid #ddd;
                padding:8px;
            }
        `
        });

    });
</script>