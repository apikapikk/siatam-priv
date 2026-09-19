<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Tentor;

class JadwalController extends Controller
{
    private Jadwal $jadwalModel;
    private Kelas $kelasModel;
    private Tentor $tentorModel;

    public function __construct()
    {
        $this->jadwalModel = new Jadwal();
        $this->kelasModel = new Kelas();
        $this->tentorModel = new Tentor();
    }

    public function index(): void
    {
        $jadwalList = $this->jadwalModel->allWithDetails();

        $this->render('admin/jadwal/index', [
            'pageTitle' => 'Jadwal Mengajar',
            'activeNav' => 'jadwal',
            'jadwalList' => $jadwalList,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/jadwal/form', [
            'pageTitle' => 'Tambah Jadwal Mengajar',
            'activeNav' => 'jadwal',
            'isEdit' => false,
            'jadwal' => null,
            'kelasList' => $this->kelasModel->allWithRelations(),
            'tentorList' => $this->tentorModel->allWithPengguna(),
            'errors' => []
        ]);
    }

    public function store(): void
    {
        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $tentorId = (int) ($_POST['tentor_id'] ?? 0);
        $hari = (int) ($_POST['hari'] ?? 0);
        $jamMulai = trim($_POST['jam_mulai'] ?? '');
        $jamSelesai = trim($_POST['jam_selesai'] ?? '');
        $ruangan = trim($_POST['ruangan'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas yang valid.';
        }

        if ($tentorId <= 0 || !$this->tentorModel->find($tentorId)) {
            $errors['tentor_id'] = 'Pilih tentor yang valid.';
        }

        if ($hari < 1 || $hari > 7) {
            $errors['hari'] = 'Pilih hari dalam seminggu yang valid (Senin - Minggu).';
        }

        if (empty($jamMulai)) {
            $errors['jam_mulai'] = 'Jam mulai wajib diisi.';
        }

        if (empty($jamSelesai)) {
            $errors['jam_selesai'] = 'Jam selesai wajib diisi.';
        } elseif ($jamSelesai <= $jamMulai) {
            $errors['jam_selesai'] = 'Jam selesai harus lebih akhir daripada jam mulai.';
        }

        if (!empty($errors)) {
            $this->render('admin/jadwal/form', [
                'pageTitle' => 'Tambah Jadwal Mengajar',
                'activeNav' => 'jadwal',
                'isEdit' => false,
                'jadwal' => [
                    'kelas_id' => $kelasId,
                    'tentor_id' => $tentorId,
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $ruangan,
                    'status_aktif' => $statusAktif,
                ],
                'kelasList' => $this->kelasModel->allWithRelations(),
                'tentorList' => $this->tentorModel->allWithPengguna(),
                'errors' => $errors
            ]);
            return;
        }

        $this->jadwalModel->create([
            'kelas_id' => $kelasId,
            'tentor_id' => $tentorId,
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'ruangan' => $ruangan ?: null,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => date('Y-m-d H:i:s'),
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Jadwal mengajar berhasil ditambahkan.';
        $this->redirect('/admin/jadwal');
    }

    public function edit(string $id): void
    {
        $idInt = (int) $id;
        $jadwal = $this->jadwalModel->find($idInt);

        if (!$jadwal) {
            $_SESSION['flash_error'] = 'Data jadwal tidak ditemukan.';
            $this->redirect('/admin/jadwal');
        }

        $this->render('admin/jadwal/form', [
            'pageTitle' => 'Edit Jadwal Mengajar',
            'activeNav' => 'jadwal',
            'isEdit' => true,
            'jadwal' => $jadwal,
            'kelasList' => $this->kelasModel->allWithRelations(),
            'tentorList' => $this->tentorModel->allWithPengguna(),
            'errors' => []
        ]);
    }

    public function update(string $id): void
    {
        $idInt = (int) $id;
        $jadwal = $this->jadwalModel->find($idInt);

        if (!$jadwal) {
            $_SESSION['flash_error'] = 'Data jadwal tidak ditemukan.';
            $this->redirect('/admin/jadwal');
        }

        $kelasId = (int) ($_POST['kelas_id'] ?? 0);
        $tentorId = (int) ($_POST['tentor_id'] ?? 0);
        $hari = (int) ($_POST['hari'] ?? 0);
        $jamMulai = trim($_POST['jam_mulai'] ?? '');
        $jamSelesai = trim($_POST['jam_selesai'] ?? '');
        $ruangan = trim($_POST['ruangan'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas yang valid.';
        }

        if ($tentorId <= 0 || !$this->tentorModel->find($tentorId)) {
            $errors['tentor_id'] = 'Pilih tentor yang valid.';
        }

        if ($hari < 1 || $hari > 7) {
            $errors['hari'] = 'Pilih hari dalam seminggu yang valid (Senin - Minggu).';
        }

        if (empty($jamMulai)) {
            $errors['jam_mulai'] = 'Jam mulai wajib diisi.';
        }

        if (empty($jamSelesai)) {
            $errors['jam_selesai'] = 'Jam selesai wajib diisi.';
        } elseif ($jamSelesai <= $jamMulai) {
            $errors['jam_selesai'] = 'Jam selesai harus lebih akhir daripada jam mulai.';
        }

        if (!empty($errors)) {
            $this->render('admin/jadwal/form', [
                'pageTitle' => 'Edit Jadwal Mengajar',
                'activeNav' => 'jadwal',
                'isEdit' => true,
                'jadwal' => array_merge($jadwal, [
                    'kelas_id' => $kelasId,
                    'tentor_id' => $tentorId,
                    'hari' => $hari,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $ruangan,
                    'status_aktif' => $statusAktif,
                ]),
                'kelasList' => $this->kelasModel->allWithRelations(),
                'tentorList' => $this->tentorModel->allWithPengguna(),
                'errors' => $errors
            ]);
            return;
        }

        $this->jadwalModel->update($idInt, [
            'kelas_id' => $kelasId,
            'tentor_id' => $tentorId,
            'hari' => $hari,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'ruangan' => $ruangan ?: null,
            'status_aktif' => $statusAktif,
            'diubah_pada' => date('Y-m-d H:i:s'),
        ]);

        $_SESSION['flash_success'] = 'Jadwal mengajar berhasil diperbarui.';
        $this->redirect('/admin/jadwal');
    }

    public function delete(string $id): void
    {
        $idInt = (int) $id;
        $jadwal = $this->jadwalModel->find($idInt);

        if (!$jadwal) {
            $_SESSION['flash_error'] = 'Data jadwal tidak ditemukan.';
            $this->redirect('/admin/jadwal');
        }

        try {
            $this->jadwalModel->delete($idInt);
            $_SESSION['flash_success'] = 'Jadwal mengajar berhasil dihapus.';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menghapus jadwal. Terkait dengan histori pertemuan.';
        }

        $this->redirect('/admin/jadwal');
    }
}
