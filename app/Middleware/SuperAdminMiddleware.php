<?php

namespace Educatudo\Middleware;

use Educatudo\Core\{Request, Response, Database};

class SuperAdminMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        if (!isset($_SESSION['user_id'])) {
            $response->redirect('/login')->send();
            return false;
        }

        $db = Database::getInstance();
        $sql = "SELECT tipo FROM usuarios WHERE id = :id";
        $user = $db->fetch($sql, ['id' => $_SESSION['user_id']]);

        if (!$user || $user['tipo'] !== 'super_admin') {
            $response->redirect('/unauthorized')->send();
            return false;
        }

        return true;
    }
}
