<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card">

    <div class="card-body">

        <div class="form-group">

            <label>Title <span class="text-danger">*</span></label>

            <input type="text"
                   name="title"
                   id="title"
                   class="form-control"
                   value="<?= set_value('title', $download['title'] ?? '') ?>"
                   required>

        </div>

        <div class="form-group">

            <label>File <span class="text-danger">*</span></label>

            <?php if (!empty($download['media_id'])): ?>

                <div class="mb-3 p-3 border rounded bg-light">

                    <div class="d-flex align-items-center">

                        <div class="mr-3">

                            <?php if (($download['mime_type'] ?? '') === 'application/pdf'): ?>

                                <i class="fas fa-file-pdf fa-3x text-danger"></i>

                            <?php elseif (strpos(($download['mime_type'] ?? ''), 'image/') === 0): ?>

                                <img src="<?= site_url('media/show/' . $download['media_id']); ?>"
                                     alt="<?= html_escape($download['title']); ?>"
                                     style="width:80px;height:60px;object-fit:cover;"
                                     class="rounded border">

                            <?php else: ?>

                                <i class="fas fa-file fa-3x text-secondary"></i>

                            <?php endif; ?>

                        </div>

                        <div>

                            <div class="font-weight-bold">

                                <?= html_escape($download['original_filename'] ?? 'Current file'); ?>

                            </div>

                            <?php if (!empty($download['mime_type'])): ?>

                                <small class="text-muted">

                                    <?= html_escape($download['mime_type']); ?>

                                </small>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

            <div class="custom-file">

                <input type="file"
                       class="custom-file-input"
                       id="media"
                       name="media"
                       accept=".pdf,image/*"
                       <?= empty($download['media_id']) ? 'required' : ''; ?>>

                <label class="custom-file-label" for="media">
                    <?= !empty($download['media_id'])
                        ? 'Pilih file baru...'
                        : 'Pilih file...'; ?>
                </label>

            </div>

            <small class="text-muted">
                File yang didukung: PDF dan gambar.
            </small>

        </div>

        <div class="form-group">

            <label>Description</label>

            <textarea name="description"
                      rows="5"
                      class="form-control"><?= set_value(
                          'description',
                          $download['description'] ?? ''
                      ) ?></textarea>

        </div>

        <div class="form-group">

            <label>Status</label>

            <select name="status"
                    class="form-control">

                <option value="draft"
                    <?= set_select(
                        'status',
                        'draft',
                        (($download['status'] ?? 'draft') === 'draft')
                    ); ?>>

                    Draft

                </option>

                <option value="published"
                    <?= set_select(
                        'status',
                        'published',
                        (($download['status'] ?? '') === 'published')
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

    });

});

</script>