<?php

namespace App\Models;

use App\Core\Model;

class Pengguna extends Model
{
    protected string $table = 'pengguna';

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `pengguna` WHERE `username` = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function isUsernameExists(string $username, ?int $exceptId = null): bool
    {
        if ($exceptId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM `pengguna` WHERE `username` = :username AND `id` != :id");
            $stmt->execute(['username' => $username, 'id' => $exceptId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM `pengguna` WHERE `username` = :username");
            $stmt->execute(['username' => $username]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    public function updateLastLogin(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE `pengguna` SET `terakhir_login` = NOW() WHERE `id` = :id");
        return $stmt->execute(['id' => $id]);
    }
}
