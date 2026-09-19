<?php

namespace App\Models;

use App\Core\Model;

class Pertemuan extends Model
{
    protected string $table = 'pertemuan';

    public function allWithDetails(): array
    {
        $sql = "SELECT p.*,
                       t.nama_lengkap AS tentor_nama,
                       k.nama AS kelas_nama, jg.nama AS jenjang_nama, pr.nama AS program_nama,
                       (SELECT COUNT(*) FROM `presensi` prs WHERE prs.pertemuan_id = p.id) AS total_presensi,
                       (SELECT COUNT(*) FROM `presensi` prs WHERE prs.pertemuan_id = p.id AND prs.status_kehadiran = 'hadir') AS total_hadir
                FROM `pertemuan` p
                JOIN `tentor` t ON t.id = p.tentor_id
                JOIN `jadwal` j ON j.id = p.jadwal_id
                JOIN `kelas` k ON k.id = j.kelas_id
                JOIN `jenjang` jg ON jg.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                ORDER BY p.tanggal DESC, p.nomor_pertemuan DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getNextNomorPertemuan(int $jadwalId): int
    {
        $sql = "SELECT MAX(nomor_pertemuan) FROM `pertemuan` WHERE `jadwal_id` = :jadwal_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['jadwal_id' => $jadwalId]);
        $max = (int) $stmt->fetchColumn();
        return $max + 1;
    }

    public function getPresensiList(int $pertemuanId): array
    {
        $sql = "SELECT prs.*, s.nama_lengkap AS siswa_nama, s.asal_sekolah
                FROM `presensi` prs
                JOIN `siswa` s ON s.id = prs.siswa_id
                WHERE prs.pertemuan_id = :pertemuan_id
                ORDER BY s.nama_lengkap ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pertemuan_id' => $pertemuanId]);
        return $stmt->fetchAll();
    }

    public function syncPresensi(int $pertemuanId, array $presensiItems): void
    {
        $this->db->beginTransaction();
        try {
            foreach ($presensiItems as $siswaId => $item) {
                $status = $item['status_kehadiran'] ?? 'none';
                $nilaiSikap = !empty($item['nilai_sikap']) ? $item['nilai_sikap'] : null;
                $nilaiAkademik = !empty($item['nilai_akademik']) ? $item['nilai_akademik'] : null;
                $catatan = !empty($item['catatan']) ? trim($item['catatan']) : null;

                // Cek apakah presensi sudah ada untuk pertemuan & siswa ini
                $stmtCheck = $this->db->prepare("SELECT id FROM `presensi` WHERE `pertemuan_id` = :pertemuan_id AND `siswa_id` = :siswa_id LIMIT 1");
                $stmtCheck->execute(['pertemuan_id' => $pertemuanId, 'siswa_id' => $siswaId]);
                $existingId = $stmtCheck->fetchColumn();

                if ($existingId) {
                    $stmtUpdate = $this->db->prepare(
                        "UPDATE `presensi`
                         SET `status_kehadiran` = :status,
                             `nilai_sikap` = :sikap,
                             `nilai_akademik` = :akademik,
                             `catatan` = :catatan,
                             `diubah_pada` = NOW()
                         WHERE `id` = :id"
                    );
                    $stmtUpdate->execute([
                        'status' => $status,
                        'sikap' => $nilaiSikap,
                        'akademik' => $nilaiAkademik,
                        'catatan' => $catatan,
                        'id' => $existingId
                    ]);
                } else {
                    $stmtInsert = $this->db->prepare(
                        "INSERT INTO `presensi` (`pertemuan_id`, `siswa_id`, `status_kehadiran`, `nilai_sikap`, `nilai_akademik`, `catatan`, `dibuat_pada`, `diubah_pada`)
                         VALUES (:pertemuan_id, :siswa_id, :status, :sikap, :akademik, :catatan, NOW(), NOW())"
                    );
                    $stmtInsert->execute([
                        'pertemuan_id' => $pertemuanId,
                        'siswa_id' => $siswaId,
                        'status' => $status,
                        'sikap' => $nilaiSikap,
                        'akademik' => $nilaiAkademik,
                        'catatan' => $catatan
                    ]);
                }
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
