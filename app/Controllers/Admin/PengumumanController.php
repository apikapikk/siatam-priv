<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Pengumuman;
use App\Models\Pengguna;

class PengumumanController extends Controller
{
    private Pengumuman $pengumumanModel;
    private Pengguna $penggunaModel;

    public function __construct()
    {
        $this->pengumumanModel = new Pengumuman();
        $this->penggunaModel = new Pengguna();
    }

    public function index(): void
    {
        $pengumumanList = $this->pengumumanModel->allWithAuthor();

        $this->render('admin/pengumuman/index', [
            'pageTitle' => 'Broadcast Pengumuman',
            'activeNav' => 'pengumuman',
            'pengumumanList' => $pengumumanList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/pengumuman/form', [
            'pageTitle' => 'Buat Pengumuman Baru',
            'activeNav' => 'pengumuman',
            'isEdit' => false,
            'pengumuman' => null,
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $judul = trim($_POST['judul'] ?? '');
        $isi = trim($_POST['isi'] ?? '');
        $targetPeran = $_POST['target_peran'] ?? 'semua';
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($judul)) {
            $errors['judul'] = 'Judul pengumuman wajib diisi.';
        }

        if (empty($isi)) {
            $errors['isi'] = 'Isi pengumuman wajib diisi.';
        }

        if (!in_array($targetPeran, ['semua', 'tentor', 'admin'])) {
            $errors['target_peran'] = 'Target peran tidak valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/pengumuman/form', [
                'pageTitle' => 'Buat Pengumuman Baru',
                'activeNav' => 'pengumuman',
                'isEdit' => false,
                'pengumuman' => [
                    'judul' => $judul,
                    'isi' => $isi,
                    'target_peran' => $targetPeran,
                    'status_aktif' => $statusAktif,
                ],
                'errors' => $errors
            ]);
            return;
        }

        // Ambil ID pengguna pembuat (default fallback id=1 jika session belum aktif)
        $dibuatOleh = $_SESSION['user_id'] ?? 1;

        $this->pengumumanModel->create([
            'judul' => $judul,
            'isi' => $isi,
            'dibuat_oleh' => $dibuatOleh,
            'target_peran' => $targetPeran,
            'diterbitkan_pada' => date('Y-m-d H:i:s'),
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Pengumuman berhasil diterbitkan.';
        $this->redirect('/admin/pengumuman');
    }

    public function edit(string $id): void
    {
        $idInt = (int) $id;
        $pengumuman = $this->pengumumanModel->find($idInt);

        if (!$pengumuman) {
            $_SESSION['flash_error'] = 'Data pengumuman tidak ditemukan.';
            $this->redirect('/admin/pengumuman');
        }

        $this->render('admin/pengumuman/form', [
            'pageTitle' => 'Edit Pengumuman',
            'activeNav' => 'pengumuman',
            'isEdit' => true,
            'pengumuman' => $pengumuman,
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $pengumuman = $this->pengumumanModel->find($idInt);

        if (!$pengumuman) {
            $_SESSION['flash_error'] = 'Data pengumuman tidak ditemukan.';
            $this->redirect('/admin/pengumuman');
        }

        $judul = trim($_POST['judul'] ?? '');
        $isi = trim($_POST['isi'] ?? '');
        $targetPeran = $_POST['target_peran'] ?? 'semua';
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if (empty($judul)) {
            $errors['judul'] = 'Judul pengumuman wajib diisi.';
        }

        if (empty($isi)) {
            $errors['isi'] = 'Isi pengumuman wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('admin/pengumuman/form', [
                'pageTitle' => 'Edit Pengumuman',
                'activeNav' => 'pengumuman',
                'isEdit' => true,
                'pengumuman' => array_merge($pengumuman, [
                    'judul' => $judul,
                    'isi' => $isi,
                    'target_peran' => $targetPeran,
                    'status_aktif' => $statusAktif,
                ]),
                'errors' => $errors
            ]);
            return;
        }

        $this->pengumumanModel->update($idInt, [
            'judul' => $judul,
            'isi' => $isi,
            'target_peran' => $targetPeran,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Pengumuman berhasil diperbarui.';
        $this->redirect('/admin/pengumuman');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $pengumuman = $this->pengumumanModel->find($idInt);

        if (!$pengumuman) {
            $_SESSION['flash_error'] = 'Data pengumuman tidak ditemukan.';
            $this->redirect('/admin/pengumuman');
        }

        $this->pengumumanModel->delete($idInt);

        $_SESSION['flash_success'] = 'Pengumuman berhasil dihapus.';
        $this->redirect('/admin/pengumuman');
    }
}
