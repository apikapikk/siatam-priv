<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Master Data Tentor</h1>
                <p class="text-xs text-gray-500">Kelola biodata dan profil pengajar</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($tentorList) ?> Tentor</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/admin/tentor/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">person_add</span>
        <span class="text-sm font-semibold tracking-wide">+ Registrasi Tentor Baru</span>
    </a>

    <!-- Search Bar -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-tentor" placeholder="Cari nama pengajar, universitas, atau username..." type="text"/>
    </div>

    <!-- List Data Tentor -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm divide-y divide-gray-100 overflow-hidden" id="tentor-card-list">
        <?php if (empty($tentorList)): ?>
            <div class="p-8 text-center text-gray-500 text-sm">
                Belum ada data profil tentor.
            </div>
        <?php else: ?>
            <?php foreach ($tentorList as $tentor): ?>
                <div class="tentor-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-3 hover:bg-[#FAF9F7] transition-colors">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="relative flex-shrink-0">
                            <?php if ($tentor['foto']): ?>
                                <img src="<?= e($tentor['foto']) ?>" alt="" class="w-12 h-12 rounded-full object-cover border-2 border-emerald-100 shadow-xs">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-full bg-[#e8f0ec] text-[#2d5a4c] font-bold flex items-center justify-center text-base border-2 border-emerald-100 shadow-xs">
                                    <?= e(strtoupper(substr($tentor['nama_lengkap'], 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 <?= $tentor['status_aktif'] ? 'bg-emerald-500' : 'bg-gray-300' ?> border-2 border-white rounded-full"></span>
                        </div>

                        <div class="flex flex-col min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-gray-900 truncate"><?= e($tentor['nama_lengkap']) ?></h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-700">@<?= e($tentor['username']) ?></span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $tentor['status_aktif'] ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' ?>">
                                    <?= $tentor['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2 flex-wrap">
                                <span><?= e($tentor['asal_universitas'] ?: 'Univ. Belum Diisi') ?></span>
                                <?php if (!empty($tentor['nomor_telepon'])): ?>
                                    <span>•</span>
                                    <span>Telp: <?= e($tentor['nomor_telepon']) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end md:self-center flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 w-full md:w-auto justify-end">
                        <a href="/admin/tentor/<?= $tentor['id'] ?>/edit" class="px-3 py-1.5 rounded-xl border border-[#2d5a4c]/30 text-[#2d5a4c] text-xs font-semibold hover:bg-[#2d5a4c] hover:text-white transition-all active:scale-95 shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            Edit
                        </a>
                        <form action="/admin/tentor/<?= $tentor['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus profil tentor ini?');" class="m-0">
                            <button type="submit" class="px-3 py-1.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-600 hover:text-white text-xs font-semibold transition-all active:scale-95 shadow-xs flex items-center gap-1">
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
    document.getElementById('search-tentor')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#tentor-card-list .tentor-card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
