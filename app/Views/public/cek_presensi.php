<?php
// Helper badge status kehadiran
function badge_status(string $status): string
{
    return match ($status) {
        'hadir' => 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200',
        'sakit' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'izin'  => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'alfa'  => 'bg-red-50 text-red-700 ring-1 ring-red-200',
        default => 'bg-gray-100 text-gray-500 ring-1 ring-gray-200',
    };
}

function label_status(string $status): string
{
    return match ($status) {
        'hadir' => 'Hadir',
        'sakit' => 'Sakit',
        'izin'  => 'Izin',
        'alfa'  => 'Alfa',
        'none'  => 'Belum Diisi',
        default => ucfirst($status),
    };
}
?>
<div class="flex flex-col gap-5 w-full">
    <!-- Header Page -->
    <div class="flex flex-col gap-1">
        <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Cek Presensi &amp; Riwayat Belajar Siswa</h1>
        <p class="text-xs text-gray-500">Pencarian terbuka riwayat kehadiran &amp; nilai untuk orang tua / wali murid</p>
    </div>

    <!-- Search Card Container -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200/80">
        <form action="/cek-presensi" method="GET" class="flex flex-col gap-3">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Cari NIS, Nama Siswa, atau Asal Sekolah</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">search</span>
                    <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Masukkan NIS atau nama lengkap siswa..." required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
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
                    <input type="number" name="tahun" value="<?= e((string) $tahun) ?>" min="2020" max="2099" class="w-full px-3 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                </div>
            </div>
            <button type="submit" class="w-full py-2.5 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-xs transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">find_in_page</span>
                Cari Data Siswa
            </button>
        </form>
    </div>

    <?php if (!empty($keyword)): ?>
        <?php if (empty($siswaResult)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Siswa dengan kata kunci "<strong><?= e($keyword) ?></strong>" tidak ditemukan.
            </div>
        <?php else: ?>

            <!-- Chip Selector — Multiple Student Results -->
            <?php if (count($siswaResult) > 1): ?>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-700">Hasil Pencarian Siswa:</label>
                    <div class="flex items-center gap-2 flex-wrap">
                        <?php foreach ($siswaResult as $s): ?>
                            <a href="/cek-presensi?q=<?= urlencode($keyword) ?>&siswa_id=<?= $s['id'] ?>&bulan=<?= (int) $bulan ?>&tahun=<?= (int) $tahun ?>"
                               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all <?= ((int) $selectedSiswaId === (int) $s['id']) ? 'bg-[#324f47] text-white shadow-xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-[#e8f0ec]' ?>">
                                <?= e($s['nama_lengkap']) ?><?= !empty($s['nis']) ? ' — ' . e($s['nis']) : '' ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Student Identity Card -->
            <?php if ($selectedSiswa): ?>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-[#e8f0ec] flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[#324f47] text-[22px]">person</span>
                    </div>
                    <div class="flex flex-col gap-0.5 flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate"><?= e($selectedSiswa['nama_lengkap']) ?></p>
                        <div class="flex items-center gap-3 flex-wrap">
                            <?php if (!empty($selectedSiswa['nis'])): ?>
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">badge</span>
                                    NIS <?= e($selectedSiswa['nis']) ?>
                                </span>
                            <?php endif; ?>
                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">school</span>
                                <?= e($selectedSiswa['asal_sekolah']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[11px] text-gray-400 font-medium"><?= date('F', mktime(0, 0, 0, (int) $bulan, 1)) ?> <?= (int) $tahun ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Ringkasan Kehadiran -->
            <?php
                $total  = (int) ($summary['total'] ?? 0);
                $hadir  = (int) ($summary['hadir'] ?? 0);
                $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
            ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#2D3E39]">Ringkasan Kehadiran</h2>
                    <span class="text-xs font-semibold <?= $persen >= 75 ? 'text-emerald-700' : ($persen >= 50 ? 'text-amber-600' : 'text-red-600') ?>">
                        <?= $persen ?>% Hadir
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full transition-all <?= $persen >= 75 ? 'bg-emerald-500' : ($persen >= 50 ? 'bg-amber-400' : 'bg-red-400') ?>"
                         style="width: <?= $persen ?>%"></div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                    <?php
                    $statItems = [
                        'total' => ['label' => 'Total',  'color' => 'text-gray-800',   'bg' => 'bg-gray-50'],
                        'hadir' => ['label' => 'Hadir',  'color' => 'text-emerald-700','bg' => 'bg-emerald-50'],
                        'sakit' => ['label' => 'Sakit',  'color' => 'text-amber-700',  'bg' => 'bg-amber-50'],
                        'izin'  => ['label' => 'Izin',   'color' => 'text-blue-700',   'bg' => 'bg-blue-50'],
                        'alfa'  => ['label' => 'Alfa',   'color' => 'text-red-700',    'bg' => 'bg-red-50'],
                        'none'  => ['label' => 'Belum',  'color' => 'text-gray-500',   'bg' => 'bg-gray-50'],
                    ];
                    foreach ($statItems as $key => $meta):
                    ?>
                        <div class="<?= $meta['bg'] ?> rounded-xl p-3 text-center border border-gray-100">
                            <div class="text-lg font-bold <?= $meta['color'] ?>"><?= format_number((int) ($summary[$key] ?? 0)) ?></div>
                            <div class="text-[11px] text-gray-500 font-medium"><?= $meta['label'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- List Presensi -->
            <div class="flex flex-col gap-3">
                <h2 class="text-base font-bold text-[#2D3E39]">Riwayat Presensi &amp; Evaluasi:</h2>
                <?php if (empty($presensiList)): ?>
                    <div class="bg-white rounded-2xl p-6 text-center text-gray-500 border border-gray-200/80 text-sm">
                        Belum ada catatan presensi untuk siswa ini pada periode ini.
                    </div>
                <?php else: ?>
                    <?php foreach ($presensiList as $p): ?>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-2.5">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <h3 class="text-sm font-bold text-gray-900">
                                    Pertemuan #<?= e($p['nomor_pertemuan']) ?> — Kelas <?= e($p['kelas_nama']) ?> <span class="font-normal text-gray-500">(<?= e($p['jenjang_nama']) ?>)</span>
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase <?= badge_status($p['status_kehadiran'] ?? 'none') ?>">
                                    <?= label_status($p['status_kehadiran'] ?? 'none') ?>
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-[#324f47]">schedule</span>
                                <?= e($p['tanggal']) ?> (<?= substr($p['jam_mulai'], 0, 5) ?>–<?= substr($p['jam_selesai'], 0, 5) ?> WIB) &bull; Tentor: <?= e($p['tentor_nama']) ?>
                            </p>

                            <?php if ($p['nilai_sikap'] || $p['nilai_akademik']): ?>
                                <div class="flex items-center gap-4 text-xs bg-[#FAF9F7] p-2.5 rounded-xl border border-gray-100">
                                    <span>
                                        Sikap:
                                        <strong class="text-gray-900"><?= e($p['nilai_sikap'] ?: '-') ?></strong>
                                        <?php if ($p['nilai_sikap']): ?>
                                            <span class="text-gray-400 font-normal">(<?= konversi_nilai_huruf($p['nilai_sikap']) ?>)</span>
                                        <?php endif; ?>
                                    </span>
                                    <span>
                                        Akademik:
                                        <strong class="text-gray-900"><?= e($p['nilai_akademik'] ?: '-') ?></strong>
                                        <?php if ($p['nilai_akademik']): ?>
                                            <span class="text-gray-400 font-normal">(<?= konversi_nilai_huruf($p['nilai_akademik']) ?>)</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($p['catatan'])): ?>
                                <p class="text-xs text-gray-600 italic bg-amber-50/60 p-2.5 rounded-xl border border-amber-100">
                                    <span class="not-italic font-semibold text-amber-700">Catatan Tentor:</span> "<?= e($p['catatan']) ?>"
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    <?php endif; ?>
</div>
