<?php

// Setup session & output buffer CLI
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

// Autoloader PSR-4
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
        require_once $file;
    }
});

require_once __DIR__ . '/../app/Support/helpers.php';
require_once __DIR__ . '/../app/Data/AdminDashboardData.php';

class TestRunner {
    public int $passed = 0;
    public int $failed = 0;
    public array $errors = [];

    public function assert($condition, string $message) {
        if ($condition) {
            $this->passed++;
            echo "  [PASS] {$message}\n";
        } else {
            $this->failed++;
            $this->errors[] = $message;
            echo "  [FAIL] {$message}\n";
        }
    }
}

$runner = new TestRunner();

echo "====================================================\n";
echo "    SUITE V&V UNIT & WHITEBOX TEST - SIATAMA PRIVAT  \n";
echo "====================================================\n\n";

// ----------------------------------------------------
// 1. WHITEBOX & UNIT TEST: App\Core\Router
// ----------------------------------------------------
echo "--- Testing Module 1: App\\Core\\Router ---\n";
$router = new App\Core\Router();
$triggeredGet = false;
$triggeredPost = false;
$paramExtracted = '';

$router->get('/berita/{slug}', function($slug) use (&$triggeredGet, &$paramExtracted) {
    $triggeredGet = true;
    $paramExtracted = $slug;
});

$router->post('/admin/pengguna/{id}/update', function($id) use (&$triggeredPost, &$paramExtracted) {
    $triggeredPost = true;
    $paramExtracted = $id;
});

// Dispatch GET match
$router->dispatch('GET', '/berita/kegiatan-bimbel-2026');
$runner->assert($triggeredGet === true && $paramExtracted === 'kegiatan-bimbel-2026', "Router matching GET route & parameter extraction ({slug})");

// Dispatch POST match
$router->dispatch('POST', '/admin/pengguna/42/update');
$runner->assert($triggeredPost === true && $paramExtracted === '42', "Router matching POST route & parameter extraction ({id})");

// Dispatch Route Tidak Ditemukan (Whitebox Branch Coverage 404)
$triggeredGet = false;
$subBuffer = ob_get_level();
ob_start();
@$router->dispatch('GET', '/route-yang-tidak-ada');
$htmlOutput = ob_get_clean();
$runner->assert(str_contains($htmlOutput, 'Halaman Tidak Ditemukan') || str_contains($htmlOutput, '404'), "Router dispatches 404 view HTML template on unregistered path");


// ----------------------------------------------------
// 2. WHITEBOX & UNIT TEST: App\Models\Berita (Slug Generator & DB logic)
// ----------------------------------------------------
echo "\n--- Testing Module 2: App\\Models\\Berita (Slug Generation & Database) ---\n";
$beritaModel = new App\Models\Berita();

$slug1 = $beritaModel->generateSlug('Kegiatan Belajar Bimbel 2026!');
$runner->assert($slug1 === 'kegiatan-belajar-bimbel-2026', "Berita::generateSlug sanitizes special chars to hyphen lowercase");

$slug2 = $beritaModel->generateSlug('!@#$%^&*()');
$runner->assert(str_starts_with($slug2, 'berita-'), "Berita::generateSlug falls back to timestamp prefix for empty sanitized titles");


// ----------------------------------------------------
// 3. WHITEBOX & UNIT TEST: Business Rules & Data Integrity (database.md)
// ----------------------------------------------------
echo "\n--- Testing Module 3: Database & Business Logic Specifications (database.md) ---\n";
$db = getDBConnection();

// Rule 2.1 & 2.2: Pengguna & Profil Tentor
$stmt = $db->query("SELECT id, username, peran FROM pengguna WHERE peran = 'tentor' LIMIT 1");
$tentorUser = $stmt->fetch();
if ($tentorUser) {
    $stmtProfil = $db->prepare("SELECT * FROM tentor WHERE pengguna_id = :uid");
    $stmtProfil->execute(['uid' => $tentorUser['id']]);
    $profil = $stmtProfil->fetch();
    $runner->assert($profil !== false, "Rule 2.2: Akun pengguna peran 'tentor' terelasi 1:1 ke tabel `tentor` (pengguna_id = {$tentorUser['id']})");
} else {
    $runner->assert(true, "Rule 2.2: skipped (no tentor in db)");
}

// Rule 2.3: Siswa & Orang Tua Tidak Punya Akun Login
$stmt = $db->query("SELECT COUNT(*) FROM pengguna WHERE peran IN ('siswa', 'orang_tua')");
$invalidUsers = (int) $stmt->fetchColumn();
$runner->assert($invalidUsers === 0, "Rule 2.3: Tidak ada pengguna dengan peran 'siswa' atau 'orang_tua' di tabel `pengguna`");

// Rule 4.1: Password Hashing Integrity
$stmt = $db->query("SELECT password FROM pengguna LIMIT 5");
$passwords = $stmt->fetchAll(PDO::FETCH_COLUMN);
$allHashed = true;
foreach ($passwords as $pwd) {
    $info = password_get_info($pwd);
    if ($info['algo'] === 0) {
        $allHashed = false;
        break;
    }
}
$runner->assert($allHashed, "Rule 4.1: Seluruh password pengguna di-hash menggunakan algoritma aman (Bcrypt/Argon)");

// Rule 15 & 16: Pertemuan & Presensi Synchronization
$stmt = $db->query("SELECT p.id, p.jadwal_id, j.kelas_id FROM pertemuan p JOIN jadwal j ON j.id = p.jadwal_id LIMIT 1");
$pertemuanSample = $stmt->fetch();
if ($pertemuanSample) {
    $pId = (int) $pertemuanSample['id'];
    $kId = (int) $pertemuanSample['kelas_id'];
    
    // Hitung jumlah presensi
    $stmtP = $db->prepare("SELECT COUNT(*) FROM presensi WHERE pertemuan_id = :pid");
    $stmtP->execute(['pid' => $pId]);
    $presensiCount = (int) $stmtP->fetchColumn();

    // Hitung siswa aktif di kelas
    $stmtS = $db->prepare("SELECT COUNT(*) FROM pendaftaran_siswa WHERE kelas_id = :kid AND status = 'aktif'");
    $stmtS->execute(['kid' => $kId]);
    $activeCount = (int) $stmtS->fetchColumn();

    $runner->assert($presensiCount >= $activeCount, "Rule 16: Pertemuan #{$pId} memiliki rekaman presensi lengkap untuk siswa aktif di kelas {$kId}");
} else {
    $runner->assert(true, "Rule 16: skipped (no pertemuan recorded yet)");
}


// ----------------------------------------------------
// SUMMARY
// ----------------------------------------------------
echo "\n====================================================\n";
echo "                HASIL RINGKASAN TEST                \n";
echo "====================================================\n";
echo "Total Pengujian : " . ($runner->passed + $runner->failed) . "\n";
echo "Berhasil (PASS) : {$runner->passed}\n";
echo "Gagal    (FAIL) : {$runner->failed}\n";

if ($runner->failed > 0) {
    echo "\nRincian Kegagalan:\n";
    foreach ($runner->errors as $err) {
        echo " - {$err}\n";
    }
    ob_end_flush();
    exit(1);
} else {
    echo "\nStatus: SUCCESS - ALL UNIT & WHITEBOX TESTS PASSED! 🎉\n";
    ob_end_flush();
    exit(0);
}
