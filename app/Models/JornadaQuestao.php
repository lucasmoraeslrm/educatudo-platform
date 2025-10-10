<?php

namespace Educatudo\Models;

class JornadaQuestao extends Model
{
    protected string $table = 'jornada_questoes';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'jornada_id',
        'questao_id',
        'ordem'
    ];

    public function getByJornada(int $jornadaId): array
    {
        $sql = "SELECT jq.*, q.pergunta, q.tipo, q.resposta_correta, q.explicacao,
                       l.titulo as lista_titulo, l.materia, l.serie
                FROM {$this->table} jq
                INNER JOIN questoes q ON jq.questao_id = q.id
                INNER JOIN listas_exercicios l ON q.lista_id = l.id
                WHERE jq.jornada_id = :jornada_id
                ORDER BY jq.ordem ASC";
        
        $questoes = $this->db->fetchAll($sql, ['jornada_id' => $jornadaId]);

        // Buscar alternativas para questões de múltipla escolha
        foreach ($questoes as &$questao) {
            if ($questao['tipo'] === 'multipla_escolha') {
                $sql = "SELECT * FROM alternativas 
                        WHERE questao_id = :questao_id 
                        ORDER BY letra ASC";
                $questao['alternativas'] = $this->db->fetchAll($sql, ['questao_id' => $questao['questao_id']]);
            }
        }

        return $questoes;
    }

    public function addQuestao(int $jornadaId, int $questaoId, int $ordem): ?int
    {
        // Verificar se já existe
        $sql = "SELECT id FROM {$this->table} 
                WHERE jornada_id = :jornada_id AND questao_id = :questao_id";
        $exists = $this->db->fetch($sql, [
            'jornada_id' => $jornadaId,
            'questao_id' => $questaoId
        ]);

        if ($exists) {
            return $exists['id'];
        }

        return $this->create([
            'jornada_id' => $jornadaId,
            'questao_id' => $questaoId,
            'ordem' => $ordem
        ]);
    }

    public function removeQuestao(int $id): bool
    {
        return $this->delete($id);
    }

    public function removeByJornadaAndQuestao(int $jornadaId, int $questaoId): bool
    {
        try {
            $sql = "DELETE FROM {$this->table} 
                    WHERE jornada_id = :jornada_id AND questao_id = :questao_id";
            $this->db->query($sql, [
                'jornada_id' => $jornadaId,
                'questao_id' => $questaoId
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao remover questão da jornada: " . $e->getMessage());
            return false;
        }
    }

    public function reordenar(int $jornadaId, array $ordens): bool
    {
        try {
            $this->db->beginTransaction();

            foreach ($ordens as $questaoId => $ordem) {
                $sql = "UPDATE {$this->table} 
                        SET ordem = :ordem 
                        WHERE jornada_id = :jornada_id AND questao_id = :questao_id";
                $this->db->query($sql, [
                    'ordem' => $ordem,
                    'jornada_id' => $jornadaId,
                    'questao_id' => $questaoId
                ]);
            }

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollback();
            error_log("Erro ao reordenar questões: " . $e->getMessage());
            return false;
        }
    }
}

