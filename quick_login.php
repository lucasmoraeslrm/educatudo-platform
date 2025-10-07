<?php
session_start();

require_once 'vendor/autoload.php';
use Educatudo\Core\{App, Database};

echo "<h1>Login Rápido para Teste</h1>";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    try {
        $app = App::getInstance();
        $db = Database::getInstance($app);
        
        $sql = "SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = :email";
        $user = $db->fetch($sql, ['email' => $email]);
        
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_type'] = $user['tipo'];
            
            echo "<p style='color: green; font-weight: bold;'>✓ Login realizado com sucesso!</p>";
            echo "<p>Usuário: " . $user['nome'] . "</p>";
            echo "<p>Tipo: " . $user['tipo'] . "</p>";
            
            if ($user['tipo'] === 'super_admin') {
                echo "<p style='color: green;'>✓ Você é Super Admin - pode acessar admin/escolas/2</p>";
                echo "<p><a href='/educatudo/admin/escolas/2' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acessar Escola ID 2</a></p>";
            } else {
                echo "<p style='color: red;'>✗ Você NÃO é Super Admin</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Email ou senha incorretos</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erro: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>Fazer Login:</h2>";
echo "<form method='POST'>";
echo "<p>Email: <input type='email' name='email' value='admin@educatudo.com' required></p>";
echo "<p>Senha: <input type='password' name='senha' value='admin123' required></p>";
echo "<p><button type='submit' name='login'>Fazer Login</button></p>";
echo "</form>";

echo "<h2>Status Atual:</h2>";
if (isset($_SESSION['user_id'])) {
    echo "<p style='color: green;'>✓ Logado como: " . $_SESSION['user_name'] . " (" . $_SESSION['user_type'] . ")</p>";
    echo "<p><a href='/educatudo/logout'>Fazer Logout</a></p>";
} else {
    echo "<p style='color: red;'>✗ Não logado</p>";
}

echo "<h2>Links de Teste:</h2>";
echo "<p><a href='/educatudo/admin'>Admin Dashboard</a></p>";
echo "<p><a href='/educatudo/admin/escolas'>Lista de Escolas</a></p>";
echo "<p><a href='/educatudo/admin/escolas/2'>Escola ID 2</a></p>";
?>
