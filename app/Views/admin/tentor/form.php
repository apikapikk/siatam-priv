<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/tentor" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight"><?= $isEdit ? 'Edit Profil Tentor' : 'Registrasi Profil Tentor Baru' ?></h1>
                <p class="text-xs text-gray-500">Lengkapi biodata dan informasi akun pengajar</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="<?= $isEdit ? '/admin/tentor/' . $tentor['id'] . '/update' : '/admin/tentor/simpan' ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Akun Pengguna <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">badge</span>
                    <select name="pengguna_id" required class="w-full pl-10 pr-10 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all appearance-none cursor-pointer">
                        <option value="">-- Pilih Akun User Tentor --</option>
                        <?php foreach ($availableUsers as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= ((int) ($tentor['pengguna_id'] ?? 0) === (int) $user['id']) ? 'selected' : '' ?>>
                                <?= e($user['username']) ?> (ID: <?= $user['id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-3.5 text-gray-400 text-[18px] pointer-events-none">expand_more</span>
                </div>
                <?php if (isset($errors['pengguna_id'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['pengguna_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Nama Lengkap Tentor <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">person</span>
                    <input type="text" name="nama_lengkap" value="<?= e($tentor['nama_lengkap'] ?? '') ?>" placeholder="Nama lengkap beserta gelar jika ada" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
                <?php if (isset($errors['nama_lengkap'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['nama_lengkap']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Asal Universitas <span class="text-red-500">*</span></label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">school</span>
                    <input type="text" name="asal_universitas" value="<?= e($tentor['asal_universitas'] ?? '') ?>" placeholder="Contoh: Universitas Gadjah Mada" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
                <?php if (isset($errors['asal_universitas'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['asal_universitas']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Nomor Telepon / WhatsApp</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">call</span>
                    <input type="text" name="nomor_telepon" value="<?= e($tentor['nomor_telepon'] ?? '') ?>" placeholder="Contoh: 081234567890" class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                </div>
                <?php if (isset($errors['nomor_telepon'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['nomor_telepon']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Foto Profil</label>
                <?php if (!empty($tentor['foto'])): ?>
                    <div class="mb-1">
                        <img src="<?= e($tentor['foto']) ?>" alt="" class="w-14 h-14 rounded-full object-cover border-2 border-emerald-100">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="w-full px-3 py-2 bg-[#FAF9F7] text-xs text-gray-700 rounded-xl border border-gray-200">
                <small class="text-[11px] text-gray-500">Format: JPG, PNG, WEBP. Maks 2 MB.</small>
                <?php if (isset($errors['foto'])): ?>
                    <small class="text-xs text-red-600"><?= e($errors['foto']) ?></small>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-gray-700">Bio / Pengalaman Mengajar</label>
                <textarea name="bio" rows="3" placeholder="Informasi singkat atau pengalaman mengajar tentor..." class="w-full px-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all"><?= e($tentor['bio'] ?? '') ?></textarea>
            </div>

            <div class="pt-1">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($tentor['status_aktif']) || $tentor['status_aktif']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#324f47] focus:ring-[#324f47]">
                    <span>Status Tentor Aktif</span>
                </label>
            </div>

            <!-- Submit Button Group -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100 mt-2">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Profil Tentor' ?>
                </button>
                <a href="/admin/tentor" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
