<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class PaisController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        
        return $this->view('pais.index', [
            'title' => 'Painel dos Pais - Educatudo'
        ]);
    }

    public function desempenho()
    {
        $this->requireAuth();
        
        return $this->view('pais.desempenho', [
            'title' => 'Desempenho do Filho - Educatudo'
        ]);
    }

    public function relatorios()
    {
        $this->requireAuth();
        
        return $this->view('pais.relatorios', [
            'title' => 'Relatórios - Educatudo'
        ]);
    }
}
