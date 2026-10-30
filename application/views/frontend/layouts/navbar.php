<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$site_name = !empty($site['site_name']) ? $site['site_name'] : 'Company';

$logo = '';

if (!empty($site['logo_media_id'])) {
    $logo = site_url('media/show/' . $site['logo_media_id']);
}

$menus = isset($menus) && is_array($menus) ? $menus : [];

if (!function_exists('renderDesktopMenu')) {
    function renderDesktopMenu($menus)
    {
        foreach ($menus as $menu) {

            $title = html_escape($menu['title']);
            $url = $menu['url']; // Already escaped and base_url applied by Menu_model
            $target = !empty($menu['target']) ? html_escape($menu['target']) : '_self';
            $children = !empty($menu['children']);

            if ($children) {
                echo '<div class="dropdown">';
                echo '<a href="' . $url . '" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" target="' . $target . '">' . $title . '</a>';
                echo '<ul class="dropdown-menu">';
                foreach ($menu['children'] as $child) {
                    echo '<li><a class="dropdown-item" href="' . $child['url'] . '" target="' . html_escape($child['target']) . '">' . html_escape($child['title']) . '</a></li>';
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<a href="' . $url . '" class="nav-link" target="' . $target . '">' . $title . '</a>';
            }
        }
    }
}

if (!function_exists('renderMobileMenu')) {
    function renderMobileMenu($menus, $level = 0)
    {
        foreach ($menus as $menu) {
            $title = html_escape(str_repeat('- ', $level) . $menu['title']);
            $url = $menu['url']; // Already escaped and base_url applied by Menu_model
            $target = !empty($menu['target']) ? html_escape($menu['target']) : '_self';
            $padding = ($level == 0) ? 0 : (20 + (($level - 1) * 24));

            if (!empty($menu['children'])) {
                echo '<details class="mobile-submenu">';
                echo '<summary class="nav-link d-flex justify-content-between align-items-center py-3 border-bottom" style="padding-left:' . $padding . 'px;border-color:var(--bd)!important;">';
                echo '<span>' . $title . '</span><i class="fa-solid fa-chevron-down"></i>';
                echo '</summary>';
                renderMobileMenu($menu['children'], $level + 1);
                echo '</details>';
            } else {
                echo '<a href="' . $url . '" target="' . $target . '" class="nav-link d-block py-3 border-bottom" style="padding-left:' . $padding . 'px;border-color:var(--bd)!important;">' . $title . '</a>';
            }
        }
    }
}

?>
<style>
    .mobile-submenu summary {
        list-style: none;
        cursor: pointer;
        background: transparent;
        color: var(--tx);
    }

    .mobile-submenu summary::-webkit-details-marker {
        display: none;
    }

    .mobile-submenu summary i {
        transition: .25s;
    }

    .mobile-submenu[open] summary i {
        transform: rotate(180deg);
    }
</style>


<style>
    .mobile-submenu-toggle {
        background: transparent;
        border: none;
        color: var(--tx);
        text-align: left;
    }

    .mobile-submenu-toggle:focus {
        box-shadow: none;
    }

    .mobile-submenu-toggle i {
        transition: .25s;
    }

    .mobile-submenu-toggle[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    .mobile-menu-group .collapse {
        background: rgba(0, 0, 0, .03);
    }
</style>
<div id="landing">

    <nav id="nbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">

                <a href="<?= base_url(); ?>" class="d-flex align-items-center gap-2 text-decoration-none">

                    <?php if ($logo): ?>
                        <img src="<?= $logo; ?>" alt="<?= html_escape($site_name); ?>" style="height:42px;">
                    <?php else: ?>
                        <div class="logo-i"><i class="fa-solid fa-layer-group"></i></div>
                    <?php endif; ?>

                    <span style="font-size:1.2rem;font-weight:700;color:var(--tx);">
                        <?= html_escape($site_name); ?>
                    </span>

                </a>

                <div class="d-none d-lg-flex align-items-center gap-3 mx-auto">
                    <?php renderDesktopMenu($menus); ?>
                </div>

                <div class="d-flex align-items-center gap-2">

                    <button class="boc d-flex align-items-center justify-content-center" id="thbtn"
                        style="width:38px;height:38px;padding:0;border-radius:12px;" aria-label="Toggle Theme">
                        <i class="fa-solid fa-sun" id="suni" style="display:none"></i>
                        <i class="fa-solid fa-moon" id="mooni"></i>
                    </button>

                    <a href="#contact" class="bgrd btn px-3 py-2 d-none d-sm-flex align-items-center gap-2">
                        Contact Us
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <button class="boc d-lg-none px-2 py-2" id="mbtog" style="border-radius:10px;">
                        <i class="fa-solid fa-bars" id="barIcon"></i>
                        <i class="fa-solid fa-xmark" id="xIcon" style="display:none"></i>
                    </button>

                </div>

            </div>
        </div>
    </nav>

    <div id="mbmenu">
        <?php renderMobileMenu($menus); ?>

        <div class="d-flex gap-2 mt-3">
            <a href="#contact" class="bgrd flex-fill btn py-2">Hubungi Kami</a>
        </div>

    </div>

</div>