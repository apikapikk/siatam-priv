<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Pengguna;
use App\Models\Tentor;

class AuthController extends Controller
{
    private Pengguna $penggunaModel;
    private Tentor $tentorModel;

    public function __construct()
    {
        $this->penggunaModel = new Pengguna();
        $this->tentorModel = new Tentor();
    }

    public function loginForm(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByUserRole($_SESSION['peran']);
        }

        $this->render('auth/login', [
            'pageTitle' => 'Login Masuk',
            'errors' => []
        ], 'auth/layout');
    }

    public function loginProcess(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username wajib diisi.';
        }

        if (empty($password)) {
            $errors['password'] = 'Password wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('auth/login', [
                'pageTitle' => 'Login Masuk',
                'username' => $username,
                'errors' => $errors
            ], 'auth/layout');
            return;
        }

        $user = $this->penggunaModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $errors['general'] = 'Username atau password tidak cocok.';
            $this->render('auth/login', [
                'pageTitle' => 'Login Masuk',
                'username' => $username,
                'errors' => $errors
            ], 'auth/layout');
            return;
        }

        if (!$user['status_aktif']) {
            $errors['general'] = 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.';
            $this->render('auth/login', [
                'pageTitle' => 'Login Masuk',
                'username' => $username,
                'errors' => $errors
            ], 'auth/layout');
            return;
        }

        // Catat timestamp login
        $this->penggunaModel->updateLastLogin((int) $user['id']);

        // Simpan Session
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['peran'] = $user['peran'];

        if ($user['peran'] === 'tentor') {
            $tentorProfil = $this->tentorModel->findByPenggunaId((int) $user['id']);
            if ($tentorProfil) {
                $_SESSION['tentor_id'] = (int) $tentorProfil['id'];
                $_SESSION['nama_lengkap'] = $tentorProfil['nama_lengkap'];
            }
        }

        $_SESSION['flash_success'] = 'Selamat datang kembali, ' . ($user['username']) . '!';
        $this->redirectByUserRole($user['peran']);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_success'] = 'Anda telah berhasil keluar.';
        $this->redirect('/login');
    }

    private function redirectByUserRole(string $peran): void
    {
        if ($peran === 'tentor') {
            $this->redirect('/tentor/beranda');
        } else {
            $this->redirect('/admin/beranda');
        }
    }
}
