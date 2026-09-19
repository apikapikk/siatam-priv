<?php
/**
 * Script Migrasi & Seeding Database
 * Jalankan via CLI: php database/migrate.php
 */

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'bimbel_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

echo "=== MENGAWALI MIGRASI DATABASE BAMBEL ===\n";

try {
    // 1. Koneksi tanpa nama DB untuk membuat database jika belum ada
    $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', DB_HOST, DB_PORT);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "[1/4] Membuat database `" . DB_NAME . "` jika belum ada...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `" . DB_NAME . "`;");

    // 2. Impor Skema Database
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("File schema.sql tidak ditemukan di: " . $schemaFile);
    }

    echo "[2/4] Mengeksekusi schema.sql...\n";
    $schemaSql = file_get_contents($schemaFile);
    $pdo->exec($schemaSql);
    echo " -> Skema tabel berhasil dibuat.\n";

    // 3. Impor Data Initial / Seed
    $seedFile = __DIR__ . '/seed.sql';
    if (!file_exists($seedFile)) {
        throw new Exception("File seed.sql tidak ditemukan di: " . $seedFile);
    }

    echo "[3/4] Mengeksekusi seed.sql...\n";
    $seedSql = file_get_contents($seedFile);
    $pdo->exec($seedSql);
    echo " -> Data awal (seeding) berhasil dimuat.\n";

    echo "[4/4] Migrasi & Seeding selesai dengan sukses!\n";
    echo "===========================================\n";

} catch (Exception $e) {
    echo "\n[ERROR] Migrasi gagal: " . $e->getMessage() . "\n";
    exit(1);
}
