<div class="flex flex-col gap-5 w-full max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-[#2D3E39]">Laporan Mengajar</h1>
            <p class="text-xs text-gray-500 mt-0.5">Rekapitulasi aktivitas bimbingan belajar.</p>
        </div>
        <form method="GET" action="/tentor/laporan" class="flex items-center gap-2">
            <label class="sr-only" for="laporan-bulan">Pilih bulan</label>
            <select id="laporan-bulan" name="bulan" class="rounded-xl border-gray-200 bg-white text-xs font-semibold text-[#2D3E39]">
                <?php $reportYear = (int) date('Y', strtotime($selectedMonth . '-01')); ?>
                <?php for ($month = 1; $month <= 12; $month++): ?>
                    <?php $monthValue = sprintf('%04d-%02d', $reportYear, $month); ?>
                    <option value="<?= e($monthValue) ?>" <?= $monthValue === $selectedMonth ? 'selected' : '' ?>>
                        <?= e(nama_bulan_indonesia($month)) ?> <?= $reportYear ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="p-2 rounded-xl bg-[#e8f0ec] text-[#324f47]" title="Ubah bulan">
                <span class="material-symbols-outlined text-[18px]">calendar_month</span>
            </button>
        </form>
    </div>

    <section class="relative overflow-hidden bg-gradient-to-br from-[#243E35] via-[#2E4F44] to-[#395D51] text-white rounded-2xl p-5 shadow-lg">
        <div class="relative z-10 flex flex-col gap-4">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div>
                    <span class="text-[11px] font-medium tracking-wide uppercase text-[#c9e9de]">Ringkasan Performa Tentor</span>
                    <h2 class="text-lg font-bold mt-0.5">Bulan <?= e(nama_bulan_indonesia((int) date('n', strtotime($selectedMonth . '-01')))) ?> <?= $reportYear ?></h2>
                </div>
                <span class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-[#ffe08b]">
                    <span class="material-symbols-outlined">workspace_premium</span>
                </span>
            </div>

            <div class="grid grid-cols-3 gap-2.5">
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <p class="text-[11px] text-gray-200">Kehadiran</p>
                    <p class="text-xl font-bold mt-1"><?= e((string) $performance['total_hadir']) ?><span class="text-xs font-normal ml-1">Kali</span></p>
                    <p class="text-[10px] text-gray-300">Total hadir</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <p class="text-[11px] text-gray-200">Izin/Sakit</p>
                    <p class="text-xl font-bold mt-1"><?= e((string) $totalIzinSakit) ?><span class="text-xs font-normal ml-1">Data</span></p>
                    <p class="text-[10px] text-gray-300">Perlu perhatian</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 border border-white/10">
                    <p class="text-[11px] text-gray-200">Durasi</p>
                    <p class="text-xl font-bold mt-1"><?= e((string) $performance['total_jam']) ?><span class="text-xs font-normal ml-1">Jam</span></p>
                    <p class="text-[10px] text-gray-300">Total mengajar</p>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-[11px] text-[#c9e9de] mb-1.5">
                    <span>Tingkat Kehadiran</span>
                    <strong class="text-white"><?= e((string) $performance['persentase_kehadiran']) ?>%</strong>
                </div>
                <div class="w-full h-2 rounded-full bg-[#182a24] overflow-hidden">
                    <div class="h-full bg-[#ffe08b] rounded-full" style="width: <?= min(100, (float) $performance['persentase_kehadiran']) ?>%;"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="flex items-center justify-between pt-1">
        <h2 class="text-base font-bold text-[#2D3E39]">Riwayat Mengajar Bulan Ini</h2>
        <span class="text-xs font-medium text-gray-600 bg-[#F2EFE9] px-2.5 py-1 rounded-full"><?= count($reportList) ?> Sesi</span>
    </section>

    <section class="flex flex-col gap-3">
        <?php if (empty($reportList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200/80">Belum ada sesi mengajar pada bulan ini.</div>
        <?php else: ?>
            <?php foreach ($reportList as $item): ?>
                <article class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-2.5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold text-sm shrink-0"><?= e((string) $item['nomor_pertemuan']) ?></div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm text-[#2D3E39] truncate">Pertemuan <?= e($item['nomor_pertemuan']) ?> · Kelas <?= e($item['kelas_nama']) ?></h3>
                                <p class="text-xs text-gray-500 mt-0.5 truncate"><?= e($item['mata_pelajaran'] ?: $item['program_nama']) ?></p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70 shrink-0">Terverifikasi</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-[#324f47]">event</span><?= e(tanggal_indonesia($item['tanggal'], false)) ?></span>
                        <span class="flex items-center gap-1.5 font-medium text-[#2D3E39]"><span class="material-symbols-outlined text-[16px] text-[#324f47]">schedule</span><?= e(substr($item['jam_mulai'], 0, 5)) ?> - <?= e(substr($item['jam_selesai'], 0, 5)) ?> WIB</span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <div class="bg-[#F2EFE9] border border-gray-200 rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-[#324f47]">verified_user</span>
        <p class="text-xs text-gray-600 leading-relaxed">Seluruh laporan pengajaran dan absensi tentor direkap otomatis oleh sistem Siatama Privat.</p>
    </div>
</div>
