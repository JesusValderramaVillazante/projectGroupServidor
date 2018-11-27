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
    
    public static function validate($data, $update = false , $existencia = false){
        $response = new Response();
    
        $key = 'nombre';
        if ( !$update ) {
            if( empty( $data[$key] )){
                $response->errors[$key][] = 'Este campor es obligatorio';
            }else{
                $value = $data[$key];

                if(strlen(self::test_input($value)) < 2 || strlen(self::test_input($value)) > 30){
                    $response->errors[$key][] = 'Debe contener como minimo 2 caracteres y maximo 30 caracteres';
                }
            }
        }else{
            if (array_key_exists($key, $data)) {
                $value = $data[$key];
                if( strlen(self::test_input($value)) < 2 || strlen(self::test_input($value)) > 30){
                    $response->errors[$key][] = 'Debe contener como minimo 2 caracteres y maximo 30 caracteres';
                }
            }
        }

        $key = 'email';
        if ( !$update ) {
            if( empty( $data[$key] )){
                $response->errors[$key][] = 'Este campo es obligatorio';
            }else{
                $value = $data[$key];

                if( !filter_var($value, FILTER_VALIDATE_EMAIL) || strlen(self::test_input($value)) > 40 || strlen(self::test_input($value)) < 2){
                    $response->errors[$key][] = 'Valor ingresado no es un correo valido';
                }
            }
        }else{
            if (array_key_exists($key, $data)) {
                $value = $data[$key];
                if( !filter_var($value, FILTER_VALIDATE_EMAIL) || strlen(self::test_input($value)) > 40 || strlen(self::test_input($value)) < 2){
                    $response->errors[$key][] = 'Valor ingresado no es un correo valido';
                }
            }
        }

        $key = 'password';
        if ( !$update ) {
            if (empty( $data[$key] )) {
                $response->errors[$key][] = 'Este campo es obligatorio';
            }else{
                $value = $data[$key];

                if (strlen(self::test_input($value)) < 2 || strlen(self::test_input($value)) > 40) {
                    $response->errors[$key][] = 'Debe contener como minimo 2 caracteres y maximo 40 caracteres';
                }
            }
        }else{
            
            if (array_key_exists($key, $data)) {
                $value = $data[$key];

                if(strlen(self::test_input($value)) < 2 || strlen(self::test_input($value)) > 40){
                    $response->errors[$key][] = 'Debe contener como minimo 2 caracteres y maximo 40 caracteres';
                }
            }
        }
        
        $key = 'email';
        if($existencia){
            $response->errors[$key][] = 'ya existe';
        }


        $response->SetResponse(count($response->errors) === 0);

        return $response;
    }
}
