<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Master Kelas</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola daftar kelas bimbingan</p>
        </div>
        <a href="/admin/kelas/tambah" class="btn btn-primary">
            + Tambah
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($kelasList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data kelas.
            </div>
        <?php else: ?>
            <?php foreach ($kelasList as $kelas): ?>
                <div class="ui-card-item">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 15px; color: #1f2937;"><?= e($kelas['nama']) ?></strong>
                            <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($kelas['jenjang_nama']) ?></span>
                            <span class="badge" style="background: #f3f1ec; color: #5c6460;"><?= e($kelas['program_nama']) ?></span>
                            <span class="status-dot <?= $kelas['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $kelas['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/kelas/<?= $kelas['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/kelas/<?= $kelas['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');" style="margin: 0;">
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
