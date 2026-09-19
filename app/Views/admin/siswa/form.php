<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/siswa" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Siswa
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Data Siswa' : 'Tambah Data Siswa Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/siswa/' . $siswa['id'] . '/update' : '/admin/siswa/simpan' ?>" method="POST">
            <h2 style="font-size: 15px; font-weight: 700; margin: 0 0 12px; color: #1b4332;">Identitas Siswa</h2>

            <div class="form-group">
                <label class="form-label">Nama Lengkap Siswa</label>
                <input type="text" name="nama_lengkap" value="<?= e($siswa['nama_lengkap'] ?? '') ?>" placeholder="Nama lengkap siswa" required class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['nama_lengkap'])): ?>
                    <small class="form-error"><?= e($errors['nama_lengkap']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" value="<?= e($siswa['asal_sekolah'] ?? '') ?>" placeholder="Contoh: SMP Negeri 1 Yogyakarta" required class="form-control <?= isset($errors['asal_sekolah']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['asal_sekolah'])): ?>
                    <small class="form-error"><?= e($errors['asal_sekolah']) ?></small>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($siswa['status_aktif']) || $siswa['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Siswa Aktif</span>
                </label>
            </div>

            <hr style="border: 0; border-top: 1px dashed #e5e7eb; margin: 20px 0;">

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                <h2 style="font-size: 15px; font-weight: 700; margin: 0; color: #1b4332;">Data Orang Tua / Wali</h2>
                <button type="button" id="btn-add-parent" class="btn btn-sm btn-secondary">+ Tambah Wali</button>
            </div>

            <div id="parent-container">
                <?php
                $parentList = !empty($parents) ? $parents : [
                    ['nama_lengkap' => '', 'nomor_telepon' => '', 'hubungan' => 'Ayah']
                ];
                foreach ($parentList as $index => $parent):
                ?>
                    <div class="parent-row ui-card" style="background: #faf9f7; margin-bottom: 12px; padding: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="font-size: 13px; color: #6b7280;">Wali #<span class="parent-number"><?= $index + 1 ?></span></strong>
                            <?php if ($index > 0): ?>
                                <button type="button" class="btn btn-sm btn-danger btn-remove-parent" style="padding: 2px 8px; font-size: 11px;">Hapus</button>
                            <?php endif; ?>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                            <div>
                                <label class="form-label">Nama Wali</label>
                                <input type="text" name="parents[<?= $index ?>][nama_lengkap]" value="<?= e($parent['nama_lengkap'] ?? '') ?>" placeholder="Nama wali" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" name="parents[<?= $index ?>][nomor_telepon]" value="<?= e($parent['nomor_telepon'] ?? '') ?>" placeholder="08123456789" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Hubungan</label>
                            <select name="parents[<?= $index ?>][hubungan]" class="form-control">
                                <option value="Ayah" <?= ($parent['hubungan'] ?? '') === 'Ayah' ? 'selected' : '' ?>>Ayah</option>
                                <option value="Ibu" <?= ($parent['hubungan'] ?? '') === 'Ibu' ? 'selected' : '' ?>>Ibu</option>
                                <option value="Wali" <?= ($parent['hubungan'] ?? '') === 'Wali' ? 'selected' : '' ?>>Wali</option>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Siswa' ?>
                </button>
                <a href="/admin/siswa" class="btn btn-secondary">
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
        newRow.className = 'parent-row ui-card';
        newRow.style.cssText = 'background: #faf9f7; margin-bottom: 12px; padding: 12px;';
        newRow.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="font-size: 13px; color: #6b7280;">Wali #<span class="parent-number">${nextIndex + 1}</span></strong>
                <button type="button" class="btn btn-sm btn-danger btn-remove-parent" style="padding: 2px 8px; font-size: 11px;">Hapus</button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px;">
                <div>
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="parents[${nextIndex}][nama_lengkap]" placeholder="Nama wali" class="form-control">
                </div>
                <div>
                    <label class="form-label">No. Telepon / WA</label>
                    <input type="text" name="parents[${nextIndex}][nomor_telepon]" placeholder="08123456789" class="form-control">
                </div>
            </div>
            <div>
                <label class="form-label">Hubungan</label>
                <select name="parents[${nextIndex}][hubungan]" class="form-control">
                    <option value="Ayah">Ayah</option>
                    <option value="Ibu">Ibu</option>
                    <option value="Wali" selected>Wali</option>
                </select>
            </div>
        `;
        container.appendChild(newRow);
    });

    container.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-remove-parent')) {
            const row = e.target.closest('.parent-row');
            row.remove();
            // Re-index parent-number
            const rows = container.querySelectorAll('.parent-row');
            rows.forEach((r, idx) => {
                r.querySelector('.parent-number').textContent = idx + 1;
            });
        }
    });
});
</script>
