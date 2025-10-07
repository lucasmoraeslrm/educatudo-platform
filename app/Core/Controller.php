<?php

namespace Educatudo\Core;

class Controller
{
    protected App $app;
    protected Database $db;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->app = App::getInstance();
        $this->db = Database::getInstance($this->app);
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view(string $view, array $data = []): Response
    {
        // Adicionar dados globais para todas as views
        $data['app'] = $this->app;
        $data['basePath'] = $this->app->getBasePath();
        $data['currentSchool'] = $this->app->getCurrentSchool();
        $data['user'] = $this->getUser(); // Adicionar dados do usuário
        
        return $this->response->view($view, $data);
    }

    protected function json(array $data, int $statusCode = 200): Response
    {
        return $this->response->setStatusCode($statusCode)->json($data);
    }

    protected function redirect(string $url, int $statusCode = 302): Response
    {
        // Se URL não começar com http, adicionar base path
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = $this->app->url($url);
        }
        
        return $this->response->redirect($url, $statusCode);
    }

    protected function getUser(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        // Verificar se o user_id é válido
        $userId = $_SESSION['user_id'];
        if (!is_numeric($userId)) {
            // Se não for numérico, limpar a sessão
            unset($_SESSION['user_id']);
            return null;
        }
        
        $userModel = new \Educatudo\Models\Usuario($this->db);
        return $userModel->find($userId);
    }

    protected function requireAuth(): void
    {
        if (!$this->getUser()) {
            $this->redirect('/login')->send();
            exit;
        }
    }

    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        if ($user['tipo'] !== $role) {
            $this->redirect('/unauthorized')->send();
            exit;
        }
    }

    protected function sanitize(string $data): string
    {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    protected function generateCSRFToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCSRFToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    protected function getCurrentSchool(): ?array
    {
        $schoolSubdomain = $this->app->getCurrentSchool();
        if (!$schoolSubdomain) {
            return null;
        }

        $escolaModel = new \Educatudo\Models\Escola($this->db);
        return $escolaModel->findBySubdomain($schoolSubdomain);
    }
}
