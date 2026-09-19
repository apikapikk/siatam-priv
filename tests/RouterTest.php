<?php

use PHPUnit\Framework\TestCase;
use App\Core\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouteMatchingAndParamExtraction()
    {
        $triggered = false;
        $capturedSlug = '';

        $this->router->get('/berita/{slug}', function ($slug) use (&$triggered, &$capturedSlug) {
            $triggered = true;
            $capturedSlug = $slug;
        });

        $this->router->dispatch('GET', '/berita/kegiatan-belajar-2026');

        $this->assertTrue($triggered);
        $this->assertEquals('kegiatan-belajar-2026', $capturedSlug);
    }
}
