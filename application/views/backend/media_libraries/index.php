<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search-input" class="form-label fw-600">Search Media</label>
                        <input type="text"
                               id="search-input"
                               class="form-control"
                               placeholder="Search by filename..."
                               autocomplete="off">
                        <small class="text-muted">Start typing to search...</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info mb-0 mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Total:</strong> <span id="total-media"><?= $total_media ?></span> media file(s)
                    </div>
                </div>
            </div>

            <!-- Media Grid Container -->
            <div class="row" id="media-container">
                <?php echo $media_html; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination-container">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i === $current_page) ? 'active' : '' ?>">
                                        <a class="page-link pagination-link" href="#" data-page="<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </section>

</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="previewModalLabel">Media Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="previewModalBody" style="max-height: 70vh; overflow-y: auto;">
                <!-- Content loaded via AJAX -->
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadBtn" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download me-2"></i> Download
                </a>
            </div>

        </div>
    </div>
</div>

<script>

$(function () {

    const perPage = 9;
    let currentSearch = '';
    let currentPage = <?= $current_page ?>;
    let totalPages = <?= $total_pages ?>;

    // Search functionality
    let searchTimeout;
    $('#search-input').on('keyup', function () {
        clearTimeout(searchTimeout);

        const search = $(this).val().trim();

        searchTimeout = setTimeout(function () {
            if (search === currentSearch) return;

            currentSearch = search;
            currentPage = 1;

            $.ajax({
                url: '<?= site_url('admin/media_libraries/search') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    search: search,
                    page: currentPage
                },
                success: function (response) {
                    if (response.success) {
                        $('#media-container').html(response.html);
                        $('#total-media').text(response.total_media);

                        totalPages = response.total_pages;
                        currentPage = response.current_page;

                        updatePagination();
                    }
                },
                error: function () {
                    alert('Error searching media');
                }
            });
        }, 300);
    });

    // Pagination click
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();

        const page = $(this).data('page');

        if (page === currentPage) return;

        currentPage = page;

        $.ajax({
            url: '<?= site_url('admin/media_libraries/load_more') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                page: page,
                search: currentSearch
            },
            success: function (response) {
                if (response.success) {
                    $('#media-container').html(response.html);
                    updatePagination();

                    $('html, body').animate({
                        scrollTop: $('#media-container').offset().top - 100
                    }, 300);
                }
            },
            error: function () {
                alert('Error loading media');
            }
        });
    });

    // Preview button click
    $(document).on('click', '.btn-preview', function () {
        const mediaId = $(this).data('media-id');

        $.ajax({
            url: '<?= site_url('admin/media_libraries/preview') ?>/' + mediaId,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    displayPreview(response);
                    $('#previewModal').modal('show');

                    // Set download button
                    $('#downloadBtn').attr('href', '<?= site_url('admin/media_libraries/download') ?>/' + mediaId);
                }
            },
            error: function () {
                alert('Error loading preview');
            }
        });
    });

    // Display preview in modal
    function displayPreview(data) {
        let html = '';

        // Preview section
        html += '<div class="mb-4">';
        
        if (data.is_image) {
            html += '<div style="text-align: center; margin-bottom: 20px;">';
            html += '<img src="' + data.data_uri + '" alt="' + data.original_filename + '" class="img-fluid" style="max-width: 100%; max-height: 500px; object-fit: contain;">';
            html += '</div>';
        } else if (data.is_pdf) {
            html += '<embed src="' + data.data_uri + '" type="application/pdf" width="100%" height="500px">';
        } else {
            html += '<div style="padding: 60px; text-align: center; background: #f8f9fa; border-radius: 8px;">';
            html += '<i class="fas fa-file" style="font-size: 80px; color: #ccc; display: block; margin-bottom: 20px;"></i>';
            html += '<p class="text-muted mb-0">Preview not available for this file type</p>';
            html += '<small class="text-muted d-block mt-2">' + data.mime_type + '</small>';
            html += '</div>';
        }

        html += '</div>';
        html += '<hr class="my-3">';

        // File information section
        html += '<div class="file-info-section">';
        html += '<h6 class="fw-600 mb-3">File Information</h6>';
        
        html += '<div class="row">';
        
        // Left column
        html += '<div class="col-md-6 mb-3">';
        html += '<div class="mb-2">';
        html += '<small class="text-muted d-block">Filename</small>';
        html += '<strong>' + data.original_filename + '</strong>';
        html += '</div>';
        
        html += '<div class="mb-2">';
        html += '<small class="text-muted d-block">File Size</small>';
        html += '<strong>' + data.file_size + '</strong>';
        html += '</div>';
        
        html += '<div class="mb-2">';
        html += '<small class="text-muted d-block">MIME Type</small>';
        html += '<strong><code style="background: #f0f0f0; padding: 4px 8px; border-radius: 3px;">' + data.mime_type + '</code></strong>';
        html += '</div>';
        html += '</div>';
        
        // Right column
        html += '<div class="col-md-6 mb-3">';
        
        if (data.width && data.height) {
            html += '<div class="mb-2">';
            html += '<small class="text-muted d-block">Dimensions</small>';
            html += '<strong>' + data.width + ' x ' + data.height + ' px</strong>';
            html += '</div>';
        }
        
        html += '<div class="mb-2">';
        html += '<small class="text-muted d-block">Upload Date</small>';
        html += '<strong>' + data.created_at + '</strong>';
        html += '</div>';
        
        html += '<div class="mb-2">';
        html += '<small class="text-muted d-block">Used Count</small>';
        html += '<strong>' + data.used_count + ' time(s)</strong>';
        html += '</div>';
        html += '</div>';
        
        html += '</div>';
        
        // UUID section
        html += '<div class="mt-3 pt-3 border-top">';
        html += '<small class="text-muted d-block mb-1">UUID</small>';
        html += '<code style="background: #f0f0f0; padding: 6px 8px; border-radius: 3px; font-size: 11px; word-break: break-all;">' + data.uuid + '</code>';
        html += '</div>';
        
        html += '</div>';

        $('#previewModalBody').html(html);
        $('#previewModalLabel').text(data.original_filename);
    }

    // Update pagination UI
    function updatePagination() {
        $('.pagination-link').parent().removeClass('active');
        $('.pagination-link[data-page="' + currentPage + '"]').parent().addClass('active');
    }

});

</script>

<style>
.media-card {
    transition: all 0.3s ease;
    background: #fff;
}

.media-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.btn-group .btn-sm {
    border-radius: 4px !important;
}

.file-info-section {
    font-size: 14px;
}

.file-info-section code {
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.pagination-link {
    cursor: pointer;
}
</style>
