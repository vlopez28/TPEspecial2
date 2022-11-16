<?php
class PropertyModel{ 

    private $db;

    function __construct(){
        $this->db = $this->connect();
    }
    
    private function connect(){
        $db = new PDO('mysql:host=localhost;'.'dbname=inmobiliaria;charset=utf8', 'root', '');
        return $db;
    }

    function update($id, $tipo_propiedad, $direccion, $habitaciones, $banios, 
    $patio, $tipo_contrato, $moneda, $precio){

        $query = $this->db->prepare('UPDATE `propiedad` SET `tipo_propiedad_id` = ?, 
        `direccion` = ?, `habitaciones` = ?, `banios` = ?, `patio` = ?, 
        `tipo_contrato` = ?, `moneda` = ?, `precio` = ?  WHERE `propiedad`.`id` = ?');
        $query->execute([$tipo_propiedad, $direccion, $habitaciones, $banios, $patio, $tipo_contrato, 
        $moneda, $precio, $id]);

    }

    function getOne($id){
        $query = $this->db->prepare('SELECT t.tipo, t.id as tipo_propiedad_id, p.id, p.direccion, p.habitaciones, p.banios, p.patio, 
        p.tipo_contrato, p.moneda, p.precio, p.imagen  
        FROM propiedad p 
        INNER JOIN tipo_propiedad t 
        ON p.tipo_propiedad_id = t.id 
        WHERE p.id=?;');
        $query->execute([$id]);
        $detallesItem = $query->fetchAll(PDO::FETCH_OBJ); 
        return $detallesItem; 
    }

    function insert($tipo_propiedad, $direccion, $habitaciones, $banios, $patio, 
        $tipo_contrato, $moneda, $precio){
        
        $query = $this->db->prepare('INSERT INTO `propiedad` (`id`, `tipo_propiedad_id`, `direccion`, 
        `habitaciones`, `banios`, `patio`, `tipo_contrato`, `moneda`, `precio`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);');
        $query->execute([NULL, $tipo_propiedad, $direccion, $habitaciones, $banios, $patio, $tipo_contrato, 
        $moneda, $precio]);
        return $this->db->lastInsertId();
    }
    private function subirImagen($imagen, $extension){
        $target = 'images/casas/' . uniqid() . '.' . $extension;
        move_uploaded_file($imagen, $target);
        return $target;
    }

    function delete($id){
        $query = $this->db->prepare('DELETE FROM propiedad WHERE id=?');
        $query->execute([$id]);
        return $this->db->lastInsertId();
    }

    function getAll($sort, $order, $search, $limit, $offSet){
        $query = $this->db->prepare(
           'SELECT p.id, p.direccion, p.habitaciones, p.banios, p.patio, p.tipo_contrato, 
              p.moneda, p.precio, p.imagen, t.tipo
            FROM propiedad p 
            INNER JOIN tipo_propiedad t ON p.tipo_propiedad_id = t.id
            WHERE t.tipo LIKE ? 
            ORDER BY ' . $sort . ' '.$order . '
            LIMIT ' . $offSet . ', ' . $limit
        );//aca ya lo ppongo xq lo valide //como hago para traer tipo prop
        $query->execute(['%'.$search.'%']);

        $properties = $query->fetchAll(PDO::FETCH_OBJ); 
       
        return $properties; 
    }

    function filteredProperties($atributte, $order){
        $query = $this->db->prepare('SELECT * FROM propiedad ORDER BY precio ASC');
        $query->execute([$atributte, $order]);
        $properties = $query->fetchAll(PDO::FETCH_OBJ); 
        return $properties; 
    }
}