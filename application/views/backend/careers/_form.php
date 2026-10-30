<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">
            <label>Position <span class="text-danger">*</span></label>
            <input type="text"
                   name="position"
                   id="position"
                   class="form-control"
                   value="<?= set_value('position', $career['position'] ?? '') ?>"
                   required>
            <small class="text-muted">Slug akan di-generate otomatis dari position.</small>
        </div>

        <div class="form-group">
            <label>Location <span class="text-danger">*</span></label>
            <input type="text"
                   name="location"
                   class="form-control"
                   value="<?= set_value('location', $career['location'] ?? '') ?>"
                   placeholder="e.g., Jakarta, Indonesia"
                   required>
        </div>

        <div class="form-group">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description"
                      id="description"
                      class="form-control"
                      rows="10"
                      required><?= set_value('description', $career['description'] ?? '') ?></textarea>
            <small class="text-muted">Gunakan editor untuk detail pekerjaan, requirement, benefit, dll.</small>
        </div>

        <div class="form-group">
            <label>Status</label>
            <div class="custom-control custom-switch">
                <input type="checkbox"
                       class="custom-control-input"
                       id="status"
                       name="status"
                       value="published"
                       <?= (set_value('status', $career['status'] ?? 'draft') === 'published') ? 'checked' : '' ?>>
                <label class="custom-control-label" for="status">
                    Publish
                </label>
            </div>
            <small class="text-muted">Jika diaktifkan, career akan tampil di website.</small>
        </div>

    </div>

</div>

<script>

$(function () {

    tinymce.init({

        selector: '#description',

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