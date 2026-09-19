<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Master Jenjang</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola jenjang pendidikan bimbel</p>
        </div>
        <a href="/admin/jenjang/tambah" class="btn btn-primary">
            + Tambah
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($jenjangList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data jenjang.
            </div>
        <?php else: ?>
            <?php foreach ($jenjangList as $item): ?>
                <div class="ui-card-item">
                    <div>
                        <strong style="font-size: 15px; color: #1f2937;"><?= e($item['nama']) ?></strong>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a href="/admin/jenjang/<?= $item['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/jenjang/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenjang ini?');" style="margin: 0;">
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
