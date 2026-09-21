<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - Siatama Privat</title>
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
<body class="bg-[#FAF9F7] text-[#1a1c1b] antialiased min-h-screen pb-28 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- TopAppBar Header Publik -->
        <header class="bg-[#FAF9F7]/95 backdrop-blur-md w-full top-0 sticky z-40 border-b border-gray-200/60 h-16 flex items-center">
            <div class="max-w-[1200px] mx-auto px-4 w-full flex justify-between items-center">
                <a class="flex items-center gap-2.5 group" href="/">
                    <div class="w-9 h-9 rounded-xl bg-[#324f47] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-base text-[#2D3E39] leading-none group-hover:text-[#324f47]">Siatama Privat</span>
                        <span class="text-[11px] text-gray-500 font-medium">Bimbingan Belajar</span>
                    </div>
                </a>
                <div class="flex items-center gap-2">
                    <a href="/login" class="px-4 py-2 rounded-xl bg-[#324f47] text-white hover:bg-[#2D3E39] text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">login</span>
                        Login Akun
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="max-w-[1200px] mx-auto px-4 pt-6 flex-1 w-full flex flex-col gap-6">
            <?php require $contentView; ?>
        </main>

        <!-- Bottom Navigation Bar Publik -->
        <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-50 py-2.5 px-6 pb-safe flex justify-around items-center shadow-[0px_-4px_16px_rgba(0,0,0,0.04)]">
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'home' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">home</span>
                <span class="text-[11px] mt-0.5 leading-none">Beranda</span>
            </a>
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'berita' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/berita">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">newspaper</span>
                <span class="text-[11px] mt-0.5 leading-none">Berita</span>
            </a>
            <a class="flex flex-col items-center justify-center py-1 px-3 rounded-xl transition-all group <?= ($activeNav ?? '') === 'cek_presensi' ? 'text-[#2d5a4c] font-semibold bg-[#e8f0ec]/70' : 'text-gray-500 hover:text-[#2d5a4c] font-medium' ?>" href="/cek-presensi">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">find_in_page</span>
                <span class="text-[11px] mt-0.5 leading-none">Cek Presensi</span>
            </a>
        </nav>
    </div>
</body>
</html>
