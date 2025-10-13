<?php
session_start();
require_once 'vendor/autoload.php';

use Educatudo\Core\{App, Router, Database, Request, Response};

// Simular login de admin_escola
$_SESSION['user_id'] = 10;
$_SESSION['user_name'] = 'Diretor Pedro';
$_SESSION['user_tipo'] = 'admin_escola';
$_SESSION['escola_id'] = 2;
$_SESSION['user_email'] = 'Koagurapedro00@gmail.com';

echo "<h1>🧪 TESTE SIMPLES - ESCOLA</h1>";

echo "<h2>📋 Estado da Sessão:</h2>";
echo "user_id: " . ($_SESSION['user_id'] ?? 'NÃO DEFINIDO') . "<br>";
echo "user_tipo: " . ($_SESSION['user_tipo'] ?? 'NÃO DEFINIDO') . "<br>";
echo "escola_id: " . ($_SESSION['escola_id'] ?? 'NÃO DEFINIDO') . "<br>";

echo "<h2>🎯 Teste das Rotas:</h2>";
echo "- <a href='http://localhost/educatudo/escola' target='_blank'>Ir para /escola</a><br>";
echo "- <a href='http://localhost/educatudo/unauthorized' target='_blank'>Ir para /unauthorized</a><br>";

echo "<h2>📝 Verificar Logs:</h2>";
echo "Verifique: C:\\xampp\\apache\\logs\\error.log";

echo "<br><br><strong>⚠️ IMPORTANTE:</strong> Se ainda der erro, verifique os logs do Apache para ver qual é o problema específico.";
?>
