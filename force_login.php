<?php
session_start();

echo "<h1>Forçar Login</h1>";

// Conectar ao banco
$config = require __DIR__ . '/config/config.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['database']['host']};dbname={$config['database']['name']};charset=utf8mb4",
        $config['database']['user'],
        $config['database']['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Buscar usuário admin_escola
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE tipo = 'admin_escola' LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p>Usuário encontrado: " . $user['nome'] . "</p>";
        
        // Forçar login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo'];
        $_SESSION['escola_id'] = $user['escola_id'];
        
        echo "<p style='color: green;'>✅ Login forçado!</p>";
        echo "<p><a href='/educatudo/escola'>Ir para /escola</a></p>";
        echo "<p><a href='/educatudo/test_session.php'>Verificar sessão</a></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Nenhum usuário admin_escola encontrado!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro: " . $e->getMessage() . "</p>";
}
?>
