<?php

namespace App\Models;

use App\Core\Model;

class Tentor extends Model
{
    protected string $table = 'tentor';

    public function allWithPengguna(): array
    {
        $sql = "SELECT t.*, p.username, p.terakhir_login
                FROM `tentor` t
                JOIN `pengguna` p ON p.id = t.pengguna_id
                ORDER BY t.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findWithPengguna(int $id): ?array
    {
        $sql = "SELECT t.*, p.username, p.terakhir_login
                FROM `tentor` t
                JOIN `pengguna` p ON p.id = t.pengguna_id
                WHERE t.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByPenggunaId(int $penggunaId): ?array
    {
        $sql = "SELECT * FROM `tentor` WHERE `pengguna_id` = :pengguna_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pengguna_id' => $penggunaId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getAvailableUsersForTentor(?int $currentPenggunaId = null): array
    {
        if ($currentPenggunaId) {
            $sql = "SELECT p.id, p.username
                    FROM `pengguna` p
                    LEFT JOIN `tentor` t ON t.pengguna_id = p.id
                    WHERE p.peran = 'tentor' AND (t.id IS NULL OR p.id = :current_pengguna_id)
                    ORDER BY p.username ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['current_pengguna_id' => $currentPenggunaId]);
        } else {
            $sql = "SELECT p.id, p.username
                    FROM `pengguna` p
                    LEFT JOIN `tentor` t ON t.pengguna_id = p.id
                    WHERE p.peran = 'tentor' AND t.id IS NULL
                    ORDER BY p.username ASC";
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchAll();
    }
}
