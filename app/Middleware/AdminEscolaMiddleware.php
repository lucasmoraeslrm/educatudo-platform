<?php

namespace Educatudo\Middleware;

use Educatudo\Core\{Request, Response, Database};

class AdminEscolaMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        // Garantir que a sessão está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        
        if (!isset($_SESSION['user_id'])) {
            $app = \Educatudo\Core\App::getInstance();
            $basePath = $app->getBasePath();
            $response->redirect($basePath . '/login')->send();
            return false;
        }

        // Verificar tipo na sessão
        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin_escola') {
            $app = \Educatudo\Core\App::getInstance();
            $basePath = $app->getBasePath();
            $response->redirect($basePath . '/unauthorized')->send();
            return false;
        }

        return true;
    }
}
