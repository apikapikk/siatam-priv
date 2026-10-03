<div class="w-full max-w-[430px] mx-auto min-h-screen bg-[#FAF9F7] flex flex-col relative pb-10">
    
    <!-- Header Halaman -->
    <header class="w-full pt-2 pb-3 px-2 flex items-center justify-between border-b border-zinc-200/60 bg-[#FAF9F7]/95 sticky top-0 z-30 backdrop-blur-sm">
        <div class="w-8 flex items-center">
            <div class="w-7 h-7 rounded-lg bg-emerald-900/10 flex items-center justify-center text-[#1B4332]">
                <span class="material-symbols-outlined text-[18px]">query_stats</span>
            </div>
        </div>
        <div class="text-center flex-1">
            <h1 class="text-base font-bold text-zinc-900 tracking-tight">Pusat Laporan &amp; Rekap</h1>
            <p class="text-[11px] font-medium text-zinc-500">Panel Administrasi Siatama</p>
        </div>
        <div class="w-8 flex justify-end">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-zinc-400">
                <span class="material-symbols-outlined text-[20px]">tune</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="flex-1 px-1 pt-4 pb-6 space-y-4">
        
        <!-- Greeting / Overview Banner -->
        <div class="bg-gradient-to-br from-[#1B4332] to-[#2D5A47] text-white p-4 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/15 text-[10px] font-semibold text-emerald-100 mb-1.5 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                    Rekap Terpadu
                </span>
                <h2 class="text-base font-bold text-white leading-snug">Monitoring &amp; Evaluasi Bimbel</h2>
                <p class="text-xs text-emerald-100/90 mt-1 leading-relaxed">Pilih jenis laporan untuk memantau performa kelas, kehadiran siswa, dan verifikasi gaji tentor secara akurat.</p>
            </div>
            <!-- Decorative Vector in Background -->
            <span class="material-symbols-outlined absolute -right-3 -bottom-4 text-white/10 text-8xl pointer-events-none select-none">
                analytics
            </span>
        </div>

        <!-- Quick Summary Metric Chips -->
        <div class="grid grid-cols-2 gap-3 pt-1">
            <div class="bg-white p-3 rounded-xl border border-zinc-200/70 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-[#1B4332] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">groups</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium text-zinc-500 uppercase tracking-wider">Total Siswa Aktif</p>
                    <p class="text-sm font-bold text-zinc-900"><?= (int)($totalSiswa ?? 0) ?> Siswa</p>
                </div>
            </div>
            <div class="bg-white p-3 rounded-xl border border-zinc-200/70 shadow-[0_1px_3px_rgba(0,0,0,0.03)] flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-[#1B4332] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">school</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium text-zinc-500 uppercase tracking-wider">Tentor Terdaftar</p>
                    <p class="text-sm font-bold text-zinc-900"><?= (int)($totalTentor ?? 0) ?> Pengajar</p>
                </div>
            </div>
        </div>

        <!-- Section Title -->
        <div class="pt-2">
            <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Pilih Kategori Laporan</p>
        </div>

        <!-- Menu Utama (Card Pilihan) -->
        <div class="space-y-4">
            
            <!-- Kartu 1: Laporan Siswa -->
            <a href="/admin/laporan/siswa" class="group block w-full bg-white rounded-2xl p-5 border border-zinc-200/80 shadow-[0_2px_8px_rgba(27,67,50,0.04)] hover:shadow-[0_6px_16px_rgba(27,67,50,0.08)] hover:border-[#1B4332]/40 transition-all duration-200 active:scale-[0.98] text-left cursor-pointer relative overflow-hidden">
                <!-- Left accent strip -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#1B4332]"></div>
                
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-[#E8EFE9] text-[#1B4332] flex items-center justify-center shrink-0 group-hover:bg-[#1B4332] group-hover:text-white transition-colors duration-200 shadow-sm">
                            <span class="material-symbols-outlined text-[26px]">description</span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-zinc-900 group-hover:text-[#1B4332] transition-colors">Laporan Siswa</h3>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100/70 text-[#1B4332] text-[10px] font-semibold">Akademik</span>
                            </div>
                            <p class="text-xs text-zinc-600 leading-relaxed pr-2">
                                Rekap kehadiran harian, tabel per kelas, dan nilai huruf siswa
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 group-hover:bg-[#1B4332] group-hover:text-white transition-colors shrink-0 mt-1">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </div>
                </div>

                <!-- Mini feature tags inside card -->
                <div class="mt-4 pt-3.5 border-t border-zinc-100 flex items-center flex-wrap gap-2 text-[11px] text-zinc-500 font-medium">
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">fact_check</span> Absensi Harian
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">table_chart</span> Rekap Per Jenjang
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">grade</span> Konversi Nilai
                    </span>
                </div>
            </a>

            <!-- Kartu 2: Laporan Tentor -->
            <a href="/admin/laporan/tentor" class="group block w-full bg-white rounded-2xl p-5 border border-zinc-200/80 shadow-[0_2px_8px_rgba(27,67,50,0.04)] hover:shadow-[0_6px_16px_rgba(27,67,50,0.08)] hover:border-[#1B4332]/40 transition-all duration-200 active:scale-[0.98] text-left cursor-pointer relative overflow-hidden">
                <!-- Left accent strip -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#4A675E]"></div>
                
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-[#E8EFE9] text-[#1B4332] flex items-center justify-center shrink-0 group-hover:bg-[#1B4332] group-hover:text-white transition-colors duration-200 shadow-sm">
                            <span class="material-symbols-outlined text-[26px]">badge</span>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-zinc-900 group-hover:text-[#1B4332] transition-colors">Laporan Tentor</h3>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100/70 text-[#1B4332] text-[10px] font-semibold">Honor &amp; Sesi</span>
                            </div>
                            <p class="text-xs text-zinc-600 leading-relaxed pr-2">
                                Log harian pengajar dan rekap bulanan untuk verifikasi gaji
                            </p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 group-hover:bg-[#1B4332] group-hover:text-white transition-colors shrink-0 mt-1">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </div>
                </div>

                <!-- Mini feature tags inside card -->
                <div class="mt-4 pt-3.5 border-t border-zinc-100 flex items-center flex-wrap gap-2 text-[11px] text-zinc-500 font-medium">
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">history_edu</span> Log Sesi Mengajar
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">payments</span> Verifikasi Honor
                    </span>
                    <span class="inline-flex items-center gap-1 bg-[#FAF9F7] px-2.5 py-1 rounded-lg border border-zinc-200/50">
                        <span class="material-symbols-outlined text-[13px] text-[#1B4332]">download</span> Ekspor PDF/Excel
                    </span>
                </div>
            </a>

        </div>

        <!-- Quick Export & Period Notice -->
        <div class="mt-2 bg-[#E8EFE9]/60 border border-emerald-900/10 rounded-xl p-3.5 flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[18px] text-[#1B4332] mt-0.5 shrink-0">info</span>
            <div class="text-[11px] text-zinc-600 leading-normal">
                <span class="font-bold text-zinc-800">Sinkronisasi Otomatis:</span> Data kehadiran yang dimasukkan oleh tentor pada menu presensi langsung diperbarui ke rekap bulanan ini secara real-time.
            </div>
        </div>

    </div>

</div>
