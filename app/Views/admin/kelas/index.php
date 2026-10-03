<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Kelas</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola daftar kelas bimbingan</p>
        </div>
        <a href="/admin/kelas/tambah" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-[#324f47] hover:bg-[#2D3E39] text-white text-xs font-semibold shadow-sm transition-colors">
            <span class="material-symbols-outlined text-[17px]">add</span> Tambah
        </a>
    </div>

    <?php if (empty($kelasList)): ?>
        <div class="bg-white rounded-2xl border border-gray-200/80 p-8 text-center text-sm text-gray-500">Belum ada data kelas.</div>
    <?php else: ?>
        <div class="flex flex-col gap-3">
            <?php foreach ($kelasList as $kelas): ?>
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-4 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <strong class="text-sm text-gray-900"><?= e($kelas['nama']) ?></strong>
                            <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-semibold"><?= e($kelas['jenjang_nama']) ?></span>
                            <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-semibold"><?= e($kelas['program_nama']) ?></span>
                            <span class="inline-flex items-center gap-1 text-[10px] <?= $kelas['status_aktif'] ? 'text-emerald-700' : 'text-gray-500' ?>"><span class="w-1.5 h-1.5 rounded-full <?= $kelas['status_aktif'] ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span><?= $kelas['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="/admin/kelas/<?= $kelas['id'] ?>/edit" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold"><span class="material-symbols-outlined text-[16px]">edit</span><span class="hidden sm:inline">Edit</span></a>
                        <form action="/admin/kelas/<?= $kelas['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold"><span class="material-symbols-outlined text-[16px]">delete</span><span class="hidden sm:inline">Hapus</span></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
