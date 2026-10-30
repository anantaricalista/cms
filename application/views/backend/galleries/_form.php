<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">

            <label>Title <span class="text-danger">*</span></label>

            <input type="text"
                   name="title"
                   id="title"
                   class="form-control"
                   value="<?= set_value('title', $gallery['title'] ?? '') ?>"
                   required>

        </div>

        <div class="form-group">

            <label>Image <span class="text-danger">*</span></label>

            <div class="mb-3">

                <img id="gallery-image-preview"
                     src="<?= !empty($gallery['media_id'])
                         ? site_url('media/show/' . $gallery['media_id'])
                         : 'https://placehold.co/1200x675?text=No+Image'; ?>"
                     class="img-fluid border rounded"
                     style="width:100%;max-height:400px;object-fit:cover;">

            </div>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="media"
                       name="media"
                       accept="image/*"
                       <?= empty($gallery['media_id']) ? 'required' : ''; ?>>

                <label class="custom-file-label" for="media">
                    Pilih gambar...
                </label>

            </div>

            <small class="text-muted">
                Rekomendasi ukuran 1600 × 900 px (16:9).
            </small>

        </div>

        <div class="form-group">

            <label>Description</label>

            <textarea name="description"
                      rows="5"
                      class="form-control"><?= set_value(
                          'description',
                          $gallery['description'] ?? ''
                      ) ?></textarea>

        </div>

        <div class="form-group">

            <label>Sort Order</label>

            <input type="number"
                   name="sort_order"
                   class="form-control"
                   value="<?= set_value(
                       'sort_order',
                       $gallery['sort_order'] ?? ''
                   ) ?>"
                   min="1">

            <?php if (empty($gallery)): ?>

                <small class="text-muted">
                    Urutan otomatis ditentukan saat gallery dibuat.
                </small>

            <?php else: ?>

                <small class="text-muted">
                    Urutan juga dapat diubah menggunakan tombol Up / Down
                    pada halaman Gallery.
                </small>

            <?php endif; ?>

        </div>

        <div class="form-group">

            <label>Status</label>

            <select name="status"
                    class="form-control">

                <option value="draft"
                    <?= set_select(
                        'status',
                        'draft',
                        (($gallery['status'] ?? 'draft') === 'draft')
                    ); ?>>
                    Draft
                </option>

                <option value="published"
                    <?= set_select(
                        'status',
                        'published',
                        (($gallery['status'] ?? '') === 'published')
                    ); ?>>
                    Published
                </option>

            </select>

        </div>

    </div>

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