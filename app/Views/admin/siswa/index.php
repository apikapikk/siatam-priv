<div class="content-section">
    <div class="section-heading">
        <div>
            <h1 style="font-size: 20px; font-weight: 700; margin: 0;">Data Siswa</h1>
            <p class="eyebrow" style="margin-top: 4px;">Kelola identitas dan wali murid</p>
        </div>
        <a href="/admin/siswa/tambah" class="btn btn-primary">
            + Tambah Siswa
        </a>
    </div>

    <div class="stack-list" style="margin-top: 16px;">
        <?php if (empty($siswaList)): ?>
            <div class="empty-card" style="text-align: center; color: var(--muted); padding: 24px;">
                Belum ada data siswa.
            </div>
        <?php else: ?>
            <?php foreach ($siswaList as $siswa): ?>
                <div class="ui-card-item">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 15px; color: #1f2937;"><?= e($siswa['nama_lengkap']) ?></strong>
                            <?php if ($siswa['kelas_aktif']): ?>
                                <span class="badge" style="background: #e0e7ff; color: #3730a3;"><?= e($siswa['kelas_aktif']) ?></span>
                            <?php else: ?>
                                <span class="badge" style="background: #f3f1ec; color: #6b7280;">Belum Ada Kelas</span>
                            <?php endif; ?>
                            <span class="status-dot <?= $siswa['status_aktif'] ? 'status-dot-active' : 'status-dot-inactive' ?>" title="<?= $siswa['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                        </div>
                        <small style="display: block; margin-top: 4px; color: #6b7280; font-size: 12px;">
                            Sekolah: <?= e($siswa['asal_sekolah']) ?>
                        </small>
                        <?php if (!empty($siswa['daftar_orang_tua'])): ?>
                            <small style="display: block; margin-top: 2px; color: #1b4332; font-size: 11px; font-weight: 500;">
                                Wali: <?= e($siswa['daftar_orang_tua']) ?>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                        <a href="/admin/siswa/<?= $siswa['id'] ?>/edit" class="btn btn-sm btn-secondary">
                            Edit
                        </a>
                        <form action="/admin/siswa/<?= $siswa['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');" style="margin: 0;">
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
