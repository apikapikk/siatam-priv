<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/pengumuman" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Pengumuman
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/pengumuman/' . $pengumuman['id'] . '/update' : '/admin/pengumuman/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Judul Pengumuman</label>
                <input type="text" name="judul" value="<?= e($pengumuman['judul'] ?? '') ?>" placeholder="Judul pengumuman penting" required class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['judul'])): ?>
                    <small class="form-error"><?= e($errors['judul']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Target Penerima</label>
                <select name="target_peran" required class="form-control">
                    <option value="semua" <?= ($pengumuman['target_peran'] ?? 'semua') === 'semua' ? 'selected' : '' ?>>Semua Pengguna</option>
                    <option value="tentor" <?= ($pengumuman['target_peran'] ?? '') === 'tentor' ? 'selected' : '' ?>>Khusus Tentor</option>
                    <option value="admin" <?= ($pengumuman['target_peran'] ?? '') === 'admin' ? 'selected' : '' ?>>Khusus Admin / Owner</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" required class="form-control">
                        <?php $kategori = $pengumuman['kategori'] ?? 'Umum'; ?>
                        <option value="Umum" <?= $kategori === 'Umum' ? 'selected' : '' ?>>Umum</option>
                        <option value="Penting" <?= $kategori === 'Penting' ? 'selected' : '' ?>>Penting</option>
                        <option value="Akademik" <?= $kategori === 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe Broadcast</label>
                    <select name="tipe_broadcast" required class="form-control">
                        <?php $tipeBroadcast = $pengumuman['tipe_broadcast'] ?? 'banner'; ?>
                        <option value="banner" <?= $tipeBroadcast === 'banner' ? 'selected' : '' ?>>Banner</option>
                        <option value="popup" <?= $tipeBroadcast === 'popup' ? 'selected' : '' ?>>Popup</option>
                        <option value="push" <?= $tipeBroadcast === 'push' ? 'selected' : '' ?>>Push</option>
                    </select>
                    <?php if (isset($errors['tipe_broadcast'])): ?>
                        <small class="form-error"><?= e($errors['tipe_broadcast']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Pengumuman</label>
                <textarea name="isi" rows="5" placeholder="Tuliskan detail pengumuman..." required class="form-control <?= isset($errors['isi']) ? 'is-invalid' : '' ?>"><?= e($pengumuman['isi'] ?? '') ?></textarea>
                <?php if (isset($errors['isi'])): ?>
                    <small class="form-error"><?= e($errors['isi']) ?></small>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($pengumuman['status_aktif']) || $pengumuman['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Pengumuman Aktif / Tampilkan</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' ?>
                </button>
                <a href="/admin/pengumuman" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
