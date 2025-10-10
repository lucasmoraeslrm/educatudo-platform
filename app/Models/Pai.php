<?php

namespace Educatudo\Models;

class Pai extends Model
{
    protected string $table = 'pais';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'usuario_id',
        'cpf',
        'telefone',
        'ativo'
    ];

    public function getByEscola(int $escolaId): array
    {
        $sql = "SELECT p.id, p.usuario_id, p.cpf, p.telefone, p.ativo, u.nome, u.email FROM {$this->table} p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE u.escola_id = :escola_id 
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function getAtivosByEscola(int $escolaId): array
    {
        $sql = "SELECT p.id, p.usuario_id, p.cpf, p.telefone, p.ativo, u.nome, u.email FROM {$this->table} p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE u.escola_id = :escola_id AND p.ativo = 1
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function findByUsuarioId(int $usuarioId): ?array
    {
        $sql = "SELECT p.*, u.nome, u.email FROM {$this->table} p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE p.usuario_id = :usuario_id";
        return $this->db->fetch($sql, ['usuario_id' => $usuarioId]);
    }

    public function findByCpf(string $cpf, int $escolaId): ?array
    {
        $sql = "SELECT p.id, p.usuario_id, p.cpf, p.telefone, p.ativo, u.nome, u.email FROM {$this->table} p 
                JOIN usuarios u ON p.usuario_id = u.id 
                WHERE p.cpf = :cpf AND u.escola_id = :escola_id AND p.ativo = 1";
        return $this->db->fetch($sql, [
            'cpf' => $cpf,
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
                    SUM(CASE WHEN p.ativo = 1 THEN 1 ELSE 0 END) as ativos,
                    SUM(CASE WHEN p.ativo = 0 THEN 1 ELSE 0 END) as inativos
                FROM {$this->table} p
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE u.escola_id = :escola_id";
        return $this->db->fetch($sql, ['escola_id' => $escolaId]) ?: [
            'total' => 0,
            'ativos' => 0,
            'inativos' => 0
        ];
    }

    // Métodos para gerenciar relacionamento com alunos
    public function getAlunos(int $paiId): array
    {
        $sql = "SELECT a.id, a.ra, u.nome, a.serie, a.ativo 
                FROM aluno_pai ap
                JOIN alunos a ON ap.aluno_id = a.id
                JOIN usuarios u ON a.usuario_id = u.id
                WHERE ap.pai_id = :pai_id
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['pai_id' => $paiId]);
    }

    public function addAluno(int $paiId, int $alunoId): bool
    {
        $sql = "INSERT INTO aluno_pai (pai_id, aluno_id) VALUES (:pai_id, :aluno_id)";
        return $this->db->query($sql, ['pai_id' => $paiId, 'aluno_id' => $alunoId]) !== false;
    }

    public function removeAluno(int $paiId, int $alunoId): bool
    {
        $sql = "DELETE FROM aluno_pai WHERE pai_id = :pai_id AND aluno_id = :aluno_id";
        return $this->db->query($sql, ['pai_id' => $paiId, 'aluno_id' => $alunoId]) !== false;
    }

    public function syncAlunos(int $paiId, array $alunosIds): bool
    {
        // Remove todos os alunos atuais
        $this->db->query("DELETE FROM aluno_pai WHERE pai_id = :pai_id", ['pai_id' => $paiId]);
        
        // Adiciona os novos alunos
        if (!empty($alunosIds)) {
            foreach ($alunosIds as $alunoId) {
                $this->addAluno($paiId, $alunoId);
            }
        }
        
        return true;
    }
}
