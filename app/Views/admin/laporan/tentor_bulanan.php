<div class="flex flex-col gap-5 w-full">

    <!-- Page Header -->
    <div class="flex items-center gap-3">
        <a href="/admin/laporan" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-[#324f47] hover:border-[#324f47]/30 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div class="flex flex-col gap-0.5">
            <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Rekap Log Mengajar & Honorarium</h1>
            <p class="text-xs text-gray-500">Estimasi honorarium tentor berdasarkan sesi & jam mengajar</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80">
        <form method="GET" action="/admin/laporan/tentor-bulanan" class="grid grid-cols-2 gap-3">
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
            <div class="col-span-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-[#324f47] hover:bg-[#2D3E39] text-white font-semibold text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Tampilkan Rekap
                </button>
            </div>
        </form>
    </div>

    <!-- Summary & Export -->
    <div class="flex items-center justify-between flex-wrap gap-2">
        <p class="text-sm font-bold text-gray-900">
            <?= date('F', mktime(0, 0, 0, (int) $bulan, 1)) ?> <?= (int) $tahun ?>
            <span class="font-normal text-gray-500 text-xs">— <?= count($payrollList) ?> tentor aktif</span>
        </p>
        <?php if (!empty($payrollList)): ?>
            <a href="/admin/laporan/tentor-bulanan/export-csv?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
               class="flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">download</span>
                Export CSV
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($payrollList)): ?>
        <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
            Tidak ada data mengajar untuk periode yang dipilih.
        </div>
    <?php else: ?>

        <!-- Total Keseluruhan -->
        <?php
            $grandTotal = array_sum(array_column($payrollList, 'total_honorarium'));
            $grandSesi  = array_sum(array_column($payrollList, 'total_sesi'));
            $grandJam   = array_sum(array_column($payrollList, 'total_jam'));
        ?>
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-emerald-50 rounded-2xl p-3 text-center border border-emerald-100">
                <div class="text-lg font-bold text-emerald-700"><?= format_number((int) $grandSesi) ?></div>
                <div class="text-[11px] text-gray-500 font-medium">Total Sesi</div>
            </div>
            <div class="bg-blue-50 rounded-2xl p-3 text-center border border-blue-100">
                <div class="text-lg font-bold text-blue-700"><?= number_format((float) $grandJam, 1) ?></div>
                <div class="text-[11px] text-gray-500 font-medium">Total Jam</div>
            </div>
            <div class="bg-amber-50 rounded-2xl p-3 text-center border border-amber-100">
                <div class="text-base font-bold text-amber-700 leading-tight"><?= format_rupiah((float) $grandTotal) ?></div>
                <div class="text-[11px] text-gray-500 font-medium">Est. Honor</div>
            </div>
        </div>

        <!-- Per-Tentor Cards -->
        <div class="flex flex-col gap-3">
            <?php foreach ($payrollList as $t): ?>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-3">
                    <!-- Identity -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#e8f0ec] flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-[#324f47]"><?= e(strtoupper(substr($t['nama_lengkap'], 0, 1))) ?></span>
                        </div>
                        <div class="flex flex-col gap-0.5 flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate"><?= e($t['nama_lengkap']) ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= e($t['asal_universitas']) ?></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-emerald-700"><?= format_rupiah((float) $t['total_honorarium']) ?></p>
                            <p class="text-[11px] text-gray-400">Est. Honorarium</p>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-[#FAF9F7] rounded-xl p-2.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-[#324f47]">event_note</span>
                            <div>
                                <span class="font-bold text-gray-900"><?= (int) $t['total_sesi'] ?></span>
                                <span class="text-gray-500"> sesi</span>
                            </div>
                        </div>
                        <div class="bg-[#FAF9F7] rounded-xl p-2.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-[#324f47]">schedule</span>
                            <div>
                                <span class="font-bold text-gray-900"><?= number_format((float) $t['total_jam'], 1) ?></span>
                                <span class="text-gray-500"> jam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tarif Info -->
                    <div class="flex items-center gap-3 text-[11px] text-gray-400 border-t border-gray-100 pt-2.5">
                        <?php if ((float) $t['tarif_per_sesi'] > 0): ?>
                            <span>Tarif/sesi: <strong class="text-gray-600"><?= format_rupiah((float) $t['tarif_per_sesi']) ?></strong></span>
                        <?php endif; ?>
                        <?php if ((float) $t['rate_gaji_per_jam'] > 0): ?>
                            <span>Rate/jam: <strong class="text-gray-600"><?= format_rupiah((float) $t['rate_gaji_per_jam']) ?></strong></span>
                        <?php endif; ?>
                        <?php if ((float) $t['tarif_per_sesi'] == 0 && (float) $t['rate_gaji_per_jam'] == 0): ?>
                            <span class="text-amber-600">⚠ Tarif belum diatur</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>
