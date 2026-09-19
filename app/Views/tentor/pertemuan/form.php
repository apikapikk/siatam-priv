<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/tentor/pertemuan" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Riwayat Pertemuan
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;">Catat Sesi Pertemuan Baru</h1>
    </div>

    <div class="ui-card">
        <form action="/tentor/pertemuan/simpan" method="POST">
            <div class="form-group">
                <label class="form-label">Pilih Jadwal Kelas</label>
                <select name="jadwal_id" required class="form-control <?= isset($errors['jadwal_id']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Pilih Jadwal --</option>
                    <?php foreach ($jadwalList as $j): ?>
                        <option value="<?= $j['id'] ?>">
                            Kelas <?= e($j['kelas_nama']) ?> (<?= hari_indonesia((int) $j['hari']) ?> <?= substr($j['jam_mulai'], 0, 5) ?>-<?= substr($j['jam_selesai'], 0, 5) ?>) - <?= e($j['jenjang_nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['jadwal_id'])): ?>
                    <small class="form-error"><?= e($errors['jadwal_id']) ?></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="form-control <?= isset($errors['tanggal']) ? 'is-invalid' : '' ?>">
                <?php if (isset($errors['tanggal'])): ?>
                    <small class="form-error"><?= e($errors['tanggal']) ?></small>
                <?php endif; ?>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label class="form-label">Jam Mulai Aktual</label>
                    <input type="time" name="jam_mulai" value="15:30" required class="form-control <?= isset($errors['jam_mulai']) ? 'is-invalid' : '' ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Jam Selesai Aktual</label>
                    <input type="time" name="jam_selesai" value="17:00" required class="form-control <?= isset($errors['jam_selesai']) ? 'is-invalid' : '' ?>">
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    Simpan & Lanjut Isi Presensi
                </button>
                <a href="/tentor/pertemuan" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
