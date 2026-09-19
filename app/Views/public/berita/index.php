<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Berita & Artikel</h1>
            <p class="eyebrow" style="margin-top: 4px;">Informasi kegiatan & pengumuman publik</p>
        </div>
    </div>

    <div class="news-list" style="margin-top: 16px;">
        <?php if (empty($beritaList)): ?>
            <div class="empty-card">Belum ada berita publik.</div>
        <?php else: ?>
            <?php foreach ($beritaList as $item): ?>
                <a href="/berita/<?= e($item['slug']) ?>" class="news-card">
                    <img src="<?= e($item['gambar'] ?: '/assets/blank-image.svg') ?>" alt="">
                    <div>
                        <strong style="font-size: 15px; color: #1f2937;"><?= e($item['judul']) ?></strong>
                        <span style="font-size: 12px; color: #6b7280; display: block; margin-top: 4px;"><?= e($item['diterbitkan_pada']) ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
