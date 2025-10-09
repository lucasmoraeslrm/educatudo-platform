-- ================================================================
-- MIGRAÇÃO COMPLETA: Isolamento Total por Escola
-- ================================================================
-- Este script garante que cada escola tenha:
-- ✅ Suas próprias MATÉRIAS independentes
-- ✅ Suas próprias TURMAS independentes
-- ✅ Seus próprios USUÁRIOS (já estava correto)
-- ================================================================
-- IMPORTANTE: Execute em seu banco REMOTO via phpMyAdmin
-- ================================================================

USE educatudo_platform;

-- ================================================================
-- PARTE 1: MATÉRIAS
-- ================================================================
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';
SELECT '📚 PARTE 1: CONFIGURANDO MATÉRIAS' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- 1.1: Adicionar timestamps em MATERIAS
SELECT '1.1: Adicionando timestamps em materias...' as status;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'materias' 
  AND COLUMN_NAME = 'created_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE materias ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'SELECT "Campo created_at já existe em materias" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'materias' 
  AND COLUMN_NAME = 'updated_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE materias ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    'SELECT "Campo updated_at já existe em materias" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 1.2: Verificar duplicatas em MATERIAS
SELECT '1.2: Verificando duplicatas em materias...' as status;

SELECT 
    CONCAT('⚠️ DUPLICATA: ', nome, ' na escola ID ', escola_id) as alerta
FROM (
    SELECT escola_id, nome, COUNT(*) as total
    FROM materias 
    GROUP BY escola_id, nome 
    HAVING COUNT(*) > 1
) AS duplicatas;

-- 1.3: Adicionar constraint em MATERIAS
SELECT '1.3: Adicionando constraint em materias...' as status;

SET @constraint_exists = 0;
SELECT COUNT(*) INTO @constraint_exists
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'educatudo_platform'
  AND TABLE_NAME = 'materias'
  AND CONSTRAINT_NAME = 'unique_materia_escola';

SET @query = IF(@constraint_exists = 0,
    'ALTER TABLE materias ADD UNIQUE KEY unique_materia_escola (escola_id, nome)',
    'SELECT "Constraint unique_materia_escola já existe" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT '✅ Matérias configuradas!' as status;

-- ================================================================
-- PARTE 2: TURMAS
-- ================================================================
SELECT '' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';
SELECT '🎓 PARTE 2: CONFIGURANDO TURMAS' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- 2.1: Adicionar timestamps em TURMAS
SELECT '2.1: Adicionando timestamps em turmas...' as status;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'turmas' 
  AND COLUMN_NAME = 'created_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE turmas ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'SELECT "Campo created_at já existe em turmas" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'educatudo_platform' 
  AND TABLE_NAME = 'turmas' 
  AND COLUMN_NAME = 'updated_at';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE turmas ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    'SELECT "Campo updated_at já existe em turmas" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2.2: Verificar duplicatas em TURMAS
SELECT '2.2: Verificando duplicatas em turmas...' as status;

SELECT 
    CONCAT('⚠️ DUPLICATA: ', nome, ' (', serie, ') na escola ID ', escola_id) as alerta
FROM (
    SELECT escola_id, nome, ano_letivo, serie, COUNT(*) as total
    FROM turmas 
    GROUP BY escola_id, nome, ano_letivo, serie 
    HAVING COUNT(*) > 1
) AS duplicatas;

-- 2.3: Adicionar constraint em TURMAS
SELECT '2.3: Adicionando constraint em turmas...' as status;

SET @constraint_exists = 0;
SELECT COUNT(*) INTO @constraint_exists
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'educatudo_platform'
  AND TABLE_NAME = 'turmas'
  AND CONSTRAINT_NAME = 'unique_turma_escola';

SET @query = IF(@constraint_exists = 0,
    'ALTER TABLE turmas ADD UNIQUE KEY unique_turma_escola (escola_id, nome, ano_letivo, serie)',
    'SELECT "Constraint unique_turma_escola já existe" as info');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SELECT '✅ Turmas configuradas!' as status;

-- ================================================================
-- VERIFICAÇÃO FINAL
-- ================================================================
SELECT '' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';
SELECT '🔍 VERIFICAÇÃO FINAL' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';

-- Resumo por escola
SELECT 
    e.id as 'ID',
    e.nome as 'Escola',
    COUNT(DISTINCT u.id) as 'Usuários',
    COUNT(DISTINCT m.id) as 'Matérias',
    COUNT(DISTINCT t.id) as 'Turmas',
    e.plano as 'Plano'
FROM escolas e
LEFT JOIN usuarios u ON e.id = u.escola_id
LEFT JOIN materias m ON e.id = m.escola_id
LEFT JOIN turmas t ON e.id = t.escola_id
GROUP BY e.id, e.nome, e.plano
ORDER BY e.nome;

-- ================================================================
-- CONCLUSÃO
-- ================================================================
SELECT '' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';
SELECT '✅ MIGRAÇÃO COMPLETA CONCLUÍDA!' as '';
SELECT '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━' as '';
SELECT '' as '';
SELECT 'Cada escola agora tem:' as info;
SELECT '✓ Usuários isolados' as info;
SELECT '✓ Matérias isoladas' as info;
SELECT '✓ Turmas isoladas' as info;
SELECT '' as '';
SELECT 'Teste criando registros em diferentes escolas!' as proximos_passos;

