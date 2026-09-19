<?php

namespace App\Models;

use App\Core\Model;

class Kelas extends Model
{
    protected string $table = 'kelas';

    public function allWithRelations(): array
    {
        $sql = "SELECT k.*, j.nama AS jenjang_nama, p.nama AS program_nama
                FROM `kelas` k
                JOIN `jenjang` j ON j.id = k.jenjang_id
                JOIN `program` p ON p.id = k.program_id
                ORDER BY k.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
