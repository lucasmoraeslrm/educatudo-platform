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
        'ativo',
        'created_at',
        'updated_at'
    ];

    public function getByEscola(int $escolaId): array
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE escola_id = :escola_id 
                    ORDER BY serie, nome";
            $result = $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
            
            // Garantir que retorne array
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            error_log("Erro ao buscar turmas da escola {$escolaId}: " . $e->getMessage());
            return [];
        }
    }

    public function getAtivasByEscola(int $escolaId): array
    {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE escola_id = :escola_id AND ativo = 1
                    ORDER BY serie, nome";
            $result = $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
            
            // Garantir que retorne array
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            error_log("Erro ao buscar turmas ativas da escola {$escolaId}: " . $e->getMessage());
            return [];
        }
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
