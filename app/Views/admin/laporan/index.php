<div class="flex flex-col gap-5 w-full">
    <!-- Page Header -->
    <div class="flex flex-col gap-1">
        <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Pusat Laporan & Rekap</h1>
        <p class="text-xs text-gray-500">Rekap bulanan presensi siswa & honorarium tentor</p>
    </div>

    <!-- Filter Bulan & Tahun -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80">
        <p class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">Filter Periode</p>
        <form method="GET" action="/admin/laporan" class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= (int) $bulan === $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Tahun</label>
                <input type="number" name="tahun" value="<?= (int) $tahun ?>" min="2020" max="2099"
                       class="w-full px-3 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
            </div>
        </form>
    </div>

    <!-- Menu Laporan -->
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide -mb-2">Pilih Jenis Laporan</p>

    <a href="/admin/laporan/siswa-bulanan?bulan=<?= (int) $bulan ?>&tahun=<?= (int) $tahun ?>"
       class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex items-center gap-4 hover:border-[#324f47]/30 hover:shadow-md transition-all group">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition-colors">
            <span class="material-symbols-outlined text-blue-600 text-[24px]">groups</span>
        </div>
        <div class="flex flex-col gap-0.5 flex-1">
            <p class="text-sm font-bold text-gray-900">Rekap Presensi & Nilai Siswa</p>
            <p class="text-xs text-gray-500">Ringkasan kehadiran & evaluasi akademik per kelas per bulan</p>
        </div>
        <span class="material-symbols-outlined text-gray-400 text-[20px] group-hover:translate-x-0.5 transition-transform">chevron_right</span>
    </a>

    <a href="/admin/laporan/tentor-bulanan?bulan=<?= (int) $bulan ?>&tahun=<?= (int) $tahun ?>"
       class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex items-center gap-4 hover:border-[#324f47]/30 hover:shadow-md transition-all group">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-100 transition-colors">
            <span class="material-symbols-outlined text-emerald-600 text-[24px]">payments</span>
        </div>
        <div class="flex flex-col gap-0.5 flex-1">
            <p class="text-sm font-bold text-gray-900">Rekap Log Mengajar & Honorarium Tentor</p>
            <p class="text-xs text-gray-500">Total sesi, jam mengajar, dan estimasi honorarium per tentor per bulan</p>
        </div>
        <span class="material-symbols-outlined text-gray-400 text-[20px] group-hover:translate-x-0.5 transition-transform">chevron_right</span>
    </a>
</div>
