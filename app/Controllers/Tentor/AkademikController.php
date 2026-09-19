<?php

namespace App\Controllers\Tentor;

use App\Core\Controller;
use App\Models\Tentor;
use App\Models\Jadwal;
use App\Models\Pertemuan;

class AkademikController extends Controller
{
    private Tentor $tentorModel;
    private Jadwal $jadwalModel;
    private Pertemuan $pertemuanModel;
    private int $tentorId;

    public function __construct()
    {
        // Proteksi Otorisasi Tentor
        if (!isset($_SESSION['user_id']) || $_SESSION['peran'] !== 'tentor') {
            $_SESSION['flash_error'] = 'Silakan login sebagai tentor untuk mengakses halaman tersebut.';
            header('Location: /login');
            exit;
        }

        $this->tentorModel = new Tentor();
        $this->jadwalModel = new Jadwal();
        $this->pertemuanModel = new Pertemuan();

        $tentorProfil = $this->tentorModel->findByPenggunaId((int) $_SESSION['user_id']);
        $this->tentorId = $tentorProfil ? (int) $tentorProfil['id'] : 0;
    }

    public function jadwal(): void
    {
        $db = \getDBConnection();
        $sql = "SELECT j.*,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama
                FROM `jadwal` j
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                WHERE j.tentor_id = :tentor_id AND j.status_aktif = 1
                ORDER BY j.hari ASC, j.jam_mulai ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute(['tentor_id' => $this->tentorId]);
        $jadwalList = $stmt->fetchAll();

        $this->render('tentor/jadwal/index', [
            'pageTitle' => 'Jadwal Mengajar Saya',
            'activeNav' => 'jadwal',
            'jadwalList' => $jadwalList,
        ], 'tentor/layout');
    }

    public function pertemuan(): void
    {
        $db = \getDBConnection();
        $sql = "SELECT p.*,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama,
                       (SELECT COUNT(*) FROM `presensi` prs WHERE prs.pertemuan_id = p.id) AS total_presensi,
                       (SELECT COUNT(*) FROM `presensi` prs WHERE prs.pertemuan_id = p.id AND prs.status_kehadiran = 'hadir') AS total_hadir
                FROM `pertemuan` p
                JOIN `jadwal` j ON j.id = p.jadwal_id
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                WHERE p.tentor_id = :tentor_id
                ORDER BY p.tanggal DESC, p.nomor_pertemuan DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute(['tentor_id' => $this->tentorId]);
        $pertemuanList = $stmt->fetchAll();

        $this->render('tentor/pertemuan/index', [
            'pageTitle' => 'Riwayat Pertemuan Mengajar',
            'activeNav' => 'pertemuan',
            'pertemuanList' => $pertemuanList,
        ], 'tentor/layout');
    }

    public function createPertemuan(): void
    {
        $db = \getDBConnection();
        $sql = "SELECT j.*,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama
                FROM `jadwal` j
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                WHERE j.status_aktif = 1
                ORDER BY j.hari ASC, j.jam_mulai ASC";

        $stmt = $db->query($sql);
        $jadwalList = $stmt->fetchAll();

        $this->render('tentor/pertemuan/form', [
            'pageTitle' => 'Catat Pertemuan Baru',
            'activeNav' => 'pertemuan',
            'jadwalList' => $jadwalList,
            'myTentorId' => $this->tentorId,
            'errors' => []
        ], 'tentor/layout');
    }

    public function storePertemuan(): void
    {
        $jadwalId = (int) ($_POST['jadwal_id'] ?? 0);
        $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
        $jamMulai = trim($_POST['jam_mulai'] ?? '');
        $jamSelesai = trim($_POST['jam_selesai'] ?? '');

        $errors = [];

        if ($jadwalId <= 0 || !$this->jadwalModel->find($jadwalId)) {
            $errors['jadwal_id'] = 'Pilih jadwal yang valid.';
        }

        if (empty($tanggal)) {
            $errors['tanggal'] = 'Tanggal pelaksanaan wajib diisi.';
        }

        if (empty($jamMulai) || empty($jamSelesai)) {
            $errors['jam_mulai'] = 'Jam mulai dan jam selesai wajib diisi.';
        }

        if (!empty($errors)) {
            $db = \getDBConnection();
            $sql = "SELECT j.*, k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama
                    FROM `jadwal` j
                    JOIN `kelas` k ON k.id = j.kelas_id
                    JOIN `jenjang` jg ON jg.id = k.jenjang_id
                    JOIN `program` pr ON pr.id = k.program_id
                    WHERE j.status_aktif = 1";
            $jadwalList = $db->query($sql)->fetchAll();

            $this->render('tentor/pertemuan/form', [
                'pageTitle' => 'Catat Pertemuan Baru',
                'activeNav' => 'pertemuan',
                'jadwalList' => $jadwalList,
                'myTentorId' => $this->tentorId,
                'errors' => $errors
            ], 'tentor/layout');
            return;
        }

        $nomorPertemuan = $this->pertemuanModel->getNextNomorPertemuan($jadwalId);

        $pertemuanId = $this->pertemuanModel->create([
            'jadwal_id' => $jadwalId,
            'tentor_id' => $this->tentorId,
            'nomor_pertemuan' => $nomorPertemuan,
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $jadwal = $this->jadwalModel->find($jadwalId);
        if ($jadwal) {
            $this->populateInitialPresensi((int) $pertemuanId, (int) $jadwal['kelas_id']);
        }

        $_SESSION['flash_success'] = 'Sesi pertemuan berhasil dicatat. Silakan lengkapi presensi & nilai siswa.';
        $this->redirect('/tentor/pertemuan/' . $pertemuanId . '/presensi');
    }

    public function presensi(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan || (int) $pertemuan['tentor_id'] !== $this->tentorId) {
            $_SESSION['flash_error'] = 'Sesi pertemuan tidak ditemukan atau bukan milik Anda.';
            $this->redirect('/tentor/pertemuan');
        }

        $jadwal = $this->jadwalModel->find((int) $pertemuan['jadwal_id']);
        if ($jadwal) {
            $this->populateInitialPresensi($idInt, (int) $jadwal['kelas_id']);
        }

        $presensiList = $this->pertemuanModel->getPresensiList($idInt);

        $this->render('tentor/pertemuan/presensi', [
            'pageTitle' => 'Input Presensi Siswa Pertemuan #' . $pertemuan['nomor_pertemuan'],
            'activeNav' => 'pertemuan',
            'pertemuan' => $pertemuan,
            'presensiList' => $presensiList,
        ], 'tentor/layout');
    }

    public function updatePresensi(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan || (int) $pertemuan['tentor_id'] !== $this->tentorId) {
            $_SESSION['flash_error'] = 'Sesi pertemuan tidak ditemukan atau bukan milik Anda.';
            $this->redirect('/tentor/pertemuan');
        }

        $presensiItems = $_POST['presensi'] ?? [];

        if (is_array($presensiItems)) {
            $this->pertemuanModel->syncPresensi($idInt, $presensiItems);
        }

        $_SESSION['flash_success'] = 'Presensi & nilai siswa berhasil disimpan.';
        $this->redirect('/tentor/pertemuan');
    }

    private function populateInitialPresensi(int $pertemuanId, int $kelasId): void
    {
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
