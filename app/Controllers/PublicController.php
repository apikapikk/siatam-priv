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
            'programList' => $programList,
            'paketList' => $paketList,
            'tentorList' => $tentorList,
            'latestBerita' => $latestBerita,
        ], 'public/layout');
    }

    public function beritaList(): void
    {
        $db = \getDBConnection();
        $stmtBerita = $db->query("SELECT * FROM `berita` WHERE `status_terbit` = 1 ORDER BY `diterbitkan_pada` DESC");
        $beritaList = $stmtBerita->fetchAll();

        $this->render('public/berita/index', [
            'pageTitle' => 'Berita & Informasi Publik',
            'beritaList' => $beritaList,
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
        $siswaResult = [];
        $presensiList = [];

        if (!empty($keyword)) {
            $db = \getDBConnection();
            $stmtSiswa = $db->prepare("SELECT * FROM `siswa` WHERE `nama_lengkap` LIKE :q OR `asal_sekolah` LIKE :q LIMIT 10");
            $stmtSiswa->execute(['q' => '%' . $keyword . '%']);
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
                     ORDER BY p.tanggal DESC, p.nomor_pertemuan DESC"
                );
                $stmtPresensi->execute(['siswa_id' => $siswaId]);
                $presensiList = $stmtPresensi->fetchAll();
            }
        }

        $this->render('public/cek_presensi', [
            'pageTitle' => 'Cek Presensi & Laporan Siswa',
            'keyword' => $keyword,
            'siswaResult' => $siswaResult,
            'presensiList' => $presensiList,
            'selectedSiswaId' => $_GET['siswa_id'] ?? 0,
        ], 'public/layout');
    }
}
