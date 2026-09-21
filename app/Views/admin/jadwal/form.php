<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/jadwal" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight"><?= $isEdit ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar Baru' ?></h1>
                <p class="text-xs text-gray-500">Atur penugasan kelas, tentor pengajar, dan slot jam mengajar</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="<?= $isEdit ? '/admin/jadwal/' . $jadwal['id'] . '/update' : '/admin/jadwal/simpan' ?>" method="POST" class="flex flex-col gap-4">
            
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Kelas Pembelajaran <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">account_tree</span>
                    <select name="kelas_id" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        <?php foreach ($kelasList as $k): ?>
                            <option value="<?= $k['id'] ?>" <?= ((int) ($jadwal['kelas_id'] ?? 0) === (int) $k['id']) ? 'selected' : '' ?>>
                                <?= e($k['nama']) ?> - <?= e($k['jenjang_nama']) ?> (<?= e($k['program_nama']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3.5 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                </div>
                <?php if (isset($errors['kelas_id'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['kelas_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Tentor Pengajar <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">badge</span>
                    <select name="tentor_id" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Tentor --</option>
                        <?php foreach ($tentorList as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ((int) ($jadwal['tentor_id'] ?? 0) === (int) $t['id']) ? 'selected' : '' ?>>
                                <?= e($t['nama_lengkap']) ?> (Univ: <?= e($t['asal_universitas']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3.5 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                </div>
                <?php if (isset($errors['tentor_id'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['tentor_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Hari Mengajar <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">calendar_today</span>
                    <select name="hari" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Hari --</option>
                        <option value="1" <?= ((int) ($jadwal['hari'] ?? 0) === 1) ? 'selected' : '' ?>>Senin</option>
                        <option value="2" <?= ((int) ($jadwal['hari'] ?? 0) === 2) ? 'selected' : '' ?>>Selasa</option>
                        <option value="3" <?= ((int) ($jadwal['hari'] ?? 0) === 3) ? 'selected' : '' ?>>Rabu</option>
                        <option value="4" <?= ((int) ($jadwal['hari'] ?? 0) === 4) ? 'selected' : '' ?>>Kamis</option>
                        <option value="5" <?= ((int) ($jadwal['hari'] ?? 0) === 5) ? 'selected' : '' ?>>Jumat</option>
                        <option value="6" <?= ((int) ($jadwal['hari'] ?? 0) === 6) ? 'selected' : '' ?>>Sabtu</option>
                        <option value="7" <?= ((int) ($jadwal['hari'] ?? 0) === 7) ? 'selected' : '' ?>>Minggu</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3.5 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                </div>
                <?php if (isset($errors['hari'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['hari']) ?></small>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Jam Mulai <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">schedule</span>
                        <input type="time" name="jam_mulai" value="<?= e($jadwal['jam_mulai'] ?? '15:30') ?>" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Jam Selesai <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">schedule</span>
                        <input type="time" name="jam_selesai" value="<?= e($jadwal['jam_selesai'] ?? '17:00') ?>" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Ruangan <small class="font-normal text-gray-500">(Opsional)</small></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">location_on</span>
                    <input type="text" name="ruangan" value="<?= e($jadwal['ruangan'] ?? '') ?>" placeholder="Contoh: Ruang Melati, Meja 02" class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                </div>
            </div>

            <div class="pt-1">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($jadwal['status_aktif']) || $jadwal['status_aktif']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#324f47] focus:ring-[#324f47]">
                    <span>Status Jadwal Aktif</span>
                </label>
            </div>

            <!-- Submit Button Group -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100 mt-2">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Jadwal' ?>
                </button>
                <a href="/admin/jadwal" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
