<?php
// Arquivo de login direto para testar
require_once __DIR__ . '/vendor/autoload.php';

use Educatudo\Controllers\AuthController;

$controller = new AuthController();
$result = $controller->showLogin();

if ($result instanceof \Educatudo\Core\Response) {
    $result->send();
} else {
    echo $result;
}
?>
