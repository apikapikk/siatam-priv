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
