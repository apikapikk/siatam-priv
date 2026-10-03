<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center gap-3">
        <a href="/tentor/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm" aria-label="Kembali">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#2D3E39]">Presensi Siswa</h1>
            <p class="text-xs text-gray-500">Pilih kelas aktif untuk mengisi kehadiran siswa.</p>
        </div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm">
            <h2 class="text-sm font-bold text-[#2D3E39] flex items-center gap-2"><span class="material-symbols-outlined text-[20px] text-[#324f47]">class</span>1. Tipe Kelas</h2>
            <div class="flex gap-2 mt-3" data-filter-group="tipe">
                <button type="button" data-filter="all" class="filter-pill flex-1 py-2.5 rounded-xl border border-[#324f47] bg-[#324f47] text-white text-sm font-semibold">Semua</button>
                <button type="button" data-filter="reguler" class="filter-pill flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-semibold">Reguler</button>
                <button type="button" data-filter="privat" class="filter-pill flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-semibold">Privat</button>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm">
            <h2 class="text-sm font-bold text-[#2D3E39] flex items-center gap-2"><span class="material-symbols-outlined text-[20px] text-[#324f47]">school</span>2. Jenjang</h2>
            <div class="flex gap-2 mt-3" data-filter-group="jenjang">
                <button type="button" data-filter="all" class="filter-pill flex-1 py-2.5 rounded-xl border border-[#324f47] bg-[#324f47] text-white text-sm font-semibold">Semua</button>
                <?php foreach (array_unique(array_map(static fn ($item) => $item['jenjang_nama'], $kelasList)) as $jenjang): ?>
                    <button type="button" data-filter="<?= e(strtolower($jenjang)) ?>" class="filter-pill flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-semibold"><?= e($jenjang) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-base font-bold text-[#2D3E39]">Kelas Aktif</h2>
        <?php if (empty($kelasList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200/80">Belum ada kelas aktif yang ditugaskan kepada Anda.</div>
        <?php else: ?>
            <div id="kelas-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php foreach ($kelasList as $kelas): ?>
                    <article class="kelas-card bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col justify-between gap-3" data-tipe="<?= e(strtolower($kelas['program_tipe'])) ?>" data-jenjang="<?= e(strtolower($kelas['jenjang_nama'])) ?>">
                        <div>
                            <span class="inline-flex px-2.5 py-1 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold"><?= e($kelas['program_nama']) ?></span>
                            <h3 class="text-lg font-bold text-[#2D3E39] mt-3">Kelas <?= e($kelas['kelas_nama']) ?></h3>
                            <p class="text-sm text-gray-500 mt-1"><?= e($kelas['jenjang_nama']) ?> · <?= e((string) $kelas['total_siswa']) ?> siswa</p>
                        </div>
                        <a href="/tentor/presensi/isi?kelas_id=<?= (int) $kelas['kelas_id'] ?>" class="w-full py-2.5 rounded-xl border border-[#324f47] text-[#324f47] text-sm font-semibold text-center hover:bg-[#e8f0ec] transition-colors">Isi Presensi</a>
                    </article>
                <?php endforeach; ?>
            </div>
            <p id="kelas-empty" class="hidden bg-white rounded-2xl p-6 text-center text-sm text-gray-500 border border-gray-200/80">Tidak ada kelas yang sesuai dengan filter.</p>
        <?php endif; ?>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-base font-bold text-[#2D3E39]">Riwayat Presensi</h2>
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            <?php if (empty($riwayatList)): ?>
                <p class="p-6 text-center text-sm text-gray-500">Belum ada riwayat presensi.</p>
            <?php else: foreach ($riwayatList as $riwayat): ?>
                <a href="/tentor/presensi/isi?pertemuan_id=<?= (int) $riwayat['id'] ?>" class="flex items-center justify-between gap-3 p-4 border-b last:border-b-0 border-gray-100 hover:bg-[#FAF9F7]">
                    <div class="flex items-center gap-3 min-w-0"><span class="w-10 h-10 rounded-full bg-[#e8f0ec] text-[#324f47] flex items-center justify-center shrink-0"><span class="material-symbols-outlined">check_circle</span></span><div class="min-w-0"><h3 class="text-sm font-semibold text-gray-900 truncate">Kelas <?= e($riwayat['kelas_nama']) ?> · <?= e($riwayat['program_nama']) ?></h3><p class="text-xs text-gray-500 mt-1"><?= e($riwayat['tanggal']) ?> · <?= e((string) $riwayat['total_hadir']) ?>/<?= e((string) $riwayat['total_presensi']) ?> hadir</p></div></div><span class="material-symbols-outlined text-gray-400">chevron_right</span>
                </a>
            <?php endforeach; endif; ?>
        </div>
    </section>
</div>
<script>
(() => {
    const state = { tipe: 'all', jenjang: 'all' };
    const cards = [...document.querySelectorAll('.kelas-card')];
    const empty = document.getElementById('kelas-empty');
    document.querySelectorAll('[data-filter-group]').forEach(group => {
        group.addEventListener('click', event => {
            const button = event.target.closest('[data-filter]');
            if (!button) return;
            const name = group.dataset.filterGroup;
            state[name] = button.dataset.filter;
            group.querySelectorAll('.filter-pill').forEach(item => item.classList.remove('bg-[#324f47]', 'text-white', 'border-[#324f47]'));
            group.querySelectorAll('.filter-pill').forEach(item => item.classList.add('text-gray-600', 'border-gray-300'));
            button.classList.add('bg-[#324f47]', 'text-white', 'border-[#324f47]');
            button.classList.remove('text-gray-600', 'border-gray-300');
            let visible = 0;
            cards.forEach(card => {
                const show = (state.tipe === 'all' || card.dataset.tipe === state.tipe) && (state.jenjang === 'all' || card.dataset.jenjang === state.jenjang);
                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            empty?.classList.toggle('hidden', visible > 0);
        });
    });
})();
</script>
