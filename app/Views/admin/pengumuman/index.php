<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Broadcast Pengumuman</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola pesan & pengumuman internal</p>
        </div>
        <a href="/admin/pengumuman/tambah" class="btn btn-primary">
            + Pengumuman Baru
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($pengumumanList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada broadcast pengumuman.
            </div>
        <?php else: ?>
            <?php foreach ($pengumumanList as $item): ?>
                <div class="ui-card" style="margin-bottom: 10px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 15px; color: #1f2937;"><?= e($item['judul']) ?></strong>
                            <span class="badge" style="background: #e8f0ec; color: #1b4332;">Target: <?= e(strtoupper($item['target_peran'])) ?></span>
                            <span class="status-dot <?= $item['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $item['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="/admin/pengumuman/<?= $item['id'] ?>/edit" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="/admin/pengumuman/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');" style="margin: 0;">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <p style="margin: 0 0 8px; color: #4b5563; font-size: 13px; line-height: 1.5; white-space: pre-line;"><?= e($item['isi']) ?></p>

                    <small style="color: #6b7280; font-size: 11px;">
                        Diterbitkan oleh @<?= e($item['pembuat_nama']) ?> • <?= e($item['diterbitkan_pada']) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
