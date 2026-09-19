<?php

namespace App\Models;

use App\Core\Model;

class Siswa extends Model
{
    protected string $table = 'siswa';

    public function allWithParentsAndClass(): array
    {
        $sql = "SELECT s.*,
                       (
                           SELECT GROUP_CONCAT(CONCAT(ot.nama_lengkap, ' (', sot.hubungan, ')') SEPARATOR ', ')
                           FROM `siswa_orang_tua` sot
                           JOIN `orang_tua` ot ON ot.id = sot.orang_tua_id
                           WHERE sot.siswa_id = s.id
                       ) AS daftar_orang_tua,
                       (
                           SELECT k.nama
                           FROM `pendaftaran_siswa` ps
                           JOIN `kelas` k ON k.id = ps.kelas_id
                           WHERE ps.siswa_id = s.id AND ps.status = 'aktif'
                           ORDER BY ps.id DESC LIMIT 1
                       ) AS kelas_aktif
                FROM `siswa` s
                ORDER BY s.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getParents(int $siswaId): array
    {
        $sql = "SELECT ot.*, sot.id AS relasi_id, sot.hubungan
                FROM `siswa_orang_tua` sot
                JOIN `orang_tua` ot ON ot.id = sot.orang_tua_id
                WHERE sot.siswa_id = :siswa_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['siswa_id' => $siswaId]);
        return $stmt->fetchAll();
    }

    public function syncParents(int $siswaId, array $parentsData): void
    {
        $this->db->beginTransaction();

        try {
            // Hapus relasi lama
            $stmtDeleteRel = $this->db->prepare("DELETE FROM `siswa_orang_tua` WHERE `siswa_id` = :siswa_id");
            $stmtDeleteRel->execute(['siswa_id' => $siswaId]);

            foreach ($parentsData as $parent) {
                $namaLengkap = trim($parent['nama_lengkap'] ?? '');
                $nomorTelepon = trim($parent['nomor_telepon'] ?? '');
                $hubungan = trim($parent['hubungan'] ?? 'Wali');

                if (empty($namaLengkap) || empty($nomorTelepon)) {
                    continue;
                }

                // Buat data orang tua baru
                $stmtOrtu = $this->db->prepare(
                    "INSERT INTO `orang_tua` (`nama_lengkap`, `nomor_telepon`, `dibuat_pada`, `diubah_pada`)
                     VALUES (:nama, :telepon, NOW(), NOW())"
                );
                $stmtOrtu->execute([
                    'nama' => $namaLengkap,
                    'telepon' => $nomorTelepon
                ]);
                $ortuId = $this->db->lastInsertId();

                // Buat relasi siswa_orang_tua
                $stmtRel = $this->db->prepare(
                    "INSERT INTO `siswa_orang_tua` (`siswa_id`, `orang_tua_id`, `hubungan`)
                     VALUES (:siswa_id, :orang_tua_id, :hubungan)"
                );
                $stmtRel->execute([
                    'siswa_id' => $siswaId,
                    'orang_tua_id' => $ortuId,
                    'hubungan' => $hubungan
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
