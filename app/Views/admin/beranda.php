<section class="hero-panel">
    <div>
        <p class="eyebrow">Ringkasan Operasional</p>
        <h1>Beranda Admin</h1>
        <p class="hero-copy">Pantau kelas, jadwal, presensi, dan publikasi dari satu tempat.</p>
    </div>
    <div class="admin-avatar" aria-hidden="true">
        <img src="/assets/blank-image.svg" alt="">
    </div>
</section>

<?php if ($dashboard['using_fallback']): ?>
    <div class="soft-alert">
        Menampilkan data contoh karena koneksi database belum tersedia.
    </div>
<?php endif; ?>

<section class="stats-grid" aria-label="Statistik admin">
    <?php foreach ($dashboard['stats'] as $stat): ?>
        <article class="stat-card <?= e('tone-' . $stat['tone']) ?>">
            <span class="stat-dot" aria-hidden="true"></span>
            <p><?= e($stat['label']) ?></p>
            <strong><?= format_number((int) $stat['value']) ?></strong>
        </article>
    <?php endforeach; ?>
</section>

<section class="quick-actions" aria-label="Aksi cepat">
    <a href="#" class="quick-action">
        <span class="action-icon calendar-icon" aria-hidden="true"></span>
        <span>Jadwal</span>
    </a>
    <a href="#" class="quick-action">
        <span class="action-icon users-icon" aria-hidden="true"></span>
        <span>Siswa</span>
    </a>
    <a href="#" class="quick-action">
        <span class="action-icon tutor-icon" aria-hidden="true"></span>
        <span>Tentor</span>
    </a>
    <a href="#" class="quick-action">
        <span class="action-icon news-icon" aria-hidden="true"></span>
        <span>Berita</span>
    </a>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Hari Ini</p>
            <h2>Jadwal Mengajar</h2>
        </div>
        <a href="#">Lihat</a>
    </div>

    <div class="stack-list">
        <?php if (count($dashboard['today_schedule']) === 0): ?>
            <article class="empty-card">Belum ada jadwal aktif hari ini.</article>
        <?php endif; ?>

        <?php foreach ($dashboard['today_schedule'] as $schedule): ?>
            <article class="schedule-card">
                <time><?= e(substr($schedule['jam_mulai'], 0, 5)) ?> - <?= e(substr($schedule['jam_selesai'], 0, 5)) ?></time>
                <div>
                    <strong><?= e($schedule['jenjang_nama'] . ' ' . $schedule['kelas_nama']) ?></strong>
                    <span><?= e($schedule['program_nama']) ?> · <?= e($schedule['tentor_nama']) ?></span>
                </div>
                <small><?= e($schedule['ruangan'] ?: hari_indonesia((int) $schedule['hari'])) ?></small>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Internal</p>
            <h2>Pengumuman</h2>
        </div>
        <a href="#">Kelola</a>
    </div>

    <div class="stack-list">
        <?php foreach ($dashboard['announcements'] as $announcement): ?>
            <article class="notice-card">
                <strong><?= e($announcement['judul']) ?></strong>
                <p><?= e($announcement['isi']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Publik</p>
            <h2>Berita Terbaru</h2>
        </div>
        <a href="#">Kelola</a>
    </div>

    <div class="news-list">
        <?php foreach ($dashboard['news'] as $news): ?>
            <article class="news-card">
                <img src="<?= e($news['gambar'] ?: '/assets/blank-image.svg') ?>" alt="">
                <div>
                    <strong><?= e($news['judul']) ?></strong>
                    <span><?= ((int) $news['status_terbit']) === 1 ? 'Terbit' : 'Draft' ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
