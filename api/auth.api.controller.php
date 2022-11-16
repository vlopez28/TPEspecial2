<?php
require_once './app/models/user.model.php';
require_once './app/views/api.view.php';
require_once './app/helpers/auth.api.helper.php';

function base64url_encode($data){
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}
class AuthApiController {
    private $model;
    private $view;
    private $authHelper;

    public function __construct() {
        $this->model = new UserModel();
        $this->view = new ApiView();
        $this->authHelper = new AuthApiHelper();
    }

    function getToken($params = null){
        //es un arreglo
        $userPass = $this->authHelper->getBasic();
        $user = $userPass["user"];
        $password = $userPass["pass"]; 
        //obtengo el usuario de la bbdd
        $userDb = $this->model->getUser($userPass["user"]);
        //si el usuario existe y las contrasenias coinciden
        if($user == $userDb->email && password_verify($password, $userDb->password)){
            $token = $this->authHelper->createToken($userPass);
            //devolver un token
            $this->view->response(["token"=>$token]);
        } else{
            $this->view->response("usuario y/o contraseña invalidos", 401);//codigo no autorizado
        }         
    }
}

    
    
