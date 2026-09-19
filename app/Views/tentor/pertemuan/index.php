<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Sesi Pertemuan Mengajar</h1>
            <p class="eyebrow" style="margin-top: 4px;">Riwayat mengajar & presensi siswa</p>
        </div>
        <a href="/tentor/pertemuan/tambah" class="btn btn-primary">
            + Catat Sesi
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($pertemuanList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada sesi pertemuan yang Anda catat.
            </div>
        <?php else: ?>
            <?php foreach ($pertemuanList as $item): ?>
                <div class="ui-card" style="margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <div>
                            <strong style="font-size: 15px; color: #1f2937;">
                                Pertemuan #<?= e($item['nomor_pertemuan']) ?> — Kelas <?= e($item['kelas_nama']) ?>
                            </strong>
                            <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($item['jenjang_nama']) ?> - <?= e($item['program_nama']) ?></span>
                        </div>

                        <div>
                            <a href="/tentor/pertemuan/<?= $item['id'] ?>/presensi" class="btn btn-sm btn-primary">
                                Presensi (<?= $item['total_hadir'] ?>/<?= $item['total_presensi'] ?> Hadir)
                            </a>
                        </div>
                    </div>

                    <small style="color: #6b7280; font-size: 12px;">
                        Tanggal: <strong><?= e($item['tanggal']) ?></strong> (<?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?>)
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
