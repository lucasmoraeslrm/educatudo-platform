<?php
session_start();
require_once 'vendor/autoload.php';

use Educatudo\Core\{App, Request, Response};

// Simular login de admin_escola
$_SESSION['user_id'] = 10;
$_SESSION['user_name'] = 'Diretor Pedro';
$_SESSION['user_tipo'] = 'admin_escola';
$_SESSION['escola_id'] = 2;
$_SESSION['user_email'] = 'Koagurapedro00@gmail.com';

echo "<h1>🧪 TESTE DE REDIRECIONAMENTO</h1>";

$app = App::getInstance();
$request = new Request();
$response = new Response();

echo "<h2>📋 Informações do Sistema:</h2>";
echo "Base Path: " . $app->getBasePath() . "<br>";
echo "URL Base: " . $app->url() . "<br>";

echo "<h2>🔍 Teste de URLs:</h2>";
echo "URL /escola: " . $app->url('/escola') . "<br>";
echo "URL /unauthorized: " . $app->url('/unauthorized') . "<br>";
echo "URL /login: " . $app->url('/login') . "<br>";

echo "<h2>🎯 Teste das Rotas:</h2>";
echo "- <a href='http://localhost/educatudo/escola' target='_blank'>Ir para /escola</a><br>";
echo "- <a href='http://localhost/educatudo/unauthorized' target='_blank'>Ir para /unauthorized</a><br>";

echo "<h2>📝 Verificar Logs:</h2>";
echo "Verifique: C:\\xampp\\apache\\logs\\error.log";

echo "<br><br><strong>✅ CORREÇÕES IMPLEMENTADAS:</strong><br>";
echo "- AdminEscolaMiddleware: Redireciona para /educatudo/unauthorized<br>";
echo "- AuthMiddleware: Redireciona para /educatudo/login<br>";
echo "- Controller: Redireciona para /educatudo/login<br>";
echo "- EscolaController: Redireciona para /educatudo/login<br>";
?>
