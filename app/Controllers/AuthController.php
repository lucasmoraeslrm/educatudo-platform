<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class AuthController extends Controller
{
    public function showLogin()
    {
        if ($this->getUser()) {
            return $this->redirectToDashboard();
        }
        
        return $this->view('auth.login', [
            'title' => 'Login - Educatudo',
            'csrf_token' => $this->generateCSRFToken(),
            'currentSchool' => $this->app->getCurrentSchool()
        ]);
    }

    public function login()
    {
        if ($this->getUser()) {
            return $this->redirectToDashboard();
        }

        $login = $this->sanitize($this->request->post('login', ''));
        $password = $this->request->post('password', '');
        $csrfToken = $this->request->post('csrf_token', '');

        if (!$this->verifyCSRFToken($csrfToken)) {
            return $this->view('auth.login', [
                'error' => 'Token CSRF inválido.',
                'login' => $login,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }

        if (empty($login) || empty($password)) {
            return $this->view('auth.login', [
                'error' => 'Por favor, preencha todos os campos.',
                'login' => $login,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }

        $user = $this->authenticateUser($login, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['escola_id'] = $user['escola_id'];
            $_SESSION['user_email'] = $user['email'];
            
            return $this->redirectToDashboard();
        }

        return $this->view('auth.login', [
            'error' => 'Login ou senha incorretos.',
            'login' => $login,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function logout()
    {
        session_destroy();
        return $this->redirect('/login');
    }

    private function authenticateUser(string $login, string $password): ?array
    {
        $userModel = new \Educatudo\Models\Usuario($this->db);
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $professorModel = new \Educatudo\Models\Professor($this->db);
        
        $currentSchool = $this->app->getCurrentSchool();
        $escolaId = null;
        
        if ($currentSchool) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->findBySubdomain($currentSchool);
            $escolaId = $escola['id'] ?? null;
        }

        // Tentar autenticação por email (admins e pais)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = $userModel->findByEmail($login);
            if ($user && $userModel->verifyPassword($password, $user['senha_hash'])) {
                // Verificar se é da escola correta (se aplicável)
                if ($escolaId && $user['escola_id'] !== $escolaId) {
                    return null;
                }
                return $user;
            }
        }

        // Tentar autenticação por RA (alunos)
        if ($escolaId) {
            $aluno = $alunoModel->findByRa($login, $escolaId);
            if ($aluno) {
                $user = $userModel->find($aluno['usuario_id']);
                if ($user && $userModel->verifyPassword($password, $user['senha_hash'])) {
                    return $user;
                }
            }
        }

        // Tentar autenticação por código do professor
        if ($escolaId) {
            $professor = $professorModel->findByCodigo($login, $escolaId);
            if ($professor) {
                $user = $userModel->find($professor['usuario_id']);
                if ($user && $userModel->verifyPassword($password, $user['senha_hash'])) {
                    return $user;
                }
            }
        }

        return null;
    }

    private function redirectToDashboard()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect('/login');
        }

        switch ($user['tipo']) {
            case 'super_admin':
                return $this->redirect('/admin');
            case 'admin_escola':
                return $this->redirect('/escola');
            case 'professor':
                return $this->redirect('/professor');
            case 'aluno':
                return $this->redirect('/aluno');
            case 'pai':
                return $this->redirect('/pais');
            default:
                return $this->redirect('/login');
        }
    }
}