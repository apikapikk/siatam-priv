<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/pendaftaran" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Pendaftaran
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Penempatan Siswa' : 'Pendaftaran Kelas Siswa Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/pendaftaran/' . $pendaftaran['id'] . '/update' : '/admin/pendaftaran/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Siswa</label>
                <select name="siswa_id" required class="form-control <?= isset($errors['siswa_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswaList as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ((int) ($pendaftaran['siswa_id'] ?? 0) === (int) $s['id']) ? 'selected' : '' ?>>
                            <?= e($s['nama_lengkap']) ?> (Sekolah: <?= e($s['asal_sekolah']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['siswa_id'])): ?>
                    <small class="form-error"><?= e($errors['siswa_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" required class="form-control <?= isset($errors['kelas_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ((int) ($pendaftaran['kelas_id'] ?? 0) === (int) $k['id']) ? 'selected' : '' ?>>
                            <?= e($k['nama']) ?> - <?= e($k['jenjang_nama']) ?> (<?= e($k['program_nama']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['kelas_id'])): ?>
                    <small class="form-error"><?= e($errors['kelas_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Paket Pembelajaran <small style="font-weight: 400; color: #6b7280;">(Opsional)</small></label>
                <select name="paket_id" class="form-control">
                    <option value="">-- Tanpa Paket / Default --</option>
                    <?php foreach ($paketList as $pk): ?>
                        <option value="<?= $pk['id'] ?>" <?= ((int) ($pendaftaran['paket_id'] ?? 0) === (int) $pk['id']) ? 'selected' : '' ?>>
                            <?= e($pk['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Mulai Mengikuti</label>
                <input type="date" name="tanggal_mulai" value="<?= e($pendaftaran['tanggal_mulai'] ?? date('Y-m-d')) ?>" required class="form-control <?= isset($errors['tanggal_mulai']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['tanggal_mulai'])): ?>
                    <small class="form-error"><?= e($errors['tanggal_mulai']) ?></small>
                <?php endif; ?>
            </div>

            <?php if ($isEdit): ?>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai Mengikuti <small style="font-weight: 400; color: #6b7280;">(Kosongkan jika masih berlangsung)</small></label>
                    <input type="date" name="tanggal_selesai" value="<?= e($pendaftaran['tanggal_selesai'] ?? '') ?>" class="form-control">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Status Pendaftaran</label>
                <select name="status" required class="form-control">
                    <option value="aktif" <?= ($pendaftaran['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif (Sedang Mengikuti)</option>
                    <option value="selesai" <?= ($pendaftaran['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai (Sudah Lulus / Pindah)</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Proses Pendaftaran' ?>
                </button>
                <a href="/admin/pendaftaran" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
