<?php

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

// Dispatch HTTP request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

