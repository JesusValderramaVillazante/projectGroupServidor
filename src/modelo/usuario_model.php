<?php
namespace Src\Modelo;
use Src\Lib\Response;

class Usuario{
    private $db;
    private $table = 'usuario';
    private $response;

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function obtener($id){
        $this->response->result = $this->db->from($this->table, $id)->fetch();
        return $this->response->SetResponse(true); ;
    }

    public function actualizar($data, $id){
        if (isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        unset($data['esAdmin']);
        $this->db->update($this->table, $data, $id)->execute();
        
        return $this->response->SetResponse(true);
    }
}