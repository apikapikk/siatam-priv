<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\PendaftaranSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Paket;

class PendaftaranSiswaController extends Controller
{
    private PendaftaranSiswa $pendaftaranModel;
    private Siswa $siswaModel;
    private Kelas $kelasModel;
    private Paket $paketModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranSiswa();
        $this->siswaModel = new Siswa();
        $this->kelasModel = new Kelas();
        $this->paketModel = new Paket();
    }

    public function index(): void
    {
        $pendaftaranList = $this->pendaftaranModel->allWithDetails();

        $this->render('admin/pendaftaran/index', [
            'pageTitle' => 'Pendaftaran & Penempatan Siswa',
            'activeNav' => 'pendaftaran',
            'pendaftaranList' => $pendaftaranList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/pendaftaran/form', [
            'pageTitle' => 'Pendaftaran Kelas Siswa',
            'activeNav' => 'pendaftaran',
            'isEdit' => false,
            'pendaftaran' => null,
            'siswaList' => $this->siswaModel->all(),
            'kelasList' => $this->kelasModel->allWithRelations(),
            'paketList' => $this->paketModel->all(),
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $siswaId = (int) ($_POST['siswa_id'] ?? 0);
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $paketId = !empty($_POST['paket_id']) ? (int) $_POST['paket_id'] : null;
        $tanggalMulai = trim($_POST['tanggal_mulai'] ?? '');
        $status = $_POST['status'] ?? 'aktif';

        $errors = [];

        if ($siswaId <= 0 || !$this->siswaModel->find($siswaId)) {
            $errors['siswa_id'] = 'Pilih siswa yang valid.';
        }

        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas yang valid.';
        }

        if (empty($tanggalMulai)) {
            $errors['tanggal_mulai'] = 'Tanggal mulai wajib diisi.';
        }

        if (!in_array($status, ['aktif', 'selesai'])) {
            $errors['status'] = 'Status pendaftaran tidak valid.';
        }

        if (!empty($errors)) {
            $this->render('admin/pendaftaran/form', [
                'pageTitle' => 'Pendaftaran Kelas Siswa',
                'activeNav' => 'pendaftaran',
                'isEdit' => false,
                'pendaftaran' => [
                    'siswa_id' => $siswaId,
                    'kelas_id' => $kelasId,
                    'paket_id' => $paketId,
                    'tanggal_mulai' => $tanggalMulai,
                    'status' => $status,
                ],
                'siswaList' => $this->siswaModel->all(),
                'kelasList' => $this->kelasModel->allWithRelations(),
                'paketList' => $this->paketModel->all(),
                'errors' => $errors
            ]);
            return;
        }

        // Jika pendaftaran baru berstatus 'aktif', nonaktifkan pendaftaran aktif terdahulu milik siswa tersebut (histori perpindahan)
        if ($status === 'aktif') {
            $this->pendaftaranModel->deactivateActiveRegistrations($siswaId);
        }

        $this->pendaftaranModel->create([
            'siswa_id' => $siswaId,
            'kelas_id' => $kelasId,
            'paket_id' => $paketId,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => ($status === 'selesai') ? date('Y-m-d') : null,
            'status' => $status,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Penempatan kelas siswa berhasil disimpan.';
        $this->redirect('/admin/pendaftaran');
    }

    public function edit(string $id): void
    {
        $idInt = (int) $id;
        $pendaftaran = $this->pendaftaranModel->find($idInt);

        if (!$pendaftaran) {
            $_SESSION['flash_error'] = 'Data pendaftaran tidak ditemukan.';
            $this->redirect('/admin/pendaftaran');
        }

        $this->render('admin/pendaftaran/form', [
            'pageTitle' => 'Edit Pendaftaran Siswa',
            'activeNav' => 'pendaftaran',
            'isEdit' => true,
            'pendaftaran' => $pendaftaran,
            'siswaList' => $this->siswaModel->all(),
            'kelasList' => $this->kelasModel->allWithRelations(),
            'paketList' => $this->paketModel->all(),
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $pendaftaran = $this->pendaftaranModel->find($idInt);

        if (!$pendaftaran) {
            $_SESSION['flash_error'] = 'Data pendaftaran tidak ditemukan.';
            $this->redirect('/admin/pendaftaran');
        }

        $siswaId = (int) ($_POST['siswa_id'] ?? 0);
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $paketId = !empty($_POST['paket_id']) ? (int) $_POST['paket_id'] : null;
        $tanggalMulai = trim($_POST['tanggal_mulai'] ?? '');
        $tanggalSelesai = !empty($_POST['tanggal_selesai']) ? trim($_POST['tanggal_selesai']) : null;
        $status = $_POST['status'] ?? 'aktif';

        $errors = [];

        if ($siswaId <= 0 || !$this->siswaModel->find($siswaId)) {
            $errors['siswa_id'] = 'Pilih siswa yang valid.';
        }

        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas yang valid.';
        }

        if (empty($tanggalMulai)) {
            $errors['tanggal_mulai'] = 'Tanggal mulai wajib diisi.';
        }

        if (!empty($errors)) {
            $this->render('admin/pendaftaran/form', [
                'pageTitle' => 'Edit Pendaftaran Siswa',
                'activeNav' => 'pendaftaran',
                'isEdit' => true,
                'pendaftaran' => array_merge($pendaftaran, [
                    'siswa_id' => $siswaId,
                    'kelas_id' => $kelasId,
                    'paket_id' => $paketId,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'status' => $status,
                ]),
                'siswaList' => $this->siswaModel->all(),
                'kelasList' => $this->kelasModel->allWithRelations(),
                'paketList' => $this->paketModel->all(),
                'errors' => $errors
            ]);
            return;
        }

        $this->pendaftaranModel->update($idInt, [
            'siswa_id' => $siswaId,
            'kelas_id' => $kelasId,
            'paket_id' => $paketId,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => ($status === 'selesai' && empty($tanggalSelesai)) ? date('Y-m-d') : $tanggalSelesai,
            'status' => $status,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Data pendaftaran siswa berhasil diperbarui.';
        $this->redirect('/admin/pendaftaran');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $pendaftaran = $this->pendaftaranModel->find($idInt);

        if (!$pendaftaran) {
            $_SESSION['flash_error'] = 'Data pendaftaran tidak ditemukan.';
            $this->redirect('/admin/pendaftaran');
        }

        $this->pendaftaranModel->delete($idInt);

        $_SESSION['flash_success'] = 'Data pendaftaran siswa berhasil dihapus.';
        $this->redirect('/admin/pendaftaran');
    }
}
