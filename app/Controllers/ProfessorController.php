<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class ProfessorController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        
        return $this->view('professor.index', [
            'title' => 'Painel do Professor - Educatudo'
        ]);
    }

    public function jornadas()
    {
        $this->requireAuth();
        
        return $this->view('professor.jornadas', [
            'title' => 'Jornadas do Aluno - Educatudo'
        ]);
    }

    public function createJornada()
    {
        $this->requireAuth();
        
        return $this->view('professor.create-jornada', [
            'title' => 'Criar Jornada - Educatudo'
        ]);
    }

    public function storeJornada()
    {
        $this->requireAuth();
        
        return $this->redirect('/professor/jornadas');
    }

    public function exercicios()
    {
        $this->requireAuth();
        
        return $this->view('professor.exercicios', [
            'title' => 'Exercícios - Educatudo'
        ]);
    }

    public function relatorios()
    {
        $this->requireAuth();
        
        return $this->view('professor.relatorios', [
            'title' => 'Relatórios - Educatudo'
        ]);
    }
}
