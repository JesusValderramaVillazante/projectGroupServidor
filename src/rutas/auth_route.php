<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->group('/auth/', function(){
    $this->post('auntenticar', function(Request $request, Response $response, $arguments){
        $param = $request->getParsedBody();
        $data = $this->model->auth->autenticar($param['email'], $param['password']);
        return $response->withJson($data);
    });
});