<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Broadcast Pengumuman</h1>
                <p class="text-xs text-gray-500">Kelola pesan & pengumuman internal maupun publik</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($pengumumanList) ?> Pengumuman</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/admin/pengumuman/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">campaign</span>
        <span class="text-sm font-semibold tracking-wide">+ Buat Pengumuman Baru</span>
    </a>

    <!-- Search Input -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-pengumuman" placeholder="Cari judul atau isi pengumuman..." type="text"/>
    </div>

    <!-- List Data Pengumuman -->
    <div class="flex flex-col gap-3 w-full" id="pengumuman-card-list">
        <?php if (empty($pengumumanList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada broadcast pengumuman.
            </div>
        <?php else: ?>
            <?php foreach ($pengumumanList as $item): ?>
                <?php
                    $targetBadge = match($item['target_peran']) {
                        'publik' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'tentor' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'admin' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-[#e8f0ec] text-[#2d5a4c] border-emerald-200',
                    };
                ?>
                <div class="pengumuman-card bg-white rounded-2xl p-4 md:p-5 border border-gray-200/80 shadow-sm hover:border-[#2d5a4c]/30 transition-all flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-[#e8f0ec] text-[#324f47] flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[22px]">campaign</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold border <?= $targetBadge ?>">
                                        Target: <?= e(strtoupper($item['target_peran'])) ?>
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
                                        <?= e($item['kategori'] ?? 'Umum') ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $item['status_aktif'] ? 'bg-emerald-50 text-emerald-800' : 'bg-gray-100 text-gray-500' ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $item['status_aktif'] ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                                        <?= $item['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 leading-snug"><?= e($item['judul']) ?></h3>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="/admin/pengumuman/<?= $item['id'] ?>/edit" class="w-8 h-8 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-600 hover:text-[#2d5a4c] hover:bg-[#e8f0ec] active:scale-95 transition" title="Edit Pengumuman">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="/admin/pengumuman/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');" class="m-0">
                                <button type="submit" class="w-8 h-8 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 hover:bg-red-100 active:scale-95 transition" title="Hapus Pengumuman">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line pl-0 md:pl-[52px]">
                        <?= e($item['isi']) ?>
                    </p>

                    <div class="flex items-center justify-between text-[11px] text-gray-400 pt-2 border-t border-gray-100 pl-0 md:pl-[52px]">
                        <span>Diterbitkan oleh <strong class="text-gray-700">@<?= e($item['pembuat_nama'] ?? 'admin') ?></strong></span>
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                            <?= e(date('d M Y, H:i', strtotime($item['diterbitkan_pada'] ?? 'now'))) ?> WIB
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search-pengumuman')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#pengumuman-card-list .pengumuman-card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
