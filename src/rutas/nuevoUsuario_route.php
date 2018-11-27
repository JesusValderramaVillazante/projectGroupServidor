<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Src\Validation\UsuarioValidation;

$app->post('/usuario/nuevo', function(Request $request, Response $response){
    $existCorreo;
    
    if( array_key_exists('email', $request->getParsedBody()) ){
        $query = $this->model->nuevoUsuario->findCorreo( $request->getParsedBody() );
        $existCorreo = count($query);
    }else{
        $existCorreo = false;
    };

    $r = UsuarioValidation::validate($request->getParsedBody(), false, $existCorreo);
    
    if (!$r->response) {
        return $response->withJson($r->errors, 422);
    };

    return $response->withJson( $this->model->nuevoUsuario->registrar( $request->getParsedBody() ), 200);
});