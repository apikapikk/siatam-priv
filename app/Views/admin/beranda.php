<?php if ($dashboard['using_fallback']): ?>
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm mb-4">
        <span class="material-symbols-outlined text-amber-600">info</span>
        <div>Menampilkan data contoh karena koneksi database belum tersedia.</div>
    </div>
<?php endif; ?>

<!-- Bagian Statistik Admin -->
<section class="flex flex-col gap-2">
    <div class="flex items-center justify-between">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ringkasan Statistik</h2>
        <span class="text-xs text-gray-400">Realtime system</span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <?php foreach ($dashboard['stats'] as $stat): ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200/80 hover:border-[#2d5a4c]/40 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500"><?= e($stat['label']) ?></span>
                    <span class="material-symbols-outlined text-[#2d5a4c] text-lg bg-[#e8f0ec] p-1 rounded-lg">insights</span>
                </div>
                <div class="text-2xl font-bold text-gray-900 leading-tight"><?= format_number((int) $stat['value']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Bagian Menu Aksi Cepat (Quick Actions) -->
<section class="flex flex-col gap-2.5">
    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi Cepat</h2>
    <div class="grid grid-cols-5 gap-2 text-center bg-white rounded-2xl p-3.5 border border-gray-200/80 shadow-sm">
        <!-- Action 1: Data Siswa -->
        <a href="/admin/siswa" class="flex flex-col items-center group focus:outline-none">
            <div class="rounded-full w-11 h-11 mx-auto flex items-center justify-center bg-[#e8f0ec] text-[#2d5a4c] group-hover:bg-[#d8e8e0] group-hover:scale-105 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">school</span>
            </div>
            <span class="text-[11px] font-medium text-gray-700 leading-tight mt-1.5 group-hover:text-[#2d5a4c]">Data Siswa</span>
        </a>
        <!-- Action 2: Jadwal -->
        <a href="/admin/jadwal" class="flex flex-col items-center group focus:outline-none">
            <div class="rounded-full w-11 h-11 mx-auto flex items-center justify-center bg-[#e8f0ec] text-[#2d5a4c] group-hover:bg-[#d8e8e0] group-hover:scale-105 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">calendar_add_on</span>
            </div>
            <span class="text-[11px] font-medium text-gray-700 leading-tight mt-1.5 group-hover:text-[#2d5a4c]">Jadwal Baru</span>
        </a>
        <!-- Action 3: Pengumuman -->
        <a href="/admin/pengumuman" class="flex flex-col items-center group focus:outline-none">
            <div class="rounded-full w-11 h-11 mx-auto flex items-center justify-center bg-[#e8f0ec] text-[#2d5a4c] group-hover:bg-[#d8e8e0] group-hover:scale-105 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">campaign</span>
            </div>
            <span class="text-[11px] font-medium text-gray-700 leading-tight mt-1.5 group-hover:text-[#2d5a4c]">Pengumuman</span>
        </a>
        <!-- Action 4: Data Tentor -->
        <a href="/admin/tentor" class="flex flex-col items-center group focus:outline-none">
            <div class="rounded-full w-11 h-11 mx-auto flex items-center justify-center bg-[#e8f0ec] text-[#2d5a4c] group-hover:bg-[#d8e8e0] group-hover:scale-105 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">badge</span>
            </div>
            <span class="text-[11px] font-medium text-gray-700 leading-tight mt-1.5 group-hover:text-[#2d5a4c]">Data Tentor</span>
        </a>
        <!-- Action 5: Berita -->
        <a href="/admin/berita" class="flex flex-col items-center group focus:outline-none">
            <div class="rounded-full w-11 h-11 mx-auto flex items-center justify-center bg-[#e8f0ec] text-[#2d5a4c] group-hover:bg-[#d8e8e0] group-hover:scale-105 transition-all shadow-sm">
                <span class="material-symbols-outlined text-xl">newspaper</span>
            </div>
            <span class="text-[11px] font-medium text-gray-700 leading-tight mt-1.5 group-hover:text-[#2d5a4c]">Berita</span>
        </a>
    </div>
</section>

<!-- Bagian Pantauan Mengajar Hari Ini -->
<section class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-800">Jadwal Mengajar Hari Ini</h3>
        <a href="/admin/jadwal" class="text-xs font-semibold text-[#2d5a4c] hover:underline flex items-center gap-0.5">
            Lihat Semua
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    <div class="flex flex-col gap-2.5">
        <?php if (count($dashboard['today_schedule']) === 0): ?>
            <div class="bg-white rounded-2xl p-6 text-center text-gray-500 border border-gray-100 text-sm">
                Belum ada jadwal aktif hari ini.
            </div>
        <?php endif; ?>

        <?php foreach ($dashboard['today_schedule'] as $schedule): ?>
            <div class="rounded-2xl bg-white p-4 shadow-sm border border-gray-100 flex items-center justify-between gap-3 hover:border-gray-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#e8f0ec] text-[#2d5a4c] flex items-center justify-center font-bold text-sm">
                        <?= e(strtoupper(substr($schedule['kelas_nama'], 0, 2))) ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-sm text-gray-900"><?= e($schedule['jenjang_nama'] . ' ' . $schedule['kelas_nama']) ?></span>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                            <span class="font-medium text-gray-700"><?= e($schedule['tentor_nama']) ?></span>
                            <span>•</span>
                            <span><?= e($schedule['program_nama']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <span class="bg-[#e8f0ec] text-[#2d5a4c] font-semibold text-xs px-3 py-1 rounded-full whitespace-nowrap">
                        <?= e(substr($schedule['jam_mulai'], 0, 5)) ?> - <?= e(substr($schedule['jam_selesai'], 0, 5)) ?> WIB
                    </span>
                    <span class="text-[11px] text-gray-400 mt-1"><?= e($schedule['ruangan'] ?: hari_indonesia((int) $schedule['hari'])) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Bagian Pengumuman Internal -->
<section class="flex flex-col gap-3">
    <div class="flex items-center justify-between">
        <h3 class="font-bold text-lg text-gray-800">Pengumuman Internal</h3>
        <a href="/admin/pengumuman" class="text-xs font-semibold text-[#2d5a4c] hover:underline flex items-center gap-0.5">
            Kelola
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php foreach ($dashboard['announcements'] as $announcement): ?>
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col gap-1">
                <strong class="text-sm font-semibold text-gray-900"><?= e($announcement['judul']) ?></strong>
                <p class="text-xs text-gray-600 line-clamp-2"><?= e($announcement['isi']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
