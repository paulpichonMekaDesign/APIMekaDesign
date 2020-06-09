<?php

class Conexion{

     static public function conectar(){

          try {
               
               $conexion = new PDO('mysql:host=localhost;dbname=mekadesign', 'root', '');

               $conexion -> exec("set names utf8");

               return $conexion;
               
          } catch (Exception $e) {

               return $e;
               
          }

     }

}