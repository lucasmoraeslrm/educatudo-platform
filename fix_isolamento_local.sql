-- ================================================================
-- FIX: Isolamento por Escola - XAMPP Local
-- ================================================================
-- Execute este arquivo no phpMyAdmin local (localhost/phpmyadmin)
-- ================================================================

USE educatudo_platform;

-- 1. Verificar o problema atual
SELECT 'DIAGNÓSTICO: Verificando dados atuais' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Ver todas as escolas
SELECT * FROM escolas;

-- Ver todas as matérias e suas escolas
SELECT m.id, m.nome, m.escola_id, e.nome as escola_nome 
FROM materias m 
LEFT JOIN escolas e ON m.escola_id = e.id
ORDER BY e.nome, m.nome;

-- Ver todas as turmas e suas escolas
SELECT t.id, t.nome, t.serie, t.escola_id, e.nome as escola_nome 
FROM turmas t 
LEFT JOIN escolas e ON t.escola_id = e.id
ORDER BY e.nome, t.nome;

-- ================================================================
-- 2. Adicionar timestamps (se não existirem)
-- ================================================================
SELECT '' as '';
SELECT 'PASSO 1: Adicionando timestamps...' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Matérias - Se der erro, ignore (significa que já existe)
ALTER TABLE materias 
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE materias 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Turmas - Se der erro, ignore (significa que já existe)
ALTER TABLE turmas 
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE turmas 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

SELECT '✅ Timestamps adicionados!' as '';

-- ================================================================
-- 3. Verificar e remover duplicatas
-- ================================================================
SELECT '' as '';
SELECT 'PASSO 2: Verificando duplicatas...' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Duplicatas em matérias
SELECT 
    escola_id, 
    nome, 
    COUNT(*) as total,
    GROUP_CONCAT(id) as ids
FROM materias 
GROUP BY escola_id, nome 
HAVING COUNT(*) > 1;

-- Duplicatas em turmas
SELECT 
    escola_id, 
    nome,
    ano_letivo,
    serie,
    COUNT(*) as total,
    GROUP_CONCAT(id) as ids
FROM turmas 
GROUP BY escola_id, nome, ano_letivo, serie 
HAVING COUNT(*) > 1;

-- ================================================================
-- 4. Adicionar constraints de unicidade
-- ================================================================
SELECT '' as '';
SELECT 'PASSO 3: Adicionando constraints...' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Tentar adicionar constraint em materias
-- (vai falhar se já existir, mas tudo bem)
ALTER TABLE materias 
ADD UNIQUE KEY unique_materia_escola (escola_id, nome);

-- Tentar adicionar constraint em turmas
-- (vai falhar se já existir, mas tudo bem)
ALTER TABLE turmas 
ADD UNIQUE KEY unique_turma_escola (escola_id, nome, ano_letivo, serie);

SELECT '✅ Constraints configuradas!' as '';

-- ================================================================
-- 5. Verificação Final
-- ================================================================
SELECT '' as '';
SELECT 'VERIFICAÇÃO FINAL' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Resumo por escola
SELECT 
    e.id,
    e.nome as Escola,
    COUNT(DISTINCT m.id) as Materias,
    COUNT(DISTINCT t.id) as Turmas
FROM escolas e
LEFT JOIN materias m ON e.id = m.escola_id
LEFT JOIN turmas t ON e.id = t.escola_id
GROUP BY e.id, e.nome
ORDER BY e.nome;

-- Mostrar estrutura das tabelas
SELECT '' as '';
SELECT 'Estrutura da tabela MATERIAS:' as '';
SHOW CREATE TABLE materias;

SELECT '' as '';
SELECT 'Estrutura da tabela TURMAS:' as '';
SHOW CREATE TABLE turmas;

SELECT '' as '';
SELECT '✅ CONCLUÍDO!' as '';

