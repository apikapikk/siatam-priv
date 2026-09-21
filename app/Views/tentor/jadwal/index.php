<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/tentor/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Jadwal Mengajar Saya</h1>
                <p class="text-xs text-gray-500">Daftar kelas & jam bimbingan yang ditugaskan kepada Anda</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($jadwalList) ?> Kelas Ditugaskan</span>
        </div>
    </div>

    <!-- Search / Live Filter Input -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-jadwal-tentor" placeholder="Cari nama kelas, jenjang, atau hari mengajar..." type="text"/>
    </div>

    <!-- List Data Jadwal Tentor -->
    <div class="flex flex-col gap-3.5 w-full" id="jadwal-tentor-list">
        <?php if (empty($jadwalList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada jadwal mengajar yang ditugaskan untuk Anda.
            </div>
        <?php else: ?>
            <?php foreach ($jadwalList as $item): ?>
                <div class="jadwal-item bg-white rounded-2xl p-4.5 shadow-sm border border-gray-200/80 flex flex-col gap-3 transition-all hover:shadow-md">
                    <div class="flex items-start justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#324f47]"></span>
                            <span class="font-bold text-base text-[#2D3E39]">
                                Hari <?= hari_indonesia((int) $item['hari']) ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= e($item['jenjang_nama']) ?></span>
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-xs font-semibold"><?= e($item['program_nama']) ?></span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-[#FAF9F6] p-3.5 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#2d5a4c] flex items-center justify-center font-bold text-sm shadow-xs border border-gray-200/60">
                                <?= e(substr($item['kelas_nama'], 0, 3)) ?>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-bold text-sm text-gray-900">Kelas <?= e($item['kelas_nama']) ?></h3>
                                <div class="flex items-center gap-2 text-xs text-gray-600 mt-0.5">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-[#2d5a4c]">schedule</span>
                                        Pukul: <strong><?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?> WIB</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 text-xs text-gray-500 self-start md:self-center">
                            <span class="material-symbols-outlined text-[16px] text-[#2d5a4c]">location_on</span>
                            <span>Ruangan: <strong><?= e($item['ruangan'] ?: 'Ruang Umum') ?></strong></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search-jadwal-tentor')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#jadwal-tentor-list .jadwal-item');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
