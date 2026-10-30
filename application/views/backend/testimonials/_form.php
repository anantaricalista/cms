<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="<?= set_value('name', $testimonial['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Company <span class="text-danger">*</span></label>
            <input type="text"
                   name="company"
                   class="form-control"
                   value="<?= set_value('company', $testimonial['company'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Position <span class="text-danger">*</span></label>
            <input type="text"
                   name="position"
                   class="form-control"
                   value="<?= set_value('position', $testimonial['position'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">

            <label>Photo</label>

            <div class="mb-3">

                <img id="photo-preview"
                     src="<?= !empty($testimonial['photo_media_id']) ? site_url('media/show/' . $testimonial['photo_media_id']) : 'https://placehold.co/200x200?text=No+Photo'; ?>"
                     class="img-fluid border rounded-circle"
                     style="width:150px;height:150px;object-fit:cover;">

            </div>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="photo"
                       name="photo"
                       accept="image/*">

                <label class="custom-file-label" for="photo">
                    Pilih photo...
                </label>

            </div>

            <small class="text-muted">
                Rekomendasi ukuran 400 × 400 px (Square).
            </small>

        </div>

        <div class="form-group">
            <label>Content <span class="text-danger">*</span></label>
            <textarea name="content"
                      id="content"
                      class="form-control"
                      rows="6"
                      required><?= set_value('content', $testimonial['content'] ?? '') ?></textarea>
        </div>

    </div>

</div>

<script>

$(function () {

    $('.custom-file-input').on('change', function () {

        let fileName = $(this).val().split('\\').pop();

        $(this).next('.custom-file-label').html(fileName);

        if (this.files && this.files[0]) {

            let reader = new FileReader();

            reader.onload = function (e) {

                $('#photo-preview').attr('src', e.target.result);

            };

            reader.readAsDataURL(this.files[0]);

        }

    });

});

</script>
