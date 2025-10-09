<?php
// Bootstrap da aplicação
require_once __DIR__ . '/vendor/autoload.php';

use Educatudo\Core\{App, Router, Database, Request, Response};

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$router->get('/admin/escolas', 'GlobalAdminController@escolas')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/create', 'GlobalAdminController@createEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas', 'GlobalAdminController@storeEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{id}', 'GlobalAdminController@showEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{id}/edit', 'GlobalAdminController@editEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{id}', 'GlobalAdminController@updateEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{id}/update', 'GlobalAdminController@updateEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Admins da Escola
$router->get('/admin/escolas/{escolaId}/usuarios/create', 'GlobalAdminController@createAdminEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/usuarios', 'GlobalAdminController@storeAdminEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/usuarios/{usuarioId}/edit', 'GlobalAdminController@editAdminEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/usuarios/{usuarioId}', 'GlobalAdminController@updateAdminEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/usuarios/{usuarioId}/delete', 'GlobalAdminController@deleteAdminEscola')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Professores
$router->get('/admin/escolas/{escolaId}/professores/create', 'GlobalAdminController@createProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/professores', 'GlobalAdminController@storeProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/professores/{professorId}/edit', 'GlobalAdminController@editProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{escolaId}/professores/{professorId}', 'GlobalAdminController@updateProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/professores/{professorId}', 'GlobalAdminController@updateProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->delete('/admin/escolas/{escolaId}/professores/{professorId}', 'GlobalAdminController@deleteProfessor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Matérias
$router->get('/admin/escolas/{escolaId}/materias/create', 'GlobalAdminController@createMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/materias', 'GlobalAdminController@storeMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/materias/{materiaId}/edit', 'GlobalAdminController@editMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{escolaId}/materias/{materiaId}', 'GlobalAdminController@updateMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/materias/{materiaId}', 'GlobalAdminController@updateMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->delete('/admin/escolas/{escolaId}/materias/{materiaId}', 'GlobalAdminController@deleteMateria')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Turmas
$router->get('/admin/escolas/{escolaId}/turmas/create', 'GlobalAdminController@createTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/turmas', 'GlobalAdminController@storeTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/turmas/{turmaId}/edit', 'GlobalAdminController@editTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{escolaId}/turmas/{turmaId}', 'GlobalAdminController@updateTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/turmas/{turmaId}', 'GlobalAdminController@updateTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->delete('/admin/escolas/{escolaId}/turmas/{turmaId}', 'GlobalAdminController@deleteTurma')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Alunos
$router->get('/admin/escolas/{escolaId}/alunos/create', 'GlobalAdminController@createAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/alunos', 'GlobalAdminController@storeAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/alunos/{alunoId}/edit', 'GlobalAdminController@editAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{escolaId}/alunos/{alunoId}', 'GlobalAdminController@updateAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/alunos/{alunoId}', 'GlobalAdminController@updateAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->delete('/admin/escolas/{escolaId}/alunos/{alunoId}', 'GlobalAdminController@deleteAluno')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas CRUD de Pais
$router->get('/admin/escolas/{escolaId}/pais/create', 'GlobalAdminController@createPai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/pais', 'GlobalAdminController@storePai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/escolas/{escolaId}/pais/{paiId}/edit', 'GlobalAdminController@editPai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->put('/admin/escolas/{escolaId}/pais/{paiId}', 'GlobalAdminController@updatePai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->post('/admin/escolas/{escolaId}/pais/{paiId}', 'GlobalAdminController@updatePai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->delete('/admin/escolas/{escolaId}/pais/{paiId}', 'GlobalAdminController@deletePai')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

$router->get('/admin/usuarios', 'GlobalAdminController@usuarios')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/exercicios', 'GlobalAdminController@exercicios')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');
$router->get('/admin/servidor', 'GlobalAdminController@servidor')->middleware('AuthMiddleware')->middleware('SuperAdminMiddleware');

// Rotas do Admin da Escola
$router->get('/admin-escola', 'EscolaController@index')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/alunos', 'EscolaController@alunos')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/professores', 'EscolaController@professores')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/turmas', 'EscolaController@turmas')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/materias', 'EscolaController@materias')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');

// Rotas do Professor
$router->get('/professor', 'ProfessorController@index')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');
$router->get('/professor/jornadas', 'ProfessorController@jornadas')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');
$router->get('/professor/jornadas/create', 'ProfessorController@createJornada')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');
$router->post('/professor/jornadas', 'ProfessorController@storeJornada')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');
$router->get('/professor/exercicios', 'ProfessorController@exercicios')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');
$router->get('/professor/relatorios', 'ProfessorController@relatorios')->middleware('AuthMiddleware')->middleware('ProfessorMiddleware');

// Rotas do Aluno
$router->get('/aluno', 'AlunoController@index')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');
$router->get('/aluno/chat-tudinha', 'AlunoController@chatTudinha')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');
$router->get('/aluno/exercicios', 'AlunoController@exercicios')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');
$router->get('/aluno/redacao', 'AlunoController@redacao')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');
$router->get('/aluno/vestibulares', 'AlunoController@vestibulares')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');
$router->get('/aluno/jogos', 'AlunoController@jogos')->middleware('AuthMiddleware')->middleware('AlunoMiddleware');

// Rotas dos Pais
$router->get('/pais', 'PaisController@index')->middleware('AuthMiddleware')->middleware('PaisMiddleware');
$router->get('/pais/desempenho', 'PaisController@desempenho')->middleware('AuthMiddleware')->middleware('PaisMiddleware');
$router->get('/pais/relatorios', 'PaisController@relatorios')->middleware('AuthMiddleware')->middleware('PaisMiddleware');

// Rotas de erro
$router->get('/unauthorized', 'ErrorController@unauthorized');
$router->get('/404', 'ErrorController@notFound');
$router->get('/500', 'ErrorController@serverError');

// Executar aplicação
try {
    $method = $request->method();
    $uri = $request->uri();

    // Debug: mostrar informações
    if (isset($_GET['debug'])) {
        echo "<h1>Debug do Sistema</h1>";
        echo "URI: " . $uri . "<br>";
        echo "Method: " . $method . "<br>";
        echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
        echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
        echo "QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "<br>";
        return;
    }

    // Tentar fazer match com rotas amigáveis primeiro
    $route = $router->match($method, $uri);
    
    // Se não conseguir fazer match e não há página especificada, mostrar página inicial
    if (!$route && !isset($_GET['page'])) {
        $controller = new \Educatudo\Controllers\HomeController();
        $result = $controller->index();
        
        if ($result instanceof Response) {
            $result->send();
        } else {
            $response->setContent($result)->send();
        }
        return;
    }
    
    // Fallback com query string (para compatibilidade)
    if (!$route && isset($_GET['page'])) {
        // Sistema de fallback com query string
        $page = $_GET['page'];
        $action = $_GET['action'] ?? 'index';
        
        switch ($page) {
            case 'home':
                $controller = new \Educatudo\Controllers\HomeController();
                $result = $controller->index();
                break;
                
            case 'login':
                $controller = new \Educatudo\Controllers\AuthController();
                if ($action === 'login' && $method === 'POST') {
                    $result = $controller->login();
                } else {
                    $result = $controller->showLogin();
                }
                break;
                
            case 'admin':
                $controller = new \Educatudo\Controllers\GlobalAdminController();
                $result = $controller->index();
                break;
                
            case 'admin-escola':
                $controller = new \Educatudo\Controllers\EscolaController();
                $result = $controller->index();
                break;
                
            case 'professor':
                $controller = new \Educatudo\Controllers\ProfessorController();
                $result = $controller->index();
                break;
                
            case 'aluno':
                $controller = new \Educatudo\Controllers\AlunoController();
                $result = $controller->index();
                break;
                
            case 'pais':
                $controller = new \Educatudo\Controllers\PaisController();
                $result = $controller->index();
                break;
                
            default:
                $result = null;
        }
        
        if ($result) {
            if ($result instanceof Response) {
                $result->send();
            } else {
                $response->setContent($result)->send();
            }
            return;
        }
    }

    if (!$route) {
        $response->setStatusCode(404)->setContent('Página não encontrada');
        $response->send();
        return;
    }

    // Executar middlewares
    foreach ($route['middlewares'] as $middleware) {
        $middlewareClass = "Educatudo\\Middleware\\{$middleware}";
        if (class_exists($middlewareClass)) {
            $middlewareInstance = new $middlewareClass();
            $result = $middlewareInstance->handle($request, $response);
            if ($result !== true) {
                $response->send();
                return;
            }
        }
    }

    // Executar controller
    [$controllerName, $method] = explode('@', $route['handler']);
    $controllerClass = "Educatudo\\Controllers\\{$controllerName}";
    
    if (!class_exists($controllerClass)) {
        throw new \Exception("Controller não encontrado: {$controllerClass}");
    }

    $controller = new $controllerClass();
    
    if (!method_exists($controller, $method)) {
        throw new \Exception("Método não encontrado: {$method} em {$controllerClass}");
    }

    $result = call_user_func_array([$controller, $method], $route['params']);
    
    if ($result instanceof Response) {
        $result->send();
    } else {
        $response->setContent($result)->send();
    }

} catch (\Exception $e) {
    if ($app->getConfig('app.debug')) {
        $response->setStatusCode(500)->setContent($e->getMessage());
    } else {
        $response->setStatusCode(500)->setContent('Erro interno do servidor');
    }
    $response->send();
}
