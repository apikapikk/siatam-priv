<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Pertemuan;
use App\Models\Jadwal;
use App\Models\Tentor;
use App\Models\PendaftaranSiswa;

class PertemuanController extends Controller
{
    private Pertemuan $pertemuanModel;
    private Jadwal $jadwalModel;
    private Tentor $tentorModel;
    private PendaftaranSiswa $pendaftaranModel;

    public function __construct()
    {
        $this->pertemuanModel = new Pertemuan();
        $this->jadwalModel = new Jadwal();
        $this->tentorModel = new Tentor();
        $this->pendaftaranModel = new PendaftaranSiswa();
    }

    public function index(): void
    {
        $pertemuanList = $this->pertemuanModel->allWithDetails();

        $this->render('admin/pertemuan/index', [
            'pageTitle' => 'Sesi Pertemuan Mengajar',
            'activeNav' => 'pertemuan',
            'pertemuanList' => $pertemuanList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/pertemuan/form', [
            'pageTitle' => 'Catat Pertemuan Baru',
            'activeNav' => 'pertemuan',
            'isEdit' => false,
            'pertemuan' => null,
            'jadwalList' => $this->jadwalModel->allWithDetails(),
            'tentorList' => $this->tentorModel->allWithPengguna(),
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $jadwalId = (int) ($_POST['jadwal_id'] ?? 0);
        $tentorId = (int) ($_POST['tentor_id'] ?? 0);
        $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
        $jamMulai = trim($_POST['jam_mulai'] ?? '');
        $jamSelesai = trim($_POST['jam_selesai'] ?? '');

        $errors = [];

        if ($jadwalId <= 0 || !$this->jadwalModel->find($jadwalId)) {
            $errors['jadwal_id'] = 'Pilih jadwal yang valid.';
        }

        if ($tentorId <= 0 || !$this->tentorModel->find($tentorId)) {
            $errors['tentor_id'] = 'Pilih tentor yang mengajar.';
        }

        if (empty($tanggal)) {
            $errors['tanggal'] = 'Tanggal pelaksanaan wajib diisi.';
        }

        if (empty($jamMulai) || empty($jamSelesai)) {
            $errors['jam_mulai'] = 'Jam mulai dan jam selesai wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('admin/pertemuan/form', [
                'pageTitle' => 'Catat Pertemuan Baru',
                'activeNav' => 'pertemuan',
                'isEdit' => false,
                'pertemuan' => [
                    'jadwal_id' => $jadwalId,
                    'tentor_id' => $tentorId,
                    'tanggal' => $tanggal,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                ],
                'jadwalList' => $this->jadwalModel->allWithDetails(),
                'tentorList' => $this->tentorModel->allWithPengguna(),
                'errors' => $errors
            ]);
            return;
        }

        $nomorPertemuan = $this->pertemuanModel->getNextNomorPertemuan($jadwalId);

        $pertemuanId = $this->pertemuanModel->create([
            'jadwal_id' => $jadwalId,
            'tentor_id' => $tentorId,
            'nomor_pertemuan' => $nomorPertemuan,
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        // Otomatis populate entri presensi siswa aktif di kelas jadwal tersebut
        $jadwal = $this->jadwalModel->find($jadwalId);
        if ($jadwal) {
            $this->populateInitialPresensi((int) $pertemuanId, (int) $jadwal['kelas_id']);
        }

        $_SESSION['flash_success'] = 'Pertemuan berhasil dicatat. Silakan lengkapi presensi siswa.';
        $this->redirect('/admin/pertemuan/' . $pertemuanId . '/presensi');
    }

    public function presensi(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan) {
            $_SESSION['flash_error'] = 'Data pertemuan tidak ditemukan.';
            $this->redirect('/admin/pertemuan');
        }

        $jadwal = $this->jadwalModel->find((int) $pertemuan['jadwal_id']);
        if ($jadwal) {
            $this->populateInitialPresensi($idInt, (int) $jadwal['kelas_id']);
        }

        $presensiList = $this->pertemuanModel->getPresensiList($idInt);

        $this->render('admin/pertemuan/presensi', [
            'pageTitle' => 'Presensi Siswa Pertemuan #' . $pertemuan['nomor_pertemuan'],
            'activeNav' => 'pertemuan',
            'pertemuan' => $pertemuan,
            'presensiList' => $presensiList,
        ]);
    }

    public function updatePresensi(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan) {
            $_SESSION['flash_error'] = 'Data pertemuan tidak ditemukan.';
            $this->redirect('/admin/pertemuan');
        }

        $presensiItems = $_POST['presensi'] ?? [];

        if (is_array($presensiItems)) {
            $this->pertemuanModel->syncPresensi($idInt, $presensiItems);
        }

        $_SESSION['flash_success'] = 'Presensi & nilai siswa berhasil disimpan.';
        $this->redirect('/admin/pertemuan');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan) {
            $_SESSION['flash_error'] = 'Data pertemuan tidak ditemukan.';
            $this->redirect('/admin/pertemuan');
        }

        $this->pertemuanModel->delete($idInt);

        $_SESSION['flash_success'] = 'Data pertemuan & presensi berhasil dihapus.';
        $this->redirect('/admin/pertemuan');
    }

    private function populateInitialPresensi(int $pertemuanId, int $kelasId): void
    {
        // Ambil semua siswa yang terdaftar aktif di kelas ini
        $db = \getDBConnection();
        $sql = "SELECT siswa_id FROM `pendaftaran_siswa` WHERE `kelas_id` = :kelas_id AND `status` = 'aktif'";
        $stmt = $db->prepare($sql);
        $stmt->execute(['kelas_id' => $kelasId]);
        $activeStudents = $stmt->fetchAll();

        foreach ($activeStudents as $std) {
            $siswaId = (int) $std['siswa_id'];
            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM `presensi` WHERE `pertemuan_id` = :p_id AND `siswa_id` = :s_id");
            $stmtCheck->execute(['p_id' => $pertemuanId, 's_id' => $siswaId]);

            if ((int) $stmtCheck->fetchColumn() === 0) {
                $stmtIns = $db->prepare(
                    "INSERT INTO `presensi` (`pertemuan_id`, `siswa_id`, `status_kehadiran`, `dibuat_pada`, `diubah_pada`)
                     VALUES (:p_id, :s_id, 'none', NOW(), NOW())"
                );
                $stmtIns->execute(['p_id' => $pertemuanId, 's_id' => $siswaId]);
            }
        }
    }
}
