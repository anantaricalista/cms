<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Frontend Header
|--------------------------------------------------------------------------
| Variable yang digunakan:
|
| $title
| $description
| $keywords
| $image
| $site
|
*/

$page_title = !empty($title)
    ? $title
    : (!empty($site['site_name'])
        ? $site['site_name']
        : 'Website');

$meta_description = !empty($description)
    ? $description
    : (!empty($site['company_summary'])
        ? $site['company_summary']
        : '');

$meta_keywords = !empty($keywords)
    ? $keywords
    : '';

$company_name = !empty($site['company_name'])
    ? $site['company_name']
    : $page_title;

$meta_image = !empty($image)
    ? $image
    : (!empty($site['logo_media_id'])
        ? site_url('media/show/' . $site['logo_media_id'])
        : '');

$favicon = !empty($site['favicon_media_id'])
    ? site_url('media/show/' . $site['favicon_media_id'])
    : base_url('assets/frontend/images/favicon.ico');

?>
<!DOCTYPE html>
<html lang="id" id="htmlRoot">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= html_escape($page_title); ?></title>

    <meta name="description"
          content="<?= html_escape($meta_description); ?>">

    <meta name="keywords"
          content="<?= html_escape($meta_keywords); ?>">

    <meta name="author"
          content="<?= html_escape($company_name); ?>">

    <meta name="robots"
          content="index,follow">

    <link rel="canonical"
          href="<?= current_url(); ?>">

    <!-- Open Graph -->

    <meta property="og:type"
          content="website">

    <meta property="og:title"
          content="<?= html_escape($page_title); ?>">

    <meta property="og:description"
          content="<?= html_escape($meta_description); ?>">

    <meta property="og:url"
          content="<?= current_url(); ?>">

    <?php if (!empty($meta_image)) : ?>

        <meta property="og:image"
              content="<?= $meta_image; ?>">

    <?php endif; ?>

    <!-- Twitter -->

    <meta name="twitter:card"
          content="summary_large_image">

    <meta name="twitter:title"
          content="<?= html_escape($page_title); ?>">

    <meta name="twitter:description"
          content="<?= html_escape($meta_description); ?>">

    <?php if (!empty($meta_image)) : ?>

        <meta name="twitter:image"
              content="<?= $meta_image; ?>">

    <?php endif; ?>

    <!-- Favicon -->

    <link rel="icon"
          href="<?= $favicon; ?>"
          type="image/x-icon">

    <link rel="shortcut icon"
          href="<?= $favicon; ?>"
          type="image/x-icon">

    <!-- Google Font -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Bootstrap -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/bootstrap.min.css'); ?>">

    <!-- AOS -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/aos.css'); ?>">

    <!-- Swiper -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/swiper-bundle.min.css'); ?>">

    <!-- Font Awesome -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/all.min.css'); ?>">

    <!-- Magnific Popup -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/magnific-popup.css'); ?>">

    <!-- Main Style -->

    <link rel="stylesheet"
          href="<?= base_url('assets/frontend/css/style_tosca.css'); ?>">

</head>

<style>
    .product-service-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        object-position: center;
    }

    .card {
        border-radius: 18px;
        overflow: hidden;
        transition: .3s ease;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, .15) !important;
    }

    .card-img-top {
        border-radius: 0;
    }

    .card {

        background:
            linear-gradient(180deg,
                rgba(116, 104, 104, 0.06),
                rgba(116, 104, 104, 0.06));

        backdrop-filter: blur(10px);

        border: 1px solid var(--bd);

        border-radius: 22px;

        overflow: hidden;

        transition: .35s;

        height: 100%;
    }

    .card:hover,
    .card:hover {

        transform: translateY(-8px);

        border-color: var(--pri);

        box-shadow:
            0 18px 45px rgba(0, 0, 0, .18);
    }

    .card-thumb {

        height: 220px;

        overflow: hidden;

        position: relative;
    }

    .card-thumb img {

        width: 100%;

        height: 100%;

        object-fit: cover;

        transition: .5s;
    }

    .card:hover img,
    .card:hover img {

        transform: scale(1.05);
    }

    .card-thumb::after {

        content: "";

        position: absolute;

        inset: 0;

        background:

            linear-gradient(to top,
                rgba(0, 0, 0, .35),
                transparent);
    }

    .card-body {

        padding: 28px;
    }

    .card-title {

        font-size: 1.3rem;

        font-weight: 700;

        margin-bottom: 15px;
    }

    .card .card-title,
    .card .card-title {

        color: var(--tx);

        font-weight: 700;

        line-height: 1.4;

        margin-bottom: 16px;
    }

    .card-text {

        color: var(--tx2);

        line-height: 1.8;
    }

    .card-footer {

        background: none;

        border: none;

        padding:

            0 28px 28px;
    }

    .btn-more {

        color: var(--pri);

        text-decoration: none;

        font-weight: 600;

        transition: .3s;
    }

    .btn-more:hover {

        letter-spacing: .5px;
    }
<body>