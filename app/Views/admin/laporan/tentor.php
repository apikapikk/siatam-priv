<?php
$harian = ($mode === 'harian');
$currentBulan = (int)($bulan ?? date('n'));
$currentTahun = (int)($tahun ?? date('Y'));
$namaBulan = nama_bulan_indonesia($currentBulan);

$prevBulan = $currentBulan === 1 ? 12 : $currentBulan - 1;
$prevTahunBulan = $currentBulan === 1 ? $currentTahun - 1 : $currentTahun;
$nextBulan = $currentBulan === 12 ? 1 : $currentBulan + 1;
$nextTahunBulan = $currentBulan === 12 ? $currentTahun + 1 : $currentTahun;

if ($harian) {
    $currentDate = $tanggal ?? date('Y-m-d');
    $prevDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
    $nextDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    $tanggalFormatted = tanggal_indonesia($currentDate, true);

    $totalSesiHariIni = count($report ?? []);
    $tentorHadirCount = count(array_unique(array_column($report ?? [], 'nama_lengkap')));
} else {
    $totalSesiBulanIni = array_sum(array_column($report ?? [], 'total_sesi'));
    $tentorAktifCount = count(array_filter($report ?? [], fn($r) => (int)($r['total_sesi'] ?? 0) > 0));
}
?>

