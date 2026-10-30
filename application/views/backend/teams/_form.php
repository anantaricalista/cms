<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="<?= set_value('name', $team['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Position <span class="text-danger">*</span></label>
            <input type="text"
                   name="position"
                   class="form-control"
                   value="<?= set_value('position', $team['position'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">

            <label>Photo</label>

            <div class="mb-3">

                <img id="photo-preview"
                     src="<?= !empty($team['photo_media_id']) ? site_url('media/show/' . $team['photo_media_id']) : 'https://placehold.co/400x400?text=No+Image'; ?>"
                     class="img-fluid border rounded"
                     style="width:100%;max-height:350px;object-fit:cover;">

            </div>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="photo"
                       name="photo"
                       accept="image/*">

                <label class="custom-file-label" for="photo">
                    Pilih foto...
                </label>

            </div>

            <small class="text-muted">
                Rekomendasi ukuran 400 × 400 px (1:1).
            </small>

        </div>

        <div class="form-group">
            <label>LinkedIn URL</label>
            <input type="url"
                   name="linkedin"
                   class="form-control"
                   value="<?= set_value('linkedin', $team['linkedin'] ?? '') ?>"
                   placeholder="https://www.linkedin.com/in/...">
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
