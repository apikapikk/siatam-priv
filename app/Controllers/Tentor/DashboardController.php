<?php

namespace App\Controllers\Tentor;

use App\Core\Controller;
use App\Models\Tentor;
use App\Models\Jadwal;
use App\Models\Pertemuan;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    private Tentor $tentorModel;
    private Jadwal $jadwalModel;
    private Pertemuan $pertemuanModel;
    private Pengumuman $pengumumanModel;

    public function __construct()
    {
        // Proteksi Otorisasi Khusus Tentor
        if (!isset($_SESSION['user_id']) || $_SESSION['peran'] !== 'tentor') {
            $_SESSION['flash_error'] = 'Silakan login sebagai tentor untuk mengakses halaman tersebut.';
            header('Location: /login');
            exit;
        }

        $this->tentorModel = new Tentor();
        $this->jadwalModel = new Jadwal();
        $this->pertemuanModel = new Pertemuan();
        $this->pengumumanModel = new Pengumuman();
    }

    public function index(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $tentorProfil = $this->tentorModel->findByPenggunaId($userId);

        $stats = [
            'total_jadwal' => 0,
            'sesi_bulan_ini' => 0,
            'total_jam' => 0,
            'persentase_kehadiran' => 0,
        ];

        $jadwalHariIni = [];
        $announcements = [];

        if ($tentorProfil) {
            $tentorId = (int) $tentorProfil['id'];

            // Hitung statistik mengajar tentor
            $db = \getDBConnection();

            $stmtJadwalCount = $db->prepare("SELECT COUNT(*) FROM `jadwal` WHERE `tentor_id` = :t_id AND `status_aktif` = 1");
            $stmtJadwalCount->execute(['t_id' => $tentorId]);
            $stats['total_jadwal'] = (int) $stmtJadwalCount->fetchColumn();

            $stmtSesiCount = $db->prepare(
                "SELECT COUNT(*) FROM `pertemuan`
                 WHERE `tentor_id` = :t_id
                   AND YEAR(tanggal) = YEAR(CURRENT_DATE())
                   AND MONTH(tanggal) = MONTH(CURRENT_DATE())"
            );
            $stmtSesiCount->execute(['t_id' => $tentorId]);
            $stats['sesi_bulan_ini'] = (int) $stmtSesiCount->fetchColumn();
            $performance = $this->tentorModel->getTeachingPerformance($tentorId, (int) date('n'), (int) date('Y'));
            $stats['total_jam'] = $performance['total_jam'];
            $stats['persentase_kehadiran'] = $performance['persentase_kehadiran'];

            // Jadwal tentor hari ini
            $stmtToday = $db->prepare(
                "SELECT j.hari, j.jam_mulai, j.jam_selesai, j.ruangan,
                        k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama
                 FROM `jadwal` j
                 JOIN `kelas` k ON k.id = j.kelas_id
                 JOIN `jenjang` jg ON jg.id = k.jenjang_id
                 JOIN `program` pr ON pr.id = k.program_id
                 WHERE j.tentor_id = :t_id
                   AND j.status_aktif = 1
                   AND j.hari = WEEKDAY(CURRENT_DATE()) + 1
                 ORDER BY j.jam_mulai ASC"
            );
            $stmtToday->execute(['t_id' => $tentorId]);
            $jadwalHariIni = $stmtToday->fetchAll();
        }

        // Pengumuman khusus tentor/semua
        $announcements = $this->pengumumanModel->getLatestActiveAnnouncements('tentor');

        $this->render('tentor/beranda', [
            'pageTitle' => 'Beranda Tentor',
            'activeNav' => 'beranda',
            'tentorProfil' => $tentorProfil,
            'stats' => $stats,
            'jadwalHariIni' => $jadwalHariIni,
            'announcements' => $announcements,
        ], 'tentor/layout');
    }
}
