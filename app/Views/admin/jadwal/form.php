<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/admin/jadwal" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Jadwal
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;"><?= $isEdit ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar Baru' ?></h1>
    </div>

    <div class="ui-card">
        <form action="<?= $isEdit ? '/admin/jadwal/' . $jadwal['id'] . '/update' : '/admin/jadwal/simpan' ?>" method="POST">
            <div class="form-group">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" required class="form-control <?= isset($errors['kelas_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ((int) ($jadwal['kelas_id'] ?? 0) === (int) $k['id']) ? 'selected' : '' ?>>
                            <?= e($k['nama']) ?> - <?= e($k['jenjang_nama']) ?> (<?= e($k['program_nama']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['kelas_id'])): ?>
                    <small class="form-error"><?= e($errors['kelas_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Tentor Pengajar</label>
                <select name="tentor_id" required class="form-control <?= isset($errors['tentor_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Tentor --</option>
                    <?php foreach ($tentorList as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ((int) ($jadwal['tentor_id'] ?? 0) === (int) $t['id']) ? 'selected' : '' ?>>
                            <?= e($t['nama_lengkap']) ?> (Univ: <?= e($t['asal_universitas']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['tentor_id'])): ?>
                    <small class="form-error"><?= e($errors['tentor_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Hari Mengajar</label>
                <select name="hari" required class="form-control <?= isset($errors['hari']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Hari --</option>
                    <option value="1" <?= ((int) ($jadwal['hari'] ?? 0) === 1) ? 'selected' : '' ?>>Senin</option>
                    <option value="2" <?= ((int) ($jadwal['hari'] ?? 0) === 2) ? 'selected' : '' ?>>Selasa</option>
                    <option value="3" <?= ((int) ($jadwal['hari'] ?? 0) === 3) ? 'selected' : '' ?>>Rabu</option>
                    <option value="4" <?= ((int) ($jadwal['hari'] ?? 0) === 4) ? 'selected' : '' ?>>Kamis</option>
                    <option value="5" <?= ((int) ($jadwal['hari'] ?? 0) === 5) ? 'selected' : '' ?>>Jumat</option>
                    <option value="6" <?= ((int) ($jadwal['hari'] ?? 0) === 6) ? 'selected' : '' ?>>Sabtu</option>
                    <option value="7" <?= ((int) ($jadwal['hari'] ?? 0) === 7) ? 'selected' : '' ?>>Minggu</option>
                </select>
                <?php if (isset($errors['hari'])): ?>
                    <small class="form-error"><?= e($errors['hari']) ?></small>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="<?= e($jadwal['jam_mulai'] ?? '15:30') ?>" required class="form-control <?= isset($errors['jam_mulai']) ? 'is-invalid' : '' ?>">
                    <?php if (isset($errors['jam_mulai'])): ?>
                        <small class="form-error"><?= e($errors['jam_mulai']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="<?= e($jadwal['jam_selesai'] ?? '17:00') ?>" required class="form-control <?= isset($errors['jam_selesai']) ? 'is-invalid' : '' ?>">
                    <?php if (isset($errors['jam_selesai'])): ?>
                        <small class="form-error"><?= e($errors['jam_selesai']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ruangan <small style="font-weight: 400; color: #6b7280;">(Opsional)</small></label>
                <input type="text" name="ruangan" value="<?= e($jadwal['ruangan'] ?? '') ?>" placeholder="Contoh: Ruang A, Ruang 102" class="form-control">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                    <input type="checkbox" name="status_aktif" value="1" <?= (!isset($jadwal['status_aktif']) || $jadwal['status_aktif']) ? 'checked' : '' ?> style="width: 18px; height: 18px; accent-color: #1b4332;">
                    <span>Jadwal Aktif</span>
                </label>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Jadwal' ?>
                </button>
                <a href="/admin/jadwal" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
