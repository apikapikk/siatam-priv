<?php

namespace App\Models;

use App\Core\Model;

class Pengumuman extends Model
{
    protected string $table = 'pengumuman';

    public function allWithAuthor(): array
    {
        $sql = "SELECT p.*, u.username AS pembuat_nama
                FROM `pengumuman` p
                JOIN `pengguna` u ON u.id = p.dibuat_oleh
                ORDER BY p.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getLatestActiveAnnouncements(string $peran, int $limit = 3): array
    {
        $allowedPeran = in_array($peran, ['admin', 'tentor'], true) ? $peran : 'admin';
        $limit = max(1, min($limit, 10));

        $sql = "SELECT judul, isi, target_peran, tipe_broadcast, kategori, diterbitkan_pada
                FROM `pengumuman`
                WHERE status_aktif = 1
                  AND target_peran IN ('semua', :peran)
                ORDER BY diterbitkan_pada DESC
                LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['peran' => $allowedPeran]);
        return $stmt->fetchAll();
    }
}
