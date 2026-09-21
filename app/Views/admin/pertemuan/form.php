<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/pertemuan" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Catat Pertemuan Baru</h1>
                <p class="text-xs text-gray-500">Pilih jadwal, tentor pengajar aktual, dan waktu pelaksanaan kelas</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="/admin/pertemuan/simpan" method="POST" class="flex flex-col gap-5">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-[#e8f0ec] text-[#324f47] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">event_note</span>
                    </div>
                    <h2 class="font-bold text-base text-[#2D3E39]">Detail Jadwal</h2>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Jadwal Kelas <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">account_tree</span>
                        <select name="jadwal_id" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer <?= isset($errors['jadwal_id']) ? 'border-red-300' : '' ?>">
                            <option value="">-- Pilih Jadwal --</option>
                            <?php foreach ($jadwalList as $j): ?>
                                <option value="<?= $j['id'] ?>" <?= ((int) ($pertemuan['jadwal_id'] ?? 0) === (int) $j['id']) ? 'selected' : '' ?>>
                                    Kelas <?= e($j['kelas_nama']) ?> (<?= hari_indonesia((int) $j['hari']) ?> <?= substr($j['jam_mulai'], 0, 5) ?>-<?= substr($j['jam_selesai'], 0, 5) ?>) - Tentor: <?= e($j['tentor_nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="material-symbols-outlined absolute right-3.5 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                    </div>
                    <?php if (isset($errors['jadwal_id'])): ?>
                        <small class="text-xs text-red-600"><?= e($errors['jadwal_id']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Tentor Aktual Mengajar <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">badge</span>
                        <select name="tentor_id" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer <?= isset($errors['tentor_id']) ? 'border-red-300' : '' ?>">
                            <option value="">-- Pilih Tentor Mengajar --</option>
                            <?php foreach ($tentorList as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= ((int) ($pertemuan['tentor_id'] ?? 0) === (int) $t['id']) ? 'selected' : '' ?>>
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
            </div>

            <div class="flex flex-col gap-4 pt-1">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                    </div>
                    <h2 class="font-bold text-base text-[#2D3E39]">Waktu Pelaksanaan</h2>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">calendar_today</span>
                        <input type="date" name="tanggal" value="<?= e($pertemuan['tanggal'] ?? date('Y-m-d')) ?>" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all <?= isset($errors['tanggal']) ? 'border-red-300' : '' ?>">
                    </div>
                    <?php if (isset($errors['tanggal'])): ?>
                        <small class="text-xs text-red-600"><?= e($errors['tanggal']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-700">Jam Mulai Aktual <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">schedule</span>
                            <input type="time" name="jam_mulai" value="<?= e($pertemuan['jam_mulai'] ?? '15:30') ?>" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all <?= isset($errors['jam_mulai']) ? 'border-red-300' : '' ?>">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-700">Jam Selesai Aktual <span class="text-red-500">*</span></label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">schedule</span>
                            <input type="time" name="jam_selesai" value="<?= e($pertemuan['jam_selesai'] ?? '17:00') ?>" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all <?= isset($errors['jam_selesai']) ? 'border-red-300' : '' ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    Simpan & Masuk ke Presensi Siswa
                </button>
                <a href="/admin/pertemuan" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
