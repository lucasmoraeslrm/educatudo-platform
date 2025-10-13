<?php
// Debug das rotas
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/helpers.php';

use Educatudo\Core\{App, Router, Database, Request, Response};

// Inicializar aplicação
$app = App::getInstance();
$db = Database::getInstance($app);
$request = new Request();
$response = new Response();

// Configurar rotas
$router = new Router();

// Rotas públicas
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Rotas do Admin Global (super_admin)
$router->get('/admin', 'GlobalAdminController@index')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas do Admin da Escola
$router->get('/escola', 'EscolaController@index')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola', 'EscolaController@redirectToEscola');

echo "<h1>Debug das Rotas</h1>";

echo "<h2>Rotas Registradas:</h2>";
$routes = $router->getRoutes();
echo "<table border='1'>";
echo "<tr><th>Método</th><th>Path</th><th>Handler</th><th>Middlewares</th></tr>";
foreach ($routes as $route) {
    echo "<tr>";
    echo "<td>" . $route['method'] . "</td>";
    echo "<td>" . $route['path'] . "</td>";
    echo "<td>" . $route['handler'] . "</td>";
    echo "<td>" . implode(', ', $route['middlewares']) . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Teste de Match:</h2>";
$testPaths = ['/escola', '/admin-escola', '/admin', '/login'];

foreach ($testPaths as $testPath) {
    $match = $router->match('GET', $testPath);
    echo "<p><strong>$testPath:</strong> ";
    if ($match) {
        echo "✅ Match encontrado - Handler: " . $match['handler'] . " - Middlewares: " . implode(', ', $match['middlewares']);
    } else {
        echo "❌ Nenhum match encontrado";
    }
    echo "</p>";
}

echo "<h2>Informações da Requisição:</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "</p>";
echo "<p><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'N/A') . "</p>";

echo "<h2>Sessão:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Teste de Acesso:</h2>";
echo "<p><a href='/educatudo/escola'>Testar /escola</a></p>";
echo "<p><a href='/educatudo/admin-escola'>Testar /admin-escola</a></p>";
echo "<p><a href='/educatudo/login'>Testar /login</a></p>";
?>
