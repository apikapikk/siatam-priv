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

    public function getMonthlyPayrollSummary(int $month, int $year): array
    {
        $sql = "SELECT t.id, t.nama_lengkap, t.asal_universitas,
                       t.rate_gaji_per_jam, t.tarif_per_sesi,
                       COUNT(p.id) AS total_sesi,
                       COALESCE(SUM(TIMESTAMPDIFF(MINUTE, p.jam_mulai, p.jam_selesai)) / 60, 0) AS total_jam,
                       COALESCE(SUM(
                           CASE
                               WHEN t.tarif_per_sesi > 0 THEN t.tarif_per_sesi
                               ELSE (TIMESTAMPDIFF(MINUTE, p.jam_mulai, p.jam_selesai) / 60) * t.rate_gaji_per_jam
                           END
                       ), 0) AS total_honorarium
                FROM `tentor` t
                LEFT JOIN `pertemuan` p
                    ON p.tentor_id = t.id
                   AND MONTH(p.tanggal) = :month
                   AND YEAR(p.tanggal) = :year
                WHERE t.status_aktif = 1
                GROUP BY t.id, t.nama_lengkap, t.asal_universitas, t.rate_gaji_per_jam, t.tarif_per_sesi
                ORDER BY t.nama_lengkap ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['month' => $month, 'year' => $year]);
        return $stmt->fetchAll();
    }

    public function getTeachingPerformance(int $tentorId, int $month, int $year): array
    {
        $sql = "SELECT COUNT(DISTINCT p.id) AS total_sesi,
                       COALESCE(SUM(TIMESTAMPDIFF(MINUTE, p.jam_mulai, p.jam_selesai)) / 60, 0) AS total_jam,
                       COUNT(prs.id) AS total_presensi,
                       SUM(CASE WHEN prs.status_kehadiran = 'hadir' THEN 1 ELSE 0 END) AS total_hadir
                FROM `pertemuan` p
                LEFT JOIN `presensi` prs ON prs.pertemuan_id = p.id
                WHERE p.tentor_id = :tentor_id
                  AND MONTH(p.tanggal) = :month
                  AND YEAR(p.tanggal) = :year";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tentor_id' => $tentorId,
            'month' => $month,
            'year' => $year,
        ]);
        $row = $stmt->fetch() ?: [];
        $totalPresensi = (int) ($row['total_presensi'] ?? 0);
        $totalHadir = (int) ($row['total_hadir'] ?? 0);

        return [
            'total_sesi' => (int) ($row['total_sesi'] ?? 0),
            'total_jam' => round((float) ($row['total_jam'] ?? 0), 1),
            'total_presensi' => $totalPresensi,
            'total_hadir' => $totalHadir,
            'persentase_kehadiran' => $totalPresensi > 0 ? round(($totalHadir / $totalPresensi) * 100, 1) : 0,
        ];
    }
}
