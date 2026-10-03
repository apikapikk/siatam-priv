<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Kelas;
use App\Models\Tentor;
use App\Models\Pertemuan;

class LaporanController extends Controller
{
    private Kelas $kelasModel;
    private Tentor $tentorModel;
    private Pertemuan $pertemuanModel;

    public function __construct()
    {
        $this->kelasModel    = new Kelas();
        $this->tentorModel   = new Tentor();
        $this->pertemuanModel = new Pertemuan();
    }

    // GET /admin/laporan
    public function index(): void
    {
        $db = \getDBConnection();
        $totalSiswa = (int) $db->query("SELECT COUNT(*) FROM siswa WHERE status_aktif = 1")->fetchColumn();
        $totalTentor = (int) $db->query("SELECT COUNT(*) FROM tentor WHERE status_aktif = 1")->fetchColumn();

        $this->render('admin/laporan/index', [
            'pageTitle'   => 'Pusat Laporan & Rekap',
            'activeNav'   => 'laporan',
            'kelasList'   => $this->kelasModel->all(),
            'totalSiswa'  => $totalSiswa,
            'totalTentor' => $totalTentor,
            'bulan'       => (int) date('n'),
            'tahun'       => (int) date('Y'),
        ]);
    }

    // GET /admin/laporan/siswa
    public function siswa(): void
    {
        $mode = ($_GET['mode'] ?? 'bulanan') === 'harian' ? 'harian' : 'bulanan';
        $data = [
            'pageTitle' => 'Laporan Siswa',
            'activeNav' => 'laporan',
            'mode'      => $mode,
            'kelasList' => $this->kelasModel->allWithRelations(),
        ];

        if ($mode === 'harian') {
            $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
            $kelasId = (int) ($_GET['kelas_id'] ?? 0);
            $data += [
                'tanggal' => $tanggal,
                'kelasId' => $kelasId,
                'report'  => $this->pertemuanModel->getDailyReportByClass($tanggal, $kelasId),
            ];
        } else {
            $kelasId = (int) ($_GET['kelas_id'] ?? 0);
            $bulan   = (int) ($_GET['bulan'] ?? date('n'));
            $tahun   = (int) ($_GET['tahun'] ?? date('Y'));
            $data += [
                'kelasId'     => $kelasId,
                'bulan'       => $bulan,
                'tahun'       => $tahun,
                'kelasDetail' => $kelasId > 0 ? $this->kelasModel->findWithRelations($kelasId) : null,
                'report'      => $kelasId > 0 ? $this->pertemuanModel->getMonthlyReportByClass($kelasId, $bulan, $tahun) : [],
            ];
            $data['meetingCounts'] = $this->pertemuanModel->getMonthlyMeetingCountsByClass($bulan, $tahun);
        }

        $this->render('admin/laporan/siswa', $data);
    }

    // GET /admin/laporan/tentor
    public function tentor(): void
    {
        $mode = ($_GET['mode'] ?? 'bulanan') === 'harian' ? 'harian' : 'bulanan';
        $data = [
            'pageTitle' => 'Laporan Tentor',
            'activeNav' => 'laporan',
            'mode'      => $mode,
        ];

        if ($mode === 'harian') {
            $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
            $data += [
                'tanggal' => $tanggal,
                'report'  => $this->tentorModel->getDailyTeachingSummary($tanggal),
            ];
        } else {
            $bulan = (int) ($_GET['bulan'] ?? date('n'));
            $tahun = (int) ($_GET['tahun'] ?? date('Y'));
            $data += [
                'bulan'      => $bulan,
                'tahun'      => $tahun,
                'report'     => $this->tentorModel->getMonthlyTeachingSummary($bulan, $tahun),
                'breakdowns' => $this->tentorModel->getMonthlyBreakdownByTentor($bulan, $tahun),
            ];
        }

        $this->render('admin/laporan/tentor', $data);
    }

    // Legacy forwarding endpoints
    public function siswaBulanan(): void
    {
        $params = array_merge(['mode' => 'bulanan'], $_GET);
        $this->redirect('/admin/laporan/siswa?' . http_build_query($params));
    }

    public function siswaHarian(): void
    {
        $params = array_merge(['mode' => 'harian'], $_GET);
        $this->redirect('/admin/laporan/siswa?' . http_build_query($params));
    }

    public function tentorBulanan(): void
    {
        $params = array_merge(['mode' => 'bulanan'], $_GET);
        $this->redirect('/admin/laporan/tentor?' . http_build_query($params));
    }

    public function tentorHarian(): void
    {
        $params = array_merge(['mode' => 'harian'], $_GET);
        $this->redirect('/admin/laporan/tentor?' . http_build_query($params));
    }


    // GET /admin/laporan/siswa-bulanan/export-csv
    public function exportSiswaCsv(): void
    {
        $kelasId = (int) ($_GET['kelas_id'] ?? 0);
        $bulan   = (int) ($_GET['bulan']    ?? date('n'));
        $tahun   = (int) ($_GET['tahun']    ?? date('Y'));

        if ($kelasId <= 0) {
            $_SESSION['flash_error'] = 'Pilih kelas terlebih dahulu untuk ekspor.';
            $this->redirect('/admin/laporan/siswa-bulanan');
        }

        $report      = $this->pertemuanModel->getMonthlyReportByClass($kelasId, $bulan, $tahun);
        $summary     = $report['summary_by_student'] ?? [];
        $namaBulan   = date('F', mktime(0, 0, 0, $bulan, 1));
        $kelasDetail = $this->kelasModel->find($kelasId);
        $namaKelas   = $kelasDetail['nama'] ?? 'Kelas';

        $filename = "rekap-siswa-{$namaKelas}-{$namaBulan}-{$tahun}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $out = fopen('php://output', 'w');
        // BOM untuk Excel agar UTF-8 terbaca dengan benar
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['Nama Siswa', 'Asal Sekolah', 'Total Sesi', 'Hadir', 'Sakit', 'Izin', 'Alfa', 'Belum Diisi', '% Kehadiran']);

        foreach ($summary as $row) {
            $total  = (int) $row['total'];
            $hadir  = (int) $row['hadir'];
            $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            fputcsv($out, [
                $row['siswa_nama'],
                $row['asal_sekolah'],
                $total,
                $hadir,
                (int) $row['sakit'],
                (int) $row['izin'],
                (int) $row['alfa'],
                (int) $row['none'],
                $persen . '%',
            ]);
        }

        fclose($out);
        exit;
    }

    // GET /admin/laporan/tentor-bulanan/export-csv
    public function exportTentorCsv(): void
    {
        $bulan = (int) ($_GET['bulan'] ?? date('n'));
        $tahun = (int) ($_GET['tahun'] ?? date('Y'));

        $payrollList = $this->tentorModel->getMonthlyTeachingSummary($bulan, $tahun);
        $namaBulan   = date('F', mktime(0, 0, 0, $bulan, 1));
        $filename    = "rekap-tentor-{$namaBulan}-{$tahun}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['Nama Tentor', 'Universitas', 'Total Sesi', 'Total Jam']);

        foreach ($payrollList as $row) {
            fputcsv($out, [
                $row['nama_lengkap'],
                $row['asal_universitas'],
                (int)   $row['total_sesi'],
                round((float) $row['total_jam'], 1),
            ]);
        }

        fclose($out);
        exit;
    }
}