<div class="w-full max-w-[430px] mx-auto min-h-screen bg-[#FAF9F7] flex flex-col relative pb-12">
    
    <!-- Bagian 1: Header & Tab Mode -->
    <header class="sticky top-0 z-30 bg-[#FAF9F7]/95 backdrop-blur-md px-2 pt-2 pb-3 border-b border-stone-200/50">
        <div class="flex items-center justify-between mb-3.5">
            <a href="/admin/laporan" class="w-10 h-10 rounded-full bg-white border border-stone-200/80 flex items-center justify-center text-stone-700 hover:bg-stone-50 active:scale-95 transition-all shadow-sm" aria-label="Kembali ke Pusat Laporan">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div class="text-center flex-1">
                <h1 class="text-base font-bold text-stone-900 tracking-tight">Laporan Tentor</h1>
                <p class="text-[11px] font-medium text-stone-500">
                    <?= $harian ? 'Log Pengajar &amp; Verifikasi Honor' : 'Rekap Sesi &amp; Verifikasi Gaji' ?>
                </p>
            </div>
            <div class="w-10 flex items-center justify-end">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
            </div>
        </div>

        <!-- Tab Mode: Harian vs Bulanan -->
        <div class="grid grid-cols-2 p-1 bg-stone-200/70 rounded-xl gap-1">
            <a href="/admin/laporan/tentor?mode=harian<?= isset($tanggal) ? '&tanggal=' . urlencode($tanggal) : '' ?>" class="py-2.5 rounded-lg text-xs font-bold <?= $harian ? 'text-white bg-[#1B4332] shadow-sm' : 'text-stone-600 hover:text-[#1B4332] hover:bg-white/60' ?> flex items-center justify-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-[17px] <?= $harian ? 'fill' : '' ?>">today</span>
                <span>Harian</span>
            </a>
            <a href="/admin/laporan/tentor?mode=bulanan&bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="py-2.5 rounded-lg text-xs font-bold <?= !$harian ? 'text-white bg-[#1B4332] shadow-sm' : 'text-stone-600 hover:text-[#1B4332] hover:bg-white/60' ?> flex items-center justify-center gap-1.5 transition-all">
                <span class="material-symbols-outlined text-[17px] <?= !$harian ? 'fill' : '' ?>">calendar_month</span>
                <span>Bulanan</span>
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 px-1 pt-4 space-y-4">

        <?php if ($harian): ?>
            <!-- ========================================== -->
            <!-- MODE HARIAN TENTOR -->
            <!-- ========================================== -->

            <!-- Bagian 2: Pemilih Tanggal Harian -->
            <section class="bg-white rounded-2xl p-3.5 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#E8F3EE] flex items-center justify-center text-[#1B4332]">
                        <span class="material-symbols-outlined text-[22px]">calendar_today</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">Tanggal Log Selesai</span>
                        <div class="text-xs font-bold text-stone-800"><?= e($tanggalFormatted) ?></div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <a href="/admin/laporan/tentor?mode=harian&tanggal=<?= $prevDate ?>" aria-label="Hari Sebelumnya" class="w-8 h-8 rounded-lg bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-[#E8F3EE] hover:text-[#1B4332] active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>
                    <!-- Quick Date Picker -->
                    <label class="w-8 h-8 rounded-lg bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-[#E8F3EE] hover:text-[#1B4332] active:scale-95 transition-all cursor-pointer relative" title="Pilih Tanggal">
                        <span class="material-symbols-outlined text-[18px]">event</span>
                        <input type="date" value="<?= e($currentDate) ?>" onchange="window.location.href='/admin/laporan/tentor?mode=harian&tanggal='+this.value" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                    </label>
                    <a href="/admin/laporan/tentor?mode=harian&tanggal=<?= $nextDate ?>" aria-label="Hari Berikutnya" class="w-8 h-8 rounded-lg bg-stone-100 flex items-center justify-center text-stone-600 hover:bg-[#E8F3EE] hover:text-[#1B4332] active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                </div>
            </section>

            <!-- Bagian 3: Ringkasan Sesi Hari Ini -->
            <section class="grid grid-cols-2 gap-3">
                <!-- Kartu 1: Total Sesi Selesai -->
                <div class="bg-white rounded-2xl p-3.5 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] relative overflow-hidden flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-medium text-stone-500">Total Sesi Selesai</span>
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#1B4332] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">task_alt</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-bold text-[#1B4332]"><?= $totalSesiHariIni ?></span>
                        <span class="text-xs font-semibold text-stone-500">Sesi Berjalan</span>
                    </div>
                    <div class="mt-2 text-[10px] font-medium text-emerald-700 bg-emerald-50/80 px-2 py-0.5 rounded-full w-fit flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span><?= $totalSesiHariIni > 0 ? '100% Selesai Tepat Waktu' : 'Tidak Ada Sesi' ?></span>
                    </div>
                </div>

                <!-- Kartu 2: Tentor Hadir -->
                <div class="bg-white rounded-2xl p-3.5 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] relative overflow-hidden flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-medium text-stone-500">Tentor Hadir</span>
                        <div class="w-7 h-7 rounded-lg bg-[#E8F3EE] text-[#1B4332] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">group</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-bold text-[#1B4332]"><?= $tentorHadirCount ?></span>
                        <span class="text-xs font-semibold text-stone-500">Pengajar</span>
                    </div>
                    <div class="mt-2 text-[10px] font-medium text-stone-600 bg-stone-100 px-2 py-0.5 rounded-full w-fit flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1B4332]"></span>
                        <span>Semua Terverifikasi</span>
                    </div>
                </div>
            </section>

            <!-- Bagian 4: Daftar Log Pengajar Selesai Mengajar -->
            <section class="space-y-3">
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-stone-700">Log Pengajar Hari Ini</h2>
                        <p class="text-[11px] text-stone-500">Daftar presensi dan riwayat sesi tentor</p>
                    </div>
                    <span class="text-[11px] font-semibold text-[#1B4332] bg-[#E8F3EE] px-2.5 py-1 rounded-full">
                        Real-time
                    </span>
                </div>

                <?php if (empty($report)): ?>
                    <div class="bg-white rounded-2xl p-8 border border-stone-200/80 text-center shadow-xs space-y-2">
                        <span class="material-symbols-outlined text-stone-400 text-3xl">event_busy</span>
                        <p class="text-sm font-bold text-stone-700">Tidak Ada Log Sesi Mengajar</p>
                        <p class="text-xs text-stone-500">Tidak ada pertemuan tentor yang terjadwal pada <?= e($tanggalFormatted) ?>.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($report as $r): ?>
                            <div class="bg-white rounded-2xl p-3.5 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:border-[#1B4332]/40 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <!-- Round Avatar -->
                                    <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-[#E8F3EE] flex-shrink-0 bg-stone-100 flex items-center justify-center shadow-inner">
                                        <?php if (!empty($r['foto']) && file_exists(__DIR__ . '/../../../../public/' . $r['foto'])): ?>
                                            <img alt="<?= e($r['nama_lengkap']) ?>" class="w-full h-full object-cover" src="/<?= e($r['foto']) ?>" />
                                        <?php else: ?>
                                            <span class="text-sm font-bold text-[#1B4332]"><?= e(strtoupper(substr($r['nama_lengkap'], 0, 2))) ?></span>
                                        <?php endif; ?>
                                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                    </div>
                                    <!-- Info Tentor -->
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-sm font-bold text-stone-900"><?= e($r['nama_lengkap']) ?></span>
                                            <span class="text-[10px] font-semibold bg-stone-100 text-stone-600 px-1.5 py-0.5 rounded">
                                                <?= e($r['mata_pelajaran'] ?? 'Reguler') ?>
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-stone-600 font-medium flex items-center gap-1.5 flex-wrap">
                                            <span class="font-semibold text-stone-800">Kelas: <?= e($r['kelas_nama']) ?></span>
                                            <span class="text-stone-300">•</span>
                                            <span>Jam: <?= substr($r['jam_mulai'], 0, 5) ?> - <?= substr($r['jam_selesai'], 0, 5) ?></span>
                                        </p>
                                        <div class="text-[10px] text-stone-500 flex items-center gap-1">
                                            <span>Status:</span>
                                            <span class="text-emerald-700 font-semibold">
                                                Selesai (Presensi <?= (int)$r['total_hadir'] ?>/<?= (int)$r['total_siswa'] ?> Siswa)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Status Kanan -->
                                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/60 shadow-xs">
                                        <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                        Hadir
                                    </span>
                                    <span class="text-[10px] text-stone-400 font-medium">Honor OK</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php else: ?>
            <!-- ========================================== -->
            <!-- MODE BULANAN TENTOR -->
            <!-- ========================================== -->

            <!-- Bagian 2: Pemilih Bulan Aktif -->
            <section class="space-y-3">
                <div class="bg-white rounded-2xl p-3 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] flex items-center justify-between">
                    <a href="/admin/laporan/tentor?mode=bulanan&bulan=<?= $prevBulan ?>&tahun=<?= $prevTahunBulan ?>" class="w-8 h-8 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center hover:bg-[#E8F3EE] hover:text-[#1B4332] transition-colors active:scale-95" title="Bulan Sebelumnya">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[#E8F3EE] flex items-center justify-center text-[#1B4332]">
                            <span class="material-symbols-outlined text-base">event_note</span>
                        </div>
                        <div class="text-center">
                            <span class="text-sm font-bold text-gray-900 tracking-tight"><?= $namaBulan ?> <?= $currentTahun ?></span>
                            <span class="block text-[10px] font-medium text-emerald-700">Periode Penggajian Berjalan</span>
                        </div>
                    </div>
                    <a href="/admin/laporan/tentor?mode=bulanan&bulan=<?= $nextBulan ?>&tahun=<?= $nextTahunBulan ?>" class="w-8 h-8 rounded-xl bg-stone-100 text-stone-700 flex items-center justify-center hover:bg-[#E8F3EE] hover:text-[#1B4332] transition-colors active:scale-95" title="Bulan Berikutnya">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </a>
                </div>

                <!-- Quick Summary Cards for Payroll Context -->
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div class="bg-[#FAF4ED] border border-[#E9DFD2] rounded-2xl p-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#E2D2BE] flex items-center justify-center text-[#70481F]">
                            <span class="material-symbols-outlined text-lg">hourglass_top</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-semibold text-gray-500">Total Sesi Mengajar</span>
                            <span class="text-sm font-extrabold text-gray-900"><?= $totalSesiBulanIni ?> Sesi</span>
                        </div>
                    </div>
                    <div class="bg-[#E8F3EE] border border-[#D0E6DB] rounded-2xl p-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#C1DFD0] flex items-center justify-center text-[#1B4332]">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-semibold text-gray-500">Pengajar Aktif</span>
                            <span class="text-sm font-extrabold text-[#1B4332]"><?= $tentorAktifCount ?> Tentor</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bagian 3: Daftar Rekap Sesi Tentor untuk Gaji -->
            <section class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500">Akumulasi Sesi &amp; Verifikasi Gaji</h2>
                    <span class="text-[11px] font-semibold text-[#1B4332] bg-[#E8F3EE] px-2.5 py-0.5 rounded-full">
                        Total: <?= count($report ?? []) ?> Pengajar
                    </span>
                </div>

                <!-- Daftar Baris Kartu Tentor -->
                <div class="space-y-3">
                    <?php if (empty($report)): ?>
                        <div class="bg-white rounded-2xl p-8 border border-stone-200 text-center text-stone-500">
                            Belum ada rekap sesi tentor pada periode ini.
                        </div>
                    <?php else: ?>
                        <?php foreach ($report as $r): ?>
                            <?php
                            $tid = (int)$r['id'];
                            $tentorBreakdown = $breakdowns[$tid] ?? [];
                            $sesiTotal = (int)$r['total_sesi'];
                            ?>
                            <div class="bg-white rounded-2xl p-4 border border-stone-200/80 shadow-[0_2px_10px_rgba(0,0,0,0.03)] hover:border-[#1B4332]/40 transition-all">
                                <div class="flex items-start justify-between gap-3">
                                    <!-- Profil & Nama -->
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-tr from-emerald-700 to-teal-500 p-0.5 shadow-sm">
                                                <?php if (!empty($r['foto']) && file_exists(__DIR__ . '/../../../../public/' . $r['foto'])): ?>
                                                    <img alt="<?= e($r['nama_lengkap']) ?>" class="w-full h-full object-cover rounded-full bg-white" src="/<?= e($r['foto']) ?>" />
                                                <?php else: ?>
                                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-sm font-bold text-[#1B4332]">
                                                        <?= e(strtoupper(substr($r['nama_lengkap'], 0, 2))) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 <?= $sesiTotal > 0 ? 'bg-emerald-500' : 'bg-amber-400' ?> border-2 border-white rounded-full"></span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="text-sm font-bold text-gray-900 tracking-tight"><?= e($r['nama_lengkap']) ?></h3>
                                                <span class="text-[10px] font-semibold text-emerald-800 bg-[#E8F3EE] px-1.5 py-0.5 rounded">
                                                    <?= $sesiTotal ?> Sesi
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-gray-500 font-medium"><?= e($r['asal_universitas']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Tombol Aksi Verifikasi -->
                                    <button type="button" onclick="toggleVerify(this)" class="verify-btn shrink-0 px-3.5 py-1.5 <?= $sesiTotal > 0 ? 'bg-[#1B4332] text-white hover:bg-[#2D6A4F]' : 'bg-stone-100 text-stone-600 border border-stone-200' ?> text-xs font-bold rounded-xl shadow-xs active:scale-95 transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">verified</span>
                                        <span class="btn-label"><?= $sesiTotal > 0 ? 'Verifikasi' : 'Terverifikasi' ?></span>
                                    </button>
                                </div>

                                <!-- Rincian Sesi Bulanan -->
                                <div class="mt-3 pt-3 border-t border-stone-200/70 bg-[#FAF9F7]/80 rounded-xl p-2.5">
                                    <div class="text-[11px] text-gray-700 leading-relaxed font-medium flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <?php if (!empty($tentorBreakdown)): ?>
                                            <?php foreach ($tentorBreakdown as $bIdx => $b): ?>
                                                <?php if ($bIdx > 0): ?>
                                                    <span class="text-gray-300">|</span>
                                                <?php endif; ?>
                                                <span class="inline-flex items-center gap-1 font-semibold text-gray-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#1B4332]"></span>
                                                    <?= e($b['jenjang_nama']) ?> (<?= e($b['kelas_nama']) ?>): <strong class="text-gray-900 font-bold"><?= (int)$b['total_sesi'] ?></strong>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-stone-400 italic">Belum ada rincian sesi mengajar</span>
                                        <?php endif; ?>
                                        <span class="text-gray-300">|</span>
                                        <span class="text-stone-600 font-medium">Total: <strong><?= number_format((float)$r['total_jam'], 1) ?> Jam</strong></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Tombol Aksi Cepat Cetak / Export Rekap Gaji -->
                <div class="pt-3">
                    <a href="/admin/laporan/tentor-bulanan/export-csv?bulan=<?= $currentBulan ?>&tahun=<?= $currentTahun ?>" class="w-full py-3.5 px-4 bg-white border-2 border-[#1B4332] text-[#1B4332] hover:bg-[#E8F3EE] text-xs font-bold rounded-2xl flex items-center justify-center gap-2 transition-all shadow-sm active:scale-[0.99]">
                        <span class="material-symbols-outlined text-base">receipt_long</span>
                        <span>Kirim / Ekspor Slip Gaji Tentor (CSV)</span>
                    </a>
                </div>
            </section>

            <script>
            function toggleVerify(btn) {
                const label = btn.querySelector('.btn-label');
                if (label.innerText.trim() === 'Verifikasi') {
                    label.innerText = 'Terverifikasi';
                    btn.classList.remove('bg-[#1B4332]', 'text-white', 'hover:bg-[#2D6A4F]');
                    btn.classList.add('bg-stone-100', 'text-stone-700', 'border', 'border-stone-300');
                } else {
                    label.innerText = 'Verifikasi';
                    btn.classList.add('bg-[#1B4332]', 'text-white', 'hover:bg-[#2D6A4F]');
                    btn.classList.remove('bg-stone-100', 'text-stone-700', 'border', 'border-stone-300');
                }
            }
            </script>

        <?php endif; ?>

    </main>

</div>
