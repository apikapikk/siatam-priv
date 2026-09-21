<div class="flex flex-col gap-5 w-full">
    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-bold text-[#2D3E39] tracking-tight">Manajemen Jadwal Mengajar</h1>
                <p class="text-xs text-gray-500">Kelola jadwal bimbingan tentor & kelas</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c]">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold"><?= count($jadwalList) ?> Jadwal Sesi</span>
        </div>
    </div>

    <!-- Primary Action: Button Tambah -->
    <a href="/admin/jadwal/tambah" class="w-full py-3 px-4 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-medium rounded-xl flex items-center justify-center gap-2 shadow-md transition-all">
        <span class="material-symbols-outlined text-[22px]">calendar_add_on</span>
        <span class="text-sm font-semibold tracking-wide">+ Buat Jadwal Mengajar Baru</span>
    </a>

    <!-- Filter/Search Bar -->
    <div class="relative flex items-center w-full bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden focus-within:border-[#2d5a4c] transition-colors">
        <div class="pl-4 pr-2 flex items-center pointer-events-none text-gray-400">
            <span class="material-symbols-outlined text-[20px]">search</span>
        </div>
        <input class="w-full py-3 pr-4 bg-transparent text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none" id="search-jadwal" placeholder="Cari nama tentor, kelas, atau hari..." type="text"/>
    </div>

    <!-- List Data Jadwal -->
    <div class="flex flex-col gap-3.5 w-full" id="jadwal-card-list">
        <?php if (empty($jadwalList)): ?>
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-200/80 text-sm">
                Belum ada jadwal mengajar terdaftar.
            </div>
        <?php else: ?>
            <?php foreach ($jadwalList as $item): ?>
                <div class="jadwal-card bg-white rounded-2xl p-4.5 shadow-sm border border-gray-200/80 flex flex-col gap-3 transition-all hover:shadow-md">
                    <div class="flex items-start justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full <?= $item['status_aktif'] ? 'bg-[#324f47]' : 'bg-gray-300' ?>"></span>
                            <span class="font-bold text-base text-[#2D3E39]">
                                <?= hari_indonesia((int) $item['hari']) ?>, <?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?> WIB
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2.5 py-0.5 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= e($item['jenjang_nama']) ?></span>
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-xs font-semibold"><?= e($item['program_nama']) ?></span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-[#FAF9F6] p-3 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#2d5a4c] flex items-center justify-center font-bold text-sm shadow-xs border border-gray-200/60">
                                <?= e(substr($item['kelas_nama'], 0, 3)) ?>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-bold text-sm text-gray-900">Kelas <?= e($item['kelas_nama']) ?></h3>
                                <div class="flex items-center gap-2 text-xs text-gray-600 mt-0.5">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-[#2d5a4c]">badge</span>
                                        Tentor: <strong class="text-gray-800"><?= e($item['tentor_nama']) ?></strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 text-xs text-gray-500 self-start md:self-center">
                            <span class="material-symbols-outlined text-[16px] text-[#2d5a4c]">location_on</span>
                            <span><?= e($item['ruangan'] ?: 'Ruangan Standar') ?></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-gray-100">
                        <a href="/admin/jadwal/<?= $item['id'] ?>/edit" class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-700 hover:bg-[#e8f0ec] hover:text-[#2d5a4c] text-xs font-semibold transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            Ubah Jadwal
                        </a>
                        <form action="/admin/jadwal/<?= $item['id'] ?>/hapus" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');" class="m-0">
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
    document.getElementById('search-jadwal')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#jadwal-card-list .jadwal-card');
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
