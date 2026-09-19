<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/pengguna" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Pengguna
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/pengguna/' . $pengguna['id'] . '/update' : '/admin/pengguna/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="<?= e($pengguna['username'] ?? '') ?>" required class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['username'])): ?>
                    <small class="form-error"><?= e($errors['username']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Password <?= $isEdit ? '<small style="font-weight: 400; color: #6b7280;">(Kosongkan jika tidak ingin mengubah)</small>' : '' ?>
                </label>
                <input type="password" name="password" <?= $isEdit ? '' : 'required' ?> class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['password'])): ?>
                    <small class="form-error"><?= e($errors['password']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Peran (Role)</label>
                <select name="peran" required class="form-control <?= isset($errors['peran']) ? 'is-invalid' : '' ?>">
                    <option value="admin" <?= ($pengguna['peran'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="owner" <?= ($pengguna['peran'] ?? '') === 'owner' ? 'selected' : '' ?>>Owner</option>
                    <option value="tentor" <?= ($pengguna['peran'] ?? '') === 'tentor' ? 'selected' : '' ?>>Tentor</option>
                </select>
                <?php if (isset($errors['peran'])): ?>
                    <small class="form-error"><?= e($errors['peran']) ?></small>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($pengguna['status_aktif']) || $pengguna['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Akun Aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
                </button>
                <a href="/admin/pengguna" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
