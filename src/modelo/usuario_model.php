<?php
namespace Src\Modelo;
use Src\Lib\Response;

class Usuario{
    private $db;
    private $table = 'usuario';
    private $response;
    private $table_evento = 'evento';
    private $table_usuario_evento = 'usuario_evento';

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function obtener($id){
        $this->response->result = $this->db->from($this->table, $id)
                                            ->select(null)
                                            ->select([
                                                'nombre', 
                                                'email', 
                                                'id', 
                                                'fotoUrl', 
                                                'esAdmin',
                                                'fechaNacimiento',
                                                'genero',
                                                'numeroMovil'
                                                ])->fetch();
        return $this->response->SetResponse(true);
    }

    public function actualizar($data, $id){
        if (isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        unset($data['esAdmin']);
        $this->db->update($this->table, $data, $id)->execute();
        
        return $this->response->SetResponse(true);
    }

    public function guardarFoto($id, $fotoUrl){
        $this->db->update($this->table, ['fotoUrl'=>$fotoUrl], $id)->execute();
        return $this->response->SetResponse(true);
    } 

    public function eventoApuntado($idUsuario, $idEvento){
        $countUser = $this->db->from($this->table_usuario_evento)         
                              ->where('IdEvento', $idEvento)
                              ->where('IdUsuario', $idUsuario)
                              ->fetch();
        if($countUser){
            return $this->response->SetResponse(true, "apuntado");
        }else{
            return $this->response->SetResponse(false, "no apuntado");
        }
    }

    public function eventoOrganiza($idUsuario, $idEvento){
        $usuarioOrganizador = $this->db->from($this->table_evento)
                              ->where('Id', $idEvento)
                              ->where('IdOrganizador', $idUsuario)
                              ->fetch();                     
        if($usuarioOrganizador){
            return $this->response->SetResponse(true, "organiza");
        }else{
            return $this->response->SetResponse(false, "no organiza");
        }
    }

    public function eliminar($idUsuario){
        $eliminarUsuario = $this->db->deleteFrom($this->table, $idUsuario)->execute();
        if($eliminarUsuario){
            return $this->response->SetResponse(true, "usuario eliminado");
        }else{
            return $this->response->SetResponse(false, "no se puede eliminar");
        }
    }
}