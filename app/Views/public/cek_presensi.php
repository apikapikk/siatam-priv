<div class="flex flex-col gap-5 w-full">
    <!-- Header Page -->
    <div class="flex flex-col gap-1">
        <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Cek Presensi & Riwayat Belajar Siswa</h1>
        <p class="text-xs text-gray-500">Pencarian terbuka riwayat kehadiran & nilai untuk orang tua / wali murid</p>
    </div>

    <!-- Search Card Container -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200/80">
        <form action="/cek-presensi" method="GET" class="flex flex-col gap-3">
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Cari Nama Siswa atau Asal Sekolah</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">search</span>
                    <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Masukkan nama lengkap siswa..." required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
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
            <!-- Filter Options for Multiple Students -->
            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-gray-700">Hasil Pencarian Siswa:</label>
                <div class="flex items-center gap-2 flex-wrap">
                    <?php foreach ($siswaResult as $s): ?>
                        <a href="/cek-presensi?q=<?= urlencode($keyword) ?>&siswa_id=<?= $s['id'] ?>" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all <?= ((int) $selectedSiswaId === (int) $s['id']) ? 'bg-[#324f47] text-white shadow-xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-[#e8f0ec]' ?>">
                            <?= e($s['nama_lengkap']) ?> (<?= e($s['asal_sekolah']) ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- List Presensi -->
            <div class="flex flex-col gap-3">
                <h2 class="text-base font-bold text-[#2D3E39]">Riwayat Presensi & Evaluasi:</h2>
                <?php if (empty($presensiList)): ?>
                    <div class="bg-white rounded-2xl p-6 text-center text-gray-500 border border-gray-200/80 text-sm">
                        Belum ada catatan presensi untuk siswa ini.
                    </div>
                <?php else: ?>
                    <?php foreach ($presensiList as $p): ?>
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col gap-2.5">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <h3 class="text-sm font-bold text-gray-900">
                                    Pertemuan #<?= e($p['nomor_pertemuan']) ?> — Kelas <?= e($p['kelas_nama']) ?> (<?= e($p['jenjang_nama']) ?>)
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase <?= $p['status_kehadiran'] === 'hadir' ? 'bg-emerald-50 text-emerald-800' : ($p['status_kehadiran'] === 'izin' ? 'bg-blue-50 text-blue-800' : 'bg-red-50 text-red-700') ?>">
                                    <?= e($p['status_kehadiran']) ?>
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-[#324f47]">schedule</span>
                                Tanggal: <?= e($p['tanggal']) ?> (<?= substr($p['jam_mulai'], 0, 5) ?>-<?= substr($p['jam_selesai'], 0, 5) ?> WIB) • Tentor: <?= e($p['tentor_nama']) ?>
                            </p>

                            <?php if ($p['nilai_sikap'] || $p['nilai_akademik']): ?>
                                <div class="flex items-center gap-4 text-xs bg-[#FAF9F7] p-2.5 rounded-xl border border-gray-100">
                                    <span>Nilai Sikap: <strong class="text-gray-900"><?= e($p['nilai_sikap'] ?: '-') ?></strong></span>
                                    <span>Nilai Akademik: <strong class="text-gray-900"><?= e($p['nilai_akademik'] ?: '-') ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($p['catatan'])): ?>
                                <p class="text-xs text-gray-600 italic bg-amber-50/60 p-2.5 rounded-xl border border-amber-100">
                                    Catatan Tentor: "<?= e($p['catatan']) ?>"
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
