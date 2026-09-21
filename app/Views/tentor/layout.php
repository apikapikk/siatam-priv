<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Tentor Siatama</title>
    <!-- Tailwind CSS CDN & Material Symbols Icons -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#324f47",
              "forest-deep": "#2D3E39",
              "surface-warm": "#FAF9F6",
            },
            fontFamily: {
              sans: ["Inter", "sans-serif"],
              heading: ["Montserrat", "sans-serif"],
            }
          }
        }
      }
    </script>
</head>
<body class="bg-[#FAF9F7] text-[#191c1b] antialiased min-h-screen pb-28 font-sans flex flex-col justify-between">
    <div class="min-h-screen flex flex-col">
        <!-- Header Profil Tentor -->
        <header class="w-full bg-[#FAF9F7]/90 sticky top-0 z-30 px-4 pt-4 pb-3 border-b border-gray-200/60 backdrop-blur-md">
            <div class="max-w-[1200px] mx-auto flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-full bg-[#2d5a4c]/10 text-[#2d5a4c] font-bold flex items-center justify-center ring-2 ring-[#2d5a4c]/20 shadow-sm text-lg">
                            <?= e(strtoupper(substr($_SESSION['user_nama'] ?? 'T', 0, 1))) ?>
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h1 class="font-bold text-base text-[#2D3E39] leading-tight">Halo, <?= e($_SESSION['user_nama'] ?? 'Tentor') ?>!</h1>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Tutor Siatama Privat</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/logout" class="p-2 rounded-full text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors border border-gray-200 shadow-sm bg-white flex items-center justify-center" title="Keluar">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content Tentor -->
        <main class="max-w-[1200px] mx-auto px-4 pt-5 flex-1 w-full flex flex-col gap-5">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <div><?= e($_SESSION['flash_success']) ?></div>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-red-600">error</span>
                    <div><?= e($_SESSION['flash_error']) ?></div>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php require $contentView; ?>
        </main>

        <!-- Bottom Navigation Bar Tentor -->
        <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50 py-2.5 px-6 pb-safe flex justify-around items-center shadow-[0px_-4px_16px_rgba(0,0,0,0.04)]">
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'beranda' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/tentor/beranda">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">home</span>
                <span class="text-[11px] mt-0.5 leading-none">Beranda</span>
            </a>
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'jadwal' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/tentor/jadwal">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">calendar_month</span>
                <span class="text-[11px] mt-0.5 leading-none">Jadwal Saya</span>
            </a>
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'pertemuan' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/tentor/pertemuan">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">fact_check</span>
                <span class="text-[11px] mt-0.5 leading-none">Sesi & Presensi</span>
            </a>
        </nav>
    </div>
</body>
</html>
