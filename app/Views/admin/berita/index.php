<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Manajemen Berita</h1>
                <p class="text-xs text-gray-500">Kelola informasi & berita halaman utama bimbel</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($beritaList) ?> Artikel</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/admin/berita/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">add_circle</span>
        <span class="text-sm font-semibold tracking-wide">+ Tambah Berita Baru</span>
    </a>

    <!-- Search Input -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-berita" placeholder="Cari judul berita atau slug..." type="text"/>
    </div>

    <!-- List Data Berita -->
    <div class="flex flex-col gap-3 w-full" id="berita-card-list">
        <?php if (empty($beritaList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada berita publik terbit.
            </div>
        <?php else: ?>
            <?php foreach ($beritaList as $item): ?>
                <div class="berita-card bg-white rounded-2xl p-3.5 border border-gray-200/80 shadow-sm hover:border-[#2d5a4c]/30 transition-all flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 relative border border-gray-200/60">
                            <?php if ($item['gambar']): ?>
                                <img src="<?= e($item['gambar']) ?>" alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-[#e8f0ec] text-[#2d5a4c] font-bold text-[10px] flex items-center justify-center">
                                    NO IMAGE
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold <?= $item['status_terbit'] ? 'bg-emerald-50 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                    <?= $item['status_terbit'] ? 'Terbit' : 'Draft' ?>
                                </span>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 leading-snug truncate"><?= e($item['judul']) ?></h3>
                            <p class="text-[11px] text-gray-400 mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                <?= $item['diterbitkan_pada'] ? e($item['diterbitkan_pada']) : 'Draft' ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="/admin/berita/<?= $item['id'] ?>/edit" class="w-8 h-8 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:text-[#2d5a4c] hover:bg-[#e8f0ec] active:scale-95 transition" title="Edit Berita">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                        <form action="/admin/berita/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?');" class="m-0">
                            <button type="submit" class="w-8 h-8 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 hover:bg-red-100 active:scale-95 transition" title="Hapus Berita">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search-berita')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#berita-card-list .berita-card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
