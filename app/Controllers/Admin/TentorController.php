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
        $availableUsers = $this->tentorModel->getAvailableUsersForTentor();

        $this->render('admin/tentor/form', [
            'pageTitle' => 'Tambah Profil Tentor',
            'activeNav' => 'tentor',
            'isEdit' => false,
            'tentor' => null,
            'availableUsers' => $availableUsers,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $asalUniversitas = trim($_POST['asal_universitas'] ?? '');
        $nomorTelepon = trim($_POST['nomor_telepon'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $rateGajiPerJam = (float) ($_POST['rate_gaji_per_jam'] ?? 0);
        $tarifPerSesi = (float) ($_POST['tarif_per_sesi'] ?? 0);
        $penggunaId = (int) ($_POST['pengguna_id'] ?? 0);
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }

        if (empty($asalUniversitas)) {
            $errors['asal_universitas'] = 'Asal universitas wajib diisi.';
        }

        if ($penggunaId <= 0) {
            $errors['pengguna_id'] = 'Pilih akun pengguna tentor.';
        } else {
            $user = $this->penggunaModel->find($penggunaId);
            if (!$user || $user['peran'] !== 'tentor') {
                $errors['pengguna_id'] = 'Akun pengguna tidak valid atau bukan bertipe tentor.';
            } elseif ($this->tentorModel->findByPenggunaId($penggunaId)) {
                $errors['pengguna_id'] = 'Akun pengguna ini sudah terikat dengan profil tentor lain.';
            }
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
                    'asal_universitas' => $asalUniversitas,
                    'nomor_telepon' => $nomorTelepon,
                    'bio' => $bio,
                    'rate_gaji_per_jam' => $rateGajiPerJam,
                    'tarif_per_sesi' => $tarifPerSesi,
                    'pengguna_id' => $penggunaId,
                    'status_aktif' => $statusAktif,
                ],
                'availableUsers' => $this->tentorModel->getAvailableUsersForTentor(),
                'errors' => $errors
            ]);
            return;
        }

        $this->tentorModel->create([
            'pengguna_id' => $penggunaId,
            'nama_lengkap' => $namaLengkap,
            'asal_universitas' => $asalUniversitas,
            'nomor_telepon' => $nomorTelepon ?: null,
            'bio' => $bio ?: null,
            'foto' => $fotoPath,
            'rate_gaji_per_jam' => $rateGajiPerJam,
            'tarif_per_sesi' => $tarifPerSesi,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Profil tentor berhasil dibuat.';
        $this->redirect('/admin/tentor');
    }

    public function edit(string $id): void
    {
        $tentor = $this->tentorModel->findWithPengguna((int) $id);

        if (!$tentor) {
            $_SESSION['flash_error'] = 'Data tentor tidak ditemukan.';
            $this->redirect('/admin/tentor');
        }

        $availableUsers = $this->tentorModel->getAvailableUsersForTentor((int) $tentor['pengguna_id']);

        $this->render('admin/tentor/form', [
            'pageTitle' => 'Edit Profil Tentor',
            'activeNav' => 'tentor',
            'isEdit' => true,
            'tentor' => $tentor,
            'availableUsers' => $availableUsers,
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
        $asalUniversitas = trim($_POST['asal_universitas'] ?? '');
        $nomorTelepon = trim($_POST['nomor_telepon'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $rateGajiPerJam = (float) ($_POST['rate_gaji_per_jam'] ?? 0);
        $tarifPerSesi = (float) ($_POST['tarif_per_sesi'] ?? 0);
        $penggunaId = (int) ($_POST['pengguna_id'] ?? 0);
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($namaLengkap)) {
            $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';
        }

        if (empty($asalUniversitas)) {
            $errors['asal_universitas'] = 'Asal universitas wajib diisi.';
        }

        if ($penggunaId <= 0) {
            $errors['pengguna_id'] = 'Pilih akun pengguna tentor.';
        } else {
            $user = $this->penggunaModel->find($penggunaId);
            if (!$user || $user['peran'] !== 'tentor') {
                $errors['pengguna_id'] = 'Akun pengguna tidak valid.';
            } else {
                $existing = $this->tentorModel->findByPenggunaId($penggunaId);
                if ($existing && (int) $existing['id'] !== $idInt) {
                    $errors['pengguna_id'] = 'Akun pengguna ini sudah terikat dengan profil tentor lain.';
                }
            }
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
                    'asal_universitas' => $asalUniversitas,
                    'nomor_telepon' => $nomorTelepon,
                    'bio' => $bio,
                    'rate_gaji_per_jam' => $rateGajiPerJam,
                    'tarif_per_sesi' => $tarifPerSesi,
                    'pengguna_id' => $penggunaId,
                    'status_aktif' => $statusAktif,
                ]),
                'availableUsers' => $this->tentorModel->getAvailableUsersForTentor((int) $tentor['pengguna_id']),
                'errors' => $errors
            ]);
            return;
        }

        $this->tentorModel->update($idInt, [
            'pengguna_id' => $penggunaId,
            'nama_lengkap' => $namaLengkap,
            'asal_universitas' => $asalUniversitas,
            'nomor_telepon' => $nomorTelepon ?: null,
            'bio' => $bio ?: null,
            'foto' => $fotoPath,
            'rate_gaji_per_jam' => $rateGajiPerJam,
            'tarif_per_sesi' => $tarifPerSesi,
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
