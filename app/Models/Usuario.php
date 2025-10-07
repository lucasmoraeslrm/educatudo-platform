<?php

namespace Educatudo\Models;

use Educatudo\Core\Database;

class Usuario extends Model
{
    protected string $table = 'usuarios';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'nome',
        'email',
        'senha_hash',
        'tipo',
        'escola_id'
    ];
    protected array $hidden = ['senha_hash', 'created_at', 'updated_at'];

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->fetch($sql, ['email' => $email]);
    }

    public function getByEscola(int $escolaId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE escola_id = :escola_id ORDER BY nome";
        return $this->db->fetchAll($sql, ['escola_id' => $escolaId]);
    }

    public function findByTipo(string $tipo, int $escolaId = null): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = :tipo";
        $params = ['tipo' => $tipo];
        
        if ($escolaId) {
            $sql .= " AND escola_id = :escola_id";
            $params['escola_id'] = $escolaId;
        }
        
        $sql .= " ORDER BY nome";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getSuperAdmins(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE tipo = 'super_admin' ORDER BY nome";
        return $this->db->fetchAll($sql);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function updateLastLogin(int $id): bool
    {
        $sql = "UPDATE {$this->table} SET ultimo_login = NOW() WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]) !== false;
    }

    public function updateStatus(int $id, bool $ativo): bool
    {
        // Como usuarios não tem coluna ativo diretamente, vamos usar uma abordagem diferente
        // ou simplesmente remover este método se não for necessário
        return true; // Placeholder - implementar conforme necessário
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = $this->hashPassword($newPassword);
        $sql = "UPDATE {$this->table} SET senha_hash = :senha_hash WHERE id = :id";
        return $this->db->query($sql, ['senha_hash' => $hash, 'id' => $id]) !== false;
    }

    public function getEstatisticas(int $escolaId = null): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN tipo = 'super_admin' THEN 1 ELSE 0 END) as super_admins,
                    SUM(CASE WHEN tipo = 'admin_escola' THEN 1 ELSE 0 END) as admin_escolas,
                    SUM(CASE WHEN tipo = 'professor' THEN 1 ELSE 0 END) as professores,
                    SUM(CASE WHEN tipo = 'aluno' THEN 1 ELSE 0 END) as alunos,
                    SUM(CASE WHEN tipo = 'pai' THEN 1 ELSE 0 END) as pais
                FROM {$this->table}";
        
        $params = [];
        if ($escolaId) {
            $sql .= " WHERE escola_id = :escola_id";
            $params['escola_id'] = $escolaId;
        }
        
        return $this->db->fetch($sql, $params) ?: [
            'total' => 0,
            'super_admins' => 0,
            'admin_escolas' => 0,
            'professores' => 0,
            'alunos' => 0,
            'pais' => 0
        ];
    }
}