<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface;
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

    $this->post('fotoPerfil', function(Request $request, Response $response){
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['fotoUrl'];
        $rutaBase = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
        $id = $request->getAttribute('id');
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $uploadedFile->moveTo($rutaBase.$filename);
        
        $rutaBd = 'http://group.es/uploads/'.$filename;
     
        return $response->withJson( $this->model->usuario->guardarFoto( $id,  $rutaBd), 200 );
    });
})->add(new AuthMiddleware($app));
