<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/tentor/pertemuan" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Pertemuan
        </a>
        <h1 style="font-size: 20px; font-weight: 700; margin: 8px 0 0;">Input Presensi & Nilai Siswa</h1>
        <p class="eyebrow" style="margin-top: 4px;">Pertemuan #<?= e($pertemuan['nomor_pertemuan']) ?> • Tanggal: <?= e($pertemuan['tanggal']) ?></p>
    </div>

    <form action="/tentor/pertemuan/<?= $pertemuan['id'] ?>/presensi/update" method="POST">
        <div class="stack-list">
            <?php if (empty($presensiList)): ?>
                <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                    Belum ada siswa yang terdaftar di kelas ini.
                </div>
            <?php else: ?>
                <?php foreach ($presensiList as $item): ?>
                    <div class="ui-card" style="margin-bottom: 12px; padding: 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                            <div>
                                <strong style="font-size: 15px; color: #1f2937;"><?= e($item['siswa_nama']) ?></strong>
                                <small style="display: block; color: #6b7280; font-size: 11px;"><?= e($item['asal_sekolah']) ?></small>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div>
                                <label class="form-label">Kehadiran</label>
                                <select name="presensi[<?= $item['siswa_id'] ?>][status_kehadiran]" class="form-control">
                                    <option value="none" <?= ($item['status_kehadiran'] === 'none') ? 'selected' : '' ?>>- Belum Diisi -</option>
                                    <option value="hadir" <?= ($item['status_kehadiran'] === 'hadir') ? 'selected' : '' ?>>Hadir</option>
                                    <option value="sakit" <?= ($item['status_kehadiran'] === 'sakit') ? 'selected' : '' ?>>Sakit</option>
                                    <option value="izin" <?= ($item['status_kehadiran'] === 'izin') ? 'selected' : '' ?>>Izin</option>
                                    <option value="alfa" <?= ($item['status_kehadiran'] === 'alfa') ? 'selected' : '' ?>>Alfa</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Nilai Sikap</label>
                                <select name="presensi[<?= $item['siswa_id'] ?>][nilai_sikap]" class="form-control">
                                    <option value="">-</option>
                                    <option value="A" <?= ($item['nilai_sikap'] === 'A') ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= ($item['nilai_sikap'] === 'B') ? 'selected' : '' ?>>B</option>
                                    <option value="C" <?= ($item['nilai_sikap'] === 'C') ? 'selected' : '' ?>>C</option>
                                    <option value="D" <?= ($item['nilai_sikap'] === 'D') ? 'selected' : '' ?>>D</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Nilai Akademik</label>
                                <select name="presensi[<?= $item['siswa_id'] ?>][nilai_akademik]" class="form-control">
                                    <option value="">-</option>
                                    <option value="A" <?= ($item['nilai_akademik'] === 'A') ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= ($item['nilai_akademik'] === 'B') ? 'selected' : '' ?>>B</option>
                                    <option value="C" <?= ($item['nilai_akademik'] === 'C') ? 'selected' : '' ?>>C</option>
                                    <option value="D" <?= ($item['nilai_akademik'] === 'D') ? 'selected' : '' ?>>D</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Catatan Perkembangan Siswa</label>
                            <input type="text" name="presensi[<?= $item['siswa_id'] ?>][catatan]" value="<?= e($item['catatan'] ?? '') ?>" placeholder="Tuliskan catatan perkembangan atau tugas siswa..." class="form-control">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($presensiList)): ?>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    Simpan Presensi & Nilai
                </button>
                <a href="/tentor/pertemuan" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>
