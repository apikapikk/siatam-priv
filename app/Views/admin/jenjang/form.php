<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/jenjang" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Jenjang
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Jenjang' : 'Tambah Jenjang Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/jenjang/' . $jenjang['id'] . '/update' : '/admin/jenjang/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Nama Jenjang</label>
                <input type="text" name="nama" value="<?= e($jenjang['nama'] ?? '') ?>" placeholder="Contoh: SD, SMP/MTs, SMA/MA" required class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['nama'])): ?>
                    <small class="form-error"><?= e($errors['nama']) ?></small>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Jenjang' ?>
                </button>
                <a href="/admin/jenjang" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
