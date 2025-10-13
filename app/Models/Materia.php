<?php

namespace Educatudo\Models;

class Materia extends Model
{
    protected string $table = 'materias';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'escola_id',
        'nome',
        'professor_id'
    ];

    public function getByEscola(int $escolaId): array
    {
        try {
            $sql = "SELECT m.*, u.nome as professor_nome FROM {$this->table} m 
                    LEFT JOIN professores p ON m.professor_id = p.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    WHERE m.escola_id = :escola_id 
                    ORDER BY m.nome";
            $result = $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
            
            // Garantir que retorne array
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            error_log("Erro ao buscar matérias da escola {$escolaId}: " . $e->getMessage());
            return [];
        }
    }

    public function getAtivasByEscola(int $escolaId): array
    {
        try {
            $sql = "SELECT m.*, u.nome as professor_nome FROM {$this->table} m 
                    LEFT JOIN professores p ON m.professor_id = p.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    WHERE m.escola_id = :escola_id
                    ORDER BY m.nome";
            $result = $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
            
            // Garantir que retorne array
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            error_log("Erro ao buscar matérias ativas da escola {$escolaId}: " . $e->getMessage());
            return [];
        }
    }

    public function findByNome(string $nome, int $escolaId): ?array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE nome = :nome AND escola_id = :escola_id";
        return $this->db->fetch($sql, [
            'nome' => $nome,
            'escola_id' => $escolaId
        ]);
    }

    public function findByNameAndEscola(string $nome, int $escolaId): ?array
    {
        return $this->findByNome($nome, $escolaId);
    }

    public function getByProfessor(int $professorId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE professor_id = :professor_id
                ORDER BY nome";
        return $this->db->fetchAll($sql, ['professor_id' => $professorId]);
    }

    public function getEstatisticas(int $escolaId): array
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE escola_id = :escola_id";
        $result = $this->db->fetch($sql, ['escola_id' => $escolaId]);
        return [
            'total' => $result['total'] ?? 0,
            'ativas' => $result['total'] ?? 0,
            'inativas' => 0
        ];
    }
}
