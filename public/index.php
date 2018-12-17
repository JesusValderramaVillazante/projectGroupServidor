<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, app-token, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

use \Slim\Views\PhpRenderer as View;
use \Slim\App as App;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
};

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/configuracion/settings.php';
$app = new App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies/dependencies.php';

// Register routes
require __DIR__ . '/../src/rutas/home.php';
require __DIR__ . '/../src/rutas/auth_route.php';
require __DIR__ . '/../src/rutas/nuevoUsuario_route.php';
require __DIR__ . '/../src/rutas/usuario_route.php';
require __DIR__ . '/../src/rutas/evento_route.php';
require __DIR__ . '/../src/rutas/admin_route.php';

$app->run();


