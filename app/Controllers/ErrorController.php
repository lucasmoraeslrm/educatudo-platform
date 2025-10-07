<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class ErrorController extends Controller
{
    public function notFound()
    {
        return $this->view('errors.404', [
            'title' => 'Página não encontrada - Educatudo'
        ])->setStatusCode(404);
    }

    public function unauthorized()
    {
        return $this->view('errors.403', [
            'title' => 'Acesso negado - Educatudo'
        ])->setStatusCode(403);
    }

    public function serverError()
    {
        return $this->view('errors.500', [
            'title' => 'Erro interno - Educatudo'
        ])->setStatusCode(500);
    }
}
