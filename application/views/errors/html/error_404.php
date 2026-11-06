<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config =& load_class('Config', 'core');
$home_url = htmlspecialchars($config->site_url(), ENT_QUOTES, 'UTF-8');
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="3;url=<?= $home_url ?>">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7faf9;
            color: #23332f;
            font: 16px/1.6 Arial, sans-serif;
            text-align: center;
        }
        main { padding: 2rem; }
        h1 { margin: 0; color: #168a78; font-size: 4rem; line-height: 1; }
        a { color: #087566; }
    </style>
</head>
<body>
    <main>
        <h1>404</h1>
        <p>Halaman tujuan Anda tidak tersedia, Anda akan dialihkan ke beranda.</p>
        <p><a href="<?= $home_url ?>">Kembali ke beranda sekarang</a></p>
    </main>
</body>
</html>
