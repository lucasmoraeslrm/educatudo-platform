-- ================================================================
-- MIGRAÇÃO: Isolamento de Matérias por Escola
-- ================================================================
-- Este script atualiza a tabela materias para garantir que cada
-- escola tenha suas próprias matérias independentes
-- ================================================================
-- IMPORTANTE: Execute em seu banco REMOTO via phpMyAdmin
-- ================================================================

USE educatudo_platform;

-- ================================================================
-- PASSO 1: Adicionar timestamps (se ainda não existem)
-- ================================================================
SELECT 'PASSO 1: Adicionando campos de timestamp...' as status;

-- Verificar e adicionar created_at
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'materias' 
  AND COLUMN_NAME = 'created_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE materias ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'SELECT "Campo created_at já existe" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verificar e adicionar updated_at
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'materias' 
  AND COLUMN_NAME = 'updated_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE materias ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    'SELECT "Campo updated_at já existe" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT 'Timestamps adicionados com sucesso!' as status;

-- ================================================================
-- PASSO 2: Verificar duplicatas (antes de adicionar constraint)
-- ================================================================
SELECT 'PASSO 2: Verificando duplicatas...' as status;

SELECT 
    escola_id, 
    nome, 
    COUNT(*) as total,
    GROUP_CONCAT(id) as ids_duplicados
FROM materias 
GROUP BY escola_id, nome 
HAVING COUNT(*) > 1;

-- Se a query acima retornar resultados, você tem duplicatas!
-- Remova-as manualmente antes de continuar.

-- ================================================================
-- PASSO 3: Adicionar constraint de unicidade
-- ================================================================
SELECT 'PASSO 3: Adicionando constraint de unicidade...' as status;

-- Verificar se a constraint já existe
SET @constraint_exists = 0;
SELECT COUNT(*) INTO @constraint_exists
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'educatudo_platform'
  AND TABLE_NAME = 'materias'
  AND CONSTRAINT_NAME = 'unique_materia_escola';

-- Adicionar constraint se não existir
SET @query = IF(@constraint_exists = 0,
    'ALTER TABLE materias ADD UNIQUE KEY unique_materia_escola (escola_id, nome)',
    'SELECT "Constraint unique_materia_escola já existe" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT 'Constraint adicionada com sucesso!' as status;

-- ================================================================
-- PASSO 4: Verificar estrutura final
-- ================================================================
SELECT 'PASSO 4: Verificando estrutura final...' as status;

DESCRIBE materias;

-- ================================================================
-- PASSO 5: Verificar isolamento (teste)
-- ================================================================
SELECT 'PASSO 5: Verificando isolamento de matérias por escola...' as status;

SELECT 
    e.id as escola_id,
    e.nome as escola_nome,
    COUNT(m.id) as total_materias,
    GROUP_CONCAT(m.nome ORDER BY m.nome SEPARATOR ', ') as materias
FROM escolas e
LEFT JOIN materias m ON e.id = m.escola_id
GROUP BY e.id, e.nome
ORDER BY e.nome;

-- ================================================================
-- CONCLUSÃO
-- ================================================================
SELECT '✅ MIGRAÇÃO CONCLUÍDA COM SUCESSO!' as status;
SELECT 'Agora cada escola tem suas próprias matérias independentes.' as info;
SELECT 'Teste criando matérias nas diferentes escolas para verificar.' as proximos_passos;

