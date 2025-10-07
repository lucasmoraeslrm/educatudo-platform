<?php

namespace Educatudo\Middleware;

use Educatudo\Core\{Request, Response};

class AuthMiddleware
{
    public function handle(Request $request, Response $response): bool
    {
        if (!isset($_SESSION['user_id'])) {
            $response->redirect('/login')->send();
            return false;
        }
        return true;
    }
}
