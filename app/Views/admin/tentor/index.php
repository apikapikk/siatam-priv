<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Profil Tentor</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola biodata dan profil pengajar</p>
        </div>
        <a href="/admin/tentor/tambah" class="btn btn-primary">
            + Tambah Tentor
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($tentorList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data profil tentor.
            </div>
        <?php else: ?>
            <?php foreach ($tentorList as $tentor): ?>
                <div class="ui-card-item">
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; overflow: hidden; background: #f3f1ec; flex-shrink: 0; border: 1px solid #e5e7eb;">
                            <?php if ($tentor['foto']): ?>
                                <img src="<?= e($tentor['foto']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="display: grid; place-items: center; width: 100%; height: 100%; color: #6b7280; font-weight: 700;">
                                    <?= e(strtoupper(substr($tentor['nama_lengkap'], 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="font-size: 15px; color: #1f2937;"><?= e($tentor['nama_lengkap']) ?></strong>
                                <span class="badge badge-tentor">@<?= e($tentor['username']) ?></span>
                                <span class="status-dot <?= $tentor['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $tentor['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                            </div>
                            <small style="display: block; margin-top: 3px; color: #6b7280; font-size: 12px;">
                                Universitas: <?= e($tentor['asal_universitas']) ?> <?= $tentor['nomor_telepon'] ? '• Telp: ' . e($tentor['nomor_telepon']) : '' ?>
                            </small>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/tentor/<?= $tentor['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/tentor/<?= $tentor['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus profil tentor ini?');" style="margin: 0;">
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
