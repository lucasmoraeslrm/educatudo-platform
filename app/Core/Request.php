<?php

namespace Educatudo\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;
    private array $cookies;
    private array $session;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->session = $_SESSION ?? [];
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function input(string $key = null, $default = null)
    {
        $value = $this->post($key);
        if ($value === null) {
            $value = $this->get($key);
        }
        return $value ?? $default;
    }

    public function file(string $key = null)
    {
        if ($key === null) {
            return $this->files;
        }
        return $this->files[$key] ?? null;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function uri(): string
    {
        $uri = $this->server['REQUEST_URI'];
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remover base path se existir
        $app = App::getInstance();
        $basePath = $app->getBasePath();
        
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        return rtrim($uri, '/') ?: '/';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }

    public function has(string $key): bool
    {
        return $this->input($key) !== null;
    }

    public function only(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->input($key);
        }
        return $data;
    }

    public function except(array $keys): array
    {
        $data = $this->input();
        foreach ($keys as $key) {
            unset($data[$key]);
        }
        return $data;
    }

    public function validate(array $rules): array
    {
        $data = $this->input();
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleArray = is_string($rule) ? explode('|', $rule) : $rule;

            foreach ($ruleArray as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field][] = "O campo {$field} é obrigatório.";
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "O campo {$field} deve ser um email válido.";
                } elseif (strpos($r, 'min:') === 0) {
                    $min = (int) substr($r, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "O campo {$field} deve ter pelo menos {$min} caracteres.";
                    }
                }
            }
        }

        return $errors;
    }

    public function getJsonBody(): array
    {
        $json = file_get_contents('php://input');
        if (empty($json)) {
            return [];
        }
        
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}
