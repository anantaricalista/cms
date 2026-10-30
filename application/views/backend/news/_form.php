<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">

            <label>Categories <span class="text-danger">*</span></label>

            <div class="row">

                <div class="col-md-11">

                    <select id="category_ids" name="category_ids[]" class="form-control select2" multiple
                        data-placeholder="Pilih Category">

                        <?php foreach ($categories as $category): ?>

                            <option value="<?= $category['id']; ?>" <?= in_array($category['id'], $news['category_ids'] ?? []) ? 'selected' : ''; ?>>

                                <?= html_escape($category['name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-1">

                    <button type="button" class="btn btn-outline-secondary btn-block" id="btn-manage-category">

                        <i class="fas fa-tags"></i>

                    </button>
                </div>

            </div>

            <small class="text-muted">
                Satu artikel dapat memiliki lebih dari satu kategori.
            </small>

        </div>

        <div class="form-group">

            <label>Title <span class="text-danger">*</span></label>

            <input type="text" name="title" id="title" class="form-control"
                value="<?= set_value('title', $news['title'] ?? '') ?>" required>

        </div>

        <div class="form-group">

            <label>Slug</label>

            <input type="text" id="slug" class="form-control" value="<?= $news['slug'] ?? ''; ?>" readonly>

            <small class="text-muted">

                Dibuat otomatis dari judul berita.

            </small>

        </div>

        <div class="form-group">

            <label>Summary</label>

            <textarea name="summary" rows="3"
                class="form-control"><?= set_value('summary', $news['summary'] ?? '') ?></textarea>

        </div>

        <div class="form-group">

            <label>Content <span class="text-danger">*</span></label>

            <textarea name="content" id="content" class="form-control">
                <?= set_value('content', $news['content'] ?? '') ?></textarea>
        </div>

        <div class="form-group">

            <label>Featured Image</label>

            <div class="mb-3">

                <img id="featured-image-preview" src="<?= !empty($news['featured_image_media_id'])
                    ? site_url('media/show/' . $news['featured_image_media_id'])
                    : 'https://placehold.co/1200x675?text=No+Image'; ?>" class="img-fluid border rounded"
                    style="width:100%;max-height:350px;object-fit:cover;">

            </div>

            <div class="custom-file">

                <input type="file" name="featured_image" id="featured_image" class="custom-file-input" accept="image/*">

                <label class="custom-file-label">

                    Pilih gambar...

                </label>

            </div>

            <small class="text-muted">

                Rekomendasi ukuran 1600 × 900 px (16:9).

            </small>

        </div>

        <hr>

        <h5>SEO</h5>

        <div class="form-group">

            <label>SEO Title</label>

            <input type="text" name="seo_title" class="form-control"
                value="<?= set_value('seo_title', $news['seo_title'] ?? '') ?>">

        </div>

        <div class="form-group">

            <label>SEO Description</label>

            <textarea name="seo_description" rows="3"
                class="form-control"><?= set_value('seo_description', $news['seo_description'] ?? '') ?></textarea>

        </div>

        <div class="form-group">

            <label>SEO Keywords</label>

            <textarea name="seo_keywords" rows="3"
                class="form-control"><?= set_value('seo_keywords', $news['seo_keywords'] ?? '') ?></textarea>

        </div>

        <div class="form-group">

            <label>Status</label>

            <select name="status" class="form-control">

                <option value="draft" <?= set_select(
                    'status',
                    'draft',
                    (($news['status'] ?? 'draft') == 'draft')
                ); ?>>

                    Draft

                </option>

                <option value="published" <?= set_select(
                    'status',
                    'published',
                    (($news['status'] ?? '') == 'published')
                ); ?>>

                    Published

                </option>

            </select>

        </div>

    </div>

</div>

<script>

    $(function () {

        $('#category_ids').select2({

            theme: 'bootstrap-5',

            width: '100%',

            placeholder: 'Pilih kategori',

            allowClear: true

        });
        $('#title').on('keyup change', function () {

            let slug = $(this).val()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

            $('#slug').val(slug);

        });

        $('.custom-file-input').on('change', function () {

            let fileName = $(this).val().split('\\').pop();

            $(this).next('.custom-file-label').html(fileName);

            if (this.files && this.files[0]) {

                let reader = new FileReader();

                reader.onload = function (e) {

                    $('#featured-image-preview').attr('src', e.target.result);

                };

                reader.readAsDataURL(this.files[0]);

            }

        });

        tinymce.init({

            selector: '#content',

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