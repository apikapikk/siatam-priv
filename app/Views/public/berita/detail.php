<div class="max-w-3xl mx-auto w-full flex flex-col gap-6 pt-2">
    <!-- Header Page & Back Button -->
    <div class="flex items-center gap-3">
        <a href="/berita" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
            <span class="material-symbols-outlined text-[22px]">arrow_back</span>
        </a>
        <div>
            <span class="text-xs text-gray-500 font-medium">Informasi & Kabar Terkini</span>
            <h1 class="text-xl md:text-2xl font-bold text-[#2D3E39] leading-tight"><?= e($berita['judul']) ?></h1>
        </div>
    </div>

    <!-- Metadata Author & Date -->
    <div class="flex items-center gap-4 text-xs text-gray-500 px-1 border-b border-gray-100 pb-3">
        <span class="flex items-center gap-1 font-medium text-gray-700">
            <span class="material-symbols-outlined text-[15px] text-[#324f47]">person</span>
            @<?= e($berita['pembuat_nama']) ?>
        </span>
        <span>•</span>
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">calendar_today</span>
            <?= e(date('d M Y, H:i', strtotime($berita['diterbitkan_pada'] ?? 'now'))) ?> WIB
        </span>
    </div>

    <!-- Gambar Artikel -->
    <?php if (!empty($berita['gambar'])): ?>
        <div class="w-full rounded-2xl overflow-hidden border border-gray-200/80 shadow-sm max-h-[360px] bg-gray-100">
            <img src="<?= e($berita['gambar']) ?>" alt="<?= e($berita['judul']) ?>" class="w-full h-full object-cover">
        </div>
    <?php endif; ?>

    <!-- Konten Berita -->
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200/80 text-gray-700 text-sm md:text-base leading-relaxed whitespace-pre-line">
        <?= e($berita['isi']) ?>
    </div>
</div>
