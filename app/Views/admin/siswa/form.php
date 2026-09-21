<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/siswa" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight"><?= $isEdit ? 'Edit Data Siswa' : 'Pendaftaran Siswa Baru' ?></h1>
                <p class="text-xs text-gray-500">Lengkapi berkas identitas siswa dan data orang tua / wali</p>
            </div>
        </div>
    </div>

    <!-- Form Section Container -->
    <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-200/80">
        <form action="<?= $isEdit ? '/admin/siswa/' . $siswa['id'] . '/update' : '/admin/siswa/simpan' ?>" method="POST" class="flex flex-col gap-6">
            
            <!-- Group 1: Identitas Siswa -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-[#e8f0ec] text-[#324f47] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">person_book</span>
                    </div>
                    <h2 class="font-bold text-base text-[#2D3E39]">Identitas Siswa</h2>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">person</span>
                        <input type="text" name="nama_lengkap" value="<?= e($siswa['nama_lengkap'] ?? '') ?>" placeholder="Masukkan nama lengkap siswa" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                    </div>
                    <?php if (isset($errors['nama_lengkap'])): ?>
                        <small class="text-xs text-red-600"><?= e($errors['nama_lengkap']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-700">Asal Sekolah <span class="text-red-500">*</span></label>
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3.5 text-gray-400 text-[18px]">school</span>
                        <input type="text" name="asal_sekolah" value="<?= e($siswa['asal_sekolah'] ?? '') ?>" placeholder="Contoh: SMP Negeri 1 Yogyakarta" required class="w-full pl-10 pr-4 py-2.5 bg-[#FAF9F7] text-sm text-gray-800 rounded-xl border border-gray-200 focus:outline-none focus:border-[#324f47] focus:ring-1 focus:ring-[#324f47] transition-all">
                    </div>
                    <?php if (isset($errors['asal_sekolah'])): ?>
                        <small class="text-xs text-red-600"><?= e($errors['asal_sekolah']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" <?= (!isset($siswa['status_aktif']) || $siswa['status_aktif']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#324f47] focus:ring-[#324f47]">
                        <span>Status Siswa Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Group 2: Data Wali / Orang Tua -->
            <div class="flex flex-col gap-4 pt-2">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">family_restroom</span>
                        </div>
                        <h2 class="font-bold text-base text-[#2D3E39]">Data Orang Tua / Wali</h2>
                    </div>
                    <button type="button" id="btn-add-parent" class="px-3 py-1.5 rounded-xl bg-[#e8f0ec] text-[#324f47] hover:bg-[#d8e8e0] text-xs font-semibold transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Wali
                    </button>
                </div>

                <div id="parent-container" class="flex flex-col gap-3">
                    <?php
                    $parentList = !empty($parents) ? $parents : [
                        ['nama_lengkap' => '', 'nomor_telepon' => '', 'hubungan' => 'Ayah']
                    ];
                    foreach ($parentList as $index => $parent):
                    ?>
                        <div class="parent-row bg-[#FAF9F7] rounded-xl p-4 border border-gray-200/80 flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-700">Wali #<span class="parent-number"><?= $index + 1 ?></span></span>
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn-remove-parent text-red-600 hover:text-red-800 text-xs font-semibold flex items-center gap-0.5">
                                        <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">Nama Wali</label>
                                    <input type="text" name="parents[<?= $index ?>][nama_lengkap]" value="<?= e($parent['nama_lengkap'] ?? '') ?>" placeholder="Nama wali" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs font-medium text-gray-600">No. Telepon / WA</label>
                                    <input type="text" name="parents[<?= $index ?>][nomor_telepon]" value="<?= e($parent['nomor_telepon'] ?? '') ?>" placeholder="08123456789" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-medium text-gray-600">Hubungan</label>
                                <select name="parents[<?= $index ?>][hubungan]" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                                    <option value="Ayah" <?= ($parent['hubungan'] ?? '') === 'Ayah' ? 'selected' : '' ?>>Ayah</option>
                                    <option value="Ibu" <?= ($parent['hubungan'] ?? '') === 'Ibu' ? 'selected' : '' ?>>Ibu</option>
                                    <option value="Wali" <?= ($parent['hubungan'] ?? '') === 'Wali' ? 'selected' : '' ?>>Wali</option>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Submit Button Group -->
            <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                <button type="submit" class="flex-1 py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Data Siswa' ?>
                </button>
                <a href="/admin/siswa" class="px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('parent-container');
    const btnAdd = document.getElementById('btn-add-parent');

    btnAdd.addEventListener('click', function() {
        const rows = container.querySelectorAll('.parent-row');
        const nextIndex = rows.length;

        const newRow = document.createElement('div');
        newRow.className = 'parent-row bg-[#FAF9F7] rounded-xl p-4 border border-gray-200/80 flex flex-col gap-3';
        newRow.innerHTML = `
            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-gray-700">Wali #<span class="parent-number">${nextIndex + 1}</span></span>
                <button type="button" class="btn-remove-parent text-red-600 hover:text-red-800 text-xs font-semibold flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">Nama Wali</label>
                    <input type="text" name="parents[${nextIndex}][nama_lengkap]" placeholder="Nama wali" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-gray-600">No. Telepon / WA</label>
                    <input type="text" name="parents[${nextIndex}][nomor_telepon]" placeholder="08123456789" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-gray-600">Hubungan</label>
                <select name="parents[${nextIndex}][hubungan]" class="w-full px-3 py-2 bg-white text-sm text-gray-800 rounded-lg border border-gray-200 focus:outline-none focus:border-[#324f47]">
                    <option value="Ayah">Ayah</option>
                    <option value="Ibu">Ibu</option>
                    <option value="Wali" selected>Wali</option>
                </select>
            </div>
        `;
        container.appendChild(newRow);
    });

    container.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.btn-remove-parent')) {
            const row = e.target.closest('.parent-row');
            row.remove();
            const rows = container.querySelectorAll('.parent-row');
            rows.forEach((r, idx) => {
                r.querySelector('.parent-number').textContent = idx + 1;
            });
        }
    });
});
</script>
