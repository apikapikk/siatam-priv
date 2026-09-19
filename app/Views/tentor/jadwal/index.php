<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Jadwal Mengajar Saya</h1>
            <p class="eyebrow" style="margin-top: 4px;">Daftar kelas yang ditugaskan kepada Anda</p>
        </div>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($jadwalList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada jadwal mengajar yang ditugaskan untuk Anda.
            </div>
        <?php else: ?>
            <?php foreach ($jadwalList as $item): ?>
                <div class="schedule-card" style="background: #ffffff; border-radius: 12px; border: 1px solid #e5e7eb; padding: 14px; margin-bottom: 10px;">
                    <div>
                        <time style="display: block; font-weight: 700; color: #1b4332; font-size: 13px;">
                            <?= hari_indonesia((int) $item['hari']) ?>
                        </time>
                        <small style="color: #6b7280; font-size: 11px;">
                            <?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?>
                        </small>
                    </div>

                    <div>
                        <strong style="font-size: 15px; color: #1f2937;">
                            Kelas <?= e($item['kelas_nama']) ?>
                            <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($item['jenjang_nama']) ?></span>
                            <span class="badge" style="background: #f3f1ec; color: #5c6460;"><?= e($item['program_nama']) ?></span>
                        </strong>
                        <small style="display: block; margin-top: 4px; color: #6b7280; font-size: 12px;">
                            Ruangan: <?= e($item['ruangan'] ?: 'Ruang Umum') ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
