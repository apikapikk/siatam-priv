<?php

namespace App\Models;

use App\Core\Model;

class Jadwal extends Model
{
    protected string $table = 'jadwal';

    public function allWithDetails(): array
    {
        $sql = "SELECT j.*,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama,
                       t.nama_lengkap AS tentor_nama
                FROM `jadwal` j
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                JOIN `tentor` t ON t.id = j.tentor_id
                ORDER BY j.hari ASC, j.jam_mulai ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTodayRealtimeScheduleWithStatus(): array
    {
        $sql = "SELECT j.*,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama,
                       t.nama_lengkap AS tentor_nama,
                       CASE
                           WHEN TIME(NOW()) < j.jam_mulai THEN 'belum_mulai'
                           WHEN TIME(NOW()) > j.jam_selesai THEN 'selesai'
                           ELSE 'sedang_berlangsung'
                       END AS status_realtime
                FROM `jadwal` j
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                JOIN `tentor` t ON t.id = j.tentor_id
                WHERE j.status_aktif = 1
                  AND j.hari = WEEKDAY(CURRENT_DATE()) + 1
                ORDER BY j.jam_mulai ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
