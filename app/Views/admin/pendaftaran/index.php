<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Pendaftaran & Penempatan Siswa</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola pendaftaran kelas dan histori siswa</p>
        </div>
        <a href="/admin/pendaftaran/tambah" class="btn btn-primary">
            + Daftarkan Siswa
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($pendaftaranList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data pendaftaran siswa.
            </div>
        <?php else: ?>
            <?php foreach ($pendaftaranList as $item): ?>
                <div class="ui-card-item">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 15px; color: #1f2937;"><?= e($item['siswa_nama']) ?></strong>
                            <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($item['kelas_nama']) ?> (<?= e($item['jenjang_nama']) ?> - <?= e($item['program_nama']) ?>)</span>
                            <?php if ($item['status'] === 'aktif'): ?>
                                <span class="badge" style="background: #ecfdf5; color: #065f46;">Aktif</span>
                            <?php else: ?>
                                <span class="badge" style="background: #f3f1ec; color: #6b7280;">Selesai</span>
                            <?php endif; ?>
                        </div>
                        <small style="display: block; margin-top: 4px; color: #6b7280; font-size: 12px;">
                            Mulai: <?= e($item['tanggal_mulai']) ?> <?= $item['tanggal_selesai'] ? '• Selesai: ' . e($item['tanggal_selesai']) : '' ?>
                            <?= $item['paket_nama'] ? '• Paket: ' . e($item['paket_nama']) : '' ?>
                        </small>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/pendaftaran/<?= $item['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/pendaftaran/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus pendaftaran ini?');" style="margin: 0;">
                            <button type="submit" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
