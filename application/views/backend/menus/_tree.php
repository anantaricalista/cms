<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Recursive Tree Renderer
 */
if ( ! function_exists('render_menu_tree'))
{
    function render_menu_tree($menus, $parent_id = NULL, $level = 0)
    {
        foreach ($menus as $menu)
        {
            $current_parent = empty($menu['parent_id']) ? NULL : $menu['parent_id'];

            if ($current_parent != $parent_id)
            {
                continue;
            }

            $margin = ($level * 35);

            $status_class = $menu['is_active']
                ? 'success'
                : 'secondary';

            $icon = !empty($menu['icon'])
                ? $menu['icon']
                : 'far fa-circle';
?>

<div class="card mb-2" style="margin-left: <?= $margin; ?>px;">

    <div class="card-body py-2">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h6 class="mb-1">

                    <i class="<?= html_escape($icon); ?>"></i>

                    <?= html_escape($menu['title']); ?>

                </h6>

                <small class="text-muted">

                    <?= !empty($menu['custom_url'])
                        ? html_escape($menu['custom_url'])
                        : '-'; ?>

                </small>

            </div>

            <div class="btn-group btn-group-sm">

                <a href="<?= site_url('admin/menus/move_up/'.$menu['id']); ?>"
                   class="btn btn-light"
                   title="Naik">

                    <i class="fas fa-arrow-up"></i>

                </a>

                <a href="<?= site_url('admin/menus/move_down/'.$menu['id']); ?>"
                   class="btn btn-light"
                   title="Turun">

                    <i class="fas fa-arrow-down"></i>

                </a>

                <button
                    type="button"
                    class="btn btn-warning btn-edit"

                    data-id="<?= $menu['id']; ?>"

                    data-title="<?= html_escape($menu['title']); ?>"

                    data-parent="<?= $menu['parent_id']; ?>"

                    data-page="<?= $menu['page_id']; ?>"

                    data-url="<?= html_escape($menu['custom_url']); ?>"

                    data-icon="<?= html_escape($menu['icon']); ?>"

                    data-target="<?= html_escape($menu['target']); ?>"
                    
                    data-active="<?= html_escape($menu['is_active']); ?>">

                    <i class="fas fa-edit"></i>

                </button>

                <a href="<?= site_url('admin/menus/toggle_status/'.$menu['id']); ?>"
                   class="btn btn-<?= $status_class; ?>"
                   title="Status">

                    <i class="fas fa-power-off"></i>

                </a>

                <a href="<?= site_url('admin/menus/delete/'.$menu['id']); ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Hapus menu ini?');"
                   title="Hapus">

                    <i class="fas fa-trash"></i>

                </a>

            </div>

        </div>

    </div>

</div>

<?php

            render_menu_tree($menus, $menu['id'], $level + 1);

        }
    }
}

render_menu_tree($menus);
?>