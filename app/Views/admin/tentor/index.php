<div class="flex flex-col gap-5 w-full max-w-3xl mx-auto">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="/admin/beranda" class="w-10 h-10 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm"><span class="material-symbols-outlined">arrow_back</span></a>
            <div><h1 class="text-xl font-bold text-[#1b4332]">Master Data Tentor</h1><p class="text-xs text-gray-500">Kelola akun dan profil pengajar</p></div>
        </div>
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
    </div>

    <div class="relative flex items-center bg-white rounded-2xl border border-gray-200 shadow-sm">
        <span class="material-symbols-outlined absolute left-4 text-gray-400">search</span>
        <input id="search-tentor" class="w-full py-3 pl-12 pr-4 text-sm bg-transparent focus:outline-none" placeholder="Cari nama pengajar..." type="text">
    </div>

    <div class="flex items-center gap-2 overflow-x-auto">
        <button type="button" class="status-filter active px-4 py-2 rounded-full bg-[#1b4332] text-white text-xs font-semibold" data-status="all">Semua <span class="ml-1 opacity-80"><?= count($tentorList) ?></span></button>
        <button type="button" class="status-filter px-4 py-2 rounded-full bg-white border border-gray-200 text-gray-600 text-xs font-medium" data-status="active">Aktif <span class="ml-1 text-emerald-700"><?= count(array_filter($tentorList, fn ($t) => (int) $t['status_aktif'] === 1)) ?></span></button>
        <button type="button" class="status-filter px-4 py-2 rounded-full bg-white border border-gray-200 text-gray-600 text-xs font-medium" data-status="inactive">Nonaktif <span class="ml-1 text-gray-500"><?= count(array_filter($tentorList, fn ($t) => (int) $t['status_aktif'] === 0)) ?></span></button>
    </div>

    <div class="flex items-center justify-between px-1 text-xs text-gray-500 font-medium"><span>Daftar Pengajar</span><span><?= count($tentorList) ?> Total Tentor</span></div>
    <div id="tentor-card-list" class="bg-white rounded-2xl border border-gray-200/75 shadow-sm divide-y divide-gray-100 overflow-hidden">
        <?php if (empty($tentorList)): ?>
            <div class="p-8 text-center text-sm text-gray-500">Belum ada data profil tentor.</div>
        <?php else: foreach ($tentorList as $tentor): ?>
            <div class="tentor-card p-4 flex items-center justify-between gap-3 hover:bg-[#faf9f7]" data-status="<?= $tentor['status_aktif'] ? 'active' : 'inactive' ?>">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative shrink-0">
                        <?php if (!empty($tentor['foto'])): ?><img src="<?= e($tentor['foto']) ?>" alt="" class="w-12 h-12 rounded-full object-cover border-2 border-emerald-100"><?php else: ?><div class="w-12 h-12 rounded-full bg-[#e8f0ec] text-[#2d5a4c] font-bold flex items-center justify-center text-base border-2 border-emerald-100"><?= e(strtoupper(substr($tentor['nama_lengkap'], 0, 1))) ?></div><?php endif; ?>
                        <span class="absolute bottom-0 right-0 w-3 h-3 <?= $tentor['status_aktif'] ? 'bg-emerald-500' : 'bg-gray-300' ?> border-2 border-white rounded-full"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap"><h3 class="text-sm font-bold text-gray-900 truncate"><?= e($tentor['nama_lengkap']) ?></h3><span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $tentor['status_aktif'] ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' ?>"><?= $tentor['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></span></div>
                        <p class="text-xs text-gray-500 mt-1"><?= e($tentor['asal_universitas'] ?: 'Universitas belum diisi') ?> · @<?= e($tentor['username']) ?></p>
                    </div>
                </div>
                <a href="/admin/tentor/<?= $tentor['id'] ?>/edit" class="shrink-0 px-3.5 py-2 rounded-xl border border-[#1b4332]/30 text-[#1b4332] text-xs font-semibold hover:bg-[#1b4332] hover:text-white">Kelola</a>
            </div>
        <?php endforeach; endif; ?>
    </div>
    <div class="p-3.5 rounded-2xl bg-[#e1ede7]/60 border border-[#c5dcd2] flex items-center gap-3"><span class="material-symbols-outlined text-[#1b4332]">info</span><p class="text-xs text-[#1b4332]">Klik <strong>Kelola</strong> untuk mengatur profil dan status tentor.</p></div>
    <a href="/admin/tentor/tambah" class="fixed bottom-24 right-6 w-14 h-14 rounded-full bg-[#1b4332] text-white flex items-center justify-center shadow-lg hover:bg-[#143527]" title="Registrasi Tentor Baru"><span class="material-symbols-outlined text-3xl">add</span></a>
</div>
<script>
const searchTentor = document.getElementById('search-tentor');
const filters = document.querySelectorAll('.status-filter');
function filterTentor() {
    const query = (searchTentor?.value || '').toLowerCase().trim();
    const status = document.querySelector('.status-filter.active')?.dataset.status || 'all';
    document.querySelectorAll('.tentor-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(query) && (status === 'all' || card.dataset.status === status) ? 'flex' : 'none';
    });
}
searchTentor?.addEventListener('input', filterTentor);
filters.forEach(button => button.addEventListener('click', () => {
    filters.forEach(item => item.classList.remove('active', 'bg-[#1b4332]', 'text-white'));
    filters.forEach(item => item.classList.add('bg-white', 'border', 'border-gray-200', 'text-gray-600'));
    button.classList.add('active', 'bg-[#1b4332]', 'text-white');
    button.classList.remove('bg-white', 'border', 'border-gray-200', 'text-gray-600');
    filterTentor();
}));
</script>
