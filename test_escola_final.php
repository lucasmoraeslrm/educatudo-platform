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

echo "<h1>🧪 TESTE FINAL - ESCOLA</h1>";

// Simular a URI correta
$_SERVER['REQUEST_URI'] = '/educatudo/escola';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/educatudo/index.php';

$app = App::getInstance();
$request = new Request();
$router = new Router();

echo "<h2>📋 Informações do Sistema:</h2>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "Base Path: " . $app->getBasePath() . "<br>";
echo "URI Processada: " . $request->uri() . "<br>";

// Registrar a rota
$router->get('/escola', 'EscolaController@index')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');

echo "<h2>🔍 Teste de Match:</h2>";
$match = $router->match('GET', $request->uri());

if ($match) {
    echo "✅ <strong>MATCH ENCONTRADO!</strong><br>";
    echo "Handler: {$match['handler']}<br>";
    echo "Middlewares: " . json_encode($match['middlewares']) . "<br>";
} else {
    echo "❌ <strong>NENHUM MATCH</strong><br>";
}

echo "<h2>🧪 Teste do Controller:</h2>";

try {
    $controller = new \Educatudo\Controllers\EscolaController();
    echo "✅ Controller criado com sucesso<br>";
    
    if (method_exists($controller, 'index')) {
        echo "✅ Método index() existe<br>";
    } else {
        echo "❌ Método index() não existe<br>";
    }
} catch (Exception $e) {
    echo "❌ Erro ao criar controller: " . $e->getMessage() . "<br>";
}

echo "<h2>🎯 Teste Real:</h2>";
echo "- <a href='http://localhost/educatudo/escola' target='_blank'>Ir para /escola</a><br>";
echo "- <a href='http://localhost/educatudo/escola?debug=1' target='_blank'>Debug /escola</a><br>";

echo "<h2>📝 Verificar Logs:</h2>";
echo "Verifique: C:\\xampp\\apache\\logs\\error.log";
?>
