<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="<?= site_url('admin/services/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Service
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="datatable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($services)): ?>
                    <?php $no = 1; ?>

                    <?php foreach ($services as $service): ?>

                        <tr>

                            <td><?= $no++ ?></td>

                            <td><?= html_escape($service['name']) ?></td>

                            <td>
                                <code><?= html_escape($service['slug']) ?></code>
                            </td>

                            <td>

                                <?php if ($service['status'] == 'published'): ?>

                                    <span class="badge bg-success">
                                        Published
                                    </span>

                                <?php elseif ($service['status'] == 'draft'): ?>

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-warning">
                                        <?= ucfirst(html_escape($service['status'])) ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?= !empty($service['published_at'])
                                    ? date('d M Y H:i', strtotime($service['published_at']))
                                    : '-' ?>

                            </td>

                            <td class="text-center">

                                <a href="<?= site_url('service/' . $service['slug']); ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-info"
                                    title="View Service">

                                    <i class="fas fa-eye"></i>

                                </a>

                                <a href="<?= site_url('admin/services/edit/' . $service['id']) ?>"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="<?= site_url('admin/services/delete/' . $service['id']) ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus service ini?');">

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