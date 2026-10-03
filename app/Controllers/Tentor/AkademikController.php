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
        $selectedMonth = trim($_GET['bulan'] ?? date('Y-m'));
        if (!preg_match('/^\\d{4}-\\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }
        $selectedDate = trim($_GET['tanggal'] ?? date('Y-m-d'));
        $monthStart = $selectedMonth . '-01';
        $monthEnd = date('Y-m-t', strtotime($monthStart));
        if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $selectedDate) || $selectedDate < $monthStart || $selectedDate > $monthEnd) {
            $selectedDate = date('Y-m-d') >= $monthStart && date('Y-m-d') <= $monthEnd ? date('Y-m-d') : $monthStart;
        }
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
        $weekday = (int) date('N', strtotime($selectedDate));
        $jadwalHariIni = array_values(array_filter($jadwalList, static fn (array $item): bool => (int) $item['hari'] === $weekday));

        $calendarStart = new \DateTime($selectedDate);
        $calendarStart->modify('monday this week');
        $calendarDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $calendarStart)->modify('+' . $i . ' days');
            $dateValue = $date->format('Y-m-d');
            $calendarDates[] = [
                'value' => $dateValue,
                'day' => $date->format('D'),
                'number' => $date->format('d'),
                'has_schedule' => (bool) array_filter($jadwalList, static fn (array $item): bool => (int) $item['hari'] === (int) $date->format('N')),
            ];
        }

        $this->render('tentor/jadwal/index', [
            'pageTitle' => 'Jadwal Saya',
            'activeNav' => 'jadwal',
            'jadwalList' => $jadwalList,
            'jadwalHariIni' => $jadwalHariIni,
            'selectedMonth' => $selectedMonth,
            'selectedDate' => $selectedDate,
            'calendarDates' => $calendarDates,
        ], 'tentor/layout');
    }

    public function detailKelas(string $id): void
    {
        $kelasIdInt = (int) $id;
        $db = \getDBConnection();
        $stmtKelas = $db->prepare(
            "SELECT k.*, jg.nama AS jenjang_nama, pr.nama AS program_nama, pr.tipe AS program_tipe
             FROM kelas k JOIN jenjang jg ON jg.id = k.jenjang_id JOIN program pr ON pr.id = k.program_id
             WHERE k.id = :kelas_id LIMIT 1"
        );
        $stmtKelas->execute(['kelas_id' => $kelasIdInt]);
        $kelas = $stmtKelas->fetch();
        $stmtJadwal = $db->prepare("SELECT * FROM jadwal WHERE kelas_id = :kelas_id AND tentor_id = :tentor_id AND status_aktif = 1 ORDER BY hari, jam_mulai");
        $stmtJadwal->execute(['kelas_id' => $kelasIdInt, 'tentor_id' => $this->tentorId]);
        $jadwalKelas = $stmtJadwal->fetchAll();
        if (!$kelas || empty($jadwalKelas)) {
            $_SESSION['flash_error'] = 'Detail kelas tidak ditemukan atau bukan kelas Anda.';
            $this->redirect('/tentor/jadwal');
        }
        $stmtPertemuan = $db->prepare(
            "SELECT p.*, j.mata_pelajaran, j.ruangan,
                    (SELECT COUNT(*) FROM presensi prs WHERE prs.pertemuan_id = p.id) AS total_presensi,
                    (SELECT COUNT(*) FROM presensi prs WHERE prs.pertemuan_id = p.id AND prs.status_kehadiran = 'hadir') AS total_hadir
             FROM pertemuan p JOIN jadwal j ON j.id = p.jadwal_id
             WHERE j.kelas_id = :kelas_id AND p.tentor_id = :tentor_id
             ORDER BY p.tanggal DESC, p.nomor_pertemuan DESC"
        );
        $stmtPertemuan->execute(['kelas_id' => $kelasIdInt, 'tentor_id' => $this->tentorId]);
        $this->render('tentor/jadwal/detail-kelas', [
            'pageTitle' => 'Detail Kelas & Riwayat', 'activeNav' => 'jadwal', 'kelas' => $kelas,
            'jadwalKelas' => $jadwalKelas, 'pertemuanList' => $stmtPertemuan->fetchAll(),
        ], 'tentor/layout');
    }

    public function detailKehadiran(string $id): void
    {
        $pertemuanId = (int) $id;
        $pertemuan = $this->pertemuanModel->find($pertemuanId);
        if (!$pertemuan || (int) $pertemuan['tentor_id'] !== $this->tentorId) {
            $_SESSION['flash_error'] = 'Detail kehadiran tidak ditemukan atau bukan milik Anda.';
            $this->redirect('/tentor/jadwal');
        }
        $db = \getDBConnection();
        $stmt = $db->prepare(
            "SELECT p.*, j.kelas_id, j.mata_pelajaran, j.ruangan,
                    k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama
             FROM pertemuan p JOIN jadwal j ON j.id = p.jadwal_id JOIN kelas k ON k.id = j.kelas_id
             JOIN jenjang jg ON jg.id = k.jenjang_id JOIN program pr ON pr.id = k.program_id
             WHERE p.id = :id AND p.tentor_id = :tentor_id LIMIT 1"
        );
        $stmt->execute(['id' => $pertemuanId, 'tentor_id' => $this->tentorId]);
        $detail = $stmt->fetch();
        $this->populateInitialPresensi($pertemuanId, (int) $detail['kelas_id']);
        $this->render('tentor/jadwal/detail-kehadiran', [
            'pageTitle' => 'Detail Kehadiran', 'activeNav' => 'jadwal', 'pertemuan' => $detail,
            'presensiList' => $this->pertemuanModel->getPresensiList($pertemuanId),
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

    public function pilihPresensi(): void
    {
        $db = \getDBConnection();
        // Kelas aktif tentor
        $sqlClasses = "SELECT k.id AS kelas_id, k.nama AS kelas_nama,
                              jg.nama AS jenjang_nama, pr.nama AS program_nama, pr.tipe AS program_tipe,
                              MIN(j.id) AS jadwal_id, MIN(j.mata_pelajaran) AS mata_pelajaran,
                              (SELECT COUNT(*) FROM pendaftaran_siswa ps WHERE ps.kelas_id = k.id AND ps.status = 'aktif') AS total_siswa
                       FROM jadwal j
                       JOIN kelas k ON k.id = j.kelas_id
                       JOIN jenjang jg ON jg.id = k.jenjang_id
                       JOIN program pr ON pr.id = k.program_id
                       WHERE j.tentor_id = :tentor_id AND j.status_aktif = 1
                       GROUP BY k.id, k.nama, jg.nama, pr.nama, pr.tipe
                       ORDER BY k.nama ASC";
        $stmtC = $db->prepare($sqlClasses);
        $stmtC->execute(['tentor_id' => $this->tentorId]);
        $kelasList = $stmtC->fetchAll();

        // Riwayat Presensi yang pernah diisi oleh tentor ini
        $sqlHistory = "SELECT p.*, k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama, j.mata_pelajaran,
                              (SELECT COUNT(*) FROM presensi prs WHERE prs.pertemuan_id = p.id) AS total_presensi,
                              (SELECT COUNT(*) FROM presensi prs WHERE prs.pertemuan_id = p.id AND prs.status_kehadiran = 'hadir') AS total_hadir
                       FROM pertemuan p
                       JOIN jadwal j ON j.id = p.jadwal_id
                       JOIN kelas k ON k.id = j.kelas_id
                       JOIN jenjang jg ON jg.id = k.jenjang_id
                       JOIN program pr ON pr.id = k.program_id
                       WHERE p.tentor_id = :tentor_id
                       ORDER BY p.tanggal DESC, p.id DESC
                       LIMIT 5";
        $stmtH = $db->prepare($sqlHistory);
        $stmtH->execute(['tentor_id' => $this->tentorId]);
        $riwayatList = $stmtH->fetchAll();

        $this->render('tentor/presensi/index', [
            'pageTitle'   => 'Presensi Siswa',
            'activeNav'   => 'presensi',
            'kelasList'   => $kelasList,
            'riwayatList' => $riwayatList,
        ], 'tentor/layout');
    }

    public function isiPresensi(): void
    {
        $db = \getDBConnection();
        $pertemuanId = (int) ($_GET['pertemuan_id'] ?? 0);
        $kelasId = (int) ($_GET['kelas_id'] ?? 0);
        $pertemuan = null;
        $jadwal = null;
        $kelas = null;

        if ($pertemuanId > 0) {
            $pertemuan = $this->pertemuanModel->find($pertemuanId);
            if ($pertemuan && (int)$pertemuan['tentor_id'] === $this->tentorId) {
                $jadwal = $this->jadwalModel->find((int)$pertemuan['jadwal_id']);
                if ($jadwal) {
                    $kelasId = (int)$jadwal['kelas_id'];
                }
            } else {
                $pertemuan = null;
            }
        }

        if ($kelasId <= 0 && $jadwal) {
            $kelasId = (int)$jadwal['kelas_id'];
        }

        if ($kelasId <= 0) {
            $_SESSION['flash_error'] = 'Pilih kelas terlebih dahulu untuk mengisi presensi.';
            $this->redirect('/tentor/presensi');
        }

        $stmtKelas = $db->prepare("SELECT k.*, jg.nama AS jenjang_nama, pr.nama AS program_nama, pr.tipe AS program_tipe
                                   FROM kelas k
                                   JOIN jenjang jg ON jg.id = k.jenjang_id
                                   JOIN program pr ON pr.id = k.program_id
                                   WHERE k.id = :id LIMIT 1");
        $stmtKelas->execute(['id' => $kelasId]);
        $kelas = $stmtKelas->fetch();

        if (!$jadwal) {
            $stmtJadwal = $db->prepare("SELECT * FROM jadwal WHERE kelas_id = :k_id AND tentor_id = :t_id AND status_aktif = 1 LIMIT 1");
            $stmtJadwal->execute(['k_id' => $kelasId, 't_id' => $this->tentorId]);
            $jadwal = $stmtJadwal->fetch();
        }

        if (!$jadwal) {
            $stmtJadwal = $db->prepare("SELECT * FROM jadwal WHERE kelas_id = :k_id AND status_aktif = 1 LIMIT 1");
            $stmtJadwal->execute(['k_id' => $kelasId]);
            $jadwal = $stmtJadwal->fetch();
        }

        // Students & Presensi list
        if ($pertemuan) {
            $this->populateInitialPresensi((int)$pertemuan['id'], $kelasId);
            $presensiList = $this->pertemuanModel->getPresensiList((int)$pertemuan['id']);
            $nomorPertemuan = (int)$pertemuan['nomor_pertemuan'];
            $tanggal = $pertemuan['tanggal'];
            $jamMulai = substr($pertemuan['jam_mulai'], 0, 5);
            $jamSelesai = substr($pertemuan['jam_selesai'], 0, 5);
        } else {
            // Next meeting number
            $jadwalId = $jadwal ? (int)$jadwal['id'] : 0;
            $stmtNextNo = $db->prepare("SELECT COALESCE(MAX(nomor_pertemuan), 0) + 1 FROM pertemuan WHERE jadwal_id = :j_id");
            $stmtNextNo->execute(['j_id' => $jadwalId]);
            $nomorPertemuan = (int) $stmtNextNo->fetchColumn();
            if ($nomorPertemuan <= 0) {
                $nomorPertemuan = 1;
            }

            $tanggal = date('Y-m-d');
            $jamMulai = $jadwal ? substr($jadwal['jam_mulai'], 0, 5) : '15:00';
            $jamSelesai = $jadwal ? substr($jadwal['jam_selesai'], 0, 5) : '16:30';

            // Active students
            $stmtStudents = $db->prepare("SELECT s.id AS siswa_id, s.nis, s.nama_lengkap AS siswa_nama, s.asal_sekolah,
                                                 'none' AS status_kehadiran, '' AS nilai_sikap, '' AS nilai_akademik, '' AS catatan
                                          FROM pendaftaran_siswa ps
                                          JOIN siswa s ON s.id = ps.siswa_id
                                          WHERE ps.kelas_id = :k_id AND ps.status = 'aktif'
                                          ORDER BY s.nama_lengkap ASC");
            $stmtStudents->execute(['k_id' => $kelasId]);
            $presensiList = $stmtStudents->fetchAll();
        }

        $this->render('tentor/presensi/form', [
            'pageTitle'      => 'Isi Kehadiran Siswa',
            'activeNav'      => 'presensi',
            'kelas'          => $kelas,
            'jadwal'         => $jadwal,
            'pertemuan'      => $pertemuan,
            'nomorPertemuan' => $nomorPertemuan,
            'tanggal'        => $tanggal,
            'jamMulai'       => $jamMulai,
            'jamSelesai'     => $jamSelesai,
            'presensiList'   => $presensiList,
        ], 'tentor/layout');
    }

    public function simpanPresensi(): void
    {
        $db = \getDBConnection();
        $pertemuanId = (int) ($_POST['pertemuan_id'] ?? 0);
        $jadwalId = (int) ($_POST['jadwal_id'] ?? 0);
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $nomorPertemuan = (int) ($_POST['pertemuan'] ?? 1);
        $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));
        $jamMulai = trim($_POST['waktu_mulai'] ?? '15:00');
        $jamSelesai = trim($_POST['waktu_selesai'] ?? '16:30');
        $presensiData = $_POST['presensi'] ?? [];

        if (strlen($jamMulai) === 5) $jamMulai .= ':00';
        if (strlen($jamSelesai) === 5) $jamSelesai .= ':00';

        if ($pertemuanId > 0) {
            $stmtUpdate = $db->prepare("UPDATE pertemuan SET nomor_pertemuan = :no, tanggal = :tgl, jam_mulai = :mulai, jam_selesai = :selesai, diubah_pada = NOW() WHERE id = :id AND tentor_id = :t_id");
            $stmtUpdate->execute([
                'no'      => $nomorPertemuan,
                'tgl'     => $tanggal,
                'mulai'   => $jamMulai,
                'selesai' => $jamSelesai,
                'id'      => $pertemuanId,
                't_id'    => $this->tentorId,
            ]);
        } else {
            $stmtInsert = $db->prepare("INSERT INTO pertemuan (jadwal_id, tentor_id, nomor_pertemuan, tanggal, jam_mulai, jam_selesai, dibuat_pada, diubah_pada)
                                        VALUES (:j_id, :t_id, :no, :tgl, :mulai, :selesai, NOW(), NOW())");
            $stmtInsert->execute([
                'j_id'    => $jadwalId,
                't_id'    => $this->tentorId,
                'no'      => $nomorPertemuan,
                'tgl'     => $tanggal,
                'mulai'   => $jamMulai,
                'selesai' => $jamSelesai,
            ]);
            $pertemuanId = (int) $db->lastInsertId();
        }

        // Sync student attendance and grades
        if (!empty($presensiData) && is_array($presensiData)) {
            $this->pertemuanModel->syncPresensi($pertemuanId, $presensiData);
        }

        $_SESSION['flash_success'] = 'Kehadiran dan penilaian siswa berhasil disimpan.';
        $this->redirect('/tentor/presensi');
    }

    public function presensi(string $id): void
    {
        $this->redirect('/tentor/presensi/isi?pertemuan_id=' . (int)$id);
    }

    public function updatePresensi(string $id): void
    {
        $idInt = (int) $id;
        $pertemuan = $this->pertemuanModel->find($idInt);

        if (!$pertemuan || (int) $pertemuan['tentor_id'] !== $this->tentorId) {
            $_SESSION['flash_error'] = 'Sesi pertemuan tidak ditemukan atau bukan milik Anda.';
            $this->redirect('/tentor/presensi');
        }

        $presensiItems = $_POST['presensi'] ?? [];

        if (is_array($presensiItems)) {
            $this->pertemuanModel->syncPresensi($idInt, $presensiItems);
        }

        $_SESSION['flash_success'] = 'Presensi & nilai siswa berhasil disimpan.';
        $this->redirect('/tentor/presensi');
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
