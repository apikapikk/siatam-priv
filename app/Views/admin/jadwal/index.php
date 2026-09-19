<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Jadwal Mengajar</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola jadwal bimbingan tentor & kelas</p>
        </div>
        <a href="/admin/jadwal/tambah" class="btn btn-primary">
            + Tambah Jadwal
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($jadwalList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada jadwal mengajar.
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
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <strong style="font-size: 15px; color: #1f2937;">
                                    Kelas <?= e($item['kelas_nama']) ?>
                                    <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($item['jenjang_nama']) ?></span>
                                    <span class="badge" style="background: #f3f1ec; color: #5c6460;"><?= e($item['program_nama']) ?></span>
                                </strong>
                                <span class="status-dot <?= $item['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $item['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                            </div>

                            <div style="display: flex; align-items: center; gap: 8px;">
                                <a href="/admin/jadwal/<?= $item['id'] ?>/edit" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="/admin/jadwal/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');" style="margin: 0;">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <small style="display: block; margin-top: 6px; color: #6b7280; font-size: 12px;">
                            Tentor: <strong><?= e($item['tentor_nama']) ?></strong> <?= $item['ruangan'] ? '• Ruangan: ' . e($item['ruangan']) : '' ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
