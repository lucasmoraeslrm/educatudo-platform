<?php
session_start();
require_once 'vendor/autoload.php';

echo "<h1>🧪 TESTE SIMPLES DE LOGIN</h1>";

// Simular login de admin_escola
$_SESSION['user_id'] = 10;
$_SESSION['user_name'] = 'Diretor Pedro';
$_SESSION['user_tipo'] = 'admin_escola';
$_SESSION['escola_id'] = 2;
$_SESSION['user_email'] = 'Koagurapedro00@gmail.com';

echo "<h2>✅ Sessão configurada:</h2>";
echo "ID: " . $_SESSION['user_id'] . "<br>";
echo "Nome: " . $_SESSION['user_name'] . "<br>";
echo "Tipo: " . $_SESSION['user_tipo'] . "<br>";
echo "Escola ID: " . $_SESSION['escola_id'] . "<br>";

echo "<br><h3>🎯 TESTE AS ROTAS:</h3>";
echo "- <a href='http://localhost/educatudo/escola' target='_blank'>Ir para /escola</a><br>";
echo "- <a href='http://localhost/educatudo/escola/professores' target='_blank'>Ir para /escola/professores</a><br>";
echo "- <a href='http://localhost/educatudo/escola/alunos' target='_blank'>Ir para /escola/alunos</a><br>";

echo "<br><p><strong>⚠️ IMPORTANTE:</strong> Teste cada link para ver se ainda dá erro de autorização.</p>";
?>
