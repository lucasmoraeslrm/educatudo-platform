<?php
// Script para criar usuário super admin de teste
require_once 'vendor/autoload.php';

use Educatudo\Core\{App, Database};

$app = App::getInstance();
$db = Database::getInstance($app);

// Verificar se já existe um super admin
$sql = "SELECT * FROM usuarios WHERE tipo = 'super_admin' LIMIT 1";
$existing = $db->fetch($sql);

if ($existing) {
    echo "<h1>Super Admin já existe!</h1>";
    echo "<p>Email: " . $existing['email'] . "</p>";
    echo "<p>Nome: " . $existing['nome'] . "</p>";
    echo "<p><a href='/educatudo/login'>Ir para Login</a></p>";
} else {
    // Criar super admin
    $sql = "INSERT INTO usuarios (tipo, nome, email, senha_hash, data_cadastro) VALUES (?, ?, ?, ?, ?)";
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $result = $db->query($sql, [
        'super_admin',
        'Administrador Global',
        'admin@educatudo.com',
        $password,
        date('Y-m-d H:i:s')
    ]);
    
    if ($result) {
        echo "<h1>Super Admin criado com sucesso!</h1>";
        echo "<p>Email: admin@educatudo.com</p>";
        echo "<p>Senha: admin123</p>";
        echo "<p><a href='/educatudo/login'>Ir para Login</a></p>";
    } else {
        echo "<h1>Erro ao criar Super Admin</h1>";
        echo "<p>Verifique se o banco de dados está configurado corretamente.</p>";
    }
}
?>
