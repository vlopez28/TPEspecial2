<?php

class TypePropertyModel{
    private $db;

    function __construct() {
        $this->db = $this->connect();
    }
    
    private function connect() {
        $db = new PDO('mysql:host=localhost;'.'dbname=inmobiliaria;charset=utf8', 'root', '');
        return $db;
    }
    
    function getTypeProperties(){
        $query = $this->db->prepare('SELECT * FROM tipo_propiedad');
        $query->execute();
        $tiposPropiedad = $query->fetchAll(PDO::FETCH_OBJ);
        return $tiposPropiedad;
    }
}
