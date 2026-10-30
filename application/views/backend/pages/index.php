<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/pages/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Page
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Title</th>
                    <th>Page Key</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($pages)): ?>
                    <?php $no = 1; ?>

                    <?php foreach ($pages as $page): ?>

                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= html_escape($page['title']) ?></td>

                            <td><?= html_escape($page['page_key']) ?></td>

                            <td>
                                <code><?= html_escape($page['slug']) ?></code>
                            </td>

                            <td>
                                <?php if ($page['status'] == 'published'): ?>

                                    <span class="badge bg-success">
                                        Published
                                    </span>

                                <?php elseif ($page['status'] == 'draft'): ?>

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-warning">
                                        <?= ucfirst(html_escape($page['status'])) ?>
                                    </span>

                                <?php endif; ?>
                            </td>

                            <td>
                                <?= !empty($page['published_at'])
                                    ? date('d M Y H:i', strtotime($page['published_at']))
                                    : '-' ?>
                            </td>

                            <td class="text-center">
                                <a href="<?= site_url('page/' . $page['slug']); ?>" target="_blank" class="btn btn-sm btn-info"
                                    title="Lihat Halaman">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= site_url('admin/pages/edit/' . $page['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="<?= site_url('admin/pages/delete/' . $page['id']) ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus halaman ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

<script>
    $(function () {
        $('#datatable').DataTable({
            responsive: true,
            autoWidth: false
        });
    });
</script>