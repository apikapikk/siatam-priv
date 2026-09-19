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
            <a class="brand" href="/admin/beranda" aria-label="Siatama Privat">
                <span class="brand-mark">
                    <img src="/assets/blank-image.svg" alt="">
                </span>
                <span>
                    <strong>Siatama</strong>
                    <small>Admin Panel</small>
                </span>
            </a>
            <button class="icon-button" type="button" aria-label="Notifikasi">
                <span aria-hidden="true">!</span>
            </button>
        </header>

        <main class="page-content">
            <?php require $contentView; ?>
        </main>

        <nav class="bottom-nav" aria-label="Navigasi admin">
            <a class="<?= $activeNav === 'beranda' ? 'is-active' : '' ?>" href="/admin/beranda">
                <span class="nav-icon dashboard-icon" aria-hidden="true"></span>
                <span>Beranda</span>
            </a>
            <a href="#">
                <span class="nav-icon calendar-icon" aria-hidden="true"></span>
                <span>Jadwal</span>
            </a>
            <a href="#">
                <span class="nav-icon users-icon" aria-hidden="true"></span>
                <span>Siswa</span>
            </a>
            <a href="#">
                <span class="nav-icon report-icon" aria-hidden="true"></span>
                <span>Laporan</span>
            </a>
        </nav>
    </div>
</body>
</html>
