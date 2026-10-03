<?php
$hariNama = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
$kelasGroups = [];
foreach ($jadwalList as $item) {
    $kelasGroups[$item['kelas_id']]['meta'] = $item;
    $kelasGroups[$item['kelas_id']]['sesi'][] = $item;
}
$hariItems = array_values(array_filter($jadwalList, fn ($item) => (int) $item['hari'] === (int) $hariAktif));
$formatJam = static fn ($value) => substr((string) $value, 0, 5);
?>
<div class="flex flex-col gap-5 w-full">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <a href="/admin/beranda" aria-label="Kembali" class="w-10 h-10 shrink-0 rounded-full bg-white text-gray-700 border border-gray-200 flex items-center justify-center shadow-sm"><span class="material-symbols-outlined text-[22px]">arrow_back</span></a>
            <div class="min-w-0"><h1 class="text-xl font-bold text-[#2D3E39] tracking-tight truncate">Manajemen Kelas</h1><p class="text-xs text-gray-500">Kelola sesi belajar, tentor, dan kelas</p></div>
        </div>
        <span class="shrink-0 px-3 py-1 rounded-full bg-[#e8f0ec] text-[#2d5a4c] text-xs font-semibold"><?= count($jadwalList) ?> Sesi</span>
    </div>

    <div class="flex items-center justify-between gap-3">
        <div class="grid grid-cols-2 gap-1 p-1 bg-[#f0f2ef] rounded-xl flex-1">
            <a href="/admin/jadwal?mode=kelas" class="flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold <?= $mode === 'kelas' ? 'bg-white text-[#2d5a4c] shadow-sm' : 'text-gray-500' ?>"><span class="material-symbols-outlined text-[17px]">view_agenda</span> Per Kelas</a>
            <a href="/admin/jadwal?mode=hari&hari=<?= $hariAktif ?>" class="flex items-center justify-center gap-1.5 py-2.5 rounded-lg text-xs font-semibold <?= $mode === 'hari' ? 'bg-[#324f47] text-white shadow-sm' : 'text-gray-500' ?>"><span class="material-symbols-outlined text-[17px]">calendar_view_day</span> Per Hari</a>
        </div>
        <a href="/admin/jadwal/tambah" aria-label="Tambah sesi" class="w-11 h-11 shrink-0 rounded-xl bg-[#324f47] text-white flex items-center justify-center shadow-sm"><span class="material-symbols-outlined">add</span></a>
    </div>

    <?php if ($mode === 'hari'): ?>
        <div class="flex gap-2 overflow-x-auto pb-1">
            <?php foreach ($hariNama as $number => $name): $jumlahHari = count(array_filter($jadwalList, fn ($i) => (int) $i['hari'] === $number)); ?>
                <a href="/admin/jadwal?mode=hari&hari=<?= $number ?>" class="shrink-0 px-4 py-2 rounded-full text-xs font-semibold <?= $hariAktif === $number ? 'bg-[#2D3E39] text-white' : 'bg-white border border-gray-200 text-gray-600' ?>"><?= $name ?><?= $hariAktif === $number ? ' (' . $jumlahHari . ')' : '' ?></a>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500"><span class="material-symbols-outlined text-[17px] text-[#324f47]">event_available</span><strong class="text-[#2D3E39]"><?= count($hariItems) ?> sesi terjadwal</strong> pada hari <?= $hariNama[$hariAktif] ?></div>
    <?php endif; ?>

    <?php if (empty($jadwalList)): ?>
        <div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200">Belum ada sesi belajar terdaftar.</div>
    <?php elseif ($mode === 'kelas'): ?>
        <div class="flex flex-col gap-4">
            <?php foreach ($kelasGroups as $group): $meta = $group['meta']; ?>
                <article class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                    <div class="px-4 py-3.5 bg-[#f5f7f4] border-b border-gray-200 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0"><div class="w-9 h-9 rounded-xl bg-[#324f47]/10 text-[#324f47] flex items-center justify-center font-bold text-xs shrink-0"><?= e(substr($meta['kelas_nama'], 0, 3)) ?></div><div class="min-w-0"><h2 class="font-bold text-[15px] text-[#2D3E39] uppercase truncate">Kelas <?= e($meta['kelas_nama']) ?></h2><p class="text-[11px] text-gray-500"><?= e($meta['jenjang_nama']) ?> · <?= count($group['sesi']) ?> sesi / minggu</p></div></div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $meta['status_aktif'] ? 'bg-[#e8f5e9] text-[#1b4332]' : 'bg-gray-100 text-gray-500' ?>"><?= $meta['status_aktif'] ? 'Aktif' : 'Nonaktif' ?></span>
                    </div>
                    <div class="p-4 space-y-2.5">
                        <?php foreach ($group['sesi'] as $item): ?>
                            <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-[#faf9f6] border border-[#f0eee8]"><div class="flex items-center gap-2.5 min-w-0"><span class="material-symbols-outlined text-[#324f47] text-[19px]">schedule</span><div class="min-w-0"><p class="text-[13px] font-semibold text-gray-800"><?= $hariNama[(int) $item['hari']] ?> · <?= $formatJam($item['jam_mulai']) ?>–<?= $formatJam($item['jam_selesai']) ?> WIB</p><p class="text-[11px] text-gray-500 truncate"><?= e($item['mata_pelajaran'] ?: 'Mata pelajaran belum diatur') ?><?= $item['ruangan'] ? ' · ' . e($item['ruangan']) : '' ?></p></div></div><div class="shrink-0 px-2.5 py-1.5 rounded-lg bg-white border border-[#e5e2db] text-[11px] font-bold text-gray-700 flex items-center gap-1"><span class="material-symbols-outlined text-[15px] text-gray-500">person</span><?= e($item['tentor_nama'] ?: 'Belum Ditugaskan') ?></div></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="px-4 pb-4"><a href="/admin/kelas/<?= $meta['kelas_id'] ?>/jadwal" class="w-full py-2.5 rounded-xl bg-[#f0eee8] text-gray-800 font-semibold text-xs flex items-center justify-center gap-2 border border-[#dedacf]"><span class="material-symbols-outlined text-[16px]">edit</span> Ubah Jadwal Kelas</a></div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="flex flex-col gap-3">
            <?php foreach ($hariItems as $item): ?>
                <article class="bg-white rounded-2xl border border-gray-200/80 p-4 shadow-sm"><div class="flex items-start justify-between gap-3"><div><p class="text-lg font-bold text-[#2D3E39]"><?= $formatJam($item['jam_mulai']) ?> – <?= $formatJam($item['jam_selesai']) ?> WIB</p><h2 class="mt-1 font-bold text-base uppercase">Kelas <?= e($item['kelas_nama']) ?></h2><p class="text-xs text-gray-500"><?= e($item['program_nama']) ?> · <?= e($item['jenjang_nama']) ?></p></div><div class="px-3 py-2 rounded-xl bg-[#f2efe9] text-right shrink-0"><span class="block text-[10px] text-gray-500">Tentor</span><strong class="text-xs text-[#2D3E39]"><?= e($item['tentor_nama'] ?: 'Belum Ditugaskan') ?></strong></div></div><div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between gap-2 text-xs text-gray-500"><span><span class="material-symbols-outlined text-[15px] text-[#324f47] align-middle">menu_book</span> <?= e($item['mata_pelajaran'] ?: 'Mata pelajaran belum diatur') ?></span><a href="/admin/manajemen-kelas/<?= $item['kelas_id'] ?>/jadwal" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs font-semibold text-gray-700">Ubah Jadwal Kelas</a></div></article>
            <?php endforeach; ?>
            <?php if (empty($hariItems)): ?><div class="bg-white rounded-2xl p-8 text-center text-sm text-gray-500 border border-gray-200">Belum ada sesi pada hari <?= $hariNama[$hariAktif] ?>.</div><?php endif; ?>
        </div>
    <?php endif; ?>
</div>
