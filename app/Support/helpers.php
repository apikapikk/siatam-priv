<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_number(int $value): string
{
    return number_format($value, 0, ',', '.');
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
