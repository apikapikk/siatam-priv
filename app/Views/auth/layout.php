<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Siatama Privat</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Outlined">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif']
            }
          }
        }
      }
    </script>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body class="font-sans bg-[#FAF9F7] text-gray-900">
    <div class="min-h-screen grid place-items-center px-4 py-8">
        <main class="w-full">
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
