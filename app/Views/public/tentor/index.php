<div class="flex flex-col gap-6">
    <header>
        <h1 class="text-xl font-bold text-[#2D3E39]">Profil Tentor</h1>
        <p class="text-xs text-gray-500 mt-1">Kenali tentor dan bidang keahlian Siatama Privat.</p>
    </header>

    <div class="relative">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
        <input id="tentor-search" type="search" placeholder="Cari nama atau universitas..."
               class="w-full h-13 pl-12 pr-4 bg-white border border-gray-200 rounded-xl text-sm focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47]">
    </div>

    <section id="tentor-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($tentorList as $tentor): ?>
            <article class="tentor-card bg-white rounded-xl p-5 shadow-sm border-l-4 border-l-transparent hover:border-l-[#d7a84b] transition-all"
                     data-search="<?= e(strtolower(($tentor['nama_lengkap'] ?? '') . ' ' . ($tentor['asal_universitas'] ?? ''))) ?>">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden shrink-0 bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold text-lg">
                        <?php if (!empty($tentor['foto'])): ?>
                            <img src="<?= e($tentor['foto']) ?>" alt="<?= e($tentor['nama_lengkap']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?= e(strtoupper(substr($tentor['nama_lengkap'], 0, 1))) ?>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-semibold text-[#2D3E39] truncate"><?= e($tentor['nama_lengkap']) ?></h2>
                        <p class="text-xs text-gray-500 mt-1 truncate"><?= e($tentor['asal_universitas'] ?: 'Tentor Siatama') ?></p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                    <span class="material-symbols-outlined text-base text-[#324f47]">school</span>
                    Tentor profesional Siatama Privat
                </div>
                <a href="/profil-tentor/<?= (int) $tentor['id'] ?>" class="mt-4 w-full py-3 rounded-lg bg-[#324f47] text-white text-sm font-semibold flex items-center justify-center gap-2">
                    Lihat Profil <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if (empty($tentorList)): ?>
        <div class="bg-white rounded-xl p-8 text-center text-sm text-gray-500 border border-gray-200">Belum ada data tentor publik.</div>
    <?php endif; ?>
</div>
<script>
    document.getElementById('tentor-search')?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        document.querySelectorAll('.tentor-card').forEach((card) => {
            card.hidden = keyword !== '' && !card.dataset.search.includes(keyword);
        });
    });
</script>
