<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">
            <label>Client Name <span class="text-danger">*</span></label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="<?= set_value('name', $client['name'] ?? '') ?>"
                   required>
        </div>

        <div class="form-group">

            <label>Logo</label>

            <div class="mb-3">

                <img id="logo-preview"
                     src="<?= !empty($client['logo_media_id']) ? site_url('media/show/' . $client['logo_media_id']) : 'https://placehold.co/300x200?text=No+Logo'; ?>"
                     class="img-fluid border rounded"
                     style="width:100%;max-height:250px;object-fit:contain;">

            </div>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="logo"
                       name="logo"
                       accept="image/*">

                <label class="custom-file-label" for="logo">
                    Pilih logo...
                </label>

            </div>

            <small class="text-muted">
                Rekomendasi ukuran 400 × 250 px.
            </small>

        </div>

        <div class="form-group">
            <label>Website URL</label>
            <input type="url"
                   name="website"
                   class="form-control"
                   value="<?= set_value('website', $client['website'] ?? '') ?>"
                   placeholder="https://www.example.com">
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

                $('#logo-preview').attr('src', e.target.result);

            };

            reader.readAsDataURL(this.files[0]);

        }

    });

});

</script>
