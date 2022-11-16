<?php

class ApiView{
    function response($dato, $code = 200){ 
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $code . " " . $this->requestStatus($code));
        //funcion de php que le doy un arreglo y lo pasa a json
        echo json_encode($dato);
    }

    function requestStatus($code){
        $status = array(
            200 => "OK",
            201 => "Created",
            400 => "Bad Request",
            401=> "Unauthorized", 
            403 => "Forbidden",
            404 => "Not found",     
            500 => "Internal Server Error"
          );
          return (isset($status[$code]))? $status[$code] : $status[500];
    
    }
}