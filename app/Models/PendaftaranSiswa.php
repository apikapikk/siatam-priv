<?php

namespace App\Models;

use App\Core\Model;

class PendaftaranSiswa extends Model
{
    protected string $table = 'pendaftaran_siswa';

    public function allWithDetails(): array
    {
        $sql = "SELECT ps.*,
                       s.nama_lengkap AS siswa_nama, s.asal_sekolah,
                       k.nama AS kelas_nama, j.nama AS jenjang_nama, pr.nama AS program_nama,
                       pk.nama AS paket_nama
                FROM `pendaftaran_siswa` ps
                JOIN `siswa` s ON s.id = ps.siswa_id
                JOIN `kelas` k ON k.id = ps.kelas_id
                JOIN `jenjang` j ON j.id = k.jenjang_id
                JOIN `program` pr ON pr.id = k.program_id
                LEFT JOIN `paket` pk ON pk.id = ps.paket_id
                ORDER BY ps.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findWithDetails(int $id): ?array
    {
        $sql = "SELECT ps.*,
                       s.nama_lengkap AS siswa_nama,
                       k.nama AS kelas_nama,
                       pk.nama AS paket_nama
                FROM `pendaftaran_siswa` ps
                JOIN `siswa` s ON s.id = ps.siswa_id
                JOIN `kelas` k ON k.id = ps.kelas_id
                LEFT JOIN `paket` pk ON pk.id = ps.paket_id
                WHERE ps.id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function deactivateActiveRegistrations(int $siswaId): void
    {
        $sql = "UPDATE `pendaftaran_siswa`
                SET `status` = 'selesai', `tanggal_selesai` = CURRENT_DATE()
                WHERE `siswa_id` = :siswa_id AND `status` = 'aktif'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['siswa_id' => $siswaId]);
    }
}
