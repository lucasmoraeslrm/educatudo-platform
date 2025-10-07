<?php

namespace Educatudo\Models;

class Turma extends Model
{
    protected string $table = 'turmas';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'escola_id',
        'nome',
        'ano_letivo',
        'serie',
        'ativo'
    ];

    public function getByEscola(int $escolaId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE escola_id = :escola_id 
                ORDER BY serie, nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function getAtivasByEscola(int $escolaId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE escola_id = :escola_id AND ativo = 1
                ORDER BY serie, nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function findByNome(string $nome, int $escolaId): ?array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nome = :nome AND escola_id = :escola_id AND ativo = 1";
        return $this->db->fetch($sql, [
            'nome' => $nome,
            'escola_id' => $escolaId
        ]);
    }

    public function updateStatus(int $id, bool $ativo): bool
    {
        $sql = "UPDATE {$this->table} SET ativo = :ativo WHERE id = :id";
        return $this->db->query($sql, ['ativo' => $ativo ? 1 : 0, 'id' => $id]) !== false;
    }

    public function getEstatisticas(int $escolaId): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN ativo = 1 THEN 1 ELSE 0 END) as ativas,
                    SUM(CASE WHEN ativo = 0 THEN 1 ELSE 0 END) as inativas
                FROM {$this->table} 
                WHERE escola_id = :escola_id";
        return $this->db->fetch($sql, ['escola_id' => $escolaId]) ?: [
            'total' => 0,
            'ativas' => 0,
            'inativas' => 0
        ];
    }
}
