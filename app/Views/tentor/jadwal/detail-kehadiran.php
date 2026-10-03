<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center gap-3">
        <a href="/tentor/jadwal/kelas/<?= (int) $pertemuan['kelas_id'] ?>" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-xl font-bold text-[#2D3E39]">Detail Kehadiran</h1>
    </div>

    <section class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
            <div class="w-11 h-11 rounded-xl bg-[#e8f0ec] text-[#324f47] flex items-center justify-center">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#2D3E39]">Pertemuan <?= e($pertemuan['nomor_pertemuan']) ?></h2>
                <p class="text-xs text-gray-500"><?= e($pertemuan['mata_pelajaran'] ?: $pertemuan['program_nama']) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4 text-sm">
            <div>
                <p class="text-xs text-gray-500">Tanggal</p>
                <p class="font-semibold"><?= e(tanggal_indonesia($pertemuan['tanggal'], false)) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Waktu</p>
                <p class="font-semibold"><?= e(substr($pertemuan['jam_mulai'], 0, 5)) ?> - <?= e(substr($pertemuan['jam_selesai'], 0, 5)) ?> WIB</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Kelas</p>
                <p class="font-semibold">Kelas <?= e($pertemuan['kelas_nama']) ?></p>
            </div>
        </div>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-500">Data Kehadiran &amp; Nilai</h2>

        <?php if (empty($presensiList)): ?>
            <div class="bg-white rounded-2xl p-6 text-center text-sm text-gray-500">Belum ada data siswa.</div>
        <?php else: ?>
            <?php foreach ($presensiList as $item): ?>
                <?php $statusLabel = $item['status_kehadiran'] === 'none' ? 'Belum Diisi' : ucfirst($item['status_kehadiran']); ?>
                <article class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm border-l-4 border-l-[#324f47]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-[#2D3E39]"><?= e($item['siswa_nama']) ?></h3>
                            <p class="text-xs text-gray-500 mt-1"><?= e($item['asal_sekolah'] ?: '-') ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $item['status_kehadiran'] === 'hadir' ? 'bg-[#324f47] text-white' : 'bg-[#F2EFE9] text-gray-700' ?>">
                            <?= e($statusLabel) ?>
                        </span>
                    </div>

                    <?php if ($item['nilai_akademik'] || $item['nilai_sikap']): ?>
                        <div class="mt-3 p-3 rounded-xl bg-[#F2EFE9] text-xs text-gray-600">
                            Nilai Kemampuan:
                            <strong class="text-[#2D3E39]"><?= e(konversi_nilai_huruf($item['nilai_akademik'])) ?></strong>
                            · Nilai Sikap:
                            <strong class="text-[#2D3E39]"><?= e(konversi_nilai_huruf($item['nilai_sikap'])) ?></strong>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <a href="/tentor/presensi" class="w-full py-3 rounded-xl bg-white border-2 border-[#324f47] text-[#324f47] text-sm font-semibold text-center flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">edit_note</span>
        Edit Data
    </a>
</div>
