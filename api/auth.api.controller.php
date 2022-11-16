<?php
require_once './app/models/user.model.php';
require_once './app/views/api.view.php';
require_once './app/helpers/auth.api.helper.php';

function base64url_encode($data){
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); //corta a la derecha los iguales y reemplaza
                                                                //los menos por los mas y barra
}
class AuthApiController {
    private $model;
    private $view;
    private $authHelper;

    private $data;

    public function __construct() {
        $this->model = new UserModel();
        $this->view = new ApiView();
        $this->authHelper = new AuthApiHelper();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    
    function getUser($params = null){
        $id = $params[':ID'];
        $user = $this->authHelper->getToken();
        if($user){
             if($id == $user->sub){
                $this->view->response($user);
            } else {
                $this->view->response("Forbidden", 403);//prohibido obtener esa info
            }
        } else {
            $this->view->response("Unauthorized", 401);
        }
       
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

    
    
