<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Direktori Siswa</h1>
                <p class="text-xs text-gray-500">Kelola data siswa dan wali murid</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($siswaList) ?> Siswa Terdaftar</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/admin/siswa/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">add</span>
        <span class="text-sm font-semibold tracking-wide">+ Tambah Siswa Baru</span>
    </a>

    <!-- Search Bar (Client-side Search) -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-siswa" placeholder="Cari nama siswa, sekolah, atau wali..." type="text"/>
    </div>

    <!-- List Data Siswa -->
    <div class="flex flex-col gap-3 w-full" id="siswa-card-list">
        <?php if (empty($siswaList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada data siswa terdaftar.
            </div>
        <?php else: ?>
            <?php foreach ($siswaList as $siswa): ?>
                <div class="siswa-card bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 flex flex-col md:flex-row md:items-center justify-between gap-3 hover:border-[#2d5a4c]/40 transition-all">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-11 h-11 rounded-xl bg-[#e8f0ec] text-[#2d5a4c] font-bold flex items-center justify-center text-base flex-shrink-0">
                            <?= e(strtoupper(substr($siswa['nama_lengkap'], 0, 1))) ?>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-base text-gray-900 truncate"><?= e($siswa['nama_lengkap']) ?></span>
                                <?php if ($siswa['kelas_aktif']): ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= e($siswa['kelas_aktif']) ?></span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">Belum Ada Kelas</span>
                                <?php endif; ?>
                                <span class="w-2 h-2 rounded-full <?= $siswa['status_aktif'] ? 'bg-emerald-500' : 'bg-red-400' ?>" title="<?= $siswa['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>"></span>
                            </div>

                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1 flex-wrap">
                                <?php if (!empty($siswa['nis'])): ?>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">tag</span>
                                        NIS: <?= e($siswa['nis']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">school</span>
                                    Sekolah: <?= e($siswa['asal_sekolah'] ?: '-') ?>
                                </span>
                                <?php if (!empty($siswa['daftar_orang_tua'])): ?>
                                    <span class="flex items-center gap-1 text-[#2d5a4c] font-medium">
                                        <span class="material-symbols-outlined text-[14px]">family_restroom</span>
                                        Wali: <?= e($siswa['daftar_orang_tua']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end md:self-center flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 w-full md:w-auto justify-end">
                        <a href="/admin/siswa/<?= $siswa['id'] ?>/edit" class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-700 hover:bg-[#e8f0ec] hover:text-[#2d5a4c] text-xs font-semibold transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            Edit
                        </a>
                        <form action="/admin/siswa/<?= $siswa['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');" class="m-0">
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search-siswa')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#siswa-card-list .siswa-card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
