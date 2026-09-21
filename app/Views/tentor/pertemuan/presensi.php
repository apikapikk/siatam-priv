<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/tentor/pertemuan" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Input Presensi & Nilai Siswa</h1>
                <p class="text-xs text-gray-500">Pertemuan #<?= e($pertemuan['nomor_pertemuan']) ?> • Tanggal: <strong><?= e($pertemuan['tanggal']) ?></strong></p>
            </div>
        </div>
    </div>

    <form action="/tentor/pertemuan/<?= $pertemuan['id'] ?>/presensi/update" method="POST" class="flex flex-col gap-4">
        <div class="flex flex-col gap-3.5">
            <?php if (empty($presensiList)): ?>
                <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                    <div class="w-12 h-12 rounded-2xl bg-[#e8f0ec] text-[#324f47] flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[26px]">group_off</span>
                    </div>
                    Belum ada siswa yang terdaftar di kelas ini.
                </div>
            <?php else: ?>
                <?php foreach ($presensiList as $item): ?>
                    <div class="bg-white rounded-2xl p-4 md:p-5 shadow-sm border border-gray-200/80 flex flex-col gap-3">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-[#e8f0ec] text-[#324f47] font-bold flex items-center justify-center text-sm shrink-0">
                                    <?= e(strtoupper(substr($item['siswa_nama'], 0, 1))) ?>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-sm text-gray-900 truncate"><?= e($item['siswa_nama']) ?></h3>
                                    <p class="text-xs text-gray-500 truncate">Sekolah: <?= e($item['asal_sekolah'] ?: '-') ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold text-gray-700">Kehadiran <span class="text-red-500">*</span></label>
                                <div class="relative flex items-center">
                                    <select name="presensi[<?= $item['siswa_id'] ?>][status_kehadiran]" class="w-full px-3 pr-9 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] appearance-none cursor-pointer">
                                        <option value="none" <?= ($item['status_kehadiran'] === 'none') ? 'selected' : '' ?>>- Belum Diisi -</option>
                                        <option value="hadir" <?= ($item['status_kehadiran'] === 'hadir') ? 'selected' : '' ?>>Hadir</option>
                                        <option value="sakit" <?= ($item['status_kehadiran'] === 'sakit') ? 'selected' : '' ?>>Sakit</option>
                                        <option value="izin" <?= ($item['status_kehadiran'] === 'izin') ? 'selected' : '' ?>>Izin</option>
                                        <option value="alfa" <?= ($item['status_kehadiran'] === 'alfa') ? 'selected' : '' ?>>Alfa</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold text-gray-700">Nilai Sikap</label>
                                <div class="relative flex items-center">
                                    <select name="presensi[<?= $item['siswa_id'] ?>][nilai_sikap]" class="w-full px-3 pr-9 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] appearance-none cursor-pointer">
                                        <option value="">-</option>
                                        <option value="A" <?= ($item['nilai_sikap'] === 'A') ? 'selected' : '' ?>>A (Sangat Baik)</option>
                                        <option value="B" <?= ($item['nilai_sikap'] === 'B') ? 'selected' : '' ?>>B (Baik)</option>
                                        <option value="C" <?= ($item['nilai_sikap'] === 'C') ? 'selected' : '' ?>>C (Cukup)</option>
                                        <option value="D" <?= ($item['nilai_sikap'] === 'D') ? 'selected' : '' ?>>D (Kurang)</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold text-gray-700">Nilai Akademik</label>
                                <div class="relative flex items-center">
                                    <select name="presensi[<?= $item['siswa_id'] ?>][nilai_akademik]" class="w-full px-3 pr-9 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] appearance-none cursor-pointer">
                                        <option value="">-</option>
                                        <option value="A" <?= ($item['nilai_akademik'] === 'A') ? 'selected' : '' ?>>A (Sangat Baik)</option>
                                        <option value="B" <?= ($item['nilai_akademik'] === 'B') ? 'selected' : '' ?>>B (Baik)</option>
                                        <option value="C" <?= ($item['nilai_akademik'] === 'C') ? 'selected' : '' ?>>C (Cukup)</option>
                                        <option value="D" <?= ($item['nilai_akademik'] === 'D') ? 'selected' : '' ?>>D (Kurang)</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="text-xs font-semibold text-gray-700">Catatan Perkembangan Siswa</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">edit_note</span>
                                <input type="text" name="presensi[<?= $item['siswa_id'] ?>][catatan]" value="<?= e($item['catatan'] ?? '') ?>" placeholder="Tuliskan catatan perkembangan atau tugas siswa..." class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47]">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($presensiList)): ?>
            <div class="flex items-center gap-3 mt-2">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Presensi & Nilai
                </button>
                <a href="/tentor/pertemuan" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>
