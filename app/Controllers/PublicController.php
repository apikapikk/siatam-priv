<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Program;
use App\Models\Paket;
use App\Models\Tentor;
use App\Models\Berita;
use App\Models\Siswa;
use App\Models\Pertemuan;

class PublicController extends Controller
{
    private Program $programModel;
    private Paket $paketModel;
    private Tentor $tentorModel;
    private Berita $beritaModel;
    private Siswa $siswaModel;
    private Pertemuan $pertemuanModel;

    public function __construct()
    {
        $this->programModel = new Program();
        $this->paketModel = new Paket();
        $this->tentorModel = new Tentor();
        $this->beritaModel = new Berita();
        $this->siswaModel = new Siswa();
        $this->pertemuanModel = new Pertemuan();
    }

    public function index(): void
    {
        $programList = $this->programModel->all();
        $paketList = $this->paketModel->all();
        $tentorList = $this->tentorModel->allWithPengguna();

        $db = \getDBConnection();
        $stmtBerita = $db->query("SELECT * FROM `berita` WHERE `status_terbit` = 1 ORDER BY `diterbitkan_pada` DESC LIMIT 3");
        $latestBerita = $stmtBerita->fetchAll();

        $this->render('public/home', [
            'pageTitle' => 'Sistem Informasi Bimbingan Belajar',
            'activeNav' => 'home',
            'programList' => $programList,
            'paketList' => $paketList,
            'tentorList' => $tentorList,
            'latestBerita' => $latestBerita,
        ], 'public/layout');
    }

    public function beritaList(): void
    {
        $db = \getDBConnection();
        $filter = trim($_GET['filter'] ?? 'semua');
        $where = "`status_terbit` = 1";
        if ($filter === 'bakti-sosial') {
            $where .= " AND (`judul` LIKE '%sosial%' OR `isi` LIKE '%sosial%')";
        } elseif ($filter === 'rekap-bulanan') {
            $where .= " AND (`judul` LIKE '%rekap%' OR `isi` LIKE '%rekap%' OR `judul` LIKE '%bulanan%' OR `isi` LIKE '%bulanan%')";
        } else {
            $filter = 'semua';
        }
        $stmtBerita = $db->query("SELECT * FROM `berita` WHERE {$where} ORDER BY `diterbitkan_pada` DESC");
        $beritaList = $stmtBerita->fetchAll();

        $this->render('public/berita/index', [
            'pageTitle' => 'Berita & Informasi Publik',
            'activeNav' => 'berita',
            'beritaList' => $beritaList,
            'filter' => $filter,
        ], 'public/layout');
    }

    public function tentorList(): void
    {
        $this->render('public/tentor/index', [
            'pageTitle' => 'Profil Tentor',
            'activeNav' => 'tentor',
            'tentorList' => $this->tentorModel->allWithPengguna(),
        ], 'public/layout');
    }

    public function tentorDetail(string $id): void
    {
        $tentor = $this->tentorModel->findWithPengguna((int) $id);
        if (!$tentor || (int) ($tentor['status_aktif'] ?? 0) !== 1) {
            http_response_code(404);
            $this->render('404', ['pageTitle' => 'Profil Tentor Tidak Ditemukan']);
            return;
        }

        $this->render('public/tentor/detail', [
            'pageTitle' => 'Profil ' . $tentor['nama_lengkap'],
            'activeNav' => 'tentor',
            'tentor' => $tentor,
        ], 'public/layout');
    }

