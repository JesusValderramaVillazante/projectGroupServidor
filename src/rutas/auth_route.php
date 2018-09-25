<?php
$app->group('/auth/', function(){
    $this->post('auntenticar', function($req, $res, $args){
        $param = $req->getParsedBody();
        $data = $this->model->auth->autenticar($param['Correo'], $param['Password']);
        return $res->withJson($data);
    });
});