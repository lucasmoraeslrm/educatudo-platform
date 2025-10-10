<?php

namespace Educatudo\Models;

class ListaExercicio extends Model
{
    protected string $table = 'listas_exercicios';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'titulo',
        'materia',
        'serie',
        'nivel_dificuldade',
        'total_questoes'
    ];

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getByMateria(string $materia): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE materia = :materia ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['materia' => $materia]);
    }

    public function getBySerie(string $serie): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE serie = :serie ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['serie' => $serie]);
    }

    public function getByNivel(string $nivel): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE nivel_dificuldade = :nivel ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['nivel' => $nivel]);
    }

    public function getWithQuestoes(int $id): ?array
    {
        $lista = $this->find($id);
        
        if (!$lista) {
            return null;
        }

        $questaoModel = new Questao($this->db);
        $lista['questoes'] = $questaoModel->getByLista($id);

        return $lista;
    }

    public function getEstatisticas(): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_listas,
                    SUM(total_questoes) as total_questoes,
                    COUNT(DISTINCT materia) as total_materias
                FROM {$this->table}";
        
        $result = $this->db->fetch($sql);
        
        return [
            'total_listas' => $result['total_listas'] ?? 0,
            'total_questoes' => $result['total_questoes'] ?? 0,
            'total_materias' => $result['total_materias'] ?? 0
        ];
    }

    public function updateTotalQuestoes(int $listaId): bool
    {
        $sql = "UPDATE {$this->table} 
                SET total_questoes = (
                    SELECT COUNT(*) FROM questoes WHERE lista_id = :lista_id
                )
                WHERE id = :lista_id";
        
        try {
            $this->db->query($sql, ['lista_id' => $listaId]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao atualizar total de questões: " . $e->getMessage());
            return false;
        }
    }
}