    public function beritaDetail(string $slug): void
    {
        $db = \getDBConnection();
        $stmt = $db->prepare("SELECT b.*, u.username AS pembuat_nama FROM `berita` b JOIN `pengguna` u ON u.id = b.dibuat_oleh WHERE b.slug = :slug AND b.status_terbit = 1 LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $berita = $stmt->fetch();

        if (!$berita) {
            http_response_code(404);
            $this->render('404', ['pageTitle' => 'Berita Tidak Ditemukan']);
            return;
        }

        $this->render('public/berita/detail', [
            'pageTitle' => $berita['judul'],
            'berita' => $berita,
        ], 'public/layout');
    }

    public function cekPresensi(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $tipe = trim($_GET['tipe'] ?? '');
        $jenjang = trim($_GET['jenjang'] ?? '');
        $kelas = trim($_GET['kelas'] ?? '');
        $tipeDb = match (strtolower($tipe)) {
            'reguler' => 'reguler',
            'privat', 'private' => 'private',
            default => $tipe,
        };
        $jenjangDb = match (strtoupper($jenjang)) {
            'SD' => 'SD',
            'SMP' => 'SMP/MTs',
            'SMA' => 'SMA/MA',
            default => $jenjang,
        };
        $month = (int) ($_GET['bulan'] ?? date('n'));
        $year = (int) ($_GET['tahun'] ?? date('Y'));
        $siswaResult = [];
        $presensiList = [];
        $summary = ['total' => 0, 'hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'none' => 0];

        if (!empty($keyword)) {
            $db = \getDBConnection();
            $stmtSiswa = $db->prepare(
                "SELECT DISTINCT s.* FROM `siswa` s
                 LEFT JOIN `pendaftaran_siswa` ps ON ps.siswa_id = s.id AND ps.status = 'aktif'
                 LEFT JOIN `kelas` k ON k.id = ps.kelas_id
                 LEFT JOIN `jenjang` jg ON jg.id = k.jenjang_id
                 LEFT JOIN `program` pr ON pr.id = k.program_id
                 WHERE (s.`nama_lengkap` LIKE :q_nama
                    OR s.`asal_sekolah` LIKE :q_sekolah
                    OR s.`nis` LIKE :q_nis
                    OR s.`id` = :id_exact)
                   AND (:tipe_empty = '' OR pr.tipe = :tipe_value)
                   AND (:jenjang_empty = '' OR jg.nama = :jenjang_value)
                   AND (:kelas_empty = '' OR k.nama = :kelas_value)
                 LIMIT 10"
            );
            $stmtSiswa->execute([
                'q_nama' => '%' . $keyword . '%',
                'q_sekolah' => '%' . $keyword . '%',
                'q_nis' => '%' . $keyword . '%',
                'id_exact' => ctype_digit($keyword) ? (int) $keyword : 0,
                'tipe_empty' => $tipeDb,
                'tipe_value' => $tipeDb,
                'jenjang_empty' => $jenjangDb,
                'jenjang_value' => $jenjangDb,
                'kelas_empty' => $kelas,
                'kelas_value' => $kelas,
            ]);
            $siswaResult = $stmtSiswa->fetchAll();

            $siswaId = (int) ($_GET['siswa_id'] ?? 0);
            if ($siswaId <= 0 && count($siswaResult) > 0) {
                $siswaId = (int) $siswaResult[0]['id'];
            }

            if ($siswaId > 0) {
                $stmtPresensi = $db->prepare(
                    "SELECT prs.*, p.tanggal, p.nomor_pertemuan, p.jam_mulai, p.jam_selesai,
                            k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama,
                            t.nama_lengkap AS tentor_nama
                     FROM `presensi` prs
                     JOIN `pertemuan` p ON p.id = prs.pertemuan_id
                     JOIN `jadwal` j ON j.id = p.jadwal_id
                     JOIN `kelas` k ON k.id = j.kelas_id
                     JOIN `jenjang` jg ON jg.id = k.jenjang_id
                     JOIN `program` pr ON pr.id = k.program_id
                     JOIN `tentor` t ON t.id = p.tentor_id
                     WHERE prs.siswa_id = :siswa_id
                       AND MONTH(p.tanggal) = :month
                       AND YEAR(p.tanggal) = :year
                     ORDER BY p.tanggal DESC, p.nomor_pertemuan DESC"
                );
                $stmtPresensi->execute([
                    'siswa_id' => $siswaId,
                    'month' => $month,
                    'year' => $year,
                ]);
                $presensiList = $stmtPresensi->fetchAll();

                foreach ($presensiList as $item) {
                    $status = $item['status_kehadiran'] ?: 'none';
                    $summary['total']++;
                    if (isset($summary[$status])) {
                        $summary[$status]++;
                    }
                }
            }
        }

        // Ambil data siswa terpilih untuk ditampilkan di view
        $selectedSiswaId = (int) ($_GET['siswa_id'] ?? 0);
        $selectedSiswa = null;
        if ($selectedSiswaId > 0) {
            foreach ($siswaResult as $s) {
                if ((int) $s['id'] === $selectedSiswaId) {
                    $selectedSiswa = $s;
                    break;
                }
            }
        } elseif (!empty($siswaResult)) {
            $selectedSiswa = $siswaResult[0];
        }

        $this->render('public/cek_presensi', [
            'pageTitle'       => 'Cek Presensi & Laporan Siswa',
            'activeNav'       => 'cek_presensi',
            'keyword'         => $keyword,
            'tipe'            => $tipe,
            'jenjang'         => $jenjang,
            'kelas'           => $kelas,
            'siswaResult'     => $siswaResult,
            'presensiList'    => $presensiList,
            'selectedSiswaId' => $selectedSiswaId ?: ($selectedSiswa['id'] ?? 0),
            'selectedSiswa'   => $selectedSiswa,
            'bulan'           => $month,
            'tahun'           => $year,
            'summary'         => $summary,
        ], 'public/layout');
    }
}
