<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">

    <div class="col-lg-8">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Hero</h3>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <label>Badge</label>
                    <input type="text" name="badge" class="form-control"
                        value="<?= set_value('badge', $hero['badge'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                        value="<?= set_value('title', $hero['title'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Subtitle</label>
                    <input type="text" name="subtitle" class="form-control"
                        value="<?= set_value('subtitle', $hero['subtitle'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="5"
                        class="form-control"><?= set_value('description', $hero['description'] ?? ''); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Primary Button Text</label>
                            <input type="text" name="primary_button_text" class="form-control"
                                value="<?= set_value('primary_button_text', $hero['primary_button_text'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Primary Button URL</label>
                            <input type="url" name="primary_button_url" class="form-control"
                                value="<?= set_value('primary_button_url', $hero['primary_button_url'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Secondary Button Text</label>
                            <input type="text" name="secondary_button_text" class="form-control"
                                value="<?= set_value('secondary_button_text', $hero['secondary_button_text'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Secondary Button URL</label>
                            <input type="url" name="secondary_button_url" class="form-control"
                                value="<?= set_value('secondary_button_url', $hero['secondary_button_url'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Video URL</label>
                    <input type="url" name="video_url" class="form-control"
                        value="<?= set_value('video_url', $hero['video_url'] ?? ''); ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Display Order</label>
                            <input type="number" name="display_order" class="form-control"
                                value="<?= set_value('display_order', $hero['display_order'] ?? 1); ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" <?= set_select('is_active', '1', ($hero['is_active'] ?? 1) == 1); ?>>
                                    Aktif
                                </option>
                                <option value="0" <?= set_select('is_active', '0', ($hero['is_active'] ?? 1) == 0); ?>>
                                    Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <div class="col-lg-4">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Background</h3>
            </div>

            <div class="card-body text-center">

                <?php if (!empty($hero['background_media_id'])): ?>
                    <img id="background-preview" src="<?= site_url('media/show/' . $hero['background_media_id']); ?>"
                        class="img-fluid rounded border mb-3">
                <?php else: ?>
                    <img id="background-preview" src="<?= base_url('assets/images/no-image.png'); ?>"
                        class="img-fluid rounded border mb-3">
                <?php endif; ?>

                <input type="file" name="background" class="form-control-file">

            </div>
        </div>

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Hero Image</h3>
            </div>

            <div class="card-body text-center">

                <?php if (!empty($hero['hero_media_id'])): ?>
                    <img id="hero-preview" src="<?= site_url('media/show/' . $hero['hero_media_id']); ?>"
                        class="img-thumbnail mb-3" style="max-width:220px;">
                <?php else: ?>
                    <img id="hero-preview" src="<?= base_url('assets/images/no-image.png'); ?>" class="img-thumbnail mb-3"
                        style="max-width:220px;">
                <?php endif; ?>

                <input type="file" name="hero_image" class="form-control-file">

            </div>

        </div>

    </div>

</div>

<script>
    function bindImagePreview(inputSelector, imageSelector) {

        const input = document.querySelector(inputSelector);
        const image = document.querySelector(imageSelector);

        if (!input || !image) return;

        input.addEventListener('change', function () {

            if (!this.files || !this.files[0]) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                image.src = e.target.result;
            };

            reader.readAsDataURL(this.files[0]);

        });

    }

    bindImagePreview('input[name="background"]', '#background-preview');
    bindImagePreview('input[name="hero_image"]', '#hero-preview');
</script>