<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Tentor Siatama</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <a class="brand" href="/tentor/beranda" aria-label="Siatama Privat">
                <span class="brand-mark">
                    <img src="/assets/blank-image.svg" alt="">
                </span>
                <span>
                    <strong>Siatama Tentor</strong>
                    <small><?= e($_SESSION['nama_lengkap'] ?? $_SESSION['username']) ?></small>
                </span>
            </a>
            <a href="/logout" class="btn btn-sm btn-danger" style="padding: 4px 10px; font-size: 11px;">
                Keluar
            </a>
        </header>

        <main class="page-content">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="soft-alert tone-green" style="background: #ecfdf5; color: #065f46; border-color: #a7f3d0; margin-bottom: 16px;">
                    <?= e($_SESSION['flash_success']) ?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="soft-alert tone-red" style="background: #fef2f2; color: #991b1b; border-color: #fecaca; margin-bottom: 16px;">
                    <?= e($_SESSION['flash_error']) ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php require $contentView; ?>
        </main>

        <nav class="bottom-nav" aria-label="Navigasi tentor">
            <a class="<?= ($activeNav ?? '') === 'beranda' ? 'is-active' : '' ?>" href="/tentor/beranda">
                <span class="nav-icon dashboard-icon" aria-hidden="true"></span>
                <span>Beranda</span>
            </a>
            <a class="<?= ($activeNav ?? '') === 'jadwal' ? 'is-active' : '' ?>" href="/tentor/jadwal">
                <span class="nav-icon calendar-icon" aria-hidden="true"></span>
                <span>Jadwal Saya</span>
            </a>
            <a class="<?= ($activeNav ?? '') === 'pertemuan' ? 'is-active' : '' ?>" href="/tentor/pertemuan">
                <span class="nav-icon report-icon" aria-hidden="true"></span>
                <span>Sesi & Presensi</span>
            </a>
        </nav>
    </div>
</body>
</html>
