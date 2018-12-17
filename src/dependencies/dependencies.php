<?php
// DIC configuration
use \Slim\Views\PhpRenderer as View;

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    return new View($c->settings['renderer']['template_path']);
};

// database
$container['db'] = function ($c) {
    $connectionString = $c->settings['$connectionString'];
    
    $pdo = new PDO($connectionString['dns'], $connectionString['user'], $connectionString['pass']);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    return new FluentPDO($pdo);
};

// models
// $c = container;
$container['model'] = function($c){
    return (object)[
        'auth' => new Src\Modelo\AuthModel($c->db),
        'nuevoUsuario' => new Src\Modelo\NuevoUsuario($c->db),
        'usuario' => new Src\Modelo\Usuario($c->db),
        'evento' => new Src\Modelo\Evento($c->db),
        'admin' => new Src\Modelo\Admin($c->db)
    ];
};