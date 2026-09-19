<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    private Pengguna $penggunaModel;

    public function __construct()
    {
        $this->penggunaModel = new Pengguna();
    }

    public function index(): void
    {
        $penggunaList = $this->penggunaModel->all();

        $this->render('admin/pengguna/index', [
            'pageTitle' => 'Manajemen Pengguna',
            'activeNav' => 'pengguna',
            'penggunaList' => $penggunaList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/pengguna/form', [
            'pageTitle' => 'Tambah Pengguna',
            'activeNav' => 'pengguna',
            'isEdit' => false,
            'pengguna' => null,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $peran = $_POST['peran'] ?? '';
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username tidak boleh kosong.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username harus antara 3 - 50 karakter.';
        } elseif ($this->penggunaModel->isUsernameExists($username)) {
            $errors['username'] = 'Username sudah digunakan oleh akun lain.';
        }

        if (empty($password)) {
            $errors['password'] = 'Password tidak boleh kosong.';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password minimal 6 karakter.';
        }

        if (!in_array($peran, ['owner', 'admin', 'tentor'])) {
            $errors['peran'] = 'Peran pengguna tidak valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/pengguna/form', [
                'pageTitle' => 'Tambah Pengguna',
                'activeNav' => 'pengguna',
                'isEdit' => false,
                'pengguna' => [
                    'username' => $username,
                    'peran' => $peran,
                    'status_aktif' => $statusAktif,
                ],
                'errors' => $errors
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $this->penggunaModel->create([
            'username' => $username,
            'password' => $passwordHash,
            'peran' => $peran,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Akun pengguna berhasil ditambahkan.';
        $this->redirect('/admin/pengguna');
    }

    public function edit(string $id): void
    {
        $pengguna = $this->penggunaModel->find((int) $id);

        if (!$pengguna) {
            $_SESSION['flash_error'] = 'Pengguna tidak ditemukan.';
            $this->redirect('/admin/pengguna');
        }

        $this->render('admin/pengguna/form', [
            'pageTitle' => 'Edit Pengguna',
            'activeNav' => 'pengguna',
            'isEdit' => true,
            'pengguna' => $pengguna,
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $pengguna = $this->penggunaModel->find($idInt);

        if (!$pengguna) {
            $_SESSION['flash_error'] = 'Pengguna tidak ditemukan.';
            $this->redirect('/admin/pengguna');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $peran = $_POST['peran'] ?? '';
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username tidak boleh kosong.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username harus antara 3 - 50 karakter.';
        } elseif ($this->penggunaModel->isUsernameExists($username, $idInt)) {
            $errors['username'] = 'Username sudah digunakan oleh akun lain.';
        }

        if (!empty($password) && strlen($password) < 6) {
            $errors['password'] = 'Password baru minimal 6 karakter.';
        }

        if (!in_array($peran, ['owner', 'admin', 'tentor'])) {
            $errors['peran'] = 'Peran pengguna tidak valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/pengguna/form', [
                'pageTitle' => 'Edit Pengguna',
                'activeNav' => 'pengguna',
                'isEdit' => true,
                'pengguna' => array_merge($pengguna, [
                    'username' => $username,
                    'peran' => $peran,
                    'status_aktif' => $statusAktif,
                ]),
                'errors' => $errors
            ]);
            return;
        }

        $updateData = [
            'username' => $username,
            'peran' => $peran,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->penggunaModel->update($idInt, $updateData);

        $_SESSION['flash_success'] = 'Akun pengguna berhasil diperbarui.';
        $this->redirect('/admin/pengguna');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $pengguna = $this->penggunaModel->find($idInt);

        if (!$pengguna) {
            $_SESSION['flash_error'] = 'Pengguna tidak ditemukan.';
            $this->redirect('/admin/pengguna');
        }

        $this->penggunaModel->delete($idInt);

        $_SESSION['flash_success'] = 'Akun pengguna berhasil dihapus.';
        $this->redirect('/admin/pengguna');
    }
}
