<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home.index', [
            'title' => 'Educatudo - Plataforma Educacional'
        ]);
    }
}