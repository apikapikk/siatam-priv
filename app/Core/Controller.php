<?php

namespace App\Core;

abstract class Controller
{
    /**
     * Render view template dengan layout admin
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'admin/layout'): void
    {
        extract($data);
        $contentView = __DIR__ . '/../Views/' . ltrim($viewPath, '/') . '.php';
        $layoutFile = __DIR__ . '/../Views/' . ltrim($layout, '/') . '.php';

        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            require $contentView;
        }
    }

    /**
     * Redirect HTTP ke URL tertentu
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Mengembalikan response JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
