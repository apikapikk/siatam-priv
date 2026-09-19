<div class="content-section">
    <div style="margin-bottom: 16px;">
        <a href="/berita" style="display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 500;">
            &larr; Kembali ke Daftar Berita
        </a>
        <h1 style="font-size: 22px; font-weight: 700; margin: 8px 0 0; color: #1f2937;"><?= e($berita['judul']) ?></h1>
        <small style="color: #6b7280; font-size: 12px;">Oleh @<?= e($berita['pembuat_nama']) ?> • Diterbitkan pada <?= e($berita['diterbitkan_pada']) ?></small>
    </div>

    <?php if ($berita['gambar']): ?>
        <div style="margin-bottom: 16px; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb;">
            <img src="<?= e($berita['gambar']) ?>" alt="" style="width: 100%; max-height: 240px; object-fit: cover; display: block;">
        </div>
    <?php endif; ?>

    <div class="ui-card">
        <p style="margin: 0; color: #374151; font-size: 14px; line-height: 1.6; white-space: pre-line;">
            <?= e($berita['isi']) ?>
        </p>
    </div>
</div>
