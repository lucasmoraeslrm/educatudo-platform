<?php

namespace Educatudo\Models;

use Educatudo\Core\Database;

class Escola extends Model
{
    protected string $table = 'escolas';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'nome',
        'subdominio',
        'cnpj',
        'endereco',
        'telefone',
        'email',
        'plano',
        'ativa',
        'created_at',
        'logo',
        'cor_primaria',
        'cor_secundaria',
        'configuracoes',
        'observacoes'
    ];
    protected array $hidden = ['created_at', 'updated_at'];

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function findBySubdomain(string $subdomain): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE subdominio = :subdominio AND ativa = 1";
        return $this->db->fetch($sql, ['subdominio' => $subdomain]);
    }

    public function findByCnpj(string $cnpj): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE cnpj = :cnpj";
        return $this->db->fetch($sql, ['cnpj' => $cnpj]);
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nome";
        return $this->db->fetchAll($sql);
    }

    public function getAllByPlano(string $plano): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE plano = :plano AND ativa = 1 ORDER BY nome";
        return $this->db->fetchAll($sql, ['plano' => $plano]);
    }

    public function updateStatus(int $id, bool $ativa): bool
    {
        $sql = "UPDATE {$this->table} SET ativa = :ativa WHERE id = :id";
        return $this->db->query($sql, ['ativa' => $ativa ? 1 : 0, 'id' => $id]) !== false;
    }

    public function updatePlano(int $id, string $plano): bool
    {
        $sql = "UPDATE {$this->table} SET plano = :plano WHERE id = :id";
        return $this->db->query($sql, ['plano' => $plano, 'id' => $id]) !== false;
    }

    public function getEstatisticas(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN ativa = 1 THEN 1 ELSE 0 END) as ativas,
                    SUM(CASE WHEN ativa = 0 THEN 1 ELSE 0 END) as inativas,
                    SUM(CASE WHEN plano = 'basico' THEN 1 ELSE 0 END) as plano_basico,
                    SUM(CASE WHEN plano = 'avancado' THEN 1 ELSE 0 END) as plano_avancado,
                    SUM(CASE WHEN plano = 'premium' THEN 1 ELSE 0 END) as plano_premium
                FROM {$this->table}";
        
        return $this->db->fetch($sql) ?: [
            'total' => 0,
            'ativas' => 0,
            'inativas' => 0,
            'plano_basico' => 0,
            'plano_avancado' => 0,
            'plano_premium' => 0
        ];
    }

    public function getConfiguracoes(int $id): array
    {
        $escola = $this->find($id);
        if (!$escola) {
            return [];
        }
        
        $configuracoes = json_decode($escola['configuracoes'] ?? '{}', true);
        return $configuracoes ?: [];
    }

    public function updateConfiguracoes(int $id, array $configuracoes): bool
    {
        $sql = "UPDATE {$this->table} SET configuracoes = :configuracoes WHERE id = :id";
        return $this->db->query($sql, [
            'configuracoes' => json_encode($configuracoes),
            'id' => $id
        ]) !== false;
    }
}