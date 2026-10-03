<div class="flex flex-col gap-5 w-full max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm"><span class="material-symbols-outlined">arrow_back</span></a>
            <div><h1 class="text-xl font-bold text-[#2D3E39]">Direktori Siswa</h1><p class="text-xs text-gray-500">Kelola siswa berdasarkan kelompok kelas</p></div>
        </div>
        <span class="px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= $totalSiswa ?> Siswa</span>
    </div>
    <a href="/admin/siswa/tambah" class="w-full py-3 px-4 bg-[#324f47] text-white rounded-xl flex items-center justify-center gap-2 shadow-md"><span class="material-symbols-outlined">add</span><span class="text-sm font-semibold">+ Tambah Siswa Baru</span></a>
    <div class="relative flex items-center bg-white rounded-xl border border-gray-200 shadow-sm"><span class="material-symbols-outlined absolute left-4 text-gray-400">search</span><input id="search-kelas" class="w-full py-3 pl-12 pr-4 bg-transparent text-sm focus:outline-none" placeholder="Cari nama kelas..." type="text"></div>
    <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#324f47]">folder_open</span><h2 class="text-xs font-bold text-[#2D3E39] uppercase tracking-wider">Daftar Kelompok Kelas</h2></div><span class="text-[11px] text-gray-500"><?= count($kelasList) ?> Kelas</span></div>
    <div id="class-card-list" class="flex flex-col gap-3">
        <?php if (empty($kelasList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200">Belum ada data kelas.</div>
        <?php else: foreach ($kelasList as $kelas): ?>
            <a href="/admin/siswa/kelas/<?= $kelas['id'] ?>" class="class-card group flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-[#324f47]/40">
                <div class="flex items-center gap-3 min-w-0"><div class="w-12 h-12 rounded-xl bg-[#f0f3f1] text-[#324f47] flex items-center justify-center shrink-0 group-hover:bg-[#e8f0ec]"><span class="material-symbols-outlined text-2xl">folder</span></div><div class="min-w-0"><div class="flex items-center gap-2 flex-wrap"><strong class="text-base text-[#2D3E39] truncate"><?= e($kelas['nama']) ?></strong><span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10px]"><?= e($kelas['program_nama']) ?></span></div><p class="text-xs text-gray-500 mt-1"><?= e($kelas['jenjang_nama']) ?> · <?= (int) $kelas['jumlah_siswa'] ?> Siswa Aktif</p></div></div>
                <span class="material-symbols-outlined text-gray-400 group-hover:text-[#324f47]">chevron_right</span>
            </a>
        <?php endforeach; endif; ?>
    </div>
</div>
<script>
document.getElementById('search-kelas')?.addEventListener('input', function (event) {
    const query = event.target.value.toLowerCase().trim();
    document.querySelectorAll('.class-card').forEach(card => { card.style.display = card.textContent.toLowerCase().includes(query) ? 'flex' : 'none'; });
});
</script>
