<?php

namespace App\Core;

use PDO;
use Exception;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = getDBConnection();
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY `{$this->primaryKey}` DESC");
        return $stmt->fetchAll();
    }

    public function find(int|string $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): int|string
    {
        $fields = array_keys($data);
        $columns = implode(', ', array_map(fn($f) => "`$f`", $fields));
        $placeholders = implode(', ', array_map(fn($f) => ":$f", $fields));

        $sql = "INSERT INTO `{$this->table}` ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    public function update(int|string $id, array $data): bool
    {
        $fields = array_keys($data);
        $setClause = implode(', ', array_map(fn($f) => "`$f` = :$f", $fields));

        $sql = "UPDATE `{$this->table}` SET $setClause WHERE `{$this->primaryKey}` = :_primary_id";
        $data['_primary_id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete(int|string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute(['id' => $id]);
    }
}
