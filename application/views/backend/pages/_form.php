<div class="card">

    <div class="card-header">
        <h3 class="card-title">Informasi Halaman</h3>
    </div>

    <div class="card-body">

        <div class="form-group">
            <label for="page_key">Page Key <span class="text-danger">*</span></label>

            <input
                type="text"
                name="page_key"
                id="page_key"
                class="form-control"
                value="<?= set_value('page_key', $page['page_key'] ?? ''); ?>"
                placeholder="Contoh: about-us"
                required>
        </div>

        <div class="form-group">
            <label for="title">Judul Halaman <span class="text-danger">*</span></label>

            <input
                type="text"
                name="title"
                id="title"
                class="form-control"
                value="<?= set_value('title', $page['title'] ?? ''); ?>"
                placeholder="Masukkan judul halaman"
                required>
        </div>

        <div class="form-group mb-0">
            <label for="content">Konten <span class="text-danger">*</span></label>

            <textarea
                name="content"
                id="content"
                rows="15"
                class="form-control"><?= set_value('content', $page['content'] ?? ''); ?></textarea>
        </div>

    </div>

</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Featured Image</h3>
    </div>

    <div class="card-body">

        <div class="text-center mb-3">

            <img
                id="featured-image-preview"

                <?php if (!empty($page['featured_image_media_id'])): ?>
                    src="<?= site_url('media/show/' . $page['featured_image_media_id']); ?>"
                    style="display:block;max-height:250px;"
                <?php else: ?>
                    src=""
                    style="display:none;max-height:250px;"
                <?php endif; ?>

                class="img-fluid img-thumbnail"
                alt="Featured Image Preview">

        </div>

        <div class="form-group mb-0">

            <label for="featured_image">
                Upload Featured Image
            </label>

            <div class="custom-file">

                <input
                    type="file"
                    class="custom-file-input"
                    id="featured_image"
                    name="featured_image"
                    accept=".jpg,.jpeg,.png,.webp">

                <label
                    class="custom-file-label"
                    for="featured_image">

                    Pilih gambar...

                </label>

            </div>

            <small class="form-text text-muted">
                Format: JPG, JPEG, PNG, WEBP.
            </small>

        </div>

    </div>

</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">SEO</h3>
    </div>

    <div class="card-body">

        <div class="form-group">
            <label for="seo_title">SEO Title</label>

            <input
                type="text"
                name="seo_title"
                id="seo_title"
                class="form-control"
                maxlength="255"
                value="<?= set_value('seo_title', $page['seo_title'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="seo_description">SEO Description</label>

            <textarea
                name="seo_description"
                id="seo_description"
                rows="4"
                class="form-control"><?= set_value('seo_description', $page['seo_description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group mb-0">
            <label for="seo_keywords">SEO Keywords</label>

            <input
                type="text"
                name="seo_keywords"
                id="seo_keywords"
                class="form-control"
                value="<?= set_value('seo_keywords', $page['seo_keywords'] ?? ''); ?>"
                placeholder="keyword1, keyword2, keyword3">
        </div>

    </div>

</div>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Publikasi</h3>
    </div>

    <div class="card-body">

        <div class="form-group mb-0">

            <label for="status">Status</label>

            <select name="status" id="status" class="form-control">

                <option value="draft" <?= set_select('status', 'draft', ($page['status'] ?? '') == 'draft'); ?>>
                    Draft
                </option>

                <option value="published" <?= set_select('status', 'published', ($page['status'] ?? '') == 'published'); ?>>
                    Published
                </option>

            </select>

        </div>

    </div>

</div>

<script>
$(function () {

    $('#featured_image').on('change', function () {

        const input = this;

        const fileName = input.files.length
            ? input.files[0].name
            : 'Pilih gambar...';

        $(input)
            .next('.custom-file-label')
            .text(fileName);

        if (input.files && input.files[0]) {

            const reader = new FileReader();

            reader.onload = function (e) {

                $('#featured-image-preview')
                    .attr('src', e.target.result)
                    .show();

            };

            reader.readAsDataURL(input.files[0]);

        }

    });

});
</script>