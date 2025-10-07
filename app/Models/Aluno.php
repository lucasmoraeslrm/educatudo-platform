<?php

namespace Educatudo\Models;

use Educatudo\Core\Database;

class Aluno extends Model
{
    protected string $table = 'alunos';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'usuario_id',
        'ra',
        'turma_id',
        'serie',
        'data_nasc',
        'responsavel_id',
        'ativo'
    ];
    protected array $hidden = ['created_at', 'updated_at'];

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function findByRa(string $ra, int $escolaId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE ra = :ra AND escola_id = :escola_id AND ativo = 1";
        return $this->db->fetch($sql, [
            'ra' => $ra,
            'escola_id' => $escolaId
        ]);
    }

    public function findByUsuarioId(int $usuarioId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id";
        return $this->db->fetch($sql, ['usuario_id' => $usuarioId]);
    }

    public function getByEscola(int $escolaId): array
    {
        $sql = "SELECT a.*, u.nome, u.email, t.nome as turma_nome FROM {$this->table} a 
                JOIN usuarios u ON a.usuario_id = u.id 
                LEFT JOIN turmas t ON a.turma_id = t.id
                WHERE u.escola_id = :escola_id 
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function getAtivosByEscola(int $escolaId): array
    {
        $sql = "SELECT a.*, u.nome, u.email, t.nome as turma_nome FROM {$this->table} a 
                JOIN usuarios u ON a.usuario_id = u.id 
                LEFT JOIN turmas t ON a.turma_id = t.id
                WHERE u.escola_id = :escola_id AND a.ativo = 1
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function getByTurma(int $turmaId): array
    {
        $sql = "SELECT a.*, u.nome, u.email FROM {$this->table} a 
                JOIN usuarios u ON a.usuario_id = u.id 
                WHERE a.turma_id = :turma_id AND a.ativo = 1
                ORDER BY u.nome";
        return $this->db->fetchAll($sql, ['turma_id' => $turmaId]);
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
                    SUM(CASE WHEN a.ativo = 1 THEN 1 ELSE 0 END) as ativos,
                    SUM(CASE WHEN a.ativo = 0 THEN 1 ELSE 0 END) as inativos
                FROM {$this->table} a
                JOIN usuarios u ON a.usuario_id = u.id
                WHERE u.escola_id = :escola_id";
        return $this->db->fetch($sql, ['escola_id' => $escolaId]) ?: [
            'total' => 0,
            'ativos' => 0,
            'inativos' => 0
        ];
    }
}