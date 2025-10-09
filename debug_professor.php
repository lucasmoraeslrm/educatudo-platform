<?php
/**
 * DEBUG: Testar criação de professor
 * URL: http://localhost/educatudo/debug_professor.php?escola_id=1
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'config/config.php';

header('Content-Type: text/html; charset=utf-8');

$escolaId = $_GET['escola_id'] ?? 1;

echo "<h2>🔍 DEBUG - Cadastro de Professor</h2>";
echo "<hr>";

try {
    $app = Educatudo\Core\App::getInstance();
    $db = new Educatudo\Core\Database($app);
    
    echo "<h3>✅ Conexão com banco OK</h3>";
    
    // 1. Verificar se a escola existe
    echo "<h3>1. Verificando escola ID: {$escolaId}</h3>";
    $escola = $db->fetch("SELECT * FROM escolas WHERE id = ?", [$escolaId]);
    if ($escola) {
        echo "<p style='color: green'>✅ Escola encontrada: " . htmlspecialchars($escola['nome']) . "</p>";
    } else {
        echo "<p style='color: red'>❌ Escola não encontrada!</p>";
        exit;
    }
    
    // 2. Verificar estrutura da tabela usuarios
    echo "<h3>2. Estrutura da tabela 'usuarios'</h3>";
    $colunas = $db->fetchAll("DESCRIBE usuarios");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Default</th></tr>";
    foreach ($colunas as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 3. Verificar estrutura da tabela professores
    echo "<h3>3. Estrutura da tabela 'professores'</h3>";
    $colunas = $db->fetchAll("DESCRIBE professores");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Default</th></tr>";
    foreach ($colunas as $col) {
        echo "<tr>";
        echo "<td>" . $col['Field'] . "</td>";
        echo "<td>" . $col['Type'] . "</td>";
        echo "<td>" . $col['Null'] . "</td>";
        echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. Testar criação de usuário
    echo "<h3>4. Testando criação de usuário</h3>";
    
    $testData = [
        'escola_id' => $escolaId,
        'tipo' => 'professor',
        'nome' => 'Professor Teste Debug',
        'email' => 'debug_' . time() . '@teste.com',
        'senha_hash' => password_hash('123456', PASSWORD_DEFAULT)
    ];
    
    echo "<pre>Dados de teste:\n";
    print_r($testData);
    echo "</pre>";
    
    try {
        $db->beginTransaction();
        
        // Inserir usuário
        $sql = "INSERT INTO usuarios (escola_id, tipo, nome, email, senha_hash) 
                VALUES (:escola_id, :tipo, :nome, :email, :senha_hash)";
        
        echo "<p><strong>SQL:</strong> $sql</p>";
        
        $stmt = $db->query($sql, $testData);
        $usuarioId = $db->lastInsertId();
        
        echo "<p style='color: green'>✅ Usuário criado com ID: {$usuarioId}</p>";
        
        // Inserir professor
        $professorData = [
            'usuario_id' => $usuarioId,
            'codigo_prof' => 'DEBUG_' . time(),
            'materias' => null,
            'ativo' => 1
        ];
        
        $sql2 = "INSERT INTO professores (usuario_id, codigo_prof, materias, ativo) 
                 VALUES (:usuario_id, :codigo_prof, :materias, :ativo)";
        
        echo "<p><strong>SQL Professor:</strong> $sql2</p>";
        echo "<pre>Dados professor:\n";
        print_r($professorData);
        echo "</pre>";
        
        $stmt2 = $db->query($sql2, $professorData);
        $professorId = $db->lastInsertId();
        
        echo "<p style='color: green'>✅ Professor criado com ID: {$professorId}</p>";
        
        $db->commit();
        
        echo "<h3>✅ SUCESSO! Professor criado com sucesso!</h3>";
        echo "<p><a href='index.php'>Voltar ao sistema</a></p>";
        
    } catch (\Exception $e) {
        $db->rollback();
        echo "<p style='color: red'><strong>❌ ERRO ao criar professor:</strong></p>";
        echo "<pre style='background: #fee; padding: 10px; border: 1px solid red;'>";
        echo htmlspecialchars($e->getMessage());
        echo "\n\nStack trace:\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo "</pre>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red'><strong>❌ ERRO GERAL:</strong></p>";
    echo "<pre style='background: #fee; padding: 10px; border: 1px solid red;'>";
    echo htmlspecialchars($e->getMessage());
    echo "\n\nStack trace:\n";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}
?>

