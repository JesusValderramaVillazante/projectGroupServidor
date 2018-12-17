<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Src\Middleware\AuthMiddleware;

$app->group('/admin/', function(){
    $this->get('usuarios', function(Request $request, Response $response){
        $isAdmin = $request->getAttribute('isAdmin');
        if($isAdmin === true){
            $resp = $this->model->admin->getUsuarios();
            return $response->withJson($resp);
        }else{
            return $response->withJson(false, 401);
        }
    });
})->add(new AuthMiddleware($app));