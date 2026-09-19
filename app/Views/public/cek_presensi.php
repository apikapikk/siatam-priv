<div class="content-section">
    <div style="margin-bottom: 16px;">
        <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Cek Presensi & Riwayat Belajar</h1>
        <p class="eyebrow" style="margin-top: 4px;">Pencarian terbuka untuk orang tua & publik</p>
    </div>

    <div class="ui-card" style="margin-bottom: 16px;">
        <form action="/cek-presensi" method="GET">
            <div class="form-group" style="margin-bottom: 10px;">
                <label class="form-label">Cari Nama Siswa atau Asal Sekolah</label>
                <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Masukkan nama siswa..." required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Cari Data Siswa
            </button>
        </form>
    </div>

    <?php if (!empty($keyword)): ?>
        <?php if (empty($siswaResult)): ?>
            <div class="empty-card">
                Siswa dengan kata kunci "<strong><?= e($keyword) ?></strong>" tidak ditemukan.
            </div>
        <?php else: ?>
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Hasil Pencarian Siswa:</label>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <?php foreach ($siswaResult as $s): ?>
                        <a href="/cek-presensi?q=<?= urlencode($keyword) ?>&siswa_id=<?= $s['id'] ?>" class="btn btn-sm <?= ((int) $selectedSiswaId === (int) $s['id']) ? 'btn-primary' : 'btn-secondary' ?>">
                            <?= e($s['nama_lengkap']) ?> (<?= e($s['asal_sekolah']) ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="stack-list">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 10px; color: #1b4332;">Riwayat Presensi & Evaluasi:</h2>
                <?php if (empty($presensiList)): ?>
                    <div class="empty-card">Belum ada catatan presensi untuk siswa ini.</div>
                <?php else: ?>
                    <?php foreach ($presensiList as $p): ?>
                        <div class="ui-card" style="margin-bottom: 10px; padding: 14px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <strong style="font-size: 14px; color: #1f2937;">
                                    Pertemuan #<?= e($p['nomor_pertemuan']) ?> — <?= e($p['kelas_nama']) ?> (<?= e($p['jenjang_nama']) ?>)
                                </strong>
                                <span class="badge" style="background: <?= $p['status_kehadiran'] === 'hadir' ? '#ecfdf5; color: #065f46;' : ($p['status_kehadiran'] === 'izin' ? '#eff6ff; color: #1e40af;' : '#fef2f2; color: #991b1b;') ?>">
                                    <?= e(strtoupper($p['status_kehadiran'])) ?>
                                </span>
                            </div>

                            <small style="display: block; color: #6b7280; font-size: 12px; margin-bottom: 6px;">
                                Tanggal: <?= e($p['tanggal']) ?> (<?= substr($p['jam_mulai'], 0, 5) ?>-<?= substr($p['jam_selesai'], 0, 5) ?>) • Tentor: <?= e($p['tentor_nama']) ?>
                            </small>

                            <?php if ($p['nilai_sikap'] || $p['nilai_akademik']): ?>
                                <div style="display: flex; gap: 12px; font-size: 12px; margin-top: 4px; background: #faf9f7; padding: 6px 10px; border-radius: 8px;">
                                    <span>Nilai Sikap: <strong><?= e($p['nilai_sikap'] ?: '-') ?></strong></span>
                                    <span>Nilai Akademik: <strong><?= e($p['nilai_akademik'] ?: '-') ?></strong></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($p['catatan'])): ?>
                                <p style="margin: 6px 0 0; color: #4b5563; font-size: 12px; font-style: italic;">
                                    Catatan: "<?= e($p['catatan']) ?>"
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
