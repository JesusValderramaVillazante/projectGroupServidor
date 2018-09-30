<?php
namespace Src\Modelo;
use Src\Lib\Response;
use Src\Lib\Auth;

class NuevoUsuario{
    private $db;
    private $table = 'usuario';
    private $response;

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function registrar($data){
        $data['password'] = md5($data['password']);
        unset($data['esAdmin']);
             
        $query = $this->db->from($this->table)->where('email', $data['email']);
        
        if(!count($query)){
            $this->db->insertInto($this->table, $data)->execute();
            return $this->response->SetResponse(true);
        }else{
            $this->response->errors['email'][] = 'existente';
            return $this->response->SetResponse(false);
        }
    }

    public function findCorreo($data){
        $query = $this->db->from($this->table)->where('email', $data['email']);
        return $query;
    }

}