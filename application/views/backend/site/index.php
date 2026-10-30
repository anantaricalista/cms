<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <form action="<?= site_url('admin/site/update'); ?>" method="post" enctype="multipart/form-data">

        <div class="card-body">

            <h4>Website Information</h4>

            <input class="form-control" name="site_name" value="<?= html_escape($site->site_name); ?>">

            <input class="form-control mt-3" name="company_name" value="<?= html_escape($site->company_name); ?>">

            <input class="form-control mt-3" name="tagline" value="<?= html_escape($site->tagline); ?>">

            <textarea class="form-control mt-3"
                name="company_summary"><?= html_escape($site->company_summary); ?></textarea>

            <hr>

            <h4>Contact</h4>

            <input class="form-control" name="email" value="<?= html_escape($site->email); ?>">

            <input class="form-control mt-3" name="phone" value="<?= html_escape($site->phone); ?>">

            <textarea class="form-control mt-3" name="address"><?= html_escape($site->address); ?></textarea>

            <textarea class="form-control mt-3"
                name="google_maps_embed"><?= html_escape($site->google_maps_embed); ?></textarea>

            <hr>

            <h4>Branding</h4>

            <div class="row">

                <div class="col-md-4">
                    <label>Primary Color</label>
                    <input type="color" class="form-control" name="primary_color"
                        value="<?= html_escape($site->primary_color); ?>">
                </div>

                <div class="col-md-4">
                    <label>Secondary Color</label>
                    <input type="color" class="form-control" name="secondary_color"
                        value="<?= html_escape($site->secondary_color); ?>">
                </div>

                <div class="col-md-4">
                    <label>Accent Color</label>
                    <input type="color" class="form-control" name="accent_color"
                        value="<?= html_escape($site->accent_color); ?>">
                </div>

            </div>

            <hr>

            <div class="row mt-4">

                <!-- LOGO -->

                <div class="col-md-6">

                    <h5>Company Logo</h5>

                    <div class="border rounded d-flex justify-content-center align-items-center mb-3"
                        style="height:180px;background:#fafafa;">

                        <?php if (!empty($site->logo_media_id)): ?>

                            <img id="logo_current" src="<?= site_url('media/show/' . $site->logo_media_id); ?>"
                                style="max-width:160px;max-height:160px;object-fit:contain;">

                        <?php else: ?>

                            <div class="text-muted">
                                No logo uploaded
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="custom-file">

                        <input type="file" class="custom-file-input" id="logo" name="logo"
                            accept=".png,.jpg,.jpeg,.svg,.webp">

                        <label class="custom-file-label">
                            Choose Logo
                        </label>

                    </div>

                    <small class="text-muted">
                        PNG, JPG, JPEG, SVG, WEBP
                    </small>

                    <div class="text-center border rounded mt-3 p-3">

                        <img id="logo_preview" class="img-fluid" style="display:none;max-height:140px;">

                        <div id="logo_placeholder">
                            Preview will appear here
                        </div>

                    </div>

                </div>

                <!-- FAVICON -->

                <div class="col-md-6">

                    <h5>Favicon</h5>

                    <div class="border rounded d-flex justify-content-center align-items-center mb-3"
                        style="height:180px;background:#fafafa;">

                        <?php if (!empty($site->favicon_media_id)): ?>

                            <img id="favicon_current" src="<?= site_url('media/show/' . $site->favicon_media_id); ?>"
                                style="max-width:160px;max-height:160px;object-fit:contain;">

                        <?php else: ?>

                            <div class="text-muted">
                                No favicon uploaded
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="custom-file">

                        <input type="file" class="custom-file-input" id="favicon" name="favicon"
                            accept=".png,.ico,.svg">

                        <label class="custom-file-label">
                            Choose Favicon
                        </label>

                    </div>

                    <small class="text-muted">
                        PNG, ICO, SVG
                    </small>

                    <div class="text-center border rounded mt-3 p-3">

                        <img id="favicon_preview" style="display:none;width:48px;height:48px;">

                        <div id="favicon_placeholder">
                            Preview will appear here
                        </div>

                    </div>

                </div>

            </div>

            <script>

                function previewFile(inputId, previewId, placeholderId) {
                    const input = document.getElementById(inputId);

                    input.addEventListener('change', function () {

                        const file = this.files[0];

                        if (!file)
                            return;

                        const reader = new FileReader();

                        reader.onload = function (e) {

                            document.getElementById(previewId).src = e.target.result;
                            document.getElementById(previewId).style.display = 'inline-block';
                            document.getElementById(placeholderId).style.display = 'none';

                        };

                        reader.readAsDataURL(file);

                        input.nextElementSibling.innerHTML = file.name;

                    });
                }

                previewFile(
                    'logo',
                    'logo_preview',
                    'logo_placeholder'
                );

                previewFile(
                    'favicon',
                    'favicon_preview',
                    'favicon_placeholder'
                );

            </script>

            <hr>

            <h4>SEO</h4>

            <input class="form-control" name="seo_title" value="<?= html_escape($site->seo_title); ?>">

            <textarea class="form-control mt-3"
                name="seo_description"><?= html_escape($site->seo_description); ?></textarea>

            <textarea class="form-control mt-3" name="seo_keywords"><?= html_escape($site->seo_keywords); ?></textarea>

            <input class="form-control mt-3" name="copyright" value="<?= html_escape($site->copyright); ?>">

        </div>

        <div class="card-footer text-end">

            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>

        </div>

    </form>

</div>