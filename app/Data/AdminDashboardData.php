<?php

require_once __DIR__ . '/../../config/database.php';

use App\Models\Jadwal;
use App\Models\Pengumuman;

function get_admin_dashboard_data(): array
{
    $fallback = get_admin_dashboard_fallback_data();

    try {
        $pdo = getDBConnection();

        return [
            'using_fallback' => false,
            'stats' => get_admin_stats($pdo),
            'today_schedule' => (new Jadwal())->getTodayRealtimeScheduleWithStatus(),
            'announcements' => (new Pengumuman())->getLatestActiveAnnouncements('admin'),
            'news' => get_recent_news($pdo),
        ];
    } catch (Throwable $exception) {
        error_log('Admin dashboard fallback: ' . $exception->getMessage());
        return $fallback;
    }
}

function get_admin_stats(PDO $pdo): array
{
    $activeStudents = (int) $pdo->query("SELECT COUNT(*) FROM siswa WHERE status_aktif = 1")->fetchColumn();
    $activeTutors = (int) $pdo->query("SELECT COUNT(*) FROM tentor WHERE status_aktif = 1")->fetchColumn();
    $activeClasses = (int) $pdo->query("SELECT COUNT(*) FROM kelas WHERE status_aktif = 1")->fetchColumn();

    $meetingStatement = $pdo->prepare(
        "SELECT COUNT(*)
         FROM pertemuan
         WHERE YEAR(tanggal) = YEAR(CURRENT_DATE())
           AND MONTH(tanggal) = MONTH(CURRENT_DATE())"
    );
    $meetingStatement->execute();

    return [
        [
            'label' => 'Siswa Aktif',
            'value' => $activeStudents,
            'tone' => 'blue',
        ],
        [
            'label' => 'Tentor Aktif',
            'value' => $activeTutors,
            'tone' => 'green',
        ],
        [
            'label' => 'Kelas Aktif',
            'value' => $activeClasses,
            'tone' => 'amber',
        ],
        [
            'label' => 'Sesi Bulan Ini',
            'value' => (int) $meetingStatement->fetchColumn(),
            'tone' => 'red',
        ],
    ];
}

function get_today_schedule(PDO $pdo): array
{
    $statement = $pdo->prepare(
        "SELECT jadwal.hari, jadwal.jam_mulai, jadwal.jam_selesai, jadwal.ruangan,
                kelas.nama AS kelas_nama, jenjang.nama AS jenjang_nama,
                program.nama AS program_nama, tentor.nama_lengkap AS tentor_nama
         FROM jadwal
         JOIN kelas ON kelas.id = jadwal.kelas_id
         JOIN jenjang ON jenjang.id = kelas.jenjang_id
         JOIN program ON program.id = kelas.program_id
         JOIN tentor ON tentor.id = jadwal.tentor_id
         WHERE jadwal.status_aktif = 1
           AND jadwal.hari = WEEKDAY(CURRENT_DATE()) + 1
         ORDER BY jadwal.jam_mulai ASC
         LIMIT 5"
    );
    $statement->execute();

    return $statement->fetchAll();
}

function get_recent_announcements(PDO $pdo): array
{
    $statement = $pdo->prepare(
        "SELECT judul, isi, diterbitkan_pada
         FROM pengumuman
         WHERE status_aktif = 1
           AND target_peran IN ('semua', 'admin')
         ORDER BY diterbitkan_pada DESC
         LIMIT 3"
    );
    $statement->execute();

    return $statement->fetchAll();
}

function get_recent_news(PDO $pdo): array
{
    $statement = $pdo->prepare(
        "SELECT judul, gambar, diterbitkan_pada, status_terbit
         FROM berita
         ORDER BY dibuat_pada DESC
         LIMIT 3"
    );
    $statement->execute();

    return $statement->fetchAll();
}

function get_admin_dashboard_fallback_data(): array
{
    return [
        'using_fallback' => true,
        'stats' => [
            ['label' => 'Siswa Aktif', 'value' => 128, 'tone' => 'blue'],
            ['label' => 'Tentor Aktif', 'value' => 18, 'tone' => 'green'],
            ['label' => 'Kelas Aktif', 'value' => 24, 'tone' => 'amber'],
            ['label' => 'Sesi Bulan Ini', 'value' => 96, 'tone' => 'red'],
        ],
        'today_schedule' => [
            [
                'hari' => 4,
                'jam_mulai' => '15:30:00',
                'jam_selesai' => '17:00:00',
                'ruangan' => 'Ruang A',
                'kelas_nama' => '7A',
                'jenjang_nama' => 'SMP/MTs',
                'program_nama' => 'Reguler',
                'tentor_nama' => 'Budi Santoso, S.Pd.',
                'status_realtime' => 'sedang_berlangsung',
            ],
            [
                'hari' => 4,
                'jam_mulai' => '19:00:00',
                'jam_selesai' => '20:30:00',
                'ruangan' => 'Ruang B',
                'kelas_nama' => '12 IPA Private',
                'jenjang_nama' => 'SMA/MA',
                'program_nama' => 'Private',
                'tentor_nama' => 'Andi Wijaya, M.Si.',
                'status_realtime' => 'belum_mulai',
            ],
        ],
        'announcements' => [
            [
                'judul' => 'Batas Pengisian Presensi',
                'isi' => 'Input presensi dan nilai maksimal 24 jam setelah sesi selesai.',
                'diterbitkan_pada' => date('Y-m-d H:i:s'),
            ],
        ],
        'news' => [
            [
                'judul' => 'Pembukaan Pendaftaran Siswa Baru TA 2026/2027',
                'gambar' => null,
                'diterbitkan_pada' => date('Y-m-d H:i:s'),
                'status_terbit' => 1,
            ],
        ],
    ];
}
