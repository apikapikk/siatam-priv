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
<body class="font-sans bg-[#FAF9F7] text-gray-900 pb-24">
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
    <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50 py-2.5 px-6 flex justify-around items-center shadow-sm">
        <a class="flex flex-col items-center text-gray-500 text-[11px]" href="/berita"><span class="material-symbols-outlined text-xl">newspaper</span>Berita</a>
        <a class="flex flex-col items-center text-gray-500 text-[11px]" href="/cek-presensi"><span class="material-symbols-outlined text-xl">co_present</span>Absensi Siswa</a>
        <a class="flex flex-col items-center text-gray-500 text-[11px]" href="/profil-tentor"><span class="material-symbols-outlined text-xl">person_search</span>Profil Tentor</a>
        <a class="flex flex-col items-center text-[#2d5a4c] font-semibold text-[11px]" href="/login"><span class="material-symbols-outlined text-xl">login</span>Login</a>
    </nav>
</body>
</html>
