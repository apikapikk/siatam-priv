<!-- Bagian Banner Pengumuman -->
<?php if (!empty($announcements)): ?>
    <?php $firstAnn = $announcements[0]; ?>
    <section class="w-full bg-emerald-50/70 border border-[#324f47]/20 rounded-2xl p-3.5 flex items-start gap-3 shadow-xs">
        <div class="w-8 h-8 rounded-xl bg-[#324f47]/10 flex items-center justify-center text-[#324f47] shrink-0 mt-0.5">
            <span class="material-symbols-outlined text-[20px]">campaign</span>
        </div>
        <div class="flex-1">
            <p class="text-xs font-semibold text-[#324f47] uppercase tracking-wide">Pengumuman Penting</p>
            <strong class="text-xs font-bold text-gray-900 block mt-0.5"><?= e($firstAnn['judul']) ?></strong>
            <p class="text-xs text-gray-600 mt-0.5 leading-relaxed"><?= e($firstAnn['isi']) ?></p>
        </div>
    </section>
<?php endif; ?>

<!-- Bagian Ringkasan Aktivitas (Widget Statistik Tentor) -->
<section class="grid grid-cols-2 gap-3">
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col justify-between gap-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 leading-snug">Jadwal Ditugaskan</span>
            <div class="w-7 h-7 rounded-lg bg-[#e8f0ec] flex items-center justify-center text-[#324f47]">
                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
            </div>
        </div>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-3xl font-bold text-[#2D3E39]"><?= format_number($stats['total_jadwal']) ?></span>
            <span class="text-xs text-gray-500 font-medium">Kelas</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm flex flex-col justify-between gap-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-gray-500 leading-snug">Sesi Mengajar Bulan Ini</span>
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-[#324f47] flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">task_alt</span>
            </div>
        </div>
        <div class="flex items-baseline gap-1 mt-1">
            <span class="text-3xl font-bold text-[#2D3E39]"><?= format_number($stats['sesi_bulan_ini']) ?></span>
            <span class="text-xs text-gray-500 font-medium">Sesi</span>
        </div>
    </div>
</section>

<!-- Bagian Jadwal Terdekat Hari Ini -->
<section class="flex flex-col gap-2.5">
    <div class="flex items-center justify-between px-1">
        <h2 class="font-bold text-base text-[#2D3E39]">Jadwal Mengajar Anda Hari Ini</h2>
        <a href="/tentor/jadwal" class="text-xs font-semibold text-[#324f47] hover:underline">Lihat Semua</a>
    </div>

    <?php if (empty($jadwalHariIni)): ?>
        <div class="bg-white rounded-2xl p-6 text-center text-gray-500 border border-gray-200/80 text-sm">
            Tidak ada jadwal mengajar untuk Anda hari ini.
        </div>
    <?php else: ?>
        <?php foreach ($jadwalHariIni as $j): ?>
            <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-sm flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#e8f0ec] text-[#324f47]">
                        <?= e($j['program_nama']) ?>
                    </span>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Jadwal Hari Ini
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg text-[#2D3E39]">Kelas <?= e($j['kelas_nama']) ?> (<?= e($j['jenjang_nama']) ?>)</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px] text-[#324f47]">location_on</span>
                        Ruangan: <?= e($j['ruangan'] ?: 'Ruang Umum') ?>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 flex flex-col gap-1.5 border border-gray-100">
                    <div class="flex items-center gap-2 text-xs font-semibold text-[#2D3E39]">
                        <span class="material-symbols-outlined text-[18px] text-[#324f47]">schedule</span>
                        <span><?= e(substr($j['jam_mulai'], 0, 5)) ?> - <?= e(substr($j['jam_selesai'], 0, 5)) ?> WIB</span>
                    </div>
                </div>

                <a href="/tentor/pertemuan" class="w-full mt-1 flex items-center justify-center gap-2 bg-[#324f47] hover:bg-[#2D3E39] active:scale-[0.99] text-white font-semibold text-sm py-2.5 px-4 rounded-xl shadow-xs transition-all">
                    <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                    Catat / Isi Presensi Sesi
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
