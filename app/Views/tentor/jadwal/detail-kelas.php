<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center gap-3">
        <a href="/tentor/jadwal" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-xl font-bold text-[#2D3E39]">Detail Kelas &amp; Riwayat</h1>
    </div>

    <section class="text-center">
        <h2 class="text-2xl font-bold text-[#2D3E39]">Kelas <?= e($kelas['nama']) ?></h2>
        <span class="inline-flex mt-2 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#324f47] text-xs font-semibold">
            <?= e($kelas['program_nama']) ?> · <?= e($kelas['jenjang_nama']) ?>
        </span>
        <a href="/tentor/presensi" class="mt-4 w-full py-3 rounded-xl bg-[#324f47] text-white text-sm font-semibold flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">add</span>
            Tambah Presensi Baru
        </a>
    </section>

    <section class="flex flex-col gap-3">
        <h2 class="text-base font-bold text-[#2D3E39]">Riwayat Pertemuan</h2>

        <?php if (empty($pertemuanList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200/80">
                Belum ada pertemuan untuk kelas ini.
            </div>
        <?php else: ?>
            <?php foreach ($pertemuanList as $item): ?>
                <article class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-bold text-[#2D3E39]">Pertemuan <?= e($item['nomor_pertemuan']) ?></h3>
                            <p class="text-xs text-gray-500 mt-1">Jadwal mengajar Anda</p>
                        </div>
                        <div class="text-right">
                            <span class="block px-2 py-1 rounded-md bg-[#F2EFE9] text-xs text-gray-600">
                                <?= e(tanggal_indonesia($item['tanggal'], false)) ?>
                            </span>
                            <span class="block mt-1 text-xs text-gray-500">
                                <?= e(substr($item['jam_mulai'], 0, 5)) ?> - <?= e(substr($item['jam_selesai'], 0, 5)) ?> WIB
                            </span>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            <?= e((string) $item['total_hadir']) ?>/<?= e((string) $item['total_presensi']) ?> hadir
                        </span>
                        <a href="/tentor/jadwal/pertemuan/<?= (int) $item['id'] ?>" class="px-4 py-2 rounded-xl border border-[#324f47] text-[#324f47] text-xs font-semibold">
                            Lihat Detail
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>
