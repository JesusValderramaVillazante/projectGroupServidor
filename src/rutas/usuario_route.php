<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Src\Middleware\AuthMiddleware;
use Src\Validation\UsuarioValidation;

$app->group('/usuario/', function(){
    $this->get('datos', function(Request $request, Response $response){
        $id = $request->getAttribute('id');    
        return $response->withJson( $this->model->usuario->obtener( $id ), 200 );
    });

    $this->put('actualizar', function(Request $request, Response $response){
        $id = $request->getAttribute('id');
        $r = UsuarioValidation::validate($request->getParsedBody(), true);

        if (!$r->response) {
            return $response->withJson($r->errors, 422);
        }

        return $response->withJson( $this->model->usuario->actualizar( $request->getParsedBody(), $id) , 200 );
    });
})->add(new AuthMiddleware($app));
