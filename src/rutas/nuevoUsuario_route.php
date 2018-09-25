<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Src\Validation\UsuarioValidation;

$app->post('/nuevo/usuario', function(Request $request, Response $response){
    
    $r = UsuarioValidation::validate($request->getParsedBody());
    
    if (!$r->response) {
        return $response->withJson($r->errors, 422);
    };

    return $response->withJson($this->model->nuevoUsuario->registrar( $request->getParsedBody() ));
});