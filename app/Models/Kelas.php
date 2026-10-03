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

    public function findWithRelations(int $id): ?array
    {
        $sql = "SELECT k.*, j.nama AS jenjang_nama, p.nama AS program_nama
                FROM `kelas` k
                JOIN `jenjang` j ON j.id = k.jenjang_id
                JOIN `program` p ON p.id = k.program_id
                WHERE k.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function allWithStudentCounts(): array
    {
        $sql = "SELECT k.*, j.nama AS jenjang_nama, p.nama AS program_nama,
                       COUNT(DISTINCT CASE WHEN s.status_aktif = 1 THEN ps.siswa_id END) AS jumlah_siswa
                FROM kelas k
                JOIN jenjang j ON j.id = k.jenjang_id
                JOIN program p ON p.id = k.program_id
                LEFT JOIN pendaftaran_siswa ps ON ps.kelas_id = k.id AND ps.status = 'aktif'
                LEFT JOIN siswa s ON s.id = ps.siswa_id
                GROUP BY k.id
                ORDER BY k.nama ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function students(int $kelasId): array
    {
        $sql = "SELECT s.*, ps.id AS pendaftaran_id, ps.tanggal_mulai, ps.paket_id,
                       GROUP_CONCAT(CONCAT(ot.nama_lengkap, ' (', sot.hubungan, ')') SEPARATOR ', ') AS daftar_orang_tua
                FROM pendaftaran_siswa ps
                JOIN siswa s ON s.id = ps.siswa_id
                LEFT JOIN siswa_orang_tua sot ON sot.siswa_id = s.id
                LEFT JOIN orang_tua ot ON ot.id = sot.orang_tua_id
                WHERE ps.kelas_id = :kelas_id AND ps.status = 'aktif'
                GROUP BY s.id, ps.id
                ORDER BY s.nama_lengkap ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kelas_id' => $kelasId]);
        return $stmt->fetchAll();
    }

    public function createWithSchedules(array $classData, array $schedules): int
    {
        $this->db->beginTransaction();
        try {
            $classId = (int) $this->create($classData);
            $stmt = $this->db->prepare(
                "INSERT INTO `jadwal` (`kelas_id`, `tentor_id`, `mata_pelajaran`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `status_aktif`, `dibuat_pada`, `diubah_pada`)
                 VALUES (:kelas_id, :tentor_id, :mata_pelajaran, :hari, :jam_mulai, :jam_selesai, :ruangan, :status_aktif, :dibuat_pada, :diubah_pada)"
            );
            foreach ($schedules as $schedule) {
                $stmt->execute(array_merge($schedule, ['kelas_id' => $classId]));
            }
            $this->db->commit();
            return $classId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
