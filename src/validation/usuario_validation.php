<?php
namespace Src\Validation;
use Src\Lib\Response;

class UsuarioValidation{
    public static function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    public static function validate($data, $update = false){
        $response = new Response();
        
        $key = 'nombre';
        if ( !$update ) {
            if( empty( self::test_input($data[$key]) )){
                $response->errors[$key][] = 'Este campor es obligatorio';
            }else{
                $value = self::test_input($data[$key]);

                if(strlen($value) < 4){
                    $response->errors[$key][] = 'Debe contener como monimo 4 caracteres';
                }
            }
        }else{
            if (!empty(self::test_input($data[$key]))) {
                $value = self::test_input($data[$key]);

                if( strlen($value) < 4 ){
                    $response->errors[$key][] = 'Debe contener como monimo 4 caracteres';
                }
            }
        }

        $key = 'email';
        if ( !$update ) {
            if(empty( self::test_input($data[$key]) )){
                $response->errors[$key][] = 'Este campo es obligatorio';
            }else{
                $value = self::test_input($data[$key]);

                if( !filter_var($value, FILTER_VALIDATE_EMAIL) ){
                    $response->errors[$key][] = 'Valor ingresado no es un correo valido';
                }
            }
        }else{
            if (!empty(self::test_input($data[$key]))) {
                $value = self::test_input($data[$key]);

                if( !filter_var($value, FILTER_VALIDATE_EMAIL) ){
                    $response->errors[$key][] = 'Valor ingresado no es un correo valido';
                }
            }
        }

        $key = 'password';
        if ( !$update ) {
            if (empty( self::test_input($data[$key]) )) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }else{
                $value = self::test_input($data[$key]);

                if (strlen($value) < 4) {
                    $response->errors[$key][] = 'Debe contener como minimo 4 caracteres';
                }
            }
        }else{
            if (!empty(self::test_input($data[$key]))) {
                $value = self::test_input($data[$key]);

                if(strlen($value) < 4){
                    $response->errors[$key][] = 'Debe contener como minimo 4 caracteres';
                }
            }
        }
        
        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
