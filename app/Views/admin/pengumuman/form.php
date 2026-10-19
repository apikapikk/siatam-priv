<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/pengumuman" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight"><?= $isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' ?></h1>
                <p class="text-xs text-gray-500">Kirim broadcast pengumuman ke seluruh pengguna atau target tertentu</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="<?= $isEdit ? '/admin/pengumuman/' . $pengumuman['id'] . '/update' : '/admin/pengumuman/simpan' ?>" method="POST" class="flex flex-col gap-5">
            <!-- Judul Pengumuman -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Judul Pengumuman <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">campaign</span>
                    <input type="text" name="judul" value="<?= e($pengumuman['judul'] ?? '') ?>" placeholder="Masukkan judul pengumuman penting" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all <?= isset($errors['judul']) ? '!border-red-500' : '' ?>">
                </div>
                <?php if (isset($errors['judul'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['judul']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Target Penerima & Kategori -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Target Penerima <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">group</span>
                        <select name="target_peran" required class="w-full pl-10 pr-8 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                            <option value="semua" <?= ($pengumuman['target_peran'] ?? 'semua') === 'semua' ? 'selected' : '' ?>>Semua Pengguna (Internal)</option>
                            <option value="publik" <?= ($pengumuman['target_peran'] ?? '') === 'publik' ? 'selected' : '' ?>>Publik (Muncul di Web Depan)</option>
                            <option value="tentor" <?= ($pengumuman['target_peran'] ?? '') === 'tentor' ? 'selected' : '' ?>>Khusus Tentor</option>
                            <option value="admin" <?= ($pengumuman['target_peran'] ?? '') === 'admin' ? 'selected' : '' ?>>Khusus Admin / Owner</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Kategori <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">label</span>
                        <?php $kategori = $pengumuman['kategori'] ?? 'Umum'; ?>
                        <select name="kategori" required class="w-full pl-10 pr-8 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                            <option value="Umum" <?= $kategori === 'Umum' ? 'selected' : '' ?>>Umum</option>
                            <option value="Penting" <?= $kategori === 'Penting' ? 'selected' : '' ?>>Penting</option>
                            <option value="Akademik" <?= $kategori === 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                            <option value="Jadwal" <?= $kategori === 'Jadwal' ? 'selected' : '' ?>>Jadwal</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Tipe Broadcast <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">notifications</span>
                        <?php $tipeBroadcast = $pengumuman['tipe_broadcast'] ?? 'banner'; ?>
                        <select name="tipe_broadcast" required class="w-full pl-10 pr-8 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47]">
                            <option value="banner" <?= $tipeBroadcast === 'banner' ? 'selected' : '' ?>>Banner</option>
                            <option value="popup" <?= $tipeBroadcast === 'popup' ? 'selected' : '' ?>>Popup</option>
                            <option value="push" <?= $tipeBroadcast === 'push' ? 'selected' : '' ?>>Push</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Isi Pengumuman -->
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Isi Pengumuman <span class="text-red-500">*</span></label>
                <textarea name="isi" rows="6" placeholder="Tuliskan isi detail pengumuman..." required class="w-full p-4 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all leading-relaxed <?= isset($errors['isi']) ? '!border-red-500' : '' ?>"><?= e($pengumuman['isi'] ?? '') ?></textarea>
                <?php if (isset($errors['isi'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['isi']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Checkbox Status Aktif -->
            <div class="pt-1">
                <label class="inline-flex items-center gap-2.5 text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($pengumuman['status_aktif']) || $pengumuman['status_aktif']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#324f47] focus:ring-[#324f47] border-gray-300">
                    <span>Pengumuman Aktif / Tampilkan Sekarang</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] text-white font-semibold rounded-xl text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' ?>
                </button>
                <a href="/admin/pengumuman" class="py-3 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
