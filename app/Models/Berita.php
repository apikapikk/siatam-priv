<?php

namespace App\Models;

use App\Core\Model;

class Berita extends Model
{
    protected string $table = 'berita';

    public function allWithAuthor(): array
    {
        $sql = "SELECT b.*, u.username AS pembuat_nama
                FROM `berita` b
                JOIN `pengguna` u ON u.id = b.dibuat_oleh
                ORDER BY b.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function generateSlug(string $judul, ?int $exceptId = null): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul), '-'));
        if (empty($slug)) {
            $slug = 'berita-' . time();
        }

        $baseSlug = $slug;
        $counter = 1;

        while ($this->isSlugExists($slug, $exceptId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function isSlugExists(string $slug, ?int $exceptId = null): bool
    {
        if ($exceptId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM `berita` WHERE `slug` = :slug AND `id` != :id");
            $stmt->execute(['slug' => $slug, 'id' => $exceptId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM `berita` WHERE `slug` = :slug");
            $stmt->execute(['slug' => $slug]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }
}
