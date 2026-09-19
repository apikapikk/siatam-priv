<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Berita;

class BeritaController extends Controller
{
    private Berita $beritaModel;

    public function __construct()
    {
        $this->beritaModel = new Berita();
    }

    public function index(): void
    {
        $beritaList = $this->beritaModel->allWithAuthor();

        $this->render('admin/berita/index', [
            'pageTitle' => 'Manajemen Berita Publik',
            'activeNav' => 'berita',
            'beritaList' => $beritaList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/berita/form', [
            'pageTitle' => 'Tulis Berita Baru',
            'activeNav' => 'berita',
            'isEdit' => false,
            'berita' => null,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $judul = trim($_POST['judul'] ?? '');
        $isi = trim($_POST['isi'] ?? '');
        $statusTerbit = isset($_POST['status_terbit']) ? 1 : 0;

        $errors = [];

        if (empty($judul)) {
            $errors['judul'] = 'Judul berita tidak boleh kosong.';
        }

        if (empty($isi)) {
            $errors['isi'] = 'Isi berita tidak boleh kosong.';
        }

        $gambarPath = null;
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleImageUpload($_FILES['gambar'], $errors);
            if ($uploaded) {
                $gambarPath = $uploaded;
            }
        }

        if (!empty($errors)) {
            $this->render('admin/berita/form', [
                'pageTitle' => 'Tulis Berita Baru',
                'activeNav' => 'berita',
                'isEdit' => false,
                'berita' => [
                    'judul' => $judul,
                    'isi' => $isi,
                    'status_terbit' => $statusTerbit,
                ],
                'errors' => $errors
            ]);
            return;
        }

        $slug = $this->beritaModel->generateSlug($judul);
        $dibuatOleh = $_SESSION['user_id'] ?? 1;

        $this->beritaModel->create([
            'judul' => $judul,
            'slug' => $slug,
            'isi' => $isi,
            'gambar' => $gambarPath,
            'dibuat_oleh' => $dibuatOleh,
            'diterbitkan_pada' => $statusTerbit ? date('Y-m-d H:i:s') : null,
            'status_terbit' => $statusTerbit,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Berita publik berhasil dibuat.';
        $this->redirect('/admin/berita');
    }

    public function edit(string $id): void
    {
        $idInt = (int) $id;
        $berita = $this->beritaModel->find($idInt);

        if (!$berita) {
            $_SESSION['flash_error'] = 'Data berita tidak ditemukan.';
            $this->redirect('/admin/berita');
        }

        $this->render('admin/berita/form', [
            'pageTitle' => 'Edit Berita Publik',
            'activeNav' => 'berita',
            'isEdit' => true,
            'berita' => $berita,
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $berita = $this->beritaModel->find($idInt);

        if (!$berita) {
            $_SESSION['flash_error'] = 'Data berita tidak ditemukan.';
            $this->redirect('/admin/berita');
        }

        $judul = trim($_POST['judul'] ?? '');
        $isi = trim($_POST['isi'] ?? '');
        $statusTerbit = isset($_POST['status_terbit']) ? 1 : 0;

        $errors = [];

        if (empty($judul)) {
            $errors['judul'] = 'Judul berita tidak boleh kosong.';
        }

        if (empty($isi)) {
            $errors['isi'] = 'Isi berita tidak boleh kosong.';
        }

        $gambarPath = $berita['gambar'];
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleImageUpload($_FILES['gambar'], $errors);
            if ($uploaded) {
                $gambarPath = $uploaded;
            }
        }

        if (!empty($errors)) {
            $this->render('admin/berita/form', [
                'pageTitle' => 'Edit Berita Publik',
                'activeNav' => 'berita',
                'isEdit' => true,
                'berita' => array_merge($berita, [
                    'judul' => $judul,
                    'isi' => $isi,
                    'status_terbit' => $statusTerbit,
                ]),
                'errors' => $errors
            ]);
            return;
        }

        $slug = ($judul !== $berita['judul']) ? $this->beritaModel->generateSlug($judul, $idInt) : $berita['slug'];

        $updateData = [
            'judul' => $judul,
            'slug' => $slug,
            'isi' => $isi,
            'gambar' => $gambarPath,
            'status_terbit' => $statusTerbit,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ];

        if ($statusTerbit && empty($berita['diterbitkan_pada'])) {
            $updateData['diterbitkan_pada'] = date('Y-m-d H:i:s');
        }

        $this->beritaModel->update($idInt, $updateData);

        $_SESSION['flash_success'] = 'Berita publik berhasil diperbarui.';
        $this->redirect('/admin/berita');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $berita = $this->beritaModel->find($idInt);

        if (!$berita) {
            $_SESSION['flash_error'] = 'Data berita tidak ditemukan.';
            $this->redirect('/admin/berita');
        }

        $this->beritaModel->delete($idInt);

        $_SESSION['flash_success'] = 'Berita publik berhasil dihapus.';
        $this->redirect('/admin/berita');
    }

    private function handleImageUpload(array $file, array &$errors): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors['gambar'] = 'Gambar berita harus berformat JPG, PNG, atau WEBP.';
            return null;
        }

        if ($file['size'] > 3 * 1024 * 1024) {
            $errors['gambar'] = 'Ukuran gambar maksimal 3 MB.';
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/berita/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'berita_' . time() . '_' . uniqid() . '.' . strtolower($extension);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/berita/' . $filename;
        }

        $errors['gambar'] = 'Gagal mengunggah gambar berita.';
        return null;
    }
}
