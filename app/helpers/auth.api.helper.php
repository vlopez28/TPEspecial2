<?php


class AuthApiHelper{
    private $key;

    function __construct(){
        
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
        
        if(strpos($header, "Basic ") === 0){
            $userPass = explode(" ", $header)[1];
            
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
     
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT' 
        );
        $payload = array(
            'sub' => 1,
            'name' => $user['user'],
            'rol' => ['admin', 'other']
        );
       
        $header = base64url_encode(json_encode($header));
        $payload = base64url_encode(json_encode($payload));
      
        $signature = hash_hmac("SHA256", "$header.$payload", $this->key, true);
        $signature = base64url_encode($signature);
        return "$header.$payload.$signature";
    }

    function getToken(){
        $header = $this->getHeader();
        
        if(strpos($header, "Bearer ") === 0) {
            $token = explode(" ", $header)[1];
      
            $parts = explode(".", $token);

            if(count($parts) === 3) {
                $header = $parts[0];
                $payload = $parts[1];
                $signature = $parts[2];
              
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

    function isLoggedIn() {
        $payload = $this->getToken();
        if(isset($payload->sub))
            return true;
        else
            return false;
    }
}