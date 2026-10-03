<div class="flex flex-col gap-5 w-full max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-3">
        <a href="/admin/siswa" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm"><span class="material-symbols-outlined">arrow_back</span></a>
        <div class="text-center flex-1"><h1 class="text-lg font-bold text-[#2D3E39]"><?= e($kelas['nama']) ?></h1><p class="text-xs text-gray-500"><?= count($siswaList) ?> Siswa Terdaftar · <?= e($kelas['program_nama']) ?></p></div>
        <span class="w-10 h-10 rounded-full bg-[#e8f0ec] text-[#324f47] flex items-center justify-center"><span class="material-symbols-outlined">folder_open</span></span>
    </div>
    <div class="flex items-center justify-between px-1 text-xs text-gray-500"><span>Direktori Siswa / <strong class="text-[#324f47]"><?= e($kelas['nama']) ?></strong></span><span><?= e($kelas['jenjang_nama']) ?></span></div>
    <div class="bg-[#324f47] text-white rounded-2xl p-5 flex items-center justify-between shadow-md"><div><p class="text-xs text-white/70">Daftar Siswa</p><h2 class="text-xl font-bold mt-1"><?= e($kelas['nama']) ?></h2><p class="text-xs text-white/80 mt-1">Siswa aktif dalam kelompok belajar ini</p></div><div class="text-right"><strong class="text-3xl"><?= count($siswaList) ?></strong><p class="text-[10px] text-white/70">SISWA</p></div></div>
    <div class="flex items-center justify-between"><h2 class="text-sm font-bold text-[#2D3E39]">Daftar Siswa Kelas</h2><a href="/admin/siswa/tambah?kelas_id=<?= $kelas['id'] ?>" class="px-3 py-2 rounded-xl bg-[#e8f0ec] text-[#324f47] text-xs font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">add</span>Tambah</a></div>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden divide-y divide-gray-100">
        <?php if (empty($siswaList)): ?>
            <div class="p-8 text-center text-sm text-gray-500">Belum ada siswa di kelas ini.</div>
        <?php else: foreach ($siswaList as $siswa): ?>
            <a href="/admin/siswa/<?= $siswa['id'] ?>/edit" class="p-4 flex items-center justify-between hover:bg-gray-50 group">
                <div class="flex items-center gap-3 min-w-0"><div class="w-10 h-10 rounded-xl bg-[#e8f0ec] text-[#324f47] font-bold text-sm flex items-center justify-center shrink-0"><?= e(strtoupper(substr($siswa['nama_lengkap'], 0, 2))) ?></div><div class="min-w-0"><div class="flex items-center gap-2"><strong class="text-sm text-gray-800 truncate"><?= e($siswa['nama_lengkap']) ?></strong><span class="w-1.5 h-1.5 rounded-full <?= $siswa['status_aktif'] ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span></div><p class="text-xs text-gray-500 mt-1">Asal: <?= e($siswa['asal_sekolah'] ?: '-') ?></p></div></div>
                <span class="material-symbols-outlined text-gray-400 group-hover:text-[#324f47]">chevron_right</span>
            </a>
        <?php endforeach; endif; ?>
    </div>
    <p class="text-[11px] text-gray-500 text-center">Klik nama siswa untuk membuka Edit Data Siswa.</p>
</div>
