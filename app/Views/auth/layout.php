<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Siatama Privat</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body style="background: #faf9f7;">
    <div class="app-shell" style="display: grid; min-height: 100vh; align-items: center; padding: 20px;">
        <main>
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
    </div>
</body>
</html>
