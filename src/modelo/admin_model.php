<?php
namespace Src\Modelo;
use Src\Lib\Response;

class Admin{
    private $db;
    private $table_usuario = 'usuario';
    private $table_evento = 'evento';
    private $response;

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function getUsuarios(){
        $this->response->result = $this->db->from($this->table_usuario)
                                           ->select(null)
                                           ->select([
                                            'nombre', 
                                            'email', 
                                            'id', 
                                            'fotoUrl', 
                                            'fechaNacimiento',
                                            'genero',
                                            'numeroMovil'
                                            ])->fetchAll();
        
        return $this->response->SetResponse(true);
    }
}