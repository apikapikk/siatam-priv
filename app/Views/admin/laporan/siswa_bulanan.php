<div class="flex flex-col gap-5 w-full">

    <!-- Page Header -->
    <div class="flex items-center gap-3">
        <a href="/admin/laporan" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-[#324f47] hover:border-[#324f47]/30 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div class="flex flex-col gap-0.5">
            <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Rekap Presensi & Nilai Siswa</h1>
            <p class="text-xs text-gray-500">Ringkasan kehadiran & evaluasi akademik per kelas</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80">
        <form method="GET" action="/admin/laporan/siswa-bulanan" class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2 flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Kelas</label>
                    <select name="kelas_id" required class="w-full px-3 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= (int) $kelasId === (int) $k['id'] ? 'selected' : '' ?>>
                                <?= e($k['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
            </div>
            <button type="submit" class="w-full py-2.5 px-4 bg-[#324f47] hover:bg-[#2D3E39] text-white font-semibold text-sm rounded-xl transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Tampilkan Rekap
            </button>
        </form>
    </div>

    <?php if ($kelasId > 0): ?>
        <?php $rows = $report['rows'] ?? []; $summary = $report['summary_by_student'] ?? []; ?>

        <!-- Summary Header -->
        <div class="flex items-center justify-between flex-wrap gap-2">
            <div>
                <p class="text-sm font-bold text-gray-900">
                    Kelas <?= e($kelasDetail['nama'] ?? '-') ?> —
                    <?= date('F', mktime(0, 0, 0, (int) $bulan, 1)) ?> <?= (int) $tahun ?>
                </p>
                <p class="text-xs text-gray-500"><?= count($summary) ?> siswa · <?= count(array_unique(array_column($rows, 'pertemuan_id'))) ?> pertemuan</p>
            </div>
            <?php if (!empty($summary)): ?>
                <a href="/admin/laporan/siswa-bulanan/export-csv?kelas_id=<?= $kelasId ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>"
                   class="flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">download</span>
                    Export CSV
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($summary)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Tidak ada data presensi untuk kelas ini pada periode yang dipilih.
            </div>
        <?php else: ?>
            <!-- Rekap Per Siswa -->
            <div class="flex flex-col gap-3">
                <?php foreach ($summary as $s): ?>
                    <?php
                        $total  = (int) $s['total'];
                        $hadir  = (int) $s['hadir'];
                        $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                        $color  = $persen >= 75 ? 'bg-emerald-500' : ($persen >= 50 ? 'bg-amber-400' : 'bg-red-400');
                        $label  = $persen >= 75 ? 'text-emerald-700' : ($persen >= 50 ? 'text-amber-600' : 'text-red-600');
                    ?>
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold text-gray-900"><?= e($s['siswa_nama']) ?></p>
                                <p class="text-xs text-gray-500"><?= e($s['asal_sekolah']) ?></p>
                            </div>
                            <span class="text-sm font-bold <?= $label ?>"><?= $persen ?>%</span>
                        </div>
                        <!-- Progress -->
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full <?= $color ?>" style="width:<?= $persen ?>%"></div>
                        </div>
                        <!-- Stats -->
                        <div class="grid grid-cols-6 gap-1.5 text-center text-[11px]">
                            <?php foreach (['total'=>['Total','text-gray-700','bg-gray-50'],'hadir'=>['Hadir','text-emerald-700','bg-emerald-50'],'sakit'=>['Sakit','text-amber-700','bg-amber-50'],'izin'=>['Izin','text-blue-700','bg-blue-50'],'alfa'=>['Alfa','text-red-700','bg-red-50'],'none'=>['Belum','text-gray-500','bg-gray-50']] as $k=>[$lbl,$tc,$bg]): ?>
                                <div class="<?= $bg ?> rounded-xl py-2">
                                    <div class="font-bold <?= $tc ?>"><?= (int) $s[$k] ?></div>
                                    <div class="text-gray-400 font-medium"><?= $lbl ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php elseif (!empty($_GET['kelas_id'])): ?>
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl text-sm">
            Kelas tidak ditemukan. Silakan pilih kelas yang valid.
        </div>
    <?php endif; ?>

</div>
