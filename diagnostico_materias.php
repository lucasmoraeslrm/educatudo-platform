<?php
/**
 * DIAGNÓSTICO: Tabela Materias
 * 
 * Execute este arquivo no navegador para verificar o estado da tabela materias
 * URL: http://localhost/educatudo/diagnostico_materias.php
 */

require 'vendor/autoload.php';
require 'config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - Tabela Materias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">🔍 Diagnóstico - Tabela Materias</h1>
        
        <?php
        try {
            $app = Educatudo\Core\App::getInstance();
            $db = new Educatudo\Core\Database($app);
            
            echo '<div class="alert alert-success"><strong>✅ Conexão com banco OK!</strong></div>';
            
            // 1. Verificar se a tabela existe
            echo '<div class="card mb-3">';
            echo '<div class="card-header"><h5>1️⃣ Verificando se a tabela existe</h5></div>';
            echo '<div class="card-body">';
            
            $tableExists = $db->fetch("SHOW TABLES LIKE 'materias'");
            if ($tableExists) {
                echo '<p class="success">✅ Tabela "materias" existe</p>';
            } else {
                echo '<p class="error">❌ Tabela "materias" NÃO existe!</p>';
                echo '<p>Você precisa executar o arquivo database/schema.sql no seu banco de dados.</p>';
            }
            echo '</div></div>';
            
            if ($tableExists) {
                // 2. Verificar estrutura da tabela
                echo '<div class="card mb-3">';
                echo '<div class="card-header"><h5>2️⃣ Estrutura da tabela</h5></div>';
                echo '<div class="card-body">';
                
                $columns = $db->fetchAll("DESCRIBE materias");
                echo '<table class="table table-sm table-bordered">';
                echo '<thead><tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr></thead>';
                echo '<tbody>';
                
                $requiredColumns = ['id', 'escola_id', 'nome', 'professor_id', 'created_at', 'updated_at'];
                $foundColumns = [];
                
                foreach ($columns as $col) {
                    $foundColumns[] = $col['Field'];
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($col['Field']) . '</td>';
                    echo '<td>' . htmlspecialchars($col['Type']) . '</td>';
                    echo '<td>' . htmlspecialchars($col['Null']) . '</td>';
                    echo '<td>' . htmlspecialchars($col['Key']) . '</td>';
                    echo '<td>' . htmlspecialchars($col['Default'] ?? 'NULL') . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
                
                // Verificar colunas ausentes
                $missingColumns = array_diff($requiredColumns, $foundColumns);
                if (empty($missingColumns)) {
                    echo '<p class="success">✅ Todas as colunas necessárias estão presentes</p>';
                } else {
                    echo '<p class="error">❌ Colunas ausentes: ' . implode(', ', $missingColumns) . '</p>';
                    echo '<p class="warning">⚠️ Você precisa executar o arquivo database/migracao_materias.sql</p>';
                }
                
                echo '</div></div>';
                
                // 3. Verificar constraints
                echo '<div class="card mb-3">';
                echo '<div class="card-header"><h5>3️⃣ Constraints e Índices</h5></div>';
                echo '<div class="card-body">';
                
                $constraints = $db->fetchAll("SHOW INDEX FROM materias");
                echo '<table class="table table-sm table-bordered">';
                echo '<thead><tr><th>Nome</th><th>Coluna</th><th>Único</th></tr></thead>';
                echo '<tbody>';
                
                $hasUniqueConstraint = false;
                foreach ($constraints as $idx) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($idx['Key_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($idx['Column_name']) . '</td>';
                    echo '<td>' . ($idx['Non_unique'] == 0 ? 'Sim' : 'Não') . '</td>';
                    echo '</tr>';
                    
                    if ($idx['Key_name'] === 'unique_materia_escola') {
                        $hasUniqueConstraint = true;
                    }
                }
                echo '</tbody></table>';
                
                if ($hasUniqueConstraint) {
                    echo '<p class="success">✅ Constraint unique_materia_escola existe</p>';
                } else {
                    echo '<p class="warning">⚠️ Constraint unique_materia_escola NÃO existe</p>';
                    echo '<p>Execute: <code>ALTER TABLE materias ADD UNIQUE KEY unique_materia_escola (escola_id, nome);</code></p>';
                }
                
                echo '</div></div>';
                
                // 4. Contar matérias por escola
                echo '<div class="card mb-3">';
                echo '<div class="card-header"><h5>4️⃣ Matérias por escola</h5></div>';
                echo '<div class="card-body">';
                
                $stats = $db->fetchAll("
                    SELECT 
                        e.id as escola_id,
                        e.nome as escola_nome,
                        COUNT(m.id) as total_materias,
                        GROUP_CONCAT(m.nome ORDER BY m.nome SEPARATOR ', ') as materias
                    FROM escolas e
                    LEFT JOIN materias m ON e.id = m.escola_id
                    GROUP BY e.id, e.nome
                    ORDER BY e.nome
                ");
                
                if (!empty($stats)) {
                    echo '<table class="table table-striped">';
                    echo '<thead><tr><th>Escola</th><th>Total</th><th>Matérias</th></tr></thead>';
                    echo '<tbody>';
                    foreach ($stats as $row) {
                        echo '<tr>';
                        echo '<td><strong>' . htmlspecialchars($row['escola_nome']) . '</strong><br><small class="text-muted">ID: ' . $row['escola_id'] . '</small></td>';
                        echo '<td><span class="badge bg-primary">' . $row['total_materias'] . '</span></td>';
                        echo '<td><small>' . ($row['materias'] ? htmlspecialchars($row['materias']) : '<em class="text-muted">Nenhuma</em>') . '</small></td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p class="text-muted">Nenhuma escola cadastrada</p>';
                }
                
                echo '</div></div>';
                
                // 5. Testar query usada pelo sistema
                echo '<div class="card mb-3">';
                echo '<div class="card-header"><h5>5️⃣ Teste da query do sistema</h5></div>';
                echo '<div class="card-body">';
                
                try {
                    $testQuery = "SELECT m.*, u.nome as professor_nome FROM materias m 
                                  LEFT JOIN professores p ON m.professor_id = p.id
                                  LEFT JOIN usuarios u ON p.usuario_id = u.id
                                  WHERE m.escola_id = :escola_id 
                                  ORDER BY m.nome";
                    
                    $result = $db->fetchAll($testQuery, ['escola_id' => 1]);
                    
                    echo '<p class="success">✅ Query executada com sucesso!</p>';
                    echo '<p><strong>Tipo do resultado:</strong> ' . gettype($result) . '</p>';
                    echo '<p><strong>Total de registros:</strong> ' . count($result) . '</p>';
                    
                    if (!empty($result)) {
                        echo '<p><strong>Primeiro registro:</strong></p>';
                        echo '<pre>' . print_r($result[0], true) . '</pre>';
                    }
                    
                } catch (Exception $e) {
                    echo '<p class="error">❌ Erro ao executar query:</p>';
                    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
                }
                
                echo '</div></div>';
                
                // 6. Verificar duplicatas
                echo '<div class="card mb-3">';
                echo '<div class="card-header"><h5>6️⃣ Verificar duplicatas</h5></div>';
                echo '<div class="card-body">';
                
                $duplicates = $db->fetchAll("
                    SELECT escola_id, nome, COUNT(*) as total
                    FROM materias
                    GROUP BY escola_id, nome
                    HAVING COUNT(*) > 1
                ");
                
                if (empty($duplicates)) {
                    echo '<p class="success">✅ Não há matérias duplicadas</p>';
                } else {
                    echo '<p class="error">❌ Encontradas matérias duplicadas:</p>';
                    echo '<table class="table table-sm table-striped">';
                    echo '<thead><tr><th>Escola ID</th><th>Matéria</th><th>Duplicatas</th></tr></thead>';
                    echo '<tbody>';
                    foreach ($duplicates as $dup) {
                        echo '<tr>';
                        echo '<td>' . $dup['escola_id'] . '</td>';
                        echo '<td>' . htmlspecialchars($dup['nome']) . '</td>';
                        echo '<td>' . $dup['total'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    echo '<p class="warning">⚠️ Remova as duplicatas antes de adicionar a constraint unique_materia_escola</p>';
                }
                
                echo '</div></div>';
            }
            
            // Resumo final
            echo '<div class="card border-primary">';
            echo '<div class="card-header bg-primary text-white"><h5>📋 Resumo e Recomendações</h5></div>';
            echo '<div class="card-body">';
            
            if ($tableExists) {
                if (empty($missingColumns) && $hasUniqueConstraint) {
                    echo '<p class="success">✅ Tudo OK! Sua tabela está configurada corretamente.</p>';
                } else {
                    echo '<p class="warning">⚠️ Ação necessária:</p>';
                    echo '<ol>';
                    if (!empty($missingColumns)) {
                        echo '<li>Execute o arquivo <code>database/migracao_materias.sql</code> no phpMyAdmin</li>';
                    }
                    if (!$hasUniqueConstraint && empty($duplicates)) {
                        echo '<li>Adicione a constraint: <code>ALTER TABLE materias ADD UNIQUE KEY unique_materia_escola (escola_id, nome);</code></li>';
                    }
                    if (!empty($duplicates)) {
                        echo '<li class="error">Remova as matérias duplicadas antes de adicionar a constraint</li>';
                    }
                    echo '</ol>';
                }
            } else {
                echo '<p class="error">❌ Execute o arquivo <code>database/schema.sql</code> no phpMyAdmin</p>';
            }
            
            echo '</div></div>';
            
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">';
            echo '<strong>❌ Erro ao conectar com o banco:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-primary">← Voltar ao Sistema</a>
        </div>
    </div>
</body>
</html>

