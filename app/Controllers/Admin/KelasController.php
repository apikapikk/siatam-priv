<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Kelas;
use App\Models\Jenjang;
use App\Models\Program;

class KelasController extends Controller
{
    private Kelas $kelasModel;
    private Jenjang $jenjangModel;
    private Program $programModel;

    public function __construct()
    {
        $this->kelasModel = new Kelas();
        $this->jenjangModel = new Jenjang();
        $this->programModel = new Program();
    }

    public function index(): void
    {
        $kelasList = $this->kelasModel->allWithRelations();

        $this->render('admin/kelas/index', [
            'pageTitle' => 'Master Data Kelas',
            'activeNav' => 'kelas',
            'kelasList' => $kelasList,
        ]);
    }

    public function create(): void
    {
        $jenjangList = $this->jenjangModel->all();
        $programList = $this->programModel->all();

        $this->render('admin/kelas/form', [
            'pageTitle' => 'Tambah Kelas',
            'activeNav' => 'kelas',
            'isEdit' => false,
            'kelas' => null,
            'jenjangList' => $jenjangList,
            'programList' => $programList,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $nama = trim($_POST['nama'] ?? '');
        $jenjangId = (int) ($_POST['jenjang_id'] ?? 0);
        $programId = (int) ($_POST['program_id'] ?? 0);
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($nama)) {
            $errors['nama'] = 'Nama kelas tidak boleh kosong.';
        } elseif (strlen($nama) > 20) {
            $errors['nama'] = 'Nama kelas maksimal 20 karakter.';
        }

        if ($jenjangId <= 0 || !$this->jenjangModel->find($jenjangId)) {
            $errors['jenjang_id'] = 'Pilih jenjang pendidikan yang valid.';
        }

        if ($programId <= 0 || !$this->programModel->find($programId)) {
            $errors['program_id'] = 'Pilih program yang valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/kelas/form', [
                'pageTitle' => 'Tambah Kelas',
                'activeNav' => 'kelas',
                'isEdit' => false,
                'kelas' => [
                    'nama' => $nama,
                    'jenjang_id' => $jenjangId,
                    'program_id' => $programId,
                    'status_aktif' => $statusAktif,
                ],
                'jenjangList' => $this->jenjangModel->all(),
                'programList' => $this->programModel->all(),
                'errors' => $errors
            ]);
            return;
        }

        $this->kelasModel->create([
            'nama' => $nama,
            'jenjang_id' => $jenjangId,
            'program_id' => $programId,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Data kelas berhasil ditambahkan.';
        $this->redirect('/admin/kelas');
    }

    public function edit(string $id): void
    {
        $kelas = $this->kelasModel->find((int) $id);

        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/kelas');
        }

        $this->render('admin/kelas/form', [
            'pageTitle' => 'Edit Kelas',
            'activeNav' => 'kelas',
            'isEdit' => true,
            'kelas' => $kelas,
            'jenjangList' => $this->jenjangModel->all(),
            'programList' => $this->programModel->all(),
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $kelas = $this->kelasModel->find($idInt);

        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/kelas');
        }

        $nama = trim($_POST['nama'] ?? '');
        $jenjangId = (int) ($_POST['jenjang_id'] ?? 0);
        $programId = (int) ($_POST['program_id'] ?? 0);
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($nama)) {
            $errors['nama'] = 'Nama kelas tidak boleh kosong.';
        } elseif (strlen($nama) > 20) {
            $errors['nama'] = 'Nama kelas maksimal 20 karakter.';
        }

        if ($jenjangId <= 0 || !$this->jenjangModel->find($jenjangId)) {
            $errors['jenjang_id'] = 'Pilih jenjang pendidikan yang valid.';
        }

        if ($programId <= 0 || !$this->programModel->find($programId)) {
            $errors['program_id'] = 'Pilih program yang valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/kelas/form', [
                'pageTitle' => 'Edit Kelas',
                'activeNav' => 'kelas',
                'isEdit' => true,
                'kelas' => array_merge($kelas, [
                    'nama' => $nama,
                    'jenjang_id' => $jenjangId,
                    'program_id' => $programId,
                    'status_aktif' => $statusAktif,
                ]),
                'jenjangList' => $this->jenjangModel->all(),
                'programList' => $this->programModel->all(),
                'errors' => $errors
            ]);
            return;
        }

        $this->kelasModel->update($idInt, [
            'nama' => $nama,
            'jenjang_id' => $jenjangId,
            'program_id' => $programId,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Data kelas berhasil diperbarui.';
        $this->redirect('/admin/kelas');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $kelas = $this->kelasModel->find($idInt);

        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/kelas');
        }

        try {
            $this->kelasModel->delete($idInt);
            $_SESSION['flash_success'] = 'Data kelas berhasil dihapus.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menghapus kelas. Pastikan tidak ada pendaftaran siswa atau jadwal yang terikat.';
        }

        $this->redirect('/admin/kelas');
    }
}
