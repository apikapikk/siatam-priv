<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center gap-3">
        <a href="/tentor/presensi" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm" aria-label="Kembali"><span class="material-symbols-outlined">arrow_back</span></a>
        <div><h1 class="text-xl font-bold text-[#2D3E39]">Isi Kehadiran Siswa</h1><p class="text-xs text-gray-500">Kelas <?= e($kelas['nama'] ?? '-') ?> · <?= e($kelas['jenjang_nama'] ?? '') ?></p></div>
    </div>

    <form id="presensi-form" action="/tentor/presensi/simpan" method="POST" class="flex flex-col gap-5">
        <?php if (!empty($pertemuan)): ?><input type="hidden" name="pertemuan_id" value="<?= (int) $pertemuan['id'] ?>"><?php endif; ?>
        <input type="hidden" name="jadwal_id" value="<?= (int) ($jadwal['id'] ?? 0) ?>">
        <input type="hidden" name="kelas_id" value="<?= (int) ($kelas['id'] ?? 0) ?>">
        <section class="bg-white rounded-2xl p-4 md:p-5 border border-gray-200/80 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <label class="text-xs font-semibold text-gray-700">Pertemuan Ke-<input required type="number" min="1" name="pertemuan" value="<?= (int) $nomorPertemuan ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#FAF9F7] text-sm"></label>
                <label class="text-xs font-semibold text-gray-700">Tanggal<input required type="date" name="tanggal" value="<?= e($tanggal) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#FAF9F7] text-sm"></label>
                <div class="grid grid-cols-2 gap-2"><label class="text-xs font-semibold text-gray-700">Mulai<input required type="time" name="waktu_mulai" value="<?= e($jamMulai) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#FAF9F7] text-sm"></label><label class="text-xs font-semibold text-gray-700">Selesai<input required type="time" name="waktu_selesai" value="<?= e($jamSelesai) ?>" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#FAF9F7] text-sm"></label></div>
            </div>
        </section>

        <section class="flex flex-col gap-3">
            <div class="flex items-center justify-between"><h2 class="text-base font-bold text-[#2D3E39]">Daftar Siswa</h2><span class="text-xs text-gray-500"><?= count($presensiList) ?> siswa</span></div>
            <?php if (empty($presensiList)): ?><div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200/80">Belum ada siswa aktif di kelas ini.</div><?php endif; ?>
            <?php foreach ($presensiList as $item): $status = $item['status_kehadiran'] ?: 'none'; ?>
                <article class="student-card bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm" data-student="<?= (int) $item['siswa_id'] ?>">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0"><div class="w-11 h-11 rounded-full bg-[#e8f0ec] text-[#324f47] flex items-center justify-center font-bold shrink-0"><?= e(strtoupper(substr($item['siswa_nama'], 0, 1))) ?></div><div class="min-w-0"><h3 class="font-bold text-sm text-gray-900 truncate"><?= e($item['siswa_nama']) ?></h3><p class="text-xs text-gray-500 truncate"><?= e($item['asal_sekolah'] ?: 'Sekolah belum diisi') ?></p></div></div>
                        <div class="flex gap-1.5" role="group" aria-label="Status kehadiran <?= e($item['siswa_nama']) ?>">
                            <?php foreach (['hadir' => 'H', 'sakit' => 'S', 'izin' => 'I', 'alfa' => 'A', 'none' => 'N'] as $value => $label): ?><button type="button" data-status="<?= $value ?>" class="status-btn w-9 h-9 rounded-full border text-xs font-bold transition-colors <?= $status === $value ? 'bg-[#324f47] border-[#324f47] text-white' : 'border-gray-300 text-gray-600 hover:bg-[#e8f0ec]' ?>"><?= $label ?></button><?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" data-field="status" name="presensi[<?= (int) $item['siswa_id'] ?>][status_kehadiran]" value="<?= e($status) ?>">
                    <input type="hidden" data-field="sikap" name="presensi[<?= (int) $item['siswa_id'] ?>][nilai_sikap]" value="<?= e($item['nilai_sikap'] ?? '') ?>">
                    <input type="hidden" data-field="akademik" name="presensi[<?= (int) $item['siswa_id'] ?>][nilai_akademik]" value="<?= e($item['nilai_akademik'] ?? '') ?>">
                    <input type="hidden" data-field="catatan" name="presensi[<?= (int) $item['siswa_id'] ?>][catatan]" value="<?= e($item['catatan'] ?? '') ?>">
                    <div class="grade-summary mt-3 text-xs text-gray-500 <?= ($item['nilai_sikap'] || $item['nilai_akademik']) ? '' : 'hidden' ?>">Nilai kemampuan: <strong data-summary="akademik"><?= e($item['nilai_akademik'] ?: '-') ?></strong> · Sikap: <strong data-summary="sikap"><?= e($item['nilai_sikap'] ?: '-') ?></strong></div>
                </article>
            <?php endforeach; ?>
        </section>
        <?php if (!empty($presensiList)): ?><div class="flex gap-3"><button type="submit" class="flex-1 py-3 rounded-xl bg-[#324f47] text-white text-sm font-semibold flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[18px]">save</span>Simpan Presensi & Nilai</button><a href="/tentor/presensi" class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 text-sm font-semibold">Batal</a></div><?php endif; ?>
    </form>
</div>

<div id="grade-modal" class="hidden fixed inset-0 z-[60] bg-black/40 items-end md:items-center justify-center p-0 md:p-4" role="dialog" aria-modal="true" aria-labelledby="grade-title">
    <div class="bg-white w-full md:max-w-lg rounded-t-3xl md:rounded-3xl p-5 md:p-6 shadow-2xl">
        <div class="flex items-center justify-between"><h2 id="grade-title" class="text-lg font-bold text-[#2D3E39]">Penilaian Siswa</h2><button type="button" id="grade-close" class="text-gray-500"><span class="material-symbols-outlined">close</span></button></div>
        <p id="grade-student" class="text-xs text-gray-500 mt-1"></p>
        <div class="grid gap-3 mt-5"><label class="text-xs font-semibold text-gray-700">Nilai Kemampuan<select id="grade-akademik" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm"><option value="">Pilih nilai kemampuan</option><option value="A">Sangat Baik (A)</option><option value="B">Baik (B)</option><option value="C">Cukup (C)</option><option value="D">Kurang (D)</option></select></label><label class="text-xs font-semibold text-gray-700">Nilai Sikap<select id="grade-sikap" class="mt-1.5 w-full rounded-xl border-gray-200 bg-[#F2EFE9] text-sm"><option value="">Pilih nilai sikap</option><option value="A">Sangat Baik (A)</option><option value="B">Baik (B)</option><option value="C">Cukup (C)</option><option value="D">Kurang (D)</option></select></label></div>
        <div class="flex gap-2 mt-6"><button type="button" id="grade-cancel" class="flex-1 py-3 rounded-full text-sm font-semibold text-gray-600 hover:bg-gray-100">Batal</button><button type="button" id="grade-save" class="flex-1 py-3 rounded-full bg-[#324f47] text-white text-sm font-semibold">Simpan Penilaian</button></div>
    </div>
</div>
<script>
(() => {
    const modal = document.getElementById('grade-modal'); let activeCard = null;
    const close = () => { modal.classList.add('hidden'); modal.classList.remove('flex'); activeCard = null; };
    document.getElementById('grade-close').addEventListener('click', close); document.getElementById('grade-cancel').addEventListener('click', close);
    document.querySelectorAll('.student-card').forEach(card => {
        const statusInput = card.querySelector('[data-field="status"]');
        card.querySelectorAll('.status-btn').forEach(button => button.addEventListener('click', () => {
            const status = button.dataset.status; statusInput.value = status;
            card.querySelectorAll('.status-btn').forEach(item => item.classList.remove('bg-[#324f47]', 'border-[#324f47]', 'text-white'));
            card.querySelectorAll('.status-btn').forEach(item => item.classList.add('border-gray-300', 'text-gray-600'));
            button.classList.add('bg-[#324f47]', 'border-[#324f47]', 'text-white'); button.classList.remove('border-gray-300', 'text-gray-600');
            if (status !== 'hadir') return;
            activeCard = card; document.getElementById('grade-student').textContent = card.querySelector('h3').textContent;
            document.getElementById('grade-akademik').value = card.querySelector('[data-field="akademik"]').value;
            document.getElementById('grade-sikap').value = card.querySelector('[data-field="sikap"]').value;
            modal.classList.remove('hidden'); modal.classList.add('flex');
        }));
    });
    document.getElementById('grade-save').addEventListener('click', () => { if (!activeCard) return; activeCard.querySelector('[data-field="akademik"]').value = document.getElementById('grade-akademik').value; activeCard.querySelector('[data-field="sikap"]').value = document.getElementById('grade-sikap').value; activeCard.querySelector('[data-summary="akademik"]').textContent = document.getElementById('grade-akademik').value || '-'; activeCard.querySelector('[data-summary="sikap"]').textContent = document.getElementById('grade-sikap').value || '-'; activeCard.querySelector('.grade-summary').classList.remove('hidden'); close(); });
})();
</script>
