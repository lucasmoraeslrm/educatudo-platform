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

echo "<h1>🧪 TESTE DO ROUTER</h1>";

$app = App::getInstance();
$router = new Router();
$request = new Request();

// Registrar algumas rotas de teste
$router->get('/escola', 'EscolaController@index')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/unauthorized', 'ErrorController@unauthorized');

echo "<h2>📋 Rotas Registradas:</h2>";
$routes = $router->getRoutes();
foreach ($routes as $method => $methodRoutes) {
    echo "<strong>{$method}:</strong><br>";
    foreach ($methodRoutes as $uri => $route) {
        echo "- {$uri} -> {$route['handler']}<br>";
    }
}

echo "<h2>🔍 Teste de Match:</h2>";

// Simular diferentes URIs
$testUris = ['/escola', '/unauthorized', '/escola/professores'];

foreach ($testUris as $uri) {
    echo "<strong>Testando URI: {$uri}</strong><br>";
    
    // Simular a URI
    $_SERVER['REQUEST_URI'] = '/educatudo' . $uri;
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    $match = $router->match($request);
    
    if ($match) {
        echo "✅ <strong>MATCH ENCONTRADO!</strong><br>";
        echo "Handler: {$match['handler']}<br>";
        echo "Params: " . json_encode($match['params']) . "<br>";
        echo "Middlewares: " . json_encode($match['middlewares']) . "<br>";
    } else {
        echo "❌ <strong>NENHUM MATCH</strong><br>";
    }
    echo "<br>";
}

echo "<h2>🎯 Teste Real:</h2>";
echo "- <a href='http://localhost/educatudo/escola' target='_blank'>Ir para /escola</a><br>";
echo "- <a href='http://localhost/educatudo/unauthorized' target='_blank'>Ir para /unauthorized</a><br>";
?>
