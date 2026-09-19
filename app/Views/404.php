<?php require_once __DIR__ . '/../Support/helpers.php'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? '404') ?> - Siatama Privat</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
    <main class="not-found">
        <h1>404</h1>
        <p>Halaman tidak ditemukan.</p>
        <a href="/admin/beranda">Kembali ke Beranda</a>
    </main>
</body>
</html>
