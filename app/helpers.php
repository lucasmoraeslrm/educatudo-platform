<?php
/**
 * Funções auxiliares globais
 */

use Educatudo\Core\App;

/**
 * Gera URL com basePath correto
 */
function url(string $path = ''): string
{
    $app = App::getInstance();
    $basePath = $app->getBasePath();
    $path = ltrim($path, '/');
    
    if (empty($path)) {
        return $basePath ?: '/';
    }
    
    return $basePath . '/' . $path;
}

/**
 * Redireciona para uma URL
 */
function redirect(string $path, int $statusCode = 302): void
{
    header('Location: ' . url($path), true, $statusCode);
    exit;
}

/**
 * Retorna o basePath atual
 */
function basePath(): string
{
    $app = App::getInstance();
    return $app->getBasePath();
}

