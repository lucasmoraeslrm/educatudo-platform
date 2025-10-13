<?php
// Script de debug para XAMPP local
session_start();

// Conectar ao banco local XAMPP
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=educatudo_platform;charset=utf8mb4",
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<h1>Debug de Autenticação - XAMPP Local</h1>";
    echo "<p style='color: green;'>Conexão com banco local estabelecida!</p>";
} catch (PDOException $e) {
    echo "<h1>Debug de Autenticação - XAMPP Local</h1>";
    echo "<p style='color: red;'>Erro ao conectar: " . $e->getMessage() . "</p>";
    echo "<p>Tentando criar o banco...</p>";
    
    // Tentar conectar sem especificar o banco
    try {
        $pdo = new PDO(
            "mysql:host=localhost;charset=utf8mb4",
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Criar banco se não existir
        $pdo->exec("CREATE DATABASE IF NOT EXISTS educatudo_platform");
        $pdo->exec("USE educatudo_platform");
        echo "<p style='color: green;'>Banco criado com sucesso!</p>";
        
        // Executar schema
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        $statements = explode(';', $schema);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Ignorar erros de tabelas já existentes
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        echo "<p style='color: orange;'>Aviso: " . $e->getMessage() . "</p>";
                    }
                }
            }
        }
        
        echo "<p style='color: green;'>Schema executado!</p>";
        
    } catch (PDOException $e2) {
        echo "<p style='color: red;'>Erro fatal: " . $e2->getMessage() . "</p>";
        exit;
    }
}

// Verificar sessão
echo "<h2>Sessão Atual:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Verificar usuários admin_escola
echo "<h2>Usuários Admin Escola:</h2>";
$stmt = $pdo->query("SELECT id, nome, email, tipo, escola_id FROM usuarios WHERE tipo = 'admin_escola'");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($admins)) {
    echo "<p style='color: red;'>Nenhum admin da escola encontrado!</p>";
    echo "<p>Criando admin da escola...</p>";
    
    // Criar admin da escola
    $pdo->exec("INSERT IGNORE INTO usuarios (escola_id, tipo, nome, email, senha_hash) VALUES 
                (1, 'admin_escola', 'Admin Escola Demo', 'admin@demo.educatudo.com', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
    
    echo "<p style='color: green;'>Admin da escola criado!</p>";
    
    // Verificar novamente
    $stmt = $pdo->query("SELECT id, nome, email, tipo, escola_id FROM usuarios WHERE tipo = 'admin_escola'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Tipo</th><th>Escola ID</th></tr>";
foreach ($admins as $admin) {
    echo "<tr>";
    echo "<td>" . $admin['id'] . "</td>";
    echo "<td>" . $admin['nome'] . "</td>";
    echo "<td>" . $admin['email'] . "</td>";
    echo "<td>" . $admin['tipo'] . "</td>";
    echo "<td>" . $admin['escola_id'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Verificar escolas
echo "<h2>Escolas:</h2>";
$stmt = $pdo->query("SELECT id, nome, subdominio FROM escolas");
$escolas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($escolas)) {
    echo "<p style='color: red;'>Nenhuma escola encontrada!</p>";
} else {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Subdomínio</th></tr>";
    foreach ($escolas as $escola) {
        echo "<tr>";
        echo "<td>" . $escola['id'] . "</td>";
        echo "<td>" . $escola['nome'] . "</td>";
        echo "<td>" . $escola['subdominio'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Testar login
if (isset($_POST['test_login'])) {
    echo "<h2>Teste de Login:</h2>";
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p>Usuário encontrado: " . $user['nome'] . " (Tipo: " . $user['tipo'] . ")</p>";
        
        if (password_verify($senha, $user['senha_hash'])) {
            echo "<p style='color: green;'>Senha correta!</p>";
            
            // Simular login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['escola_id'] = $user['escola_id'];
            
            echo "<p style='color: green;'>Sessão criada! <a href='/educatudo/escola'>Ir para /escola</a></p>";
        } else {
            echo "<p style='color: red;'>Senha incorreta!</p>";
        }
    } else {
        echo "<p style='color: red;'>Usuário não encontrado!</p>";
    }
}
?>

<form method="POST">
    <h2>Teste de Login:</h2>
    <p>Email: <input type="email" name="email" value="admin@demo.educatudo.com"></p>
    <p>Senha: <input type="password" name="senha" value="password"></p>
    <p><input type="submit" name="test_login" value="Testar Login"></p>
</form>

<p><a href="/educatudo/escola">Testar acesso /escola</a></p>
<p><a href="/educatudo/admin">Testar acesso /admin</a></p>
