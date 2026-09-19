<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Siswa;

class SiswaController extends Controller
{
    private Siswa $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new Siswa();
    }

    public function index(): void
    {
        $siswaList = $this->siswaModel->allWithParentsAndClass();

        $this->render('admin/siswa/index', [
            'pageTitle' => 'Manajemen Data Siswa',
            'activeNav' => 'siswa',
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
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $asalSekolah = trim($_POST['asal_sekolah'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $parentsInput = $_POST['parents'] ?? [];

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap siswa wajib diisi.';
        }

        if (empty($asalSekolah)) {
            $errors['asal_sekolah'] = 'Asal sekolah siswa wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('admin/siswa/form', [
                'pageTitle' => 'Tambah Data Siswa',
                'activeNav' => 'siswa',
                'isEdit' => false,
                'siswa' => [
                    'nama_lengkap' => $namaLengkap,
                    'asal_sekolah' => $asalSekolah,
                    'status_aktif' => $statusAktif,
                ],
                'parents' => $parentsInput,
                'errors' => $errors
            ]);
            return;
        }

        $siswaId = $this->siswaModel->create([
            'nama_lengkap' => $namaLengkap,
            'asal_sekolah' => $asalSekolah,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($parentsInput) && is_array($parentsInput)) {
            $this->siswaModel->syncParents((int) $siswaId, $parentsInput);
        }

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
        $asalSekolah = trim($_POST['asal_sekolah'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;
        $parentsInput = $_POST['parents'] ?? [];

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap siswa wajib diisi.';
        }

        if (empty($asalSekolah)) {
            $errors['asal_sekolah'] = 'Asal sekolah siswa wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('admin/siswa/form', [
                'pageTitle' => 'Edit Data Siswa',
                'activeNav' => 'siswa',
                'isEdit' => true,
                'siswa' => array_merge($siswa, [
                    'nama_lengkap' => $namaLengkap,
                    'asal_sekolah' => $asalSekolah,
                    'status_aktif' => $statusAktif,
                ]),
                'parents' => $parentsInput,
                'errors' => $errors
            ]);
            return;
        }

        $this->siswaModel->update($idInt, [
            'nama_lengkap' => $namaLengkap,
            'asal_sekolah' => $asalSekolah,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        if (is_array($parentsInput)) {
            $this->siswaModel->syncParents($idInt, $parentsInput);
        }

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
}
