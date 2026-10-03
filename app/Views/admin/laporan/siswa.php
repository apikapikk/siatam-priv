<?php
$harian = ($mode === 'harian');
$isDetailBulanan = (!$harian && ($kelasId ?? 0) > 0);
$namaBulan = nama_bulan_indonesia((int)($bulan ?? date('n')));
$currentBulan = (int)($bulan ?? date('n'));
$currentTahun = (int)($tahun ?? date('Y'));

$prevBulan = $currentBulan === 1 ? 12 : $currentBulan - 1;
$prevTahunBulan = $currentBulan === 1 ? $currentTahun - 1 : $currentTahun;
$nextBulan = $currentBulan === 12 ? 1 : $currentBulan + 1;
$nextTahunBulan = $currentBulan === 12 ? $currentTahun + 1 : $currentTahun;

if ($harian) {
    $currentDate = $tanggal ?? date('Y-m-d');
    $prevDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
    $nextDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    $tanggalFormatted = tanggal_indonesia($currentDate, true);

    // Group report by meeting session
    $sessions = [];
    $totalRecords = count($report ?? []);
    $totalHadir = 0;
    $gradeCount = 0;
    $gradeScoreTotal = 0;
    $gradeMap = ['A' => 4, 'B' => 3, 'C' => 2, 'D' => 1];

    foreach ($report ?? [] as $r) {
        $pid = (int) $r['pertemuan_id'];
        if (!isset($sessions[$pid])) {
            $sessions[$pid] = [
                'pertemuan_id'   => $pid,
                'kelas_nama'     => $r['kelas_nama'],
                'tentor_nama'    => $r['tentor_nama'],
                'mata_pelajaran' => $r['mata_pelajaran'] ?? 'Reguler',
                'jam_mulai'      => $r['jam_mulai'],
                'jam_selesai'    => $r['jam_selesai'],
                'students'       => [],
            ];
        }
        $sessions[$pid]['students'][] = $r;

        if ($r['status_kehadiran'] === 'hadir') {
            $totalHadir++;
        }
        if (!empty($r['nilai_akademik']) && isset($gradeMap[$r['nilai_akademik']])) {
            $gradeScoreTotal += $gradeMap[$r['nilai_akademik']];
            $gradeCount++;
        }
    }

    $sesiCount = count($sessions);
    $persenHadir = $totalRecords > 0 ? round(($totalHadir / $totalRecords) * 100) : 0;
    $avgGradeText = 'A / Sangat Baik';
    if ($gradeCount > 0) {
        $gpa = $gradeScoreTotal / $gradeCount;
        if ($gpa >= 3.5) {
            $avgGradeText = 'A / Sangat Baik';
        } elseif ($gpa >= 2.5) {
            $avgGradeText = 'B / Baik';
        } elseif ($gpa >= 1.5) {
            $avgGradeText = 'C / Cukup';
        } else {
            $avgGradeText = 'D / Perlu Bimbingan';
        }
    }
} else {
    // Bulanan
    $meetingsInMonth = [];
    if ($isDetailBulanan) {
        foreach ($report['rows'] ?? [] as $r) {
            $pid = (int) $r['pertemuan_id'];
            if (!isset($meetingsInMonth[$pid])) {
                $meetingsInMonth[$pid] = [
                    'pertemuan_id'    => $pid,
                    'nomor_pertemuan' => $r['nomor_pertemuan'] ?? 1,
                    'tanggal'         => $r['tanggal'],
                    'tentor_nama'     => $r['tentor_nama'],
                    'jam_mulai'       => $r['jam_mulai'],
                    'jam_selesai'     => $r['jam_selesai'],
                    'students'        => [],
                ];
            }
            $meetingsInMonth[$pid]['students'][] = $r;
        }
    }
}
?>

