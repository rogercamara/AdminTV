<?php

// Define root path
// Se o index.php estiver na pasta public, sobe um nível. Se estiver na raiz (junto com app), usa o diretório atual.
define('ROOT_PATH', is_dir(__DIR__ . '/app') ? __DIR__ : dirname(__DIR__));

// Handle PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $arquivo = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($arquivo)) {
        return false;
    }
}

// Carrega o autoloader (pra achar as classes sozinho)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/'; // Usando ROOT_PATH definido acima

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Inicia a sessão
session_start();

// Carrega a Configuração
require_once ROOT_PATH . '/app/Core/Configuracao.php';

// Inicializa o Roteador
use App\Core\Roteador; // Mudado de Router para Roteador

// Pega a URL que o usuário digitou
$url = $_GET['url'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove a barra inicial se presente para corresponder ao formato esperado (opcional, mas o Roteador geralmente espera 'admin' e não '/admin')
$url = ltrim($url, '/');

// Chama o Roteador pra decidir o destino
// Roger
$roteador = new Roteador(); // Mudado de $router = new Router() para $roteador = new Roteador()
$roteador->despachar($url); // Mudado de dispatch para despachar
