<section class="hero-panel">
    <div>
        <p class="eyebrow">Siatama Privat Bimbel</p>
        <h1>Bimbingan Belajar Berprestasi</h1>
        <p class="hero-copy">Solusi belajar terbaik untuk jenjang SD, SMP, SMA dengan tentor berkualitas & sistem pemantauan presensi.</p>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Pilihan Belajar</p>
            <h2>Program Pembelajaran</h2>
        </div>
    </div>

    <div class="stats-grid">
        <?php foreach ($programList as $p): ?>
            <div class="ui-card">
                <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 4px;"><?= e($p['nama']) ?></strong>
                <span class="badge" style="background: #e8f0ec; color: #1b4332;"><?= e($p['tipe']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Tentor Kami</p>
            <h2>Pengajar Berkualitas</h2>
        </div>
    </div>

    <div class="stack-list">
        <?php foreach ($tentorList as $t): ?>
            <div class="ui-card-item">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #f3f1ec; overflow: hidden; display: grid; place-items: center; font-weight: 700;">
                        <?php if ($t['foto']): ?>
                            <img src="<?= e($t['foto']) ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <?= e(strtoupper(substr($t['nama_lengkap'], 0, 1))) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong style="font-size: 14px; color: #1f2937;"><?= e($t['nama_lengkap']) ?></strong>
                        <small style="display: block; color: #6b7280; font-size: 11px;"><?= e($t['asal_universitas']) ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Publikasi</p>
            <h2>Berita Terbaru</h2>
        </div>
        <a href="/berita">Lihat Semua</a>
    </div>

    <div class="news-list">
        <?php foreach ($latestBerita as $news): ?>
            <a href="/berita/<?= e($news['slug']) ?>" class="news-card">
                <img src="<?= e($news['gambar'] ?: '/assets/blank-image.svg') ?>" alt="">
                <div>
                    <strong><?= e($news['judul']) ?></strong>
                    <span style="font-size: 11px; color: #6b7280;"><?= e($news['diterbitkan_pada']) ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
