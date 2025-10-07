<?php

namespace Educatudo\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function get(string $path, string $handler, array $middlewares = []): self
    {
        return $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, string $handler, array $middlewares = []): self
    {
        return $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, string $handler, array $middlewares = []): self
    {
        return $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function delete(string $path, string $handler, array $middlewares = []): self
    {
        return $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    public function middleware(string $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    private function addRoute(string $method, string $path, string $handler, array $middlewares = []): self
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => array_merge($this->middlewares, $middlewares)
        ];
        
        // Reset middlewares para próxima rota
        $this->middlewares = [];
        
        return $this;
    }

    public function match(string $method, string $path): ?array
    {
        // Remover query string da URI
        $path = parse_url($path, PHP_URL_PATH);
        
        // Garantir que path comece com /
        if (empty($path) || $path === '/') {
            $path = '/';
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);
            if (preg_match($pattern, $path, $matches)) {
                // Remover o primeiro match (path completo)
                array_shift($matches);
                
                return [
                    'handler' => $route['handler'],
                    'middlewares' => $route['middlewares'],
                    'params' => $matches
                ];
            }
        }

        return null;
    }

    private function convertToRegex(string $path): string
    {
        // Converter {id} para regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
