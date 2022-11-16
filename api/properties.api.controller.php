<?php
require_once './app/models/property.model.php';
require_once './app/models/tipe.property.model.php';
require_once './app/views/api.view.php';
require_once './app/helpers/auth.api.helper.php';


class PropertiesApiController {
    private $model;
    private $view;
    private $data;
    private $authHelper;
    private $modelTipeProperty;
    const DEFAULT_LIMIT = 100;
    const DEFAULT_OFF_SET = 0;
    const DEFAULT_SORT = 'id';
    const AVAILABLE_SORT = array('id','direccion', 'habitaciones', 'banios', 'patio', 'tipo_contrato', 
                                 'moneda', 'precio');
    const DEFAULT_ORDER = 'asc';
    const AVAILABLE_ORDER = array('asc', 'desc');
    
    function __construct() {
        $this->model = new PropertyModel();
        $this->modelTipeProperty = new TypePropertyModel();
        $this->view = new ApiView();
        $this->data = file_get_contents("php://input");
        $this->authHelper = new AuthApiHelper();
    }

    private function getData() {
        return json_decode($this->data);
    }
    
    private function queryParamsValidator($key, $list, $defaultValue){
        if(isset($_GET[$key]) && !empty($_GET[$key]) && in_array(strtolower($_GET[$key]), $list, true)){
            return $_GET[$key]; 
         } 
 
         return $defaultValue;
    }
    
    private function validateSort(){
        return $this->queryParamsValidator('sort', self::AVAILABLE_SORT, self::DEFAULT_SORT);
    }

    private function validateOrder(){
        return $this->queryParamsValidator('order', self::AVAILABLE_ORDER, self::DEFAULT_ORDER);
    }

    private function validateSearch(){
        if(isset($_GET['search'])){
            return $_GET['search'];
        }
        return '';
    }

    private function validateQueryParamsPagination($key, $defaultValue){
        if(isset($_GET[$key]) && is_numeric($_GET[$key])) {
            return intval($_GET[$key]); 
        } 

        return $defaultValue;
    }

    function getAll($params = null){
        $sort = $this->validateSort();
        $order = $this->validateOrder();
        $search = $this->validateSearch(); 
        $limit = $this->validateQueryParamsPagination('limit', self::DEFAULT_LIMIT);
        $offSet = $this->validateQueryParamsPagination('offset', self::DEFAULT_OFF_SET);
        echo($sort . $order . $search .$limit . $offSet);
        $properties = $this->model->getAll($sort, $order, $search, $limit, $offSet);
        $this->view->response($properties);
              
    }
    function getOne($params = null){
        $id = $params[':ID']; 
        $property = $this->model->getOne($id);
        if($property)
            $this->view->response($property);
        else
            $this->view->response("Property id=$id not found", 404);
    }

    function delete($params = null){
        $id = $params[':ID']; 
        if(!$this->authHelper->isLoggedIn()){
            $this->view->response("Unauthorized", 401);
            return;
        }
        $property = $this->model->getOne($id);
        if($property){
            $this->model->delete($id);
            $this->view->response($property);
        }
        else{
            $this->view->response("Property id=$id not found", 404);
        }
    }

    private function  verifyTypeProperty($tipo){
        $typesProperties = $this->modelTipeProperty->getTypeProperties();

        foreach($typesProperties as $item){
            if($item->id == $tipo){
                return true;
            } 
        }return false;
    }

    function insert($params = null){
        if(!$this->authHelper->isLoggedIn()){
            $this->view->response("Unauthorized", 401);
            return;
        }
        $body = $this->getData();
        if(empty($body->direccion) || empty($body->habitaciones) || 
           empty($body->banios) || empty($body->patio) || empty($body->tipo_contrato) || 
           empty($body->moneda) || empty($body->precio) || empty($body->tipo)) {
           
            $this->view->response("All fields are required", 400); //esta bien este error??
        } else{
                $verifyTypeProperty = $this->verifyTypeProperty($body->tipo);
                if(!$verifyTypeProperty){
                    $this->view->response("Type property not exist", 400);
                } else{ 
                    $id = $this->model->insert($body->tipo, $body->direccion, $body->habitaciones,
                                        $body->banios, $body->patio, $body->tipo_contrato, 
                                        $body->moneda, $body->precio);
                    $property = $this->model->getOne($id);
                    $this->view->response($property, 201);
                }
        }
        
    }

    function update($params = null){
        $id = $params[':ID']; 
        if(!$this->authHelper->isLoggedIn()){
            $this->view->response("Unauthorized", 401);
            return;
        }
        $property = $this->model->getOne($id); 
        if($property){
            $body = $this->getData();
            if(empty($body->direccion) || empty($body->habitaciones) || 
               empty($body->banios) || empty($body->patio) || empty($body->tipo_contrato) || 
               empty($body->moneda) || empty($body->precio) || empty($body->tipo)){
           
                $this->view->response("All fields are required", 400);
            } else{
                $verifyTypeProperty = $this->verifyTypeProperty($body->tipo);
                if(!$verifyTypeProperty) {
                    $this->view->response("Type property not exist", 400);
                } else{ 
                    $this->model->update($id, $body->tipo, $body->direccion, $body->habitaciones,
                                             $body->banios, $body->patio, $body->tipo_contrato, 
                                             $body->moneda, $body->precio);
                    $this->view->response("Property id=$id successfully updated", 200);
                }
            }
        } else {
            $this->view->response("Property id=$id not found", 404);
        } 
    }                            
}

