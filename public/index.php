<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

require_once __DIR__ . '/../app/Support/helpers.php';
require_once __DIR__ . '/../app/Data/AdminDashboardData.php';

use App\Core\Router;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PenggunaController;
use App\Controllers\Admin\JenjangController;
use App\Controllers\Admin\KelasController;
use App\Controllers\Admin\TentorController;
use App\Controllers\Admin\SiswaController;
use App\Controllers\Admin\PendaftaranSiswaController;
use App\Controllers\Admin\JadwalController;
use App\Controllers\Admin\PengumumanController;
use App\Controllers\Admin\BeritaController;
use App\Controllers\Admin\PertemuanController;

$router = new Router();

// Redirect root ke admin beranda
$router->get('/', function () {
    header('Location: /admin/beranda');
    exit;
});
$router->get('/admin', function () {
    header('Location: /admin/beranda');
    exit;
});

// Admin Dashboard
$router->get('/admin/beranda', [DashboardController::class, 'index']);

// CRUD Pengguna Admin
$router->get('/admin/pengguna', [PenggunaController::class, 'index']);
$router->get('/admin/pengguna/tambah', [PenggunaController::class, 'create']);
$router->post('/admin/pengguna/simpan', [PenggunaController::class, 'store']);
$router->get('/admin/pengguna/{id}/edit', [PenggunaController::class, 'edit']);
$router->post('/admin/pengguna/{id}/update', [PenggunaController::class, 'update']);
$router->post('/admin/pengguna/{id}/hapus', [PenggunaController::class, 'delete']);

// Master Data Jenjang
$router->get('/admin/jenjang', [JenjangController::class, 'index']);
$router->get('/admin/jenjang/tambah', [JenjangController::class, 'create']);
$router->post('/admin/jenjang/simpan', [JenjangController::class, 'store']);
$router->get('/admin/jenjang/{id}/edit', [JenjangController::class, 'edit']);
$router->post('/admin/jenjang/{id}/update', [JenjangController::class, 'update']);
$router->post('/admin/jenjang/{id}/hapus', [JenjangController::class, 'delete']);

// Master Data Kelas
$router->get('/admin/kelas', [KelasController::class, 'index']);
$router->get('/admin/kelas/tambah', [KelasController::class, 'create']);
$router->post('/admin/kelas/simpan', [KelasController::class, 'store']);
$router->get('/admin/kelas/{id}/edit', [KelasController::class, 'edit']);
$router->post('/admin/kelas/{id}/update', [KelasController::class, 'update']);
$router->post('/admin/kelas/{id}/hapus', [KelasController::class, 'delete']);

// Profil Tentor
$router->get('/admin/tentor', [TentorController::class, 'index']);
$router->get('/admin/tentor/tambah', [TentorController::class, 'create']);
$router->post('/admin/tentor/simpan', [TentorController::class, 'store']);
$router->get('/admin/tentor/{id}/edit', [TentorController::class, 'edit']);
$router->post('/admin/tentor/{id}/update', [TentorController::class, 'update']);
$router->post('/admin/tentor/{id}/hapus', [TentorController::class, 'delete']);

// Data Siswa & Wali Murid
$router->get('/admin/siswa', [SiswaController::class, 'index']);
$router->get('/admin/siswa/tambah', [SiswaController::class, 'create']);
$router->post('/admin/siswa/simpan', [SiswaController::class, 'store']);
$router->get('/admin/siswa/{id}/edit', [SiswaController::class, 'edit']);
$router->post('/admin/siswa/{id}/update', [SiswaController::class, 'update']);
$router->post('/admin/siswa/{id}/hapus', [SiswaController::class, 'delete']);

// Pendaftaran & Penempatan Siswa
$router->get('/admin/pendaftaran', [PendaftaranSiswaController::class, 'index']);
$router->get('/admin/pendaftaran/tambah', [PendaftaranSiswaController::class, 'create']);
$router->post('/admin/pendaftaran/simpan', [PendaftaranSiswaController::class, 'store']);
$router->get('/admin/pendaftaran/{id}/edit', [PendaftaranSiswaController::class, 'edit']);
$router->post('/admin/pendaftaran/{id}/update', [PendaftaranSiswaController::class, 'update']);
$router->post('/admin/pendaftaran/{id}/hapus', [PendaftaranSiswaController::class, 'delete']);

// Penjadwalan Mengajar
$router->get('/admin/jadwal', [JadwalController::class, 'index']);
$router->get('/admin/jadwal/tambah', [JadwalController::class, 'create']);
$router->post('/admin/jadwal/simpan', [JadwalController::class, 'store']);
$router->get('/admin/jadwal/{id}/edit', [JadwalController::class, 'edit']);
$router->post('/admin/jadwal/{id}/update', [JadwalController::class, 'update']);
$router->post('/admin/jadwal/{id}/hapus', [JadwalController::class, 'delete']);

// Sesi Pertemuan Mengajar & Presensi
$router->get('/admin/pertemuan', [PertemuanController::class, 'index']);
$router->get('/admin/pertemuan/tambah', [PertemuanController::class, 'create']);
$router->post('/admin/pertemuan/simpan', [PertemuanController::class, 'store']);
$router->get('/admin/pertemuan/{id}/presensi', [PertemuanController::class, 'presensi']);
$router->post('/admin/pertemuan/{id}/presensi/update', [PertemuanController::class, 'updatePresensi']);
$router->post('/admin/pertemuan/{id}/hapus', [PertemuanController::class, 'delete']);

// Broadcast Pengumuman
$router->get('/admin/pengumuman', [PengumumanController::class, 'index']);
$router->get('/admin/pengumuman/tambah', [PengumumanController::class, 'create']);
$router->post('/admin/pengumuman/simpan', [PengumumanController::class, 'store']);
$router->get('/admin/pengumuman/{id}/edit', [PengumumanController::class, 'edit']);
$router->post('/admin/pengumuman/{id}/update', [PengumumanController::class, 'update']);
$router->post('/admin/pengumuman/{id}/hapus', [PengumumanController::class, 'delete']);

// Berita Publik
$router->get('/admin/berita', [BeritaController::class, 'index']);
$router->get('/admin/berita/tambah', [BeritaController::class, 'create']);
$router->post('/admin/berita/simpan', [BeritaController::class, 'store']);
$router->get('/admin/berita/{id}/edit', [BeritaController::class, 'edit']);
$router->post('/admin/berita/{id}/update', [BeritaController::class, 'update']);
$router->post('/admin/berita/{id}/hapus', [BeritaController::class, 'delete']);

// Dispatch HTTP request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);










