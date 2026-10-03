<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Kelas;
use App\Models\PendaftaranSiswa;
use App\Models\Siswa;

class SiswaController extends Controller
{
    private Siswa $siswaModel;
    private Kelas $kelasModel;
    private PendaftaranSiswa $pendaftaranModel;

    public function __construct()
    {
        $this->siswaModel = new Siswa();
        $this->kelasModel = new Kelas();
        $this->pendaftaranModel = new PendaftaranSiswa();
    }

    public function index(): void
    {
        $kelasList = $this->kelasModel->allWithStudentCounts();

        $this->render('admin/siswa/index', [
            'pageTitle' => 'Direktori Siswa',
            'activeNav' => 'siswa',
            'kelasList' => $kelasList,
            'totalSiswa' => array_sum(array_map(static fn ($kelas) => (int) $kelas['jumlah_siswa'], $kelasList)),
        ]);
    }

    public function kelas(string $id): void
    {
        $kelasId = (int) $id;
        $kelas = $this->kelasModel->findWithRelations($kelasId);
        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/siswa');
        }

        $siswaList = $this->kelasModel->students($kelasId);
        $this->render('admin/siswa/detail_kelas', [
            'pageTitle' => 'Siswa ' . $kelas['nama'],
            'activeNav' => 'siswa',
            'kelas' => $kelas,
            'siswaList' => $siswaList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/siswa/form', [
            'pageTitle' => 'Tambah Data Siswa',
            'activeNav' => 'siswa',
            'isEdit' => false,
            'siswa' => null,
            'parents' => [],
            'kelasList' => $this->kelasModel->allWithRelations(),
            'kelasId' => (int) ($_GET['kelas_id'] ?? 0),
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $nis = trim($_POST['nis'] ?? '');
        $asalSekolah = trim($_POST['asal_sekolah'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);

        $parentsInput = $_POST['parents'] ?? [];

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap siswa wajib diisi.';
        }

        if (empty($asalSekolah)) {
            $errors['asal_sekolah'] = 'Asal sekolah siswa wajib diisi.';
        }
        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas atau program pembelajaran yang valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/siswa/form', [
                'pageTitle' => 'Tambah Data Siswa',
                'activeNav' => 'siswa',
                'isEdit' => false,
                'siswa' => [
                    'nama_lengkap' => $namaLengkap,
                    'nis' => $nis,
                    'asal_sekolah' => $asalSekolah,
                    'status_aktif' => $statusAktif,
                ],
                'parents' => $parentsInput,
                'kelasList' => $this->kelasModel->allWithRelations(),
                'kelasId' => $kelasId,
                'errors' => $errors
            ]);
            return;
        }

        $siswaId = $this->siswaModel->create([
            'nama_lengkap' => $namaLengkap,
            'nis' => $nis ?: null,
            'asal_sekolah' => $asalSekolah,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($parentsInput) && is_array($parentsInput)) {
            $this->siswaModel->syncParents((int) $siswaId, $parentsInput);
        }
        $this->pendaftaranModel->syncActivePlacement((int) $siswaId, $kelasId);

        $_SESSION['flash_success'] = 'Data siswa & orang tua berhasil disimpan.';
        $this->redirect('/admin/siswa');
    }

    public function edit(string $id): void
    {
        $idInt = (int) $id;
        $siswa = $this->siswaModel->find($idInt);

        if (!$siswa) {
            $_SESSION['flash_error'] = 'Data siswa tidak ditemukan.';
            $this->redirect('/admin/siswa');
        }

        $parents = $this->siswaModel->getParents($idInt);

        $this->render('admin/siswa/form', [
            'pageTitle' => 'Edit Data Siswa',
            'activeNav' => 'siswa',
            'isEdit' => true,
            'siswa' => $siswa,
            'parents' => $parents,
            'kelasList' => $this->kelasModel->allWithRelations(),
            'kelasId' => $this->activeClassId($idInt),
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $siswa = $this->siswaModel->find($idInt);

        if (!$siswa) {
            $_SESSION['flash_error'] = 'Data siswa tidak ditemukan.';
            $this->redirect('/admin/siswa');
        }

        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $nis = trim($_POST['nis'] ?? '');
        $asalSekolah = trim($_POST['asal_sekolah'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $parentsInput = $_POST['parents'] ?? [];

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap siswa wajib diisi.';
        }

        if (empty($asalSekolah)) {
            $errors['asal_sekolah'] = 'Asal sekolah siswa wajib diisi.';
        }
        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas atau program pembelajaran yang valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/siswa/form', [
                'pageTitle' => 'Edit Data Siswa',
                'activeNav' => 'siswa',
                'isEdit' => true,
                'siswa' => array_merge($siswa, [
                    'nama_lengkap' => $namaLengkap,
                    'nis' => $nis,
                    'asal_sekolah' => $asalSekolah,
                    'status_aktif' => $statusAktif,
                ]),
                'parents' => $parentsInput,
                'kelasList' => $this->kelasModel->allWithRelations(),
                'kelasId' => $kelasId,
                'errors' => $errors
            ]);
            return;
        }

        $this->siswaModel->update($idInt, [
            'nama_lengkap' => $namaLengkap,
            'nis' => $nis ?: null,
            'asal_sekolah' => $asalSekolah,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        if (is_array($parentsInput)) {
            $this->siswaModel->syncParents($idInt, $parentsInput);
        }
        $this->pendaftaranModel->syncActivePlacement($idInt, $kelasId);

        $_SESSION['flash_success'] = 'Data siswa & orang tua berhasil diperbarui.';
        $this->redirect('/admin/siswa');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $siswa = $this->siswaModel->find($idInt);

        if (!$siswa) {
            $_SESSION['flash_error'] = 'Data siswa tidak ditemukan.';
            $this->redirect('/admin/siswa');
        }

        try {
            $this->siswaModel->delete($idInt);
            $_SESSION['flash_success'] = 'Data siswa berhasil dihapus.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menghapus siswa. Terikat dengan data pendaftaran atau presensi.';
        }

        $this->redirect('/admin/siswa');
    }

    private function activeClassId(int $siswaId): int
    {
        $stmt = $this->siswaModel->getActivePlacement($siswaId);
        return $stmt ? (int) $stmt['kelas_id'] : 0;
    }
}
