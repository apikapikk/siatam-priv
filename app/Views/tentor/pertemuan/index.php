<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/tentor/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Laporan & Sesi Mengajar</h1>
                <p class="text-xs text-gray-500">Rekapitulasi aktivitas bimbingan belajar & presensi</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($pertemuanList) ?> Sesi Terekam</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/tentor/pertemuan/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">post_add</span>
        <span class="text-sm font-semibold tracking-wide">+ Catat Sesi Pertemuan Baru</span>
    </a>

    <!-- Live Search Input -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-pertemuan-tentor" placeholder="Cari nomor pertemuan, kelas, atau tanggal..." type="text"/>
    </div>

    <!-- List Data Pertemuan Sesi -->
    <div class="flex flex-col gap-3.5 w-full" id="pertemuan-tentor-list">
        <?php if (empty($pertemuanList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada sesi pertemuan yang Anda catat.
            </div>
        <?php else: ?>
            <?php foreach ($pertemuanList as $item): ?>
                <div class="pertemuan-item bg-white rounded-2xl p-4.5 shadow-sm border border-gray-200/80 flex flex-col gap-3 transition-all hover:shadow-md">
                    <div class="flex items-start justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#324f47] text-white text-xs font-bold">
                                Pertemuan #<?= e($item['nomor_pertemuan']) ?>
                            </span>
                            <h3 class="font-bold text-base text-gray-900">Kelas <?= e($item['kelas_nama']) ?></h3>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= e($item['jenjang_nama']) ?></span>
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-xs font-semibold"><?= e($item['program_nama']) ?></span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-[#FAF9F6] p-3 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#2d5a4c] flex items-center justify-center font-bold text-sm shadow-xs border border-gray-200/60">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-900">Tanggal: <?= e($item['tanggal']) ?></span>
                                <span class="text-xs text-gray-500 mt-0.5">Waktu: <strong><?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?> WIB</strong></span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 self-start md:self-center">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                                <?= $item['total_hadir'] ?>/<?= $item['total_presensi'] ?> Hadir
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-gray-100">
                        <a href="/tentor/pertemuan/<?= $item['id'] ?>/presensi" class="px-4 py-2 rounded-xl bg-[#324f47] text-white hover:bg-[#2D3E39] text-xs font-semibold transition-colors flex items-center gap-1 shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">fact_check</span>
                            Isi / Edit Presensi Siswa
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search-pertemuan-tentor')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#pertemuan-tentor-list .pertemuan-item');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
