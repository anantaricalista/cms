<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">
            <label>Service Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="<?= set_value('name', $service['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Slug</label>
            <input type="text"
                   id="slug"
                   class="form-control"
                   value="<?= isset($service['slug']) ? $service['slug'] : '' ?>"
                   readonly>
            <small class="text-muted">
                Dibuat otomatis dari Service Name.
            </small>
        </div>

        <div class="form-group">
            <label>Summary</label>
            <textarea name="summary"
                      rows="3"
                      class="form-control"><?= set_value('summary', $service['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description"
                      id="description"
                      rows="15"
                      class="form-control"><?= set_value('description', $service['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">

            <label>Featured Image</label>

            <div class="mb-3">

                <img id="featured-image-preview"
                     src="<?= !empty($service['featured_image_media_id']) ? site_url('media/show/' . $service['featured_image_media_id']) : 'https://placehold.co/1200x675?text=No+Image'; ?>"
                     class="img-fluid border rounded"
                     style="width:100%;max-height:350px;object-fit:cover;">

            </div>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="featured_image"
                       name="featured_image"
                       accept="image/*">

                <label class="custom-file-label" for="featured_image">
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
            <input type="text"
                   name="seo_title"
                   class="form-control"
                   value="<?= set_value('seo_title', $service['seo_title'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>SEO Description</label>
            <textarea name="seo_description"
                      rows="3"
                      class="form-control"><?= set_value('seo_description', $service['seo_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>SEO Keywords</label>
            <textarea name="seo_keywords"
                      rows="3"
                      class="form-control"><?= set_value('seo_keywords', $service['seo_keywords'] ?? '') ?></textarea>
        </div>

        <div class="form-group">

            <label>Status</label>

            <select name="status" class="form-control">

                <option value="draft"
                    <?= set_select('status', 'draft', (($service['status'] ?? 'draft') == 'draft')); ?>>
                    Draft
                </option>

                <option value="published"
                    <?= set_select('status', 'published', (($service['status'] ?? '') == 'published')); ?>>
                    Published
                </option>

            </select>

        </div>

    </div>

</div>

<script>

$(function () {

    $('#name').on('keyup change', function () {

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

});

</script>