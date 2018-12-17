<?php
namespace Src\Modelo;
use Src\Lib\Response;
use DateTime;

class Evento{
    private $db;
    private $table = 'evento';
    private $table_usuario_evento = 'usuario_evento';

    public function __CONSTRUCT($db){
        $this->db = $db;
        $this->response = new Response();
    }

    public function saveEvento($evento){
        $this->db->insertInto($this->table, $evento)->execute();
        return $this->response->SetResponse(true);
    }

    public function getEvento($d, $idUsuario){    
        $dia = intval($d);
        $datetime = new DateTime();

        if($dia == 1 | $dia == 2){
            $hora = $datetime->format('00:00');
        }else{
            $hora = $datetime->format('H:00');
        }
        
        if($dia === 0 || $dia === 1 || $dia === 2 ){
            $datetime->modify("+{$dia} day");
            $fecha = $datetime->format('Y-m-d');

            $eventos = $this->db->from('evento')->leftJoin('usuario ON usuario.id = evento.IdOrganizador')
                                                ->select(['usuario.nombre as organizador', 'usuario.fotoUrl'])
                                                ->where('evento.Dia', $fecha)
                                                ->where('evento.Hora > ?', $hora)
                                                ->orderBy('evento.Hora')
                                                ->fetchAll();
            
            foreach ($eventos as $evento) {
                $idEvento = $evento->Id;
                $idOrganizador = $evento->IdOrganizador;
                $respParticipa =  $this->db->from($this->table_usuario_evento)         
                                  ->where('IdEvento', $idEvento)
                                  ->where('IdUsuario', $idUsuario)
                                  ->fetch();    
                
                $numeroParticipantes = $this->db->from($this->table_usuario_evento)
                                  ->select(null)
                                  ->select('IdEvento, COUNT(*) AS participantes')
                                  ->groupBy('IdEvento')
                                  ->where('IdEvento', $idEvento)
                                  ->fetch('participantes');
                                  
                $evento->numeroParticipantes = (int)$numeroParticipantes;

                if($respParticipa){
                    $evento->Participa = true;
                }else{
                    $evento->Participa = false;
                }

                if($idOrganizador === $idUsuario){
                    $evento->Organiza = true;
                }else{
                    $evento->Organiza = false;
                }

            }

            $this->response->result = $eventos;
            return $this->response->SetResponse(true, $fecha);
        }else{
            return $this->response->SetResponse(false);
        }
    }

    public function getEventoJugar($id){
        $this->response->result = $this->db->from('evento')->leftJoin('usuario ON usuario.id = evento.IdOrganizador')
                                           ->select(['usuario.nombre as organizador', 'usuario.fotoUrl'])
                                           ->where('evento.id', $id)
                                           ->fetchAll();
        
        return $this->response;
    }

    public function postEventoParticipar($eventoParticipar){
        $countUser = $this->db->from($this->table_usuario_evento)         
                              ->where('IdEvento', $eventoParticipar['IdEvento'])
                              ->where('IdUsuario', $eventoParticipar['IdUsuario'])
                              ->fetch();
              
                     
        $eventoParticipantes = $this->db->from($this->table)
                                        ->where('Id', $eventoParticipar['IdEvento'])
                                        ->select(null)
                                        ->select('Participantes')
                                        ->fetch('Participantes');
           
        $numeroParticipantes = $this->db->from($this->table_usuario_evento)
                                        ->select(null)
                                        ->select('IdEvento, COUNT(*) AS participantes')
                                        ->groupBy('IdEvento')
                                        ->where('IdEvento', $eventoParticipar['IdEvento'])
                                        ->fetch('participantes');

        if(!$countUser && $numeroParticipantes < $eventoParticipantes){
            $this->db->insertInto($this->table_usuario_evento, $eventoParticipar)->execute();
            return $this->response->SetResponse(true);
        }else{
            return $this->response->SetResponse(false, 'Usuario esta apuntado o evento completo');
        };
    }

    public function getParticipantes($id){
        $this->response->result = $this->db->from($this->table_usuario_evento)
                                           ->leftJoin('usuario ON usuario.id = usuario_evento.idUsuario')
                                           ->select(['usuario.nombre', 'usuario.fotoUrl, usuario_evento.estado'])
                                           ->where('usuario_evento.IdEvento = ?', $id)
                                           ->fetchAll();
        
        return $this->response;
    }

    public function deleteBorrarme($idEvento, $idUsuario){
        $this->db->deleteFrom($this->table_usuario_evento)
                 ->where('IdEvento', $idEvento)
                 ->where('IdUsuario', $idUsuario)
                 ->execute();
        return $this->response->SetResponse(true);
    }

    public function deleteEvento($idEvento, $idUsuario){
        $usuarioOrganizador = $this->db->from($this->table)
                                ->where('Id', $idEvento)
                                ->where('IdOrganizador', $idUsuario)
                                ->fetch();
        if($usuarioOrganizador){
            $this->db->deleteFrom($this->table)->where('Id', $idEvento)->execute();
            return $this->response->SetResponse(true, "Borrado con exito");
        }else{
            return $this->response->SetResponse(false, "Solo los organizadores puede borrar sus eventos");
        }
     }
}