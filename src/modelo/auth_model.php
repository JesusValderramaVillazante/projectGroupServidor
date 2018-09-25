<?php
namespace Src\Modelo;
use Src\Lib\Response;
use Src\Lib\Auth;

class AuthModel{
    private $db;
    private $table = 'usuario';
    private $response;

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function autenticar($correo, $password){
        $usuario = $this->db->from($this->table)
        ->where('Correo', $correo)
        ->where('Password', $password)
        ->fetch();
        
        if (is_object($usuario)) {
            $nombre = explode(' ', $usuario->nombre)[0];

            $token = Auth::SignIn([
                'id' => $usuario->id,
                'Nombre' => $nombre,
                'NombreCompleto' => $usuario->nombre,
                'EsAdmin' => (bool)$usuario->EsAdmin
            ]);

            $this->response->result = $token;

            return $this->response->SetResponse(true, 'todo ok');
        }else{
            return $this->response->SetResponse(false, "Credenciales no validos");
        }

        return $usuario;
    }
}