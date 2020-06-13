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

          $statement = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre_usuario, apellido_usuario, correo, password, foto_perfil, tipo_usuario, hash, id_cliente, llave_secreta) VALUES (:nombre_usuario, :apellido_usuario, :correo, :password, :foto_perfil, :tipo_usuario, :hash, :id_cliente, :llave_secreta)");

          $statement -> bindParam(":nombre_usuario", $datos["nombre"], PDO::PARAM_STR);
          $statement -> bindParam(":apellido_usuario", $datos["apellido"], PDO::PARAM_STR);
          $statement -> bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
          $statement -> bindParam(":password", $datos["password"], PDO::PARAM_STR);
          $statement -> bindParam(":foto_perfil", $datos["imagenRuta"], PDO::PARAM_STR);
          $statement -> bindParam(":tipo_usuario", $datos["tipoUsuario"], PDO::PARAM_STR);
          $statement -> bindParam(":hash", $datos["hash"], PDO::PARAM_STR);
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

     // mostrar un solo usuario
     static public function show($tabla, $id){
          // para este caso en vez de utilizar el id se esta comparando el id_cliente en lugar del id del usuario
          $statement = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE hash = :id");

          $statement -> bindParam(":id", $id, PDO::PARAM_LOB  );

          $statement -> execute();

          return $statement -> fetchAll(PDO::FETCH_ASSOC);

          $statement -> close();

          $statement = null;

     }

     // Editar un registro
     //-------------------------------
     static public function update($tabla, $datos){

          if ($datos["password"] == "*****") {
               
               $statement = Conexion::conectar()->prepare("UPDATE $tabla SET nombre_usuario = :nombre_usuario, apellido_usuario = :apellido_usuario, correo = :correo, foto_perfil = :foto_perfil, tipo_usuario = :tipo_usuario WHERE hash = :id");

               $statement -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
               $statement -> bindParam(":nombre_usuario", $datos["nombre"], PDO::PARAM_STR);
               $statement -> bindParam(":apellido_usuario", $datos["apellido"], PDO::PARAM_STR);
               $statement -> bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
               $statement -> bindParam(":foto_perfil", $datos["imagenRuta"], PDO::PARAM_STR);
               $statement -> bindParam(":tipo_usuario", $datos["tipoUsuario"], PDO::PARAM_STR);

          } else {

               //encriptar la contraseña
               $passwordEncriptada = crypt($datos["password"] , '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
               
               $statement = Conexion::conectar()->prepare("UPDATE $tabla SET nombre_usuario = :nombre_usuario, apellido_usuario = :apellido_usuario, correo = :correo, password = :password, foto_perfil = :foto_perfil, tipo_usuario = :tipo_usuario WHERE hash = :id");

               $statement -> bindParam(":id", $datos["id"], PDO::PARAM_STR);
               $statement -> bindParam(":nombre_usuario", $datos["nombre"], PDO::PARAM_STR);
               $statement -> bindParam(":apellido_usuario", $datos["apellido"], PDO::PARAM_STR);
               $statement -> bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
               $statement -> bindParam(":password", $passwordEncriptada, PDO::PARAM_STR);
               $statement -> bindParam(":foto_perfil", $datos["imagenRuta"], PDO::PARAM_STR);
               $statement -> bindParam(":tipo_usuario", $datos["tipoUsuario"], PDO::PARAM_STR);

          }
          
          

          if ($statement -> execute()) {
               
               return "ok";

          }else{

               print_r(Conexion::conectar()->errorInfo());

          }

          $statement -> close();

          $statement = null;

     }

}