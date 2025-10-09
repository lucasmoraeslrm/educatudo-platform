-- ================================================================
-- CORRIGIR BANCO LOCAL - EXECUTE AGORA
-- ================================================================
-- 1. Acesse: http://localhost/phpmyadmin
-- 2. Selecione o database: educatudo_platform
-- 3. Clique em "SQL"
-- 4. Copie e cole TUDO deste arquivo
-- 5. Clique em "Executar"
-- ================================================================

USE educatudo_platform;

-- ================================================================
-- PARTE 1: Adicionar colunas de timestamp
-- ================================================================

-- Adicionar em MATERIAS (ignora erro se já existir)
ALTER TABLE materias ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE materias ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Adicionar em TURMAS (ignora erro se já existir)
ALTER TABLE turmas ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE turmas ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ================================================================
-- PARTE 2: Adicionar constraints de unicidade
-- ================================================================

-- Constraint para MATERIAS (ignora erro se já existir)
ALTER TABLE materias ADD UNIQUE KEY unique_materia_escola (escola_id, nome);

-- Constraint para TURMAS (ignora erro se já existir)
ALTER TABLE turmas ADD UNIQUE KEY unique_turma_escola (escola_id, nome, ano_letivo, serie);

-- ================================================================
-- VERIFICAÇÃO: Ver o resultado
-- ================================================================

-- Mostrar quantas matérias cada escola tem
SELECT 
    e.nome as Escola,
    COUNT(m.id) as Total_Materias
FROM escolas e
LEFT JOIN materias m ON e.id = m.escola_id
GROUP BY e.id, e.nome;

-- Mostrar quantas turmas cada escola tem
SELECT 
    e.nome as Escola,
    COUNT(t.id) as Total_Turmas
FROM escolas e
LEFT JOIN turmas t ON e.id = t.escola_id
GROUP BY e.id, e.nome;

-- ================================================================
-- CONCLUÍDO!
-- ================================================================

