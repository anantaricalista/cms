<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<aside class="app-sidebar shadow" style="background:#ffffff;" data-bs-theme="light">

    <div class="sidebar-brand" style="background:#fff;border-bottom:1px solid #e9eef3;">
        <a href="<?= site_url('admin'); ?>" class="brand-link">

            <?php if (!empty($site->logo_media_id)): ?>

                <img src="<?= site_url('media/show/' . $site->logo_media_id); ?>" class="brand-image"
                    style="opacity:.9;object-fit:contain;">

            <?php else: ?>

            <span class="brand-text font-weight-light">
                <?= html_escape($site->site_name); ?>
            </span>
            <?php endif; ?>

        </a>
    </div>

    <div class="sidebar-wrapper" style="background:#fff;">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column" style="--bs-nav-link-color:#475569;" data-lte-toggle="treeview"
                data-accordion="false">

                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard'); ?>" class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header" style="color:#94a3b8;font-size:11px;font-weight:700;letter-spacing:1px;">WEBSITE
                </li>

                <li class="nav-item"><a href="<?= site_url('admin/site'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-globe"></i>
                        <p>Site</p>
                    </a></li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/heroes'); ?>" class="nav-link">
                        <i class="nav-icon bi bi-images"></i>
                        <p>Hero</p>
                    </a>
                </li>
                <li class="nav-item"><a href="<?= site_url('admin/menus'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-list"></i>
                        <p>Menus</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/pages'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Pages</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/services'); ?>" class="nav-link"><i
                            class="nav-icon fas fa-concierge-bell"></i>
                        <p>Services</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/products'); ?>" class="nav-link"><i
                            class="nav-icon fas fa-boxes"></i>
                        <p>Products</p>
                    </a></li>
                <li class="nav-header" style="color:#94a3b8;font-size:11px;font-weight:700;letter-spacing:1px;">CONTENT
                </li>

                <li class="nav-item"><a href="<?= site_url('admin/news'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-newspaper"></i>
                        <p>News</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/portfolios'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-briefcase"></i>
                        <p>Portfolio</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/galleries'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-images"></i>
                        <p>Gallery</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/downloads'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-download"></i>
                        <p>Downloads</p>
                    </a></li>

                <li class="nav-header" style="color:#94a3b8;font-size:11px;font-weight:700;letter-spacing:1px;">COMPANY
                </li>

                <li class="nav-item"><a href="<?= site_url('admin/teams'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-people"></i>
                        <p>Team</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/clients'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-building"></i>
                        <p>Clients</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/testimonials'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-chat-square-quote"></i>
                        <p>Testimonials</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/careers'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-briefcase-fill"></i>
                        <p>Careers</p>
                    </a></li>

                <li class="nav-header" style="color:#94a3b8;font-size:11px;font-weight:700;letter-spacing:1px;">MEDIA
                </li>

                <li class="nav-item"><a href="<?= site_url('admin/media_libraries'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-folder2-open"></i>
                        <p>Media Library</p>
                    </a></li>

                <li class="nav-header" style="color:#94a3b8;font-size:11px;font-weight:700;letter-spacing:1px;">SYSTEM
                </li>

                <li class="nav-item"><a href="<?= site_url('admin/users'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-person"></i>
                        <p>Users</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/roles'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-shield-lock"></i>
                        <p>Roles</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/permissions'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-key"></i>
                        <p>Permissions</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/redirects'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-arrow-left-right"></i>
                        <p>Redirects</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/activity-logs'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-clock-history"></i>
                        <p>Activity Logs</p>
                    </a></li>
                <li class="nav-item"><a href="<?= site_url('admin/settings'); ?>" class="nav-link"><i
                            class="nav-icon bi bi-gear"></i>
                        <p>Settings</p>
                    </a></li>

            </ul>

        </nav>

    </div>

</aside>
