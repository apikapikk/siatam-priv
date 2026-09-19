<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Jenjang;

class JenjangController extends Controller
{
    private Jenjang $jenjangModel;

    public function __construct()
    {
        $this->jenjangModel = new Jenjang();
    }

    public function index(): void
    {
        $jenjangList = $this->jenjangModel->all();

        $this->render('admin/jenjang/index', [
            'pageTitle' => 'Master Data Jenjang',
            'activeNav' => 'jenjang',
            'jenjangList' => $jenjangList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/jenjang/form', [
            'pageTitle' => 'Tambah Jenjang',
            'activeNav' => 'jenjang',
            'isEdit' => false,
            'jenjang' => null,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $nama = trim($_POST['nama'] ?? '');
        $errors = [];

        if (empty($nama)) {
            $errors['nama'] = 'Nama jenjang tidak boleh kosong.';
        } elseif (strlen($nama) > 50) {
            $errors['nama'] = 'Nama jenjang maksimal 50 karakter.';
        }

        if (!empty($errors)) {
            $this->render('admin/jenjang/form', [
                'pageTitle' => 'Tambah Jenjang',
                'activeNav' => 'jenjang',
                'isEdit' => false,
                'jenjang' => ['nama' => $nama],
                'errors' => $errors
            ]);
            return;
        }

        $this->jenjangModel->create(['nama' => $nama]);

        $_SESSION['flash_success'] = 'Data jenjang berhasil ditambahkan.';
        $this->redirect('/admin/jenjang');
    }

    public function edit(string $id): void
    {
        $jenjang = $this->jenjangModel->find((int) $id);

        if (!$jenjang) {
            $_SESSION['flash_error'] = 'Jenjang tidak ditemukan.';
            $this->redirect('/admin/jenjang');
        }

        $this->render('admin/jenjang/form', [
            'pageTitle' => 'Edit Jenjang',
            'activeNav' => 'jenjang',
            'isEdit' => true,
            'jenjang' => $jenjang,
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $jenjang = $this->jenjangModel->find($idInt);

        if (!$jenjang) {
            $_SESSION['flash_error'] = 'Jenjang tidak ditemukan.';
            $this->redirect('/admin/jenjang');
        }

        $nama = trim($_POST['nama'] ?? '');
        $errors = [];

        if (empty($nama)) {
            $errors['nama'] = 'Nama jenjang tidak boleh kosong.';
        } elseif (strlen($nama) > 50) {
            $errors['nama'] = 'Nama jenjang maksimal 50 karakter.';
        }

        if (!empty($errors)) {
            $this->render('admin/jenjang/form', [
                'pageTitle' => 'Edit Jenjang',
                'activeNav' => 'jenjang',
                'isEdit' => true,
                'jenjang' => array_merge($jenjang, ['nama' => $nama]),
                'errors' => $errors
            ]);
            return;
        }

        $this->jenjangModel->update($idInt, ['nama' => $nama]);

        $_SESSION['flash_success'] = 'Data jenjang berhasil diperbarui.';
        $this->redirect('/admin/jenjang');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $jenjang = $this->jenjangModel->find($idInt);

        if (!$jenjang) {
            $_SESSION['flash_error'] = 'Jenjang tidak ditemukan.';
            $this->redirect('/admin/jenjang');
        }

        try {
            $this->jenjangModel->delete($idInt);
            $_SESSION['flash_success'] = 'Data jenjang berhasil dihapus.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menghapus jenjang. Pastikan tidak ada kelas yang terhubung.';
        }

        $this->redirect('/admin/jenjang');
    }
}
