<?php
// Configuração Manual do Educatudo
// Descomente e ajuste conforme necessário

// Para mudar o nome da pasta, altere apenas esta linha:
$FOLDER_NAME = 'educatudo'; // Mude para qualquer nome que quiser

// Para mudar a URL base, altere esta linha:
$BASE_URL = 'http://localhost/' . $FOLDER_NAME;

// Para mudar o basePath, altere esta linha:
$BASE_PATH = '/' . $FOLDER_NAME;

// Configurações do sistema
return [
    'app' => [
        'name' => 'Educatudo Platform',
        'version' => '1.0.0',
        'url' => $BASE_URL,
        'base_path' => $BASE_PATH,
        'timezone' => 'America/Sao_Paulo',
        'debug' => true,
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'educatudo_platform',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'auth' => [
        'session_timeout' => 3600,
        'remember_me_days' => 30,
        'max_login_attempts' => 5,
        'lockout_duration' => 900,
    ],
    'upload' => [
        'max_size' => 10485760,
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
