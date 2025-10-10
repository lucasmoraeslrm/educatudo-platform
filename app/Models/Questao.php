<?php

namespace Educatudo\Models;

class Questao extends Model
{
    protected string $table = 'questoes';
    protected string $primaryKey = 'id';

    protected array $fillable = [
        'lista_id',
        'ordem',
        'pergunta',
        'tipo',
        'resposta_correta',
        'explicacao'
    ];

    public function getByLista(int $listaId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE lista_id = :lista_id 
                ORDER BY ordem ASC";
        $questoes = $this->db->fetchAll($sql, ['lista_id' => $listaId]);

        // Buscar alternativas para cada questão de múltipla escolha
        foreach ($questoes as &$questao) {
            if ($questao['tipo'] === 'multipla_escolha') {
                $questao['alternativas'] = $this->getAlternativas($questao['id']);
            }
        }

        return $questoes;
    }

    public function findWithAlternativas(int $id): ?array
    {
        $questao = $this->find($id);
        
        if (!$questao) {
            return null;
        }

        if ($questao['tipo'] === 'multipla_escolha') {
            $questao['alternativas'] = $this->getAlternativas($id);
        }

        return $questao;
    }

    public function getAlternativas(int $questaoId): array
    {
        $sql = "SELECT * FROM alternativas 
                WHERE questao_id = :questao_id 
                ORDER BY letra ASC";
        return $this->db->fetchAll($sql, ['questao_id' => $questaoId]);
    }

    public function createWithAlternativas(array $questaoData, array $alternativas = [], bool $useTransaction = true): ?int
    {
        try {
            // Só iniciar transação se não houver uma ativa
            $startedTransaction = false;
            if ($useTransaction && !$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $startedTransaction = true;
            }

            // Criar questão
            $questaoId = $this->create($questaoData);
            
            if (!$questaoId) {
                throw new \Exception('Erro ao criar questão');
            }

            // Se for múltipla escolha, criar alternativas
            if ($questaoData['tipo'] === 'multipla_escolha' && !empty($alternativas)) {
                foreach ($alternativas as $letra => $texto) {
                    $sql = "INSERT INTO alternativas (questao_id, letra, texto) 
                            VALUES (:questao_id, :letra, :texto)";
                    $this->db->query($sql, [
                        'questao_id' => $questaoId,
                        'letra' => $letra,
                        'texto' => $texto
                    ]);
                }
            }

            // Só fazer commit se iniciamos a transação aqui
            if ($startedTransaction) {
                $this->db->commit();
            }
            
            return $questaoId;

        } catch (\Exception $e) {
            // Só fazer rollback se iniciamos a transação aqui
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Erro ao criar questão com alternativas: " . $e->getMessage());
            throw $e; // Re-throw para o controller tratar
        }
    }

    public function deleteWithAlternativas(int $id, bool $useTransaction = true): bool
    {
        try {
            // Só iniciar transação se não houver uma ativa
            $startedTransaction = false;
            if ($useTransaction && !$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $startedTransaction = true;
            }

            // Deletar alternativas primeiro
            $sql = "DELETE FROM alternativas WHERE questao_id = :questao_id";
            $this->db->query($sql, ['questao_id' => $id]);

            // Deletar questão
            $deleted = $this->delete($id);

            // Só fazer commit se iniciamos a transação aqui
            if ($startedTransaction) {
                $this->db->commit();
            }
            
            return $deleted;

        } catch (\Exception $e) {
            // Só fazer rollback se iniciamos a transação aqui
            if ($startedTransaction && $this->db->inTransaction()) {
                $this->db->rollback();
            }
            error_log("Erro ao deletar questão: " . $e->getMessage());
            return false;
        }
    }
}

