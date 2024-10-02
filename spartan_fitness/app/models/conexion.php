<?php

class Conexion{

    public function conectar(){
        $pdo = new PDO("mysql:host=localhost;dbname=spartan_php","root","123456789");
        return $pdo;
    }
}