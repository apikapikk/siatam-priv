<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_number(int $value): string
{
    return number_format($value, 0, ',', '.');
}

function format_rupiah(int|float $nominal): string
{
    return 'Rp ' . number_format((float) $nominal, 0, ',', '.');
}

function hari_indonesia(int $day): string
{
    $days = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    return $days[$day] ?? '-';
}

function status_sesi_mengajar(string $jamMulai, string $jamSelesai): string
{
    $now = date('H:i:s');
    $mulai = strlen($jamMulai) === 5 ? $jamMulai . ':00' : $jamMulai;
    $selesai = strlen($jamSelesai) === 5 ? $jamSelesai . ':00' : $jamSelesai;

    if ($now < $mulai) {
        return 'belum_mulai';
    }

    if ($now > $selesai) {
        return 'selesai';
    }

    return 'sedang_berlangsung';
}

function konversi_nilai_huruf(?string $nilai): string
{
    $labels = [
        'A' => 'Sangat Baik',
        'B' => 'Baik',
        'C' => 'Cukup',
        'D' => 'Kurang',
    ];

    return $labels[$nilai ?? ''] ?? '-';
}

function tanggal_indonesia(string $date, bool $withDay = true): string
{
    if (empty($date)) {
        return '-';
    }
    $timestamp = strtotime($date);
    if (!$timestamp) {
        return $date;
    }

    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][(int) date('w', $timestamp)];
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ][(int) date('n', $timestamp)] ?? '';

    $tgl = date('d', $timestamp);
    $thn = date('Y', $timestamp);

    return $withDay ? "{$hari}, {$tgl} {$bulan} {$thn}" : "{$tgl} {$bulan} {$thn}";
}

function nama_bulan_indonesia(int $month): string
{
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    return $bulan[$month] ?? '-';
}