<div class="w-full max-w-[430px] mx-auto min-h-screen bg-[#FAF9F7] flex flex-col relative pb-12">
    
    <!-- Bagian 1: Header & Tab Periode -->
    <header class="sticky top-0 z-30 bg-[#FAF9F7]/95 backdrop-blur-md px-2 pt-2 pb-3 border-b border-[#EAE8E3]">
        <div class="flex items-center justify-between mb-3.5">
            <?php if ($isDetailBulanan): ?>
                <a href="/admin/laporan/siswa?mode=bulanan&bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="w-10 h-10 rounded-full bg-white border border-[#E5E3DD] flex items-center justify-center text-[#1B4332] shadow-sm hover:bg-[#F4F3F0] active:scale-95 transition-all" aria-label="Kembali ke Daftar Kelas">
                    <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                </a>
                <div class="text-center flex-1 px-2">
                    <h1 class="text-base font-bold text-[#1B4332] leading-tight tracking-tight">Rekap Bulanan: <?= e($kelasDetail['nama'] ?? 'Kelas') ?></h1>
                    <p class="text-[11px] font-medium text-[#6B7280]">Periode: <?= $namaBulan ?> <?= $currentTahun ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-white border border-[#E5E3DD] flex items-center justify-center text-[#1B4332] shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                </div>
            <?php else: ?>
                <a href="/admin/laporan" class="w-10 h-10 rounded-full bg-white border border-[#E5E3DD] flex items-center justify-center text-[#1B4332] shadow-sm hover:bg-[#F4F3F0] active:scale-95 transition-all" aria-label="Kembali ke Pusat Laporan">
                    <span class="material-symbols-outlined text-[22px]">arrow_back</span>
                </a>
                <div class="text-center flex-1">
                    <h1 class="text-lg font-bold text-[#1B4332] tracking-tight">Laporan Siswa</h1>
                    <p class="text-[11px] font-medium text-[#6B7280]">Monitoring Kehadiran &amp; Nilai</p>
                </div>
                <div class="w-10 flex items-center justify-end">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$isDetailBulanan): ?>
            <!-- Tab Periode (Harian vs Bulanan) -->
            <div class="grid grid-cols-2 p-1 bg-[#ECEAE6] rounded-xl gap-1">
                <a href="/admin/laporan/siswa?mode=harian<?= isset($tanggal) ? '&tanggal=' . urlencode($tanggal) : '' ?>" class="py-2.5 rounded-lg text-xs font-bold <?= $harian ? 'text-white bg-[#1B4332] shadow-sm' : 'text-[#4B5563] hover:text-[#1B4332] hover:bg-white/60' ?> flex items-center justify-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px] <?= $harian ? 'fill' : '' ?>">today</span>
                    Harian
                </a>
                <a href="/admin/laporan/siswa?mode=bulanan&bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="py-2.5 rounded-lg text-xs font-bold <?= !$harian ? 'text-white bg-[#1B4332] shadow-sm' : 'text-[#4B5563] hover:text-[#1B4332] hover:bg-white/60' ?> flex items-center justify-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px] <?= !$harian ? 'fill' : '' ?>">calendar_month</span>
                    Bulanan
                </a>
            </div>
        <?php endif; ?>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 px-1 pt-4 space-y-4">

        <?php if ($harian): ?>
            <!-- ========================================== -->
            <!-- MODE HARIAN -->
            <!-- ========================================== -->

            <!-- Kotak Tanggal Interaktif -->
            <div class="bg-white rounded-2xl p-3.5 border border-[#EAE8E3] shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#F0F7F4] border border-[#D8EBE2] flex items-center justify-center text-[#1B4332]">
                        <span class="material-symbols-outlined text-[22px]">event_note</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold tracking-wider text-[#6B7280] uppercase">Tanggal Laporan</span>
                        <div class="text-xs font-bold text-[#1B4332] flex items-center gap-1">
                            <?= e($tanggalFormatted) ?>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <a href="/admin/laporan/siswa?mode=harian&tanggal=<?= $prevDate ?><?= $kelasId > 0 ? '&kelas_id=' . $kelasId : '' ?>" class="w-8 h-8 rounded-lg bg-[#FAF9F7] border border-[#E5E3DD] flex items-center justify-center text-[#4B5563] hover:text-[#1B4332] active:scale-95 transition-all" title="Hari Sebelumnya">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>
                    <!-- Quick Date Picker -->
                    <label class="w-8 h-8 rounded-lg bg-[#FAF9F7] border border-[#E5E3DD] flex items-center justify-center text-[#4B5563] hover:text-[#1B4332] active:scale-95 transition-all cursor-pointer relative" title="Pilih Tanggal">
                        <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                        <input type="date" value="<?= e($currentDate) ?>" onchange="window.location.href='/admin/laporan/siswa?mode=harian&tanggal='+this.value+'<?= $kelasId > 0 ? '&kelas_id=' . $kelasId : '' ?>'" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    </label>
                    <a href="/admin/laporan/siswa?mode=harian&tanggal=<?= $nextDate ?><?= $kelasId > 0 ? '&kelas_id=' . $kelasId : '' ?>" class="w-8 h-8 rounded-lg bg-[#FAF9F7] border border-[#E5E3DD] flex items-center justify-center text-[#4B5563] hover:text-[#1B4332] active:scale-95 transition-all" title="Hari Berikutnya">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Filter Kelas Dropdown -->
            <form method="GET" action="/admin/laporan/siswa" class="flex items-center gap-2">
                <input type="hidden" name="mode" value="harian">
                <input type="hidden" name="tanggal" value="<?= e($currentDate) ?>">
                <div class="relative flex-1">
                    <select name="kelas_id" onchange="this.form.submit()" class="w-full appearance-none bg-white border border-[#E5E3DD] rounded-xl px-3.5 py-2.5 pr-8 text-xs font-semibold text-zinc-800 shadow-xs focus:ring-1 focus:ring-[#1B4332] focus:border-[#1B4332]">
                        <option value="0">Semua Kelas</option>
                        <?php foreach ($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ((int)$kelasId === (int)$k['id']) ? 'selected' : '' ?>>
                                Kelas <?= e($k['nama']) ?> (<?= e($k['jenjang_nama'] ?? '') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-zinc-500">
                        <span class="material-symbols-outlined text-[18px]">expand_more</span>
                    </div>
                </div>
            </form>

            <!-- Quick Summary Stats Banner -->
            <div class="grid grid-cols-3 gap-2">
                <div class="bg-white rounded-xl p-2.5 border border-[#EAE8E3] text-center shadow-xs">
                    <p class="text-[10px] font-medium text-[#6B7280]">Kelas Berjalan</p>
                    <p class="text-base font-bold text-[#1B4332] mt-0.5"><?= $sesiCount ?> Sesi</p>
                </div>
                <div class="bg-[#F0F7F4] rounded-xl p-2.5 border border-[#D8EBE2] text-center shadow-xs">
                    <p class="text-[10px] font-medium text-[#2D6A4F]">Hadir (Presensi)</p>
                    <p class="text-base font-bold text-[#1B4332] mt-0.5"><?= $persenHadir ?>%</p>
                </div>
                <div class="bg-white rounded-xl p-2.5 border border-[#EAE8E3] text-center shadow-xs">
                    <p class="text-[10px] font-medium text-[#6B7280]">Rata-rata Nilai</p>
                    <p class="text-xs font-bold text-[#1B4332] mt-1"><?= $avgGradeText ?></p>
                </div>
            </div>

            <!-- List of Classes & Students -->
            <?php if (empty($sessions)): ?>
                <div class="bg-white rounded-2xl p-8 border border-[#EAE8E3] text-center shadow-xs space-y-2">
                    <div class="w-12 h-12 rounded-full bg-[#FAF9F7] text-zinc-400 mx-auto flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">event_busy</span>
                    </div>
                    <p class="text-sm font-bold text-zinc-700">Tidak Ada Presensi Pada Tanggal Ini</p>
                    <p class="text-xs text-zinc-500">Silakan gunakan tombol panah atau kalender di atas untuk melihat tanggal lain.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($sessions as $s): ?>
                        <section class="bg-white rounded-2xl border border-[#EAE8E3] shadow-sm overflow-hidden">
                            <!-- Section Header -->
                            <div class="bg-[#FAF9F7] px-4 py-3 border-b border-[#EAE8E3] flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-[#1B4332] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                                        <?= e(substr($s['kelas_nama'], 0, 3)) ?>
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-bold text-[#1B4332] leading-tight">Kelas <?= e($s['kelas_nama']) ?></h2>
                                        <p class="text-[11px] font-medium text-[#4B5563]">Tentor: <span class="font-semibold text-[#1B4332]"><?= e($s['tentor_nama']) ?></span> • <?= e($s['mata_pelajaran']) ?></p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-[#D8EBE2] text-[#1B4332] text-[10px] font-bold rounded-full border border-[#B5DACB]/50 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F]"></span> Selesai
                                </span>
                            </div>

                            <!-- Student Rows -->
                            <div class="p-4 divide-y divide-[#F4F3F0]">
                                <?php foreach ($s['students'] as $idx => $st): ?>
                                    <div class="<?= $idx > 0 ? 'pt-3.5' : '' ?> <?= $idx < count($s['students']) - 1 ? 'pb-3.5' : '' ?>">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-[#E8F3EE] border border-[#B5DACB] text-[#1B4332] font-bold flex items-center justify-center text-sm shadow-xs">
                                                    <?= e(strtoupper(substr($st['siswa_nama'], 0, 2))) ?>
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-bold text-[#1F2937]"><?= e($st['siswa_nama']) ?></h3>
                                                    <p class="text-[11px] text-[#6B7280]">NIS: <?= e($st['nis'] ?: '-') ?> • <?= e($st['asal_sekolah'] ?: '-') ?></p>
                                                </div>
                                            </div>
                                            <!-- Attendance Status Badge -->
                                            <?php if ($st['status_kehadiran'] === 'hadir'): ?>
                                                <span class="px-2.5 py-1 bg-[#F0F7F4] text-[#1B4332] text-xs font-bold rounded-lg border border-[#B5DACB] flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-[#2D6A4F]">check_circle</span>
                                                    Hadir
                                                </span>
                                            <?php elseif ($st['status_kehadiran'] === 'sakit'): ?>
                                                <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-lg border border-amber-200 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-amber-600">healing</span>
                                                    Sakit
                                                </span>
                                            <?php elseif ($st['status_kehadiran'] === 'izin'): ?>
                                                <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-xs font-bold rounded-lg border border-blue-200 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-blue-600">event_busy</span>
                                                    Izin
                                                </span>
                                            <?php elseif ($st['status_kehadiran'] === 'alfa'): ?>
                                                <span class="px-2.5 py-1 bg-red-50 text-red-800 text-xs font-bold rounded-lg border border-red-200 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-red-600">cancel</span>
                                                    Alfa
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 bg-zinc-100 text-zinc-600 text-xs font-bold rounded-lg border border-zinc-200 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                                    Belum
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Detailed Metrics: Nilai Sikap & Nilai Akademik -->
                                        <div class="mt-3 grid grid-cols-2 gap-2 bg-[#FAF9F7] p-2.5 rounded-xl border border-[#EAE8E3]">
                                            <div class="flex items-center justify-between bg-white px-3 py-1.5 rounded-lg border border-[#E5E3DD]">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[16px] text-[#2D6A4F]">psychology</span>
                                                    <span class="text-[11px] font-medium text-[#4B5563]">Nilai Sikap</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-0.5 bg-[#D8EBE2] text-[#1B4332] rounded-md"><?= e($st['nilai_sikap'] ?: '-') ?></span>
                                            </div>
                                            <div class="flex items-center justify-between bg-white px-3 py-1.5 rounded-lg border border-[#E5E3DD]">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="material-symbols-outlined text-[16px] text-[#2D6A4F]">school</span>
                                                    <span class="text-[11px] font-medium text-[#4B5563]">Akademik</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-0.5 bg-[#D8EBE2] text-[#1B4332] rounded-md"><?= e($st['nilai_akademik'] ?: '-') ?></span>
                                            </div>
                                        </div>

                                        <!-- Optional Feedback Snippet -->
                                        <?php if (!empty($st['catatan'])): ?>
                                            <div class="mt-2.5 flex items-start gap-1.5 text-[11px] text-[#4B5563] bg-[#F9F9F8] p-2 rounded-lg border border-[#F0EFEB]">
                                                <span class="material-symbols-outlined text-[15px] text-[#6B7280] mt-0.5">chat_bubble_outline</span>
                                                <span>Catatan Tentor: <?= e($st['catatan']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($isDetailBulanan): ?>
            <!-- ========================================== -->
            <!-- DETAIL REKAP BULANAN DARI FOLDER KELAS -->
            <!-- ========================================== -->

            <!-- Ringkasan Singkat Kelas (Context Banner) -->
            <div class="bg-white rounded-2xl p-4 border border-[#EAE8E3] shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-[#E8F5E9] text-[#1B4332] flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-[24px]">groups</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-bold text-[#1B4332]">Kelas <?= e($kelasDetail['nama'] ?? '') ?> <?= e($kelasDetail['program_nama'] ?? '') ?></span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#E8F5E9] text-[#1B4332]"><?= e($kelasDetail['jenjang_nama'] ?? 'Aktif') ?></span>
                        </div>
                        <p class="text-xs text-zinc-500 mt-0.5">Total Siswa: <span class="font-medium text-zinc-800"><?= count($report['summary_by_student'] ?? []) ?> Siswa</span></p>
                    </div>
                </div>
                <div class="text-right pl-2 shrink-0">
                    <span class="text-lg font-extrabold text-[#1B4332]"><?= count($meetingsInMonth) ?></span>
                    <p class="text-[10px] font-medium text-zinc-500">Pertemuan Selesai</p>
                </div>
            </div>

            <!-- Daftar Pertemuan Selesai Sebulan -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500">Daftar Pertemuan Selesai</h2>
                    <span class="text-xs font-semibold text-[#1B4332] bg-[#E8F3EE] px-2.5 py-0.5 rounded-full border border-emerald-900/10">
                        <?= $namaBulan ?> <?= $currentTahun ?>
                    </span>
                </div>

                <?php if (empty($meetingsInMonth)): ?>
                    <div class="bg-white rounded-2xl p-8 border border-[#EAE8E3] text-center shadow-xs space-y-2">
                        <span class="material-symbols-outlined text-zinc-400 text-3xl">event_busy</span>
                        <p class="text-sm font-bold text-zinc-700">Belum Ada Pertemuan Pada Periode Ini</p>
                        <p class="text-xs text-zinc-500">Belum ada sesi presensi yang tersimpan untuk kelas ini di bulan <?= $namaBulan ?> <?= $currentTahun ?>.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($meetingsInMonth as $m): ?>
                        <div class="bg-white rounded-2xl border border-[#EAE8E3] p-4 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Meeting Header -->
                            <div class="flex items-center justify-between border-b border-[#EAE8E3]/80 pb-3 mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-[#1B4332]"></div>
                                    <h3 class="text-sm font-bold text-[#1B4332]"><?= e(tanggal_indonesia($m['tanggal'], true)) ?></h3>
                                </div>
                                <div class="flex items-center gap-1 text-xs font-semibold text-[#1B4332] bg-[#E8F5E9]/70 px-2.5 py-0.5 rounded-md">
                                    <span class="material-symbols-outlined text-[14px]">person</span>
                                    <span>Tentor: <?= e($m['tentor_nama']) ?></span>
                                </div>
                            </div>

                            <!-- Attendance List in Meeting -->
                            <div class="space-y-2">
                                <?php foreach ($m['students'] as $st): ?>
                                    <div class="flex items-center justify-between py-1.5 px-2.5 rounded-xl bg-[#F4F3F0]/60">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-white border border-[#EAE8E3] flex items-center justify-center text-xs font-bold text-[#1B4332]">
                                                <?= e(strtoupper(substr($st['siswa_nama'], 0, 2))) ?>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-zinc-800"><?= e($st['siswa_nama']) ?></p>
                                                <p class="text-[10px] text-zinc-500">NIS: <?= e($st['nis'] ?? '-') ?></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <?php if (!empty($st['nilai_akademik'])): ?>
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 bg-[#D8EBE2] text-[#1B4332] rounded">Nilai: <?= e($st['nilai_akademik']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($st['status_kehadiran'] === 'hadir'): ?>
                                                <span class="flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                    Hadir
                                                </span>
                                            <?php elseif ($st['status_kehadiran'] === 'sakit'): ?>
                                                <span class="flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                    Sakit
                                                </span>
                                            <?php elseif ($st['status_kehadiran'] === 'izin'): ?>
                                                <span class="flex items-center gap-1 bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                    Izin
                                                </span>
                                            <?php else: ?>
                                                <span class="flex items-center gap-1 bg-red-50 text-red-800 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                    <?= e(ucfirst($st['status_kehadiran'] ?: 'Belum')) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Rekapitulasi Kehadiran Siswa Per Bulan -->
            <?php if (!empty($report['summary_by_student'])): ?>
                <div class="space-y-3 pt-2">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500">Akumulasi Kehadiran Siswa</h2>
                    <?php foreach ($report['summary_by_student'] as $s): ?>
                        <?php $persen = $s['total'] > 0 ? round(($s['hadir'] / $s['total']) * 100) : 0; ?>
                        <div class="bg-white rounded-2xl p-4 border border-[#EAE8E3] shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#E8F3EE] text-[#1B4332] flex items-center justify-center font-bold text-sm">
                                        <?= e(strtoupper(substr($s['siswa_nama'], 0, 2))) ?>
                                    </div>
                                    <div>
                                        <b class="text-sm text-zinc-900"><?= e($s['siswa_nama']) ?></b>
                                        <p class="text-[11px] text-zinc-500"><?= e($s['asal_sekolah']) ?></p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#E8F5E9] text-[#1B4332]">
                                    <?= $persen ?>% Hadir
                                </span>
                            </div>
                            <div class="grid grid-cols-5 gap-1.5 mt-3 text-center text-[10px]">
                                <div class="bg-[#FAF9F7] border border-zinc-200/60 rounded-lg py-1.5">
                                    <b class="text-xs text-zinc-800"><?= $s['total'] ?></b>
                                    <span class="block text-zinc-500 mt-0.5">Sesi</span>
                                </div>
                                <div class="bg-emerald-50 border border-emerald-200/60 rounded-lg py-1.5 text-emerald-800">
                                    <b class="text-xs"><?= $s['hadir'] ?></b>
                                    <span class="block text-emerald-700 mt-0.5">Hadir</span>
                                </div>
                                <div class="bg-amber-50 border border-amber-200/60 rounded-lg py-1.5 text-amber-800">
                                    <b class="text-xs"><?= $s['sakit'] ?></b>
                                    <span class="block text-amber-700 mt-0.5">Sakit</span>
                                </div>
                                <div class="bg-blue-50 border border-blue-200/60 rounded-lg py-1.5 text-blue-800">
                                    <b class="text-xs"><?= $s['izin'] ?></b>
                                    <span class="block text-blue-700 mt-0.5">Izin</span>
                                </div>
                                <div class="bg-red-50 border border-red-200/60 rounded-lg py-1.5 text-red-800">
                                    <b class="text-xs"><?= $s['alfa'] ?></b>
                                    <span class="block text-red-700 mt-0.5">Alfa</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Sticky Bottom Unduh Rekap Button -->
            <div class="pt-3">
                <a href="/admin/laporan/siswa-bulanan/export-csv?kelas_id=<?= $kelasId ?>&bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="w-full py-3.5 px-4 bg-white hover:bg-[#E8F5E9] border-2 border-[#1B4332] text-[#1B4332] font-bold text-sm rounded-xl shadow-sm flex items-center justify-center gap-2 active:scale-[0.99] transition-all">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    <span>Unduh Rekap Kelas (CSV/Excel)</span>
                </a>
            </div>

        <?php else: ?>
            <!-- ========================================== -->
            <!-- MODE BULANAN: DAFTAR FOLDER KELAS -->
            <!-- ========================================== -->

            <!-- Periode & Filter Keterangan -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-1.5">
                        <a href="/admin/laporan/siswa?mode=bulanan&bulan=<?= $prevBulan ?>&tahun=<?= $prevTahunBulan ?>" class="w-7 h-7 rounded-lg bg-white border border-[#E5E3DD] flex items-center justify-center text-zinc-600 hover:text-[#1B4332]" title="Bulan Sebelumnya">
                            <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                        </a>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100/70 px-2.5 py-1 rounded-full">
                            <?= $namaBulan ?> <?= $currentTahun ?>
                        </span>
                        <a href="/admin/laporan/siswa?mode=bulanan&bulan=<?= $nextBulan ?>&tahun=<?= $nextTahunBulan ?>" class="w-7 h-7 rounded-lg bg-white border border-[#E5E3DD] flex items-center justify-center text-zinc-600 hover:text-[#1B4332]" title="Bulan Berikutnya">
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    </div>
                    <!-- Quick Month Selector -->
                    <form method="GET" action="/admin/laporan/siswa" class="flex items-center gap-1">
                        <input type="hidden" name="mode" value="bulanan">
                        <select name="bulan" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg border-[#E5E3DD] bg-white py-1 px-2 text-zinc-700">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $currentBulan === $m ? 'selected' : '' ?>><?= nama_bulan_indonesia($m) ?></option>
                            <?php endfor; ?>
                        </select>
                        <input type="hidden" name="tahun" value="<?= $currentTahun ?>">
                    </form>
                </div>
                <p class="text-xs text-neutral-600 font-medium leading-relaxed mt-1">
                    Pilih kelas untuk melihat rekapitulasi kehadiran sebulan penuh
                </p>
            </div>

            <!-- Daftar Folder Kelas Bulanan -->
            <div class="space-y-3">
                <?php if (empty($kelasList)): ?>
                    <div class="bg-white rounded-2xl p-8 border border-neutral-200 text-center text-zinc-500">
                        Belum ada kelas yang terdaftar.
                    </div>
                <?php else: ?>
                    <?php foreach ($kelasList as $k): ?>
                        <?php $pertemuanCount = (int)($meetingCounts[$k['id']] ?? 0); ?>
                        <a href="/admin/laporan/siswa?mode=bulanan&kelas_id=<?= $k['id'] ?>&bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="group bg-white rounded-2xl p-4 border border-neutral-200/80 shadow-[0_2px_8px_rgba(0,0,0,0.03)] hover:border-[#1B4332]/40 hover:shadow-md active:scale-[0.99] transition-all flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-[#E8F1EC] text-[#1B4332] flex items-center justify-center flex-shrink-0 group-hover:bg-[#1B4332] group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[26px]">folder</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-neutral-900 text-sm tracking-tight">Kelas <?= e($k['nama']) ?></h3>
                                        <span class="text-[10px] font-semibold bg-neutral-100 text-neutral-600 px-1.5 py-0.5 rounded"><?= e($k['jenjang_nama'] ?? 'Reguler') ?></span>
                                    </div>
                                    <p class="text-xs text-neutral-500 font-medium mt-0.5">Total <?= $pertemuanCount ?> Pertemuan Bulan Ini</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-neutral-400 group-hover:text-[#1B4332] group-hover:translate-x-0.5 transition-all">
                                <span class="material-symbols-outlined text-[22px]">chevron_right</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </main>

</div>
