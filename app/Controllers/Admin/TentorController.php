<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Tentor;
use App\Models\Pengguna;

class TentorController extends Controller
{
    private Tentor $tentorModel;
    private Pengguna $penggunaModel;

    public function __construct()
    {
        $this->tentorModel = new Tentor();
        $this->penggunaModel = new Pengguna();
    }

    public function index(): void
    {
        $tentorList = $this->tentorModel->allWithPengguna();

        $this->render('admin/tentor/index', [
            'pageTitle' => 'Manajemen Tentor',
            'activeNav' => 'tentor',
            'tentorList' => $tentorList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/tentor/form', [
            'pageTitle' => 'Tambah Profil Tentor',
            'activeNav' => 'tentor',
            'isEdit' => false,
            'tentor' => null,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $asalUniversitas = trim($_POST['asal_universitas'] ?? '');
        $nomorTelepon = trim($_POST['nomor_telepon'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }

        if (empty($asalUniversitas)) {
            $errors['asal_universitas'] = 'Asal universitas wajib diisi.';
        }

        if (empty($username)) {
            $errors['username'] = 'Username tentor wajib diisi.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username harus antara 3 - 50 karakter.';
        } elseif ($this->penggunaModel->isUsernameExists($username)) {
            $errors['username'] = 'Username sudah digunakan oleh akun lain.';
        }

        // Upload foto profil jika ada
        $fotoPath = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleFileUpload($_FILES['foto'], $errors);
            if ($uploaded) {
                $fotoPath = $uploaded;
            }
        }

        if (!empty($errors)) {
            $this->render('admin/tentor/form', [
                'pageTitle' => 'Tambah Profil Tentor',
                'activeNav' => 'tentor',
                'isEdit' => false,
                'tentor' => [
                    'nama_lengkap' => $namaLengkap,
                    'username' => $username,
                    'asal_universitas' => $asalUniversitas,
                    'nomor_telepon' => $nomorTelepon,
                    'bio' => $bio,
                    'status_aktif' => $statusAktif,
                ],
                'errors' => $errors
            ]);
            return;
        }

        $temporaryPassword = $this->generateTemporaryPassword();
        $db = \getDBConnection();
        try {
            $db->beginTransaction();
            $penggunaId = (int) $this->penggunaModel->create([
                'username' => $username,
                'password' => password_hash($temporaryPassword, PASSWORD_DEFAULT),
                'peran' => 'tentor',
                'status_aktif' => $statusAktif,
                'dibuat_pada' => date('Y-m-d H:i:s'),
                'diubah_pada' => date('Y-m-d H:i:s'),
            ]);
            $this->tentorModel->create([
                'pengguna_id' => $penggunaId,
                'nama_lengkap' => $namaLengkap,
                'asal_universitas' => $asalUniversitas,
                'nomor_telepon' => $nomorTelepon ?: null,
                'bio' => $bio ?: null,
                'foto' => $fotoPath,
                'status_aktif' => $statusAktif,
                'dibuat_pada' => date('Y-m-d H:i:s'),
                'diubah_pada' => date('Y-m-d H:i:s'),
            ]);
            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $_SESSION['flash_error'] = 'Profil dan akun tentor gagal dibuat.';
            $this->redirect('/admin/tentor/tambah');
        }

        $this->setTemporaryCredentials($username, $temporaryPassword);
        $_SESSION['flash_success'] = 'Profil dan akun tentor berhasil dibuat.';
        $this->redirect('/admin/tentor');
    }

    public function edit(string $id): void
    {
        $tentor = $this->tentorModel->findWithPengguna((int) $id);

        if (!$tentor) {
            $_SESSION['flash_error'] = 'Data tentor tidak ditemukan.';
            $this->redirect('/admin/tentor');
        }

        $this->render('admin/tentor/form', [
            'pageTitle' => 'Edit Profil Tentor',
            'activeNav' => 'tentor',
            'isEdit' => true,
            'tentor' => $tentor,
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $tentor = $this->tentorModel->find($idInt);

        if (!$tentor) {
            $_SESSION['flash_error'] = 'Data tentor tidak ditemukan.';
            $this->redirect('/admin/tentor');
        }

        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $asalUniversitas = trim($_POST['asal_universitas'] ?? '');
        $nomorTelepon = trim($_POST['nomor_telepon'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }

        if (empty($asalUniversitas)) {
            $errors['asal_universitas'] = 'Asal universitas wajib diisi.';
        }

        if (empty($username)) {
            $errors['username'] = 'Username tentor wajib diisi.';
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors['username'] = 'Username harus antara 3 - 50 karakter.';
        } elseif ($this->penggunaModel->isUsernameExists($username, (int) $tentor['pengguna_id'])) {
            $errors['username'] = 'Username sudah digunakan oleh akun lain.';
        }

        $fotoPath = $tentor['foto'];
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleFileUpload($_FILES['foto'], $errors);
            if ($uploaded) {
                $fotoPath = $uploaded;
            }
        }

        if (!empty($errors)) {
            $this->render('admin/tentor/form', [
                'pageTitle' => 'Edit Profil Tentor',
                'activeNav' => 'tentor',
                'isEdit' => true,
                'tentor' => array_merge($tentor, [
                    'nama_lengkap' => $namaLengkap,
                    'username' => $username,
                    'asal_universitas' => $asalUniversitas,
                    'nomor_telepon' => $nomorTelepon,
                    'bio' => $bio,
                    'status_aktif' => $statusAktif,
                ]),
                'errors' => $errors
            ]);
            return;
        }

        $this->tentorModel->update($idInt, [
            'nama_lengkap' => $namaLengkap,
            'asal_universitas' => $asalUniversitas,
            'nomor_telepon' => $nomorTelepon ?: null,
            'bio' => $bio ?: null,
            'foto' => $fotoPath,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $this->penggunaModel->update((int) $tentor['pengguna_id'], [
            'username' => $username,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Profil tentor berhasil diperbarui.';
        $this->redirect('/admin/tentor');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $tentor = $this->tentorModel->find($idInt);

        if (!$tentor) {
            $_SESSION['flash_error'] = 'Data tentor tidak ditemukan.';
            $this->redirect('/admin/tentor');
        }

        try {
            $this->tentorModel->delete($idInt);
            $_SESSION['flash_success'] = 'Profil tentor berhasil dihapus.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menghapus profil tentor. Terkait data mengajar/jadwal.';
        }

        $this->redirect('/admin/tentor');
    }

    public function resetPassword(string $id): void
    {
        $tentor = $this->tentorModel->findWithPengguna((int) $id);
        if (!$tentor) {
            $_SESSION['flash_error'] = 'Data tentor tidak ditemukan.';
            $this->redirect('/admin/tentor');
        }

        $temporaryPassword = $this->generateTemporaryPassword();
        $this->penggunaModel->update((int) $tentor['pengguna_id'], [
            'password' => password_hash($temporaryPassword, PASSWORD_DEFAULT),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);
        $this->setTemporaryCredentials($tentor['username'], $temporaryPassword);
        $_SESSION['flash_success'] = 'Password tentor berhasil di-reset.';
        $this->redirect('/admin/tentor');
    }

    private function generateTemporaryPassword(): string
    {
        return substr(bin2hex(random_bytes(8)), 0, 12);
    }

    private function setTemporaryCredentials(string $username, string $password): void
    {
        $_SESSION['temporary_credentials'] = [
            'username' => $username,
            'password' => $password,
            'expires_at' => time() + 600,
        ];
    }

    private function handleFileUpload(array $file, array &$errors): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors['foto'] = 'Format foto harus JPG, PNG, atau WEBP.';
            return null;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            $errors['foto'] = 'Ukuran file foto maksimal 2 MB.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/tentor/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'tentor_' . time() . '_' . uniqid() . '.' . strtolower($extension);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/tentor/' . $filename;
        }

        $errors['foto'] = 'Gagal mengunggah foto profil.';
        return null;
    }
}
