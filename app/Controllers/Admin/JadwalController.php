<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Jenjang;
use App\Models\Program;
use App\Models\Tentor;

class JadwalController extends Controller
{
    private Jadwal $jadwalModel;
    private Kelas $kelasModel;
    private Jenjang $jenjangModel;
    private Program $programModel;
    private Tentor $tentorModel;

    public function __construct()
    {
        $this->jadwalModel = new Jadwal();
        $this->kelasModel = new Kelas();
        $this->jenjangModel = new Jenjang();
        $this->programModel = new Program();
        $this->tentorModel = new Tentor();
    }

    public function index(): void
    {
        $jadwalList = $this->jadwalModel->allWithDetails(true);
        $mode = ($_GET['mode'] ?? 'kelas') === 'hari' ? 'hari' : 'kelas';
        $hariAktif = max(1, min(7, (int) ($_GET['hari'] ?? date('N'))));

        $this->render('admin/jadwal/index', [
            'pageTitle' => 'Manajemen Kelas',
            'activeNav' => 'jadwal',
            'jadwalList' => $jadwalList,
            'mode' => $mode,
            'hariAktif' => $hariAktif,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/kelas/form', [
            'pageTitle' => 'Tambah Jadwal Kelas',
            'activeNav' => 'jadwal',
            'isEdit' => false,
            'kelas' => null,
            'jenjangList' => $this->jenjangModel->all(),
            'programList' => $this->programModel->all(),
            'tentorList' => $this->tentorModel->allWithPengguna(),
            'sesiList' => [['hari' => '', 'jam_mulai' => '', 'jam_selesai' => '', 'tentor_id' => '', 'mata_pelajaran' => '', 'ruangan' => '']],
            'errors' => [],
            'requireSchedule' => true,
            'formContext' => 'jadwal',
        ]);
    }

    public function store(): void
    {
        $nama = trim($_POST['nama'] ?? '');
        $jenjangId = (int) ($_POST['jenjang_id'] ?? 0);
        $programId = (int) ($_POST['program_id'] ?? 0);
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;
        $sesiInput = $_POST['sesi'] ?? [];

        $errors = [];

        if ($nama === '') {
            $errors['nama'] = 'Nama kelas tidak boleh kosong.';
        } elseif (strlen($nama) > 20) {
            $errors['nama'] = 'Nama kelas maksimal 20 karakter.';
        }
        if ($jenjangId <= 0 || !$this->jenjangModel->find($jenjangId)) {
            $errors['jenjang_id'] = 'Pilih jenjang pendidikan yang valid.';
        }
        if ($programId <= 0 || !$this->programModel->find($programId)) {
            $errors['program_id'] = 'Pilih program yang valid.';
        }

        [$sesiList, $sesiErrors] = $this->normaliseSchedules(is_array($sesiInput) ? $sesiInput : []);
        $errors = array_merge($errors, $sesiErrors);
        if (!$sesiList && !$sesiErrors) {
            $errors['sesi'] = 'Tambahkan minimal satu sesi belajar yang lengkap.';
        }

        if (!empty($errors)) {
            $this->render('admin/kelas/form', [
                'pageTitle' => 'Tambah Jadwal Kelas',
                'activeNav' => 'jadwal',
                'isEdit' => false,
                'kelas' => [
                    'nama' => $nama,
                    'jenjang_id' => $jenjangId,
                    'program_id' => $programId,
                    'status_aktif' => $statusAktif,
                ],
                'jenjangList' => $this->jenjangModel->all(),
                'programList' => $this->programModel->all(),
                'tentorList' => $this->tentorModel->allWithPengguna(),
                'sesiList' => is_array($sesiInput) ? array_values($sesiInput) : [],
                'errors' => $errors,
                'requireSchedule' => true,
                'formContext' => 'jadwal',
            ]);
            return;
        }

        $now = date('Y-m-d H:i:s');
        $this->kelasModel->createWithSchedules([
            'nama' => $nama,
            'jenjang_id' => $jenjangId,
            'program_id' => $programId,
            'status_aktif' => $statusAktif,
            'dibuat_pada' => $now,
            'diubah_pada' => $now,
        ], $sesiList);

        $_SESSION['flash_success'] = 'Jadwal kelas berhasil ditambahkan.';
        $this->redirect('/admin/jadwal');
    }

