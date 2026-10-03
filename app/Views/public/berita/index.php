<div class="max-w-2xl mx-auto w-full flex flex-col gap-5 pt-2">
    <header class="flex items-center gap-3">
        <a href="/" class="text-[#324f47]" aria-label="Kembali ke beranda"><span class="material-symbols-outlined">arrow_back</span></a>
        <h1 class="flex-1 text-center text-xl font-bold text-[#2D3E39]">Kegiatan Siatama</h1>
        <span class="w-6"></span>
    </header>

    <div class="flex gap-2 overflow-x-auto pb-1" aria-label="Filter berita">
        <?php $filters = ['semua' => 'Semua', 'bakti-sosial' => 'Bakti Sosial', 'rekap-bulanan' => 'Rekap Bulanan']; ?>
        <?php foreach ($filters as $value => $label): ?>
            <a href="/berita?filter=<?= e($value) ?>" class="whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold <?= ($filter ?? 'semua') === $value ? 'bg-[#324f47] text-white' : 'bg-white border border-gray-200 text-gray-600' ?>">
                <?= e($label) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <section class="flex flex-col gap-5">
        <?php if (empty($beritaList)): ?>
            <div class="bg-white rounded-xl p-8 text-center text-sm text-gray-500 border border-gray-200">Belum ada berita publik untuk filter ini.</div>
        <?php else: ?>
            <?php foreach ($beritaList as $item): ?>
                <a href="/berita/<?= e($item['slug']) ?>" class="bg-white rounded-xl shadow-sm overflow-hidden border border-transparent hover:border-[#d7a84b] transition-all group">
                    <div class="h-48 bg-[#e8f0ec] relative overflow-hidden">
                        <?php if (!empty($item['gambar'])): ?>
                            <img src="<?= e($item['gambar']) ?>" alt="<?= e($item['judul']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-[#324f47]"><span class="material-symbols-outlined text-6xl">newspaper</span></div>
                        <?php endif; ?>
                        <span class="absolute top-4 left-4 bg-[#324f47]/90 text-white px-3 py-1 rounded-full text-[11px] font-semibold">Kegiatan Siatama</span>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 text-gray-500 text-xs"><span class="material-symbols-outlined text-sm">calendar_today</span><?= e($item['diterbitkan_pada'] ?: 'Terbit') ?></div>
                        <h2 class="mt-2 text-lg font-bold text-[#2D3E39] group-hover:text-[#324f47]"><?= e($item['judul']) ?></h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600 line-clamp-3"><?= e(mb_substr(strip_tags($item['isi']), 0, 180)) ?><?= mb_strlen(strip_tags($item['isi'])) > 180 ? '...' : '' ?></p>
                        <div class="mt-4 flex items-center text-[#324f47] text-sm font-semibold">Baca Selengkapnya <span class="material-symbols-outlined text-lg ml-1">arrow_forward</span></div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
