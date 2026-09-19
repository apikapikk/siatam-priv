<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Siatama Privat</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <a class="brand" href="/" aria-label="Siatama Privat">
                <span class="brand-mark">
                    <img src="/assets/blank-image.svg" alt="">
                </span>
                <span>
                    <strong>Siatama Privat</strong>
                    <small>Bimbingan Belajar</small>
                </span>
            </a>
            <a href="/login" class="btn btn-sm btn-primary">
                Login Akun
            </a>
        </header>

        <main class="page-content">
            <?php require $contentView; ?>
        </main>

        <nav class="bottom-nav" aria-label="Navigasi publik">
            <a href="/">
                <span class="nav-icon dashboard-icon" aria-hidden="true"></span>
                <span>Beranda</span>
            </a>
            <a href="/berita">
                <span class="nav-icon news-icon" aria-hidden="true"></span>
                <span>Berita</span>
            </a>
            <a href="/cek-presensi">
                <span class="nav-icon report-icon" aria-hidden="true"></span>
                <span>Cek Presensi</span>
            </a>
        </nav>
    </div>
</body>
</html>
