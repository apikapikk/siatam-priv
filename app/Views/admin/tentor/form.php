<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/tentor" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Tentor
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Profil Tentor' : 'Tambah Profil Tentor Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/tentor/' . $tentor['id'] . '/update' : '/admin/tentor/simpan' ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Akun Pengguna</label>
                <select name="pengguna_id" required class="form-control <?= isset($errors['pengguna_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Akun User Tentor --</option>
                    <?php foreach ($availableUsers as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= ((int) ($tentor['pengguna_id'] ?? 0) === (int) $user['id']) ? 'selected' : '' ?>>
                            <?= e($user['username']) ?> (ID: <?= $user['id'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['pengguna_id'])): ?>
                    <small class="form-error"><?= e($errors['pengguna_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= e($tentor['nama_lengkap'] ?? '') ?>" placeholder="Nama lengkap beserta gelar jika ada" required class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['nama_lengkap'])): ?>
                    <small class="form-error"><?= e($errors['nama_lengkap']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Asal Universitas</label>
                <input type="text" name="asal_universitas" value="<?= e($tentor['asal_universitas'] ?? '') ?>" placeholder="Contoh: Universitas Gadjah Mada" required class="form-control <?= isset($errors['asal_universitas']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['asal_universitas'])): ?>
                    <small class="form-error"><?= e($errors['asal_universitas']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon / WhatsApp</label>
                <input type="text" name="nomor_telepon" value="<?= e($tentor['nomor_telepon'] ?? '') ?>" placeholder="Contoh: 081234567890" class="form-control <?= isset($errors['nomor_telepon']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['nomor_telepon'])): ?>
                    <small class="form-error"><?= e($errors['nomor_telepon']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Profil</label>
                <?php if (!empty($tentor['foto'])): ?>
                    <div style="margin-bottom: 8px;">
                        <img src="<?= e($tentor['foto']) ?>" alt="" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 1px solid #e5e7eb;">
                    </div>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control <?= isset($errors['foto']) ? 'is-invalid' : '' ?>">
                <small style="color: #6b7280; font-size: 12px;">Format: JPG, PNG, WEBP. Maks 2 MB.</small>
                <?php if (isset($errors['foto'])): ?>
                    <small class="form-error"><?= e($errors['foto']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Bio / Catatan Singkat</label>
                <textarea name="bio" rows="3" placeholder="Informasi atau pengalaman tentor" class="form-control"><?= e($tentor['bio'] ?? '') ?></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($tentor['status_aktif']) || $tentor['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Tentor Aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Tentor' ?>
                </button>
                <a href="/admin/tentor" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
