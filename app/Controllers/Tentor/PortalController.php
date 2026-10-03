<?php

namespace App\Controllers\Tentor;

use App\Core\Controller;
use App\Models\Pengguna;
use App\Models\Tentor;

class PortalController extends Controller
{
    private Tentor $tentorModel;
    private Pengguna $penggunaModel;
    private int $tentorId;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['peran'] ?? '') !== 'tentor') {
            $_SESSION['flash_error'] = 'Silakan login sebagai tentor untuk mengakses halaman tersebut.';
            $this->redirect('/login');
        }

        $this->tentorModel = new Tentor();
        $this->penggunaModel = new Pengguna();
        $profil = $this->tentorModel->findByPenggunaId((int) $_SESSION['user_id']);
        $this->tentorId = $profil ? (int) $profil['id'] : 0;
    }

    public function laporan(): void
    {
        $selectedMonth = trim($_GET['bulan'] ?? date('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }

        $month = (int) date('n', strtotime($selectedMonth . '-01'));
        $year = (int) date('Y', strtotime($selectedMonth . '-01'));
        $reportList = $this->tentorModel->getMonthlyTeachingReport($this->tentorId, $month, $year);
        $performance = $this->tentorModel->getTeachingPerformance($this->tentorId, $month, $year);
        $totalIzinSakit = array_sum(array_map(static fn (array $item): int => (int) $item['total_izin_sakit'], $reportList));

        $this->render('tentor/laporan', [
            'pageTitle' => 'Laporan Mengajar',
            'activeNav' => 'laporan',
            'selectedMonth' => $selectedMonth,
            'reportList' => $reportList,
            'performance' => $performance,
            'totalIzinSakit' => $totalIzinSakit,
        ], 'tentor/layout');
    }

    public function profil(): void
    {
        $profil = $this->tentorModel->findWithPengguna($this->tentorId);
        if (!$profil) {
            $_SESSION['flash_error'] = 'Profil tentor tidak ditemukan.';
            $this->redirect('/tentor/beranda');
        }

        $this->render('tentor/profil', [
            'pageTitle' => 'Profil Tentor',
            'activeNav' => 'profil',
            'profil' => $profil,
            'errors' => [],
        ], 'tentor/layout');
    }

    public function updateProfil(): void
    {
        $profil = $this->tentorModel->findWithPengguna($this->tentorId);
        if (!$profil) {
            $_SESSION['flash_error'] = 'Profil tentor tidak ditemukan.';
            $this->redirect('/tentor/beranda');
        }

        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $asalUniversitas = trim($_POST['asal_universitas'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password_baru'] ?? '');
        $konfirmasiPassword = (string) ($_POST['konfirmasi_password'] ?? '');
        $errors = [];

        if ($namaLengkap === '') {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }
        if ($asalUniversitas === '') {
            $errors['asal_universitas'] = 'Asal universitas wajib diisi.';
        }
        if ($username === '') {
            $errors['username'] = 'Username wajib diisi.';
        } elseif ($this->penggunaModel->isUsernameExists($username, (int) $profil['pengguna_id'])) {
            $errors['username'] = 'Username sudah digunakan.';
        }
        if ($password !== '' && (strlen($password) < 6 || $password !== $konfirmasiPassword)) {
            $errors['password'] = 'Password minimal 6 karakter dan konfirmasi harus sama.';
        }

        if (!empty($errors)) {
            $profil = array_merge($profil, [
                'nama_lengkap' => $namaLengkap,
                'asal_universitas' => $asalUniversitas,
                'username' => $username,
            ]);
            $this->render('tentor/profil', [
                'pageTitle' => 'Profil Tentor', 'activeNav' => 'profil', 'profil' => $profil, 'errors' => $errors,
            ], 'tentor/layout');
            return;
        }

        $this->tentorModel->update($this->tentorId, [
            'nama_lengkap' => $namaLengkap,
            'asal_universitas' => $asalUniversitas,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);
        $accountData = ['username' => $username, 'diubah_pada' => date('Y-m-d H:i:s')];
        if ($password !== '') {
            $accountData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $this->penggunaModel->update((int) $profil['pengguna_id'], $accountData);
        $_SESSION['user_nama'] = $namaLengkap;
        $_SESSION['nama_lengkap'] = $namaLengkap;
        $_SESSION['username'] = $username;
        $_SESSION['flash_success'] = 'Profil berhasil diperbarui.';
        $this->redirect('/tentor/profil');
    }
}
