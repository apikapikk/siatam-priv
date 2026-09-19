<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/kelas" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Kelas
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/kelas/' . $kelas['id'] . '/update' : '/admin/kelas/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Nama Kelas</label>
                <input type="text" name="nama" value="<?= e($kelas['nama'] ?? '') ?>" placeholder="Contoh: 7A, 8B, 12 IPA Private" required class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['nama'])): ?>
                    <small class="form-error"><?= e($errors['nama']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Jenjang Pendidikan</label>
                <select name="jenjang_id" required class="form-control <?= isset($errors['jenjang_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Jenjang --</option>
                    <?php foreach ($jenjangList as $j): ?>
                        <option value="<?= $j['id'] ?>" <?= ((int) ($kelas['jenjang_id'] ?? 0) === (int) $j['id']) ? 'selected' : '' ?>>
                            <?= e($j['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['jenjang_id'])): ?>
                    <small class="form-error"><?= e($errors['jenjang_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Program</label>
                <select name="program_id" required class="form-control <?= isset($errors['program_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Program --</option>
                    <?php foreach ($programList as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ((int) ($kelas['program_id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
                            <?= e($p['nama']) ?> (<?= e($p['tipe']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['program_id'])): ?>
                    <small class="form-error"><?= e($errors['program_id']) ?></small>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($kelas['status_aktif']) || $kelas['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Kelas Aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kelas' ?>
                </button>
                <a href="/admin/kelas" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
