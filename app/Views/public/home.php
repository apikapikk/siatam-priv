<!-- Hero Section -->
<section class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/80 flex flex-col items-center text-center gap-4">
    <div class="w-20 h-20 rounded-full bg-[#e8f0ec] text-[#324f47] flex items-center justify-center shadow-xs">
        <span class="material-symbols-outlined text-4xl">account_tree</span>
    </div>
    <div>
        <h2 class="text-xl font-bold text-[#2D3E39] tracking-tight">Apa itu Siatama Privat?</h2>
        <p class="text-xs text-gray-600 mt-2 leading-relaxed max-w-lg mx-auto">
            Siatama Privat adalah lembaga bimbingan belajar terpercaya untuk jenjang SD, SMP, & SMA. Didukung pengajar profesional serta sistem pemantauan presensi dan perkembangan belajar siswa yang dapat diakses secara mudah.
        </p>
    </div>
    <div class="flex items-center justify-center gap-3 mt-1">
        <a href="/cek-presensi" class="px-4 py-2 rounded-xl bg-[#324f47] text-white text-xs font-semibold hover:bg-[#2D3E39] transition-all flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">search</span>
            Cek Presensi Siswa
        </a>
    </div>
</section>

<!-- Section Program Pembelajaran -->
<section class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-800">Program Pembelajaran</h3>
        <span class="text-xs text-gray-500 font-medium"><?= count($programList) ?> Program Utama</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <?php foreach ($programList as $p): ?>
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-[#324f47]/40 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold"><?= e($p['tipe']) ?></span>
                    <span class="material-symbols-outlined text-[#324f47] text-lg">auto_stories</span>
                </div>
                <strong class="text-sm font-bold text-gray-900"><?= e($p['nama']) ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section Tentor Kami -->
<section class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-800">Tentor & Pengajar Kami</h3>
        <span class="text-xs text-gray-500 font-medium">Pengajar Berkualitas</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        <?php foreach ($tentorList as $t): ?>
            <div class="bg-white rounded-2xl p-3.5 border border-gray-200/80 shadow-sm flex items-center gap-3 hover:border-[#324f47]/40 transition-all">
                <div class="w-11 h-11 rounded-full bg-[#e8f0ec] text-[#324f47] font-bold flex items-center justify-center text-sm overflow-hidden flex-shrink-0 border border-emerald-100">
                    <?php if ($t['foto']): ?>
                        <img src="<?= e($t['foto']) ?>" alt="" class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= e(strtoupper(substr($t['nama_lengkap'], 0, 1))) ?>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 truncate"><?= e($t['nama_lengkap']) ?></h4>
                    <p class="text-xs text-gray-500 truncate"><?= e($t['asal_universitas'] ?: 'Tentor Siatama') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section Berita Terbaru -->
<section class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-800">Berita & Informasi Terbaru</h3>
        <a href="/berita" class="text-xs font-semibold text-[#324f47] hover:underline flex items-center gap-0.5">
            Lihat Semua
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php foreach ($latestBerita as $news): ?>
            <a href="/berita/<?= e($news['slug']) ?>" class="bg-white rounded-2xl p-3.5 border border-gray-200/80 shadow-sm hover:border-[#324f47]/40 transition-all flex items-center gap-3">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 relative border border-gray-200/60">
                    <?php if ($news['gambar']): ?>
                        <img src="<?= e($news['gambar']) ?>" alt="" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-[#e8f0ec] text-[#324f47] font-bold text-[10px] flex items-center justify-center">
                            NO IMAGE
                        </div>
                    <?php endif; ?>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-bold text-gray-900 leading-snug line-clamp-2"><?= e($news['judul']) ?></h4>
                    <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                        <?= e($news['diterbitkan_pada'] ?: 'Terbit') ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
