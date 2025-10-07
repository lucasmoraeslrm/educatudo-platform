<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class AlunoController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        
        return $this->view('aluno.index', [
            'title' => 'Painel do Aluno - Educatudo'
        ]);
    }

    public function chatTudinha()
    {
        $this->requireAuth();
        
        return $this->view('aluno.chat-tudinha', [
            'title' => 'Chat Tudinha - Educatudo'
        ]);
    }

    public function exercicios()
    {
        $this->requireAuth();
        
        return $this->view('aluno.exercicios', [
            'title' => 'Exercícios - Educatudo'
        ]);
    }

    public function redacao()
    {
        $this->requireAuth();
        
        return $this->view('aluno.redacao', [
            'title' => 'Redação - Educatudo'
        ]);
    }

    public function vestibulares()
    {
        $this->requireAuth();
        
        return $this->view('aluno.vestibulares', [
            'title' => 'Vestibulares - Educatudo'
        ]);
    }

    public function jogos()
    {
        $this->requireAuth();
        
        return $this->view('aluno.jogos', [
            'title' => 'Jogos Educativos - Educatudo'
        ]);
    }
}
