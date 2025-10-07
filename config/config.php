<?php
// Configuração principal do Educatudo - VERSÃO FLEXÍVEL

// Detectar automaticamente o basePath baseado no ambiente
function detectBasePath() {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Se estiver rodando via linha de comando (CLI), usar caminho padrão
    if (php_sapi_name() === 'cli') {
        return '/educatudo';
    }
    
    // Se estiver rodando em produção (domínio próprio)
    if ($host !== 'localhost' && $host !== '127.0.0.1') {
        return ''; // Sem basePath em produção
    }
    
    // Se estiver rodando na raiz do servidor local
    if (strpos($scriptName, '/educatudo') !== false) {
        return '/educatudo';
    }
    
    // Se estiver rodando em subdiretório
    $pathInfo = pathinfo($scriptName);
    if ($pathInfo['dirname'] !== '/' && $pathInfo['dirname'] !== '.') {
        return $pathInfo['dirname'];
    }
    
    // Padrão para desenvolvimento local
    return '/educatudo';
}

// Detectar URL base automaticamente
function detectBaseUrl() {
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $basePath = detectBasePath();
    
    return $protocol . '://' . $host . $basePath;
}

return [
    'app' => [
        'name' => 'Educatudo',
        'version' => '1.0.0',
        'url' => detectBaseUrl(),
        'base_path' => detectBasePath(), // Detectado automaticamente
        'timezone' => 'America/Sao_Paulo',
        'debug' => true,
    ],
    'database' => [
        'host' => '186.209.113.149',
        'name' => 'educatudo_platform',
        'user' => 'educatudo_platform',
        'pass' => '117910Campi!25',
        'charset' => 'utf8mb4',
    ],
    'auth' => [
        'session_timeout' => 3600, // 1 hora
        'remember_me_days' => 30,
        'max_login_attempts' => 5,
        'lockout_duration' => 900, // 15 minutos
    ],
    'upload' => [
        'max_size' => 10485760, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'path' => 'uploads/',
    ],
    'email' => [
        'smtp_host' => 'localhost',
        'smtp_port' => 587,
        'smtp_user' => '',
        'smtp_pass' => '',
        'from_email' => 'noreply@educatudo.com',
        'from_name' => 'Educatudo Platform',
    ],
    'ai' => [
        'openai_api_key' => '',
        'chat_model' => 'gpt-3.5-turbo',
        'max_tokens' => 1000,
    ],
];