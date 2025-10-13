<?php

namespace Educatudo\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function json(array $data): self
    {
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
        return $this;
    }

    public function redirect(string $url, int $statusCode = 302): self
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        return $this;
    }

    public function view(string $view, array $data = []): self
    {
        $viewPath = __DIR__ . '/../../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View não encontrada: {$view}");
        }

        // Extrair variáveis para o escopo da view
        extract($data);
        
        // Capturar o conteúdo da view
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        $this->setContent($content);
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        echo $this->content;
        
        // Se for redirect, parar execução
        if (isset($this->headers['Location'])) {
            exit();
        }
    }
}
