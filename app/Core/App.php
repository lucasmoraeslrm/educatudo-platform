<?php

namespace Educatudo\Core;

class App
{
    private static ?App $instance = null;
    private array $config;
    private string $basePath;
    private ?string $currentSchool = null;

    private function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->basePath = $this->config['app']['base_path'];
        
        // Configurar timezone
        date_default_timezone_set($this->config['app']['timezone']);
        
        // Configurar error reporting
        if ($this->config['app']['debug']) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }
        
        // Detectar escola atual (subdomínio ou parâmetro)
        $this->detectCurrentSchool();
    }

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->config;
        }

        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function setBasePath(string $path): void
    {
        $this->basePath = $path;
        $this->config['app']['base_path'] = $path;
    }

    public function getCurrentSchool(): ?string
    {
        return $this->currentSchool;
    }

    public function setCurrentSchool(?string $school): void
    {
        $this->currentSchool = $school;
    }

    public function url(string $path = ''): string
    {
        $baseUrl = $this->getConfig('app.url');
        $basePath = $this->getBasePath();
        
        // Se há escola atual e subdomínios estão habilitados
        if ($this->currentSchool && $this->getConfig('subdomains.enabled')) {
            $mainDomain = $this->getConfig('subdomains.main_domain');
            $baseUrl = "http://{$this->currentSchool}.{$mainDomain}";
        }
        
        return rtrim($baseUrl . '/' . ltrim($path, '/'), '/');
    }

    public function asset(string $path): string
    {
        return $this->url('public/assets/' . ltrim($path, '/'));
    }

    public function route(string $route, array $params = []): string
    {
        $url = $this->url($route);
        
        foreach ($params as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        
        return $url;
    }

    private function detectCurrentSchool(): void
    {
        // Em desenvolvimento local, usar parâmetro GET
        if (!$this->getConfig('subdomains.enabled')) {
            $this->currentSchool = $_GET['escola'] ?? null;
            return;
        }

        // Em produção, detectar subdomínio
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $mainDomain = $this->getConfig('subdomains.main_domain');
        
        if (strpos($host, $mainDomain) !== false) {
            $subdomain = str_replace('.' . $mainDomain, '', $host);
            if ($subdomain !== 'www' && $subdomain !== 'admin') {
                $this->currentSchool = $subdomain;
            }
        }
    }

    public function isGlobalAdmin(): bool
    {
        return $this->currentSchool === null || $this->currentSchool === 'admin';
    }

    public function isSchoolAdmin(): bool
    {
        return $this->currentSchool !== null && $this->currentSchool !== 'admin';
    }
}
