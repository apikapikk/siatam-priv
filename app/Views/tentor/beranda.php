<section class="hero-panel" style="background: #1b4332;">
    <div>
        <p class="eyebrow">Portal Pengajar</p>
        <h1>Halo, <?= e($tentorProfil ? $tentorProfil['nama_lengkap'] : $_SESSION['username']) ?>!</h1>
        <p class="hero-copy">Selamat datang di portal mengajar Siatama Privat.</p>
    </div>
    <div class="admin-avatar" style="border-radius: 50%; overflow: hidden; width: 56px; height: 56px;">
        <?php if (!empty($tentorProfil['foto'])): ?>
            <img src="<?= e($tentorProfil['foto']) ?>" alt="">
        <?php else: ?>
            <div style="display: grid; place-items: center; width: 100%; height: 100%; background: #ffffff; color: #1b4332; font-weight: 700;">
                <?= e(strtoupper(substr($_SESSION['username'], 0, 1))) ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="stats-grid" style="margin-top: 16px;">
    <article class="stat-card tone-green">
        <span class="stat-dot" aria-hidden="true"></span>
        <p>Jadwal Ditugaskan</p>
        <strong><?= format_number($stats['total_jadwal']) ?></strong>
    </article>
    <article class="stat-card tone-amber">
        <span class="stat-dot" aria-hidden="true"></span>
        <p>Sesi Mengajar Bulan Ini</p>
        <strong><?= format_number($stats['sesi_bulan_ini']) ?></strong>
    </article>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Hari Ini</p>
            <h2>Jadwal Mengajar Anda</h2>
        </div>
        <a href="/tentor/jadwal">Lihat Semua</a>
    </div>

    <div class="stack-list">
        <?php if (empty($jadwalHariIni)): ?>
            <article class="empty-card">Tidak ada jadwal mengajar untuk Anda hari ini.</article>
        <?php else: ?>
            <?php foreach ($jadwalHariIni as $j): ?>
                <article class="schedule-card">
                    <time><?= e(substr($j['jam_mulai'], 0, 5)) ?> - <?= e(substr($j['jam_selesai'], 0, 5)) ?></time>
                    <div>
                        <strong>Kelas <?= e($j['kelas_nama']) ?> (<?= e($j['jenjang_nama']) ?>)</strong>
                        <span>Program <?= e($j['program_nama']) ?></span>
                    </div>
                    <small><?= e($j['ruangan'] ?: 'Ruang Umum') ?></small>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="content-section">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Broadcast</p>
            <h2>Pengumuman Internal</h2>
        </div>
    </div>

    <div class="stack-list">
        <?php if (empty($announcements)): ?>
            <article class="empty-card">Belum ada pengumuman baru.</article>
        <?php else: ?>
            <?php foreach ($announcements as $ann): ?>
                <article class="notice-card">
                    <strong><?= e($ann['judul']) ?></strong>
                    <p><?= e($ann['isi']) ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
