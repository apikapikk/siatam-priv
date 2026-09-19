<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/berita" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Berita
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Berita Publik' : 'Tulis Berita Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/berita/' . $berita['id'] . '/update' : '/admin/berita/simpan' ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Judul Berita</label>
                <input type="text" name="judul" value="<?= e($berita['judul'] ?? '') ?>" placeholder="Judul berita menarik" required class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['judul'])): ?>
                    <small class="form-error"><?= e($errors['judul']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Utama / Header Berita <small style="font-weight: 400; color: #6b7280;">(Opsional)</small></label>
                <?php if (!empty($berita['gambar'])): ?>
                    <div style="margin-bottom: 8px;">
                        <img src="<?= e($berita['gambar']) ?>" alt="" style="width: 120px; height: 80px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb;">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="form-control <?= isset($errors['gambar']) ? 'is-invalid' : '' ?>">
                <small style="color: #6b7280; font-size: 12px;">Format: JPG, PNG, WEBP. Maksimal 3 MB.</small>
                <?php if (isset($errors['gambar'])): ?>
                    <small class="form-error"><?= e($errors['gambar']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Konten / Isi Berita</label>
                <textarea name="isi" rows="8" placeholder="Tuliskan konten berita..." required class="form-control <?= isset($errors['isi']) ? 'is-invalid' : '' ?>"><?= e($berita['isi'] ?? '') ?></textarea>
                <?php if (isset($errors['isi'])): ?>
                    <small class="form-error"><?= e($errors['isi']) ?></small>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_terbit" value="1" <?= (!empty($berita['status_terbit'])) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Terbitkan Berita Ke Publik (Publish)</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Berita' ?>
                </button>
                <a href="/admin/berita" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
