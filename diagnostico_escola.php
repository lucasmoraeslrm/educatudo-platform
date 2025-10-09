<?php
/**
 * DIAGNÓSTICO: Verificar isolamento por escola
 * Execute: http://localhost/educatudo/diagnostico_escola.php
 */

require 'vendor/autoload.php';
require 'config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Diagnóstico - Isolamento por Escola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error { background: #fee; padding: 10px; border-left: 4px solid red; margin: 10px 0; }
        .success { background: #efe; padding: 10px; border-left: 4px solid green; margin: 10px 0; }
        .warning { background: #ffe; padding: 10px; border-left: 4px solid orange; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1>🔍 Diagnóstico - Isolamento por Escola</h1>
        <hr>
        
        <?php
        try {
            $app = Educatudo\Core\App::getInstance();
            $db = new Educatudo\Core\Database($app);
            
            echo '<div class="success"><strong>✅ Conexão OK!</strong></div>';
            
            // 1. Listar todas as escolas
            echo '<h3>1. Escolas Cadastradas</h3>';
            $escolas = $db->fetchAll("SELECT * FROM escolas ORDER BY id");
            echo '<table class="table table-bordered table-sm">';
            echo '<thead><tr><th>ID</th><th>Nome</th><th>Subdomínio</th></tr></thead>';
            foreach ($escolas as $escola) {
                echo '<tr>';
                echo '<td>' . $escola['id'] . '</td>';
                echo '<td>' . htmlspecialchars($escola['nome']) . '</td>';
                echo '<td>' . htmlspecialchars($escola['subdominio']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            
            // 2. Matérias por escola
            echo '<h3>2. Matérias por Escola</h3>';
            foreach ($escolas as $escola) {
                echo '<h5>Escola: ' . htmlspecialchars($escola['nome']) . ' (ID: ' . $escola['id'] . ')</h5>';
                
                $materias = $db->fetchAll(
                    "SELECT m.*, u.nome as professor_nome 
                     FROM materias m 
                     LEFT JOIN professores p ON m.professor_id = p.id
                     LEFT JOIN usuarios u ON p.usuario_id = u.id
                     WHERE m.escola_id = :escola_id 
                     ORDER BY m.nome",
                    ['escola_id' => $escola['id']]
                );
                
                if (empty($materias)) {
                    echo '<p class="text-muted">Nenhuma matéria cadastrada</p>';
                } else {
                    echo '<table class="table table-sm table-striped">';
                    echo '<thead><tr><th>ID</th><th>Nome</th><th>Escola ID</th><th>Professor</th></tr></thead>';
                    foreach ($materias as $m) {
                        $rowClass = ($m['escola_id'] != $escola['id']) ? 'table-danger' : '';
                        echo '<tr class="' . $rowClass . '">';
                        echo '<td>' . $m['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($m['nome']) . '</td>';
                        echo '<td>' . $m['escola_id'] . '</td>';
                        echo '<td>' . ($m['professor_nome'] ?? '-') . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            
            // 3. Turmas por escola
            echo '<h3>3. Turmas por Escola</h3>';
            foreach ($escolas as $escola) {
                echo '<h5>Escola: ' . htmlspecialchars($escola['nome']) . ' (ID: ' . $escola['id'] . ')</h5>';
                
                $turmas = $db->fetchAll(
                    "SELECT * FROM turmas 
                     WHERE escola_id = :escola_id 
                     ORDER BY serie, nome",
                    ['escola_id' => $escola['id']]
                );
                
                if (empty($turmas)) {
                    echo '<p class="text-muted">Nenhuma turma cadastrada</p>';
                } else {
                    echo '<table class="table table-sm table-striped">';
                    echo '<thead><tr><th>ID</th><th>Nome</th><th>Escola ID</th><th>Série</th><th>Ano Letivo</th></tr></thead>';
                    foreach ($turmas as $t) {
                        $rowClass = ($t['escola_id'] != $escola['id']) ? 'table-danger' : '';
                        echo '<tr class="' . $rowClass . '">';
                        echo '<td>' . $t['id'] . '</td>';
                        echo '<td>' . htmlspecialchars($t['nome']) . '</td>';
                        echo '<td>' . $t['escola_id'] . '</td>';
                        echo '<td>' . htmlspecialchars($t['serie']) . '</td>';
                        echo '<td>' . $t['ano_letivo'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                }
            }
            
            // 4. Teste do Model
            echo '<h3>4. Teste dos Models</h3>';
            $materiaModel = new \Educatudo\Models\Materia($db);
            $turmaModel = new \Educatudo\Models\Turma($db);
            
            foreach ($escolas as $escola) {
                echo '<h5>' . htmlspecialchars($escola['nome']) . '</h5>';
                
                $materiasModel = $materiaModel->getByEscola($escola['id']);
                $turmasModel = $turmaModel->getByEscola($escola['id']);
                
                echo '<p>';
                echo '<strong>Via Model:</strong><br>';
                echo 'Matérias: ' . count($materiasModel) . '<br>';
                echo 'Turmas: ' . count($turmasModel);
                echo '</p>';
                
                if (!empty($materiasModel)) {
                    echo '<ul>';
                    foreach ($materiasModel as $m) {
                        echo '<li>' . htmlspecialchars($m['nome']) . ' (escola_id: ' . $m['escola_id'] . ')</li>';
                    }
                    echo '</ul>';
                }
            }
            
            // 5. Verificar constraints
            echo '<h3>5. Constraints Configuradas</h3>';
            
            $constraints = $db->fetchAll("SHOW INDEX FROM materias WHERE Key_name LIKE '%unique%'");
            echo '<h5>Matérias:</h5>';
            if (empty($constraints)) {
                echo '<div class="warning">⚠️ Nenhuma constraint UNIQUE encontrada!</div>';
            } else {
                echo '<ul>';
                foreach ($constraints as $c) {
                    echo '<li>' . $c['Key_name'] . ' (' . $c['Column_name'] . ')</li>';
                }
                echo '</ul>';
            }
            
            $constraints = $db->fetchAll("SHOW INDEX FROM turmas WHERE Key_name LIKE '%unique%'");
            echo '<h5>Turmas:</h5>';
            if (empty($constraints)) {
                echo '<div class="warning">⚠️ Nenhuma constraint UNIQUE encontrada!</div>';
            } else {
                echo '<ul>';
                foreach ($constraints as $c) {
                    echo '<li>' . $c['Key_name'] . ' (' . $c['Column_name'] . ')</li>';
                }
                echo '</ul>';
            }
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<strong>❌ Erro:</strong><br>';
            echo htmlspecialchars($e->getMessage());
            echo '</div>';
        }
        ?>
        
        <hr>
        <a href="index.php" class="btn btn-primary">← Voltar</a>
    </div>
</body>
</html>

