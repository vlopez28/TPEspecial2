<?php

/*function base64url_encode($data){
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); //corta a la derecha los iguales y reemplaza
                                                                //los menos por los mas y barra
}*/

class AuthApiHelper{
    private $key;

    function __construct(){
        //clave secreta 
        $this->key = "ClaveSecreta1234";
    }

    

    function getHeader(){
        $header = "";
        if(isset($_SERVER['HTTP_AUTHORIZATION']))
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        return $header;
    }

    function getBasic(){
        $header = $this->getHeader();
        //verificar si el header es basic, barer u otra cosa
        //Basic base64(user:pass)
        //strpos me devuelve la primera pos 
        if(strpos($header, "Basic ") === 0){//si teneemos algo con basic espacio " "  debe estar en pos 0
            //base64(user:pass)
            $userPass = explode(" ", $header)[1];
            //user:pass
            $userPass = base64_decode($userPass);
            $userPass = explode(":", $userPass);
            if(count($userPass) == 2){
                $user = $userPass[0];
                $pass = $userPass[1];
                return array(
                    "user"=>$user,
                    "pass"=>$pass
                );
            }

        }
        return null;
    }

    function createToken($user){
        //se crea header y payload
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT' 
        );
        $payload = array(
            'sub' => 1,
            'name' => $user['user'],
            'rol' => ['admin', 'other']
        );
        //codifico el header y el payload pasa a ser un string
        
        //codificamos el string en base64
        //todo se codifica en json y luego en base64 excepto la firma que solo es en base64
        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));
        //firma
        $signature = hash_hmac("SHA256", "$header.$payload", $this->key, true);
        $signature = base64url_encode($signature);
        return "$header.$payload.$signature";
    }

    function getToken(){
        $header = $this->getHeader();
        //Bearer token(ksakdhasjkjdbsfcjnjbsl)
        if(strpos($header, "Bearer ") === 0){
            $token = explode(" ", $header)[1];
            //obtener cada parte header - payload - signature
            $parts = explode(".", $token);

            if(count($parts) ===3){
                $header = $parts[0];
                $payload = $parts[1];
                $signature = $parts[2];
                //voovemos a firmar
                $new_signature = hash_hmac("SHA256", "$header.$payload", $this->key, true);
                $new_signature = base64url_encode($new_signature);
                if($signature == $new_signature){
                    $payload = base64_decode($payload);
                    $payload = json_decode($payload);
                    return $payload;
                }
            }
        }
        return null;
    }

    function isLoggedIn(){
        $payload = $this->getToken();
        if(isset($payload->sub)) /// xq mira si esta seteado el id????? 
            return true;
        else
            return false;
    }
}