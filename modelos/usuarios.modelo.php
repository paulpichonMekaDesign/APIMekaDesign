<?php

require_once "conexion.php";

class ModeloUsuarios{

     // mostrar todos los usuarios
     static public function index($tabla){

          $statement = Conexion::conectar()->prepare("SELECT * FROM $tabla");

          $statement -> execute();

          return $statement -> fetchAll(PDO::FETCH_ASSOC);

          $statement -> close();

          $statement = null;

     }

     // crear un registro
     //-------------------------------
     static public function create($tabla, $datos){

          $statement = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_usuario, apellido_usuario, correo, id_cliente, llave_secreta) VALUES (:nombre_usuario, :apellido_usuario, :correo, :id_cliente, :llave_secreta)");

          $statement -> bindParam(":nombre_usuario", $datos["nombre"], PDO::PARAM_STR);
          $statement -> bindParam(":apellido_usuario", $datos["apellido"], PDO::PARAM_STR);
          $statement -> bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
          $statement -> bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_STR);
          $statement -> bindParam(":llave_secreta", $datos["llave_secreta"], PDO::PARAM_STR);

          if ($statement -> execute()) {
               
               return "ok";

          }else{

               print_r(Conexion::conectar()->errorInfo());

          }

          $statement -> close();

          $statement = null;

     }

}