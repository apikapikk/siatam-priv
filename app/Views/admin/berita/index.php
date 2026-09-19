<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Berita Publik</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola artikel & berita halaman depan</p>
        </div>
        <a href="/admin/berita/tambah" class="btn btn-primary">
            + Tulis Berita
        </a>
    </div>

    <div class="news-list" style="margin-top: 16px;">
        <?php if (empty($beritaList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada berita publik.
            </div>
        <?php else: ?>
            <?php foreach ($beritaList as $item): ?>
                <div class="news-card">
                    <?php if ($item['gambar']): ?>
                        <img src="<?= e($item['gambar']) ?>" alt="">
                    <?php else: ?>
                        <div style="width: 72px; height: 54px; border-radius: 8px; background: #f3f1ec; display: grid; place-items: center; color: #6b7280; font-size: 10px; font-weight: 700;">
                            NO IMAGE
                        </div>
                    <?php endif; ?>

                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <strong style="font-size: 14px; color: #1f2937;"><?= e($item['judul']) ?></strong>
                                <?php if ($item['status_terbit']): ?>
                                    <span class="badge" style="background: #ecfdf5; color: #065f46;">Terbit</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #f3f1ec; color: #6b7280;">Draft</span>
                                <?php endif; ?>
                            </div>

                            <div style="display: flex; align-items: center; gap: 6px;">
                                <a href="/admin/berita/<?= $item['id'] ?>/edit" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="/admin/berita/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?');" style="margin: 0;">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <span style="display: block; margin-top: 4px; color: #6b7280; font-size: 11px;">
                            Slug: <?= e($item['slug']) ?> • <?= $item['diterbitkan_pada'] ? e($item['diterbitkan_pada']) : 'Draft' ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