    private function normaliseSchedules(array $input): array
    {
        $schedules = [];
        $errors = [];
        foreach ($input as $index => $row) {
            if (!is_array($row)) continue;
            $hari = (int) ($row['hari'] ?? 0);
            $mulai = trim($row['jam_mulai'] ?? '');
            $selesai = trim($row['jam_selesai'] ?? '');
            $tentorId = (int) ($row['tentor_id'] ?? 0);
            $mapel = trim($row['mata_pelajaran'] ?? '');
            $ruangan = trim($row['ruangan'] ?? '');
            if ($hari === 0 && $mulai === '' && $selesai === '' && $tentorId === 0 && $mapel === '' && $ruangan === '') continue;
            $prefix = 'Sesi ' . ((int) $index + 1) . ': ';
            if ($hari < 1 || $hari > 7) $errors['sesi_' . $index] = $prefix . 'pilih hari yang valid.';
            elseif ($mulai === '' || $selesai === '') $errors['sesi_' . $index] = $prefix . 'jam mulai dan selesai wajib diisi.';
            elseif ($selesai <= $mulai) $errors['sesi_' . $index] = $prefix . 'jam selesai harus lebih akhir.';
            elseif ($tentorId > 0 && !$this->tentorModel->find($tentorId)) $errors['sesi_' . $index] = $prefix . 'tentor tidak valid.';
            if (isset($errors['sesi_' . $index])) continue;
            $schedules[] = [
                'tentor_id' => $tentorId ?: null,
                'mata_pelajaran' => $mapel ?: null,
                'hari' => $hari,
                'jam_mulai' => $mulai,
                'jam_selesai' => $selesai,
                'ruangan' => $ruangan ?: null,
                'status_aktif' => 1,
                'dibuat_pada' => date('Y-m-d H:i:s'),
                'diubah_pada' => date('Y-m-d H:i:s'),
            ];
        }
        return [$schedules, $errors];
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
        $mataPelajaran = trim($_POST['mata_pelajaran'] ?? '');
        $hari = (int) ($_POST['hari'] ?? 0);
        $jamMulai = trim($_POST['jam_mulai'] ?? '');
        $jamSelesai = trim($_POST['jam_selesai'] ?? '');
        $ruangan = trim($_POST['ruangan'] ?? '');
        $statusAktif = isset($_POST['status_aktif']) ? 1 : 0;

        $errors = [];

        if ($kelasId <= 0 || !$this->kelasModel->find($kelasId)) {
            $errors['kelas_id'] = 'Pilih kelas yang valid.';
        }

        if ($tentorId > 0 && !$this->tentorModel->find($tentorId)) {
            $errors['tentor_id'] = 'Pilih tentor yang valid.';
        }

        if ($mataPelajaran === '') {
            $errors['mata_pelajaran'] = 'Mata pelajaran wajib diisi.';
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
                    'mata_pelajaran' => $mataPelajaran,
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
            'tentor_id' => $tentorId ?: null,
            'mata_pelajaran' => $mataPelajaran,
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

    public function editKelas(string $id): void
    {
        $kelasId = (int) $id;
        $kelas = $this->kelasModel->findWithRelations($kelasId);
        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/jadwal');
        }

        $this->render('admin/jadwal/kelas_form', [
            'pageTitle' => 'Ubah Jadwal Kelas',
            'activeNav' => 'jadwal',
            'kelas' => $kelas,
            'sesiList' => $this->jadwalModel->forKelas($kelasId),
            'tentorList' => $this->tentorModel->allWithPengguna(),
            'errors' => [],
        ]);
    }

    public function updateKelas(string $id): void
    {
        $kelasIdInt = (int) $id;
        $kelas = $this->kelasModel->findWithRelations($kelasIdInt);
        if (!$kelas) {
            $_SESSION['flash_error'] = 'Kelas tidak ditemukan.';
            $this->redirect('/admin/jadwal');
        }

        $existing = $this->jadwalModel->forKelas($kelasIdInt);
        $existingIds = array_map(static fn ($item) => (int) $item['id'], $existing);
        $submitted = is_array($_POST['sesi'] ?? null) ? $_POST['sesi'] : [];
        $errors = [];
        $validIds = [];
        $rows = [];

        foreach ($submitted as $index => $row) {
            if (!is_array($row)) continue;
            $id = (int) ($row['id'] ?? 0);
            $hari = (int) ($row['hari'] ?? 0);
            $mulai = trim($row['jam_mulai'] ?? '');
            $selesai = trim($row['jam_selesai'] ?? '');
            $tentorId = (int) ($row['tentor_id'] ?? 0);
            $prefix = 'Sesi ' . ((int) $index + 1) . ': ';
            if ($id > 0 && !in_array($id, $existingIds, true)) $errors['sesi_' . $index] = $prefix . 'sesi tidak valid.';
            elseif ($hari < 1 || $hari > 7) $errors['sesi_' . $index] = $prefix . 'pilih hari yang valid.';
            elseif ($mulai === '' || $selesai === '' || $selesai <= $mulai) $errors['sesi_' . $index] = $prefix . 'periksa jam mulai dan selesai.';
            elseif ($tentorId > 0 && !$this->tentorModel->find($tentorId)) $errors['sesi_' . $index] = $prefix . 'tentor tidak valid.';
            if (isset($errors['sesi_' . $index])) continue;
            $rows[] = ['id' => $id, 'tentor_id' => $tentorId ?: null, 'mata_pelajaran' => trim($row['mata_pelajaran'] ?? '') ?: null, 'hari' => $hari, 'jam_mulai' => $mulai, 'jam_selesai' => $selesai, 'ruangan' => trim($row['ruangan'] ?? '') ?: null];
            if ($id > 0) $validIds[] = $id;
        }

        if ($errors) {
            $this->render('admin/jadwal/kelas_form', ['pageTitle' => 'Ubah Jadwal Kelas', 'activeNav' => 'jadwal', 'kelas' => $kelas, 'sesiList' => $submitted, 'tentorList' => $this->tentorModel->allWithPengguna(), 'errors' => $errors]);
            return;
        }

        foreach ($rows as $row) {
            $data = ['tentor_id' => $row['tentor_id'], 'mata_pelajaran' => $row['mata_pelajaran'], 'hari' => $row['hari'], 'jam_mulai' => $row['jam_mulai'], 'jam_selesai' => $row['jam_selesai'], 'ruangan' => $row['ruangan'], 'status_aktif' => 1, 'diubah_pada' => date('Y-m-d H:i:s')];
            if ($row['id'] > 0) $this->jadwalModel->update($row['id'], $data);
            else $this->jadwalModel->create(array_merge($data, ['kelas_id' => $kelasIdInt, 'dibuat_pada' => date('Y-m-d H:i:s')]));
        }
        foreach ($existingIds as $id) {
            if (!in_array($id, $validIds, true)) $this->jadwalModel->update($id, ['status_aktif' => 0, 'diubah_pada' => date('Y-m-d H:i:s')]);
        }

        $_SESSION['flash_success'] = 'Jadwal kelas berhasil diperbarui.';
        $this->redirect('/admin/jadwal');
    }
}
