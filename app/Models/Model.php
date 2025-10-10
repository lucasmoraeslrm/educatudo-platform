<?php

namespace Educatudo\Models;

use Educatudo\Core\Database;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    public function find($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    public function findAll(array $conditions = [], string $orderBy = null, int $limit = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function create(array $data)
    {
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        
        $fields = array_keys($fillableData);
        $placeholders = array_map(fn($field) => ":{$field}", $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $result = $this->db->query($sql, $fillableData);
        
        if ($result !== false) {
            return (int) $this->db->lastInsertId();
        }
        
        return false;
    }

    public function update($id, array $data): bool
    {
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        
        $fields = array_map(fn($field) => "{$field} = :{$field}", array_keys($fillableData));
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        
        $fillableData['id'] = $id;
        
        return $this->db->query($sql, $fillableData) !== false;
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }

    protected function hideFields(array $data): array
    {
        return array_diff_key($data, array_flip($this->hidden));
    }
}
