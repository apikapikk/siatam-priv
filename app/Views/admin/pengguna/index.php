<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Manajemen Pengguna</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola akun Admin, Owner & Tentor</p>
        </div>
        <a href="/admin/pengguna/tambah" class="btn btn-primary">
            + Tambah
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($penggunaList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data pengguna.
            </div>
        <?php else: ?>
            <?php foreach ($penggunaList as $user): ?>
                <div class="ui-card-item">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 15px; color: #1f2937;"><?= e($user['username']) ?></strong>
                            <span class="badge badge-<?= e($user['peran']) ?>">
                                <?= e($user['peran']) ?>
                            </span>
                            <span class="status-dot <?= $user['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $user['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                        </div>
                        <small style="display: block; margin-top: 4px; color: #6b7280; font-size: 12px;">
                            Terakhir login: <?= $user['terakhir_login'] ? e($user['terakhir_login']) : '-' ?>
                        </small>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/pengguna/<?= $user['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/pengguna/<?= $user['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');" style="margin: 0;">
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
