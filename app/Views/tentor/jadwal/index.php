<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center gap-3">
        <a href="/tentor/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm" aria-label="Kembali">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-xl font-bold text-[#2D3E39]">Jadwal Saya</h1>
            <p class="text-xs text-gray-500">Jadwal mengajar dan riwayat kelas Anda.</p>
        </div>
    </div>

    <section class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col gap-3">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-[11px] uppercase tracking-wide font-semibold text-gray-500">Tahun Ajaran</p>
                <h2 class="text-lg font-bold text-[#2D3E39]">Jadwal Mengajar</h2>
            </div>
            <span class="px-3 py-1 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold">
                <?= count($jadwalHariIni) ?> Kelas Hari Ini
            </span>
        </div>

        <form method="GET" action="/tentor/jadwal" class="flex items-end gap-2">
            <label class="flex-1 text-sm font-semibold text-[#2D3E39]">
                Pilih Bulan
                <select name="bulan" class="block mt-1 w-full rounded-xl border-gray-200 bg-[#FAF9F7] text-sm">
                    <option value="" disabled>Pilih bulan</option>
                    <?php $calendarYear = (int) date('Y', strtotime($selectedMonth . '-01')); ?>
                    <?php for ($month = 1; $month <= 12; $month++): ?>
                        <?php $monthValue = sprintf('%04d-%02d', $calendarYear, $month); ?>
                        <option value="<?= e($monthValue) ?>" <?= $monthValue === $selectedMonth ? 'selected' : '' ?>>
                            <?= e(nama_bulan_indonesia($month)) ?> <?= $calendarYear ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </label>
            <button type="submit" class="shrink-0 px-4 py-2.5 rounded-xl bg-[#e8f0ec] text-[#324f47] text-sm font-semibold">Ubah</button>
        </form>
    </section>

    <section class="flex flex-col gap-2">
        <div class="flex items-center justify-between px-1">
            <span class="text-sm font-semibold text-gray-600">
                <?= e(nama_bulan_indonesia((int) date('n', strtotime($selectedMonth . '-01')))) ?> <?= e(date('Y', strtotime($selectedMonth . '-01'))) ?>
            </span>
            <a href="/tentor/jadwal?bulan=<?= e(date('Y-m')) ?>&tanggal=<?= e(date('Y-m-d')) ?>" class="text-xs font-semibold text-[#324f47]">Bulan Ini</a>
        </div>

        <div class="grid grid-cols-7 gap-1.5 sm:gap-2">
            <?php foreach ($calendarDates as $date): ?>
                <?php $active = $date['value'] === $selectedDate; ?>
                <?php $dayNames = ['Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab', 'Sun' => 'Min']; ?>
                <a href="/tentor/jadwal?bulan=<?= e(substr($date['value'], 0, 7)) ?>&tanggal=<?= e($date['value']) ?>" class="min-w-0 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl flex flex-col items-center gap-1 shadow-sm <?= $active ? 'bg-[#324f47] text-white ring-2 ring-[#324f47]/20' : 'bg-[#F2EFE9] text-gray-600' ?>">
                    <span class="text-[10px] sm:text-xs font-semibold"><?= e($dayNames[$date['day']] ?? $date['day']) ?></span>
                    <span class="text-base sm:text-lg font-bold"><?= e($date['number']) ?></span>
                    <span class="w-1.5 h-1.5 rounded-full <?= $date['has_schedule'] ? ($active ? 'bg-amber-300' : 'bg-[#727975]') : 'bg-transparent' ?>"></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-[#2D3E39]">Jadwal Hari Ini</h2>
            <p class="text-xs text-gray-500"><?= e(tanggal_indonesia($selectedDate)) ?></p>
        </div>
        <span class="px-3 py-1 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold">Sesi Aktif</span>
    </section>

    <section class="flex flex-col gap-3">
        <?php if (empty($jadwalHariIni)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200/80">
                Tidak ada jadwal mengajar pada tanggal ini.
            </div>
        <?php else: ?>
            <?php foreach ($jadwalHariIni as $item): ?>
                <article class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#e8f0ec] text-[#324f47] text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">schedule</span>
                            <?= e(substr($item['jam_mulai'], 0, 5)) ?> - <?= e(substr($item['jam_selesai'], 0, 5)) ?> WIB
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-[#F2EFE9] text-[#2D3E39] text-xs font-semibold"><?= e($item['jenjang_nama']) ?></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold text-sm shrink-0">
                            <?= e(substr($item['kelas_nama'], 0, 3)) ?>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-lg text-[#2D3E39] truncate">Kelas <?= e($item['kelas_nama']) ?></h3>
                            <p class="text-sm text-gray-500 truncate"><?= e($item['mata_pelajaran'] ?: $item['program_nama']) ?></p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[15px]">location_on</span>
                                <?= e($item['ruangan'] ?: 'Ruang Umum') ?>
                            </p>
                        </div>
                    </div>

                    <a href="/tentor/jadwal/kelas/<?= (int) $item['kelas_id'] ?>" class="w-full py-3 rounded-xl bg-[#F2EFE9] text-[#324f47] text-sm font-semibold text-center">
                        Lihat Detail Kelas
                        <span class="material-symbols-outlined align-middle text-[17px]">arrow_forward</span>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
