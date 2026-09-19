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
}
