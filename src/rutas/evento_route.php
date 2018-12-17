<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Src\Middleware\AuthMiddleware;

$app->group('/evento/', function(){
    $this->post('crear', function(Request $request, Response $response){
        $Id = $request->getAttribute('id');   
        $evento = $request->getParsedBody();
        $evento["IdOrganizador"] = $Id;
        /*
        $r = EventoValidation::validate($request->getParsedBody());
    
        if (!$r->response) {
            return $response->withJson($r->errors, 422);
        };
        */
        return $response->withJson($this->model->evento->saveEvento($evento), 200);
    });

    $this->get('obtener/{day}', function(Request $request, Response $response, $args){   
        $dia = $args["day"];
        $idUsuario = $request->getAttribute('id');   

        return $response->withJson($this->model->evento->getEvento($dia, $idUsuario), 200);
    });

    $this->get('jugar/{id}', function(Request $request, Response $response, $args){   
        $id = $args["id"];
        $resp = $this->model->evento->getEventoJugar($id);
        if(count($resp->result) > 0){
            $resp->SetResponse(true);
            return $response->withJson($resp, 200);
        }else{
            $resp->SetResponse(false);
            return $response->withJson($resp, 400);
        }
    });

    $this->post('participar', function(Request $request, Response $response){
        $eventoParticpar = $request->getParsedBody();
        $eventoParticpar['estado'] = true;
        $eventoParticpar['IdUsuario'] = $request->getAttribute('id');

        $resp = $this->model->evento->postEventoParticipar($eventoParticpar);

        return $response->withJson($resp, 200);
    });

    $this->get('participantes/{id}', function(Request $request, Response $response, $args){
        $id = $args["id"];
        $resp = $this->model->evento->getParticipantes($id);
        
        if(count($resp->result) > 0){
            $resp->SetResponse(true);
            return $response->withJson($resp, 200);
        }else{
            $resp->SetResponse(false);
            return $response->withJson($resp, 200);
        }
    });

    $this->delete('borrarme/{idEvento}', function(Request $request, Response $response, $args){
        $idEvento = $args["idEvento"];
        $idUsuario = $request->getAttribute('id');

        $resp = $this->model->evento->deleteBorrarme($idEvento, $idUsuario);
        return $response->withJson($resp , 200);
    });

    $this->delete('borrar/{idEvento}', function(Request $request, Response $response, $args){
        $idEvento = $args["idEvento"];
        $idUsuario = $request->getAttribute('id');
    
        $resp = $this->model->evento->deleteEvento($idEvento, $idUsuario);
        if($resp->response === true ){
            return $response->withJson($resp , 200);
        }else{
            return $response->withJson($resp , 422);
        }
    });

})->add(new AuthMiddleware($app));