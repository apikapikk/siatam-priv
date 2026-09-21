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
        $this->render('admin/laporan/index', [
            'pageTitle' => 'Pusat Laporan & Rekap',
            'activeNav' => 'laporan',
            'kelasList' => $this->kelasModel->all(),
            'bulan'     => (int) date('n'),
            'tahun'     => (int) date('Y'),
        ]);
    }

    // GET /admin/laporan/siswa-bulanan
    public function siswaBulanan(): void
    {
        $kelasId = (int) ($_GET['kelas_id'] ?? 0);
        $bulan   = (int) ($_GET['bulan']    ?? date('n'));
        $tahun   = (int) ($_GET['tahun']    ?? date('Y'));

        $report      = [];
        $kelasDetail = null;

        if ($kelasId > 0) {
            $kelasDetail = $this->kelasModel->find($kelasId);
            $report      = $this->pertemuanModel->getMonthlyReportByClass($kelasId, $bulan, $tahun);
        }

        $this->render('admin/laporan/siswa_bulanan', [
            'pageTitle'   => 'Rekap Presensi & Nilai Siswa Bulanan',
            'activeNav'   => 'laporan',
            'kelasList'   => $this->kelasModel->all(),
            'kelasId'     => $kelasId,
            'kelasDetail' => $kelasDetail,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'report'      => $report,
        ]);
    }

    // GET /admin/laporan/tentor-bulanan
    public function tentorBulanan(): void
    {
        $bulan = (int) ($_GET['bulan'] ?? date('n'));
        $tahun = (int) ($_GET['tahun'] ?? date('Y'));

        $payrollList = $this->tentorModel->getMonthlyPayrollSummary($bulan, $tahun);

        $this->render('admin/laporan/tentor_bulanan', [
            'pageTitle'   => 'Rekap Log Mengajar & Honorarium Tentor',
            'activeNav'   => 'laporan',
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'payrollList' => $payrollList,
        ]);
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

        $payrollList = $this->tentorModel->getMonthlyPayrollSummary($bulan, $tahun);
        $namaBulan   = date('F', mktime(0, 0, 0, $bulan, 1));
        $filename    = "rekap-honorarium-tentor-{$namaBulan}-{$tahun}.csv";

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, ['Nama Tentor', 'Universitas', 'Total Sesi', 'Total Jam', 'Tarif/Sesi', 'Rate/Jam', 'Estimasi Honorarium']);

        foreach ($payrollList as $row) {
            fputcsv($out, [
                $row['nama_lengkap'],
                $row['asal_universitas'],
                (int)   $row['total_sesi'],
                round((float) $row['total_jam'], 1),
                'Rp ' . number_format((float) $row['tarif_per_sesi'], 0, ',', '.'),
                'Rp ' . number_format((float) $row['rate_gaji_per_jam'], 0, ',', '.'),
                'Rp ' . number_format((float) $row['total_honorarium'], 0, ',', '.'),
            ]);
        }

        fclose($out);
        exit;
    }
}
