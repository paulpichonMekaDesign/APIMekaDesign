<?php

class ControladorCliente{

     /**************** mostrar todos los registros de usuarios****************************/
     public function index(){

          /***************************************/
          /* VALIDAR LAS CREDENCIALES DEL USUARIO*/
          /***************************************/
          $usuarios = ModeloUsuarios::index("usuarios");


          if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

               foreach ($usuarios as $key => $valueUsuario) {

                    if ("Basic ".base64_encode($_SERVER['PHP_AUTH_USER']).":".($_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueUsuario["id_cliente"]).":".($valueUsuario["llave_secreta"])) {
                         
                         // mostrar todos los usuarios 
                         //peticion al modelo
                         $usuarios = ModeloUsuarios::index("usuarios");

                         if (!empty($usuarios)) {
               
                              $json = array(

                                   "status" => 200,
                                   "total_registros" => count($usuarios),
                                   "detalle" => $usuarios
     
                              );
     
                              echo json_encode($json, true);
     
                              return;
               
                         } else {
                              
                              $json = array(
               
                                   "status" => 200,
                                   "total_registros" => 0,
                                   "detalle" => "No hay ningún usuario registrado"
               
                              );
               
                              echo json_encode($json, true);
               
                              return;
               
                         }

                    }else {
                         
                         $json = array(

                              "status" => 404,
                              "detalle" => "Token inválido"

                         );

                         
                    }
                    
               }
               

          }else {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "Debes tener una autorizacion para esa peticion"

               );

               
          }

          echo json_encode($json, true);

          return;
          
     }

     /**************** crear un usuario ****************************/
     public function create($datos){

          // validar nombre
         if ( isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ]+$/', $datos["nombre"]) ) {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "error en el campo nombre, solo se permiten letras"
     
               );

               echo json_encode($json, true);

               return;

          }

          // validar apellido
          if ( isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ]+$/', $datos["apellido"]) ) {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "error en el campo apellido, solo se permiten letras"
     
               );

               echo json_encode($json, true);

               return;

          }

          // validar CORREO
          if ( isset($datos["correo"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["correo"]) ) {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "error en el campo correo, ingrese un email válido"
     
               );

               echo json_encode($json, true);

               return;

          }

          // validar que el correo no este repetido
          $usuarios = ModeloUsuarios::index("usuarios");

          foreach ($usuarios as $key => $value) {
               
               if ($value["correo"] == $datos["correo"]) {
                    
                    $json = array(

                         "status" => 404,
                         "detalle" => "este correo ya esta registrado en la base de datos"
          
                    );
     
                    echo json_encode($json, true);
     
                    return;

               }

          }

          // GENERAR CREDENCIALES DEL USUARIO **********
          //********************************************/

          //para quitar los signos de $ se usa str_replace()
          $id_cliente = str_replace("$", "a", crypt($datos["nombre"].$datos["apellido"].$datos["correo"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
          $llave_secreta = str_replace("$", "a", crypt($datos["correo"].$datos["apellido"].$datos["nombre"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
          // echo "<pre>"; print_r($id_cliente); echo "</pre>"; 
          // echo "<pre>"; print_r($llave_secreta); echo "</pre>"; 
          // return;

          // llevar datos al modelo
          //************************
          $datos = array("nombre"=>$datos["nombre"],
                         "apellido"=>$datos["apellido"],
                         "correo"=>$datos["correo"],
                         "password"=>$datos["password"],
                         "imagenRuta"=>$datos["imagenRuta"],
                         "tipoUsuario"=>$datos["tipoUsuario"],
                         "hash"=>$datos["hash"],
                         "id_cliente"=>$id_cliente,
                         "llave_secreta"=>$llave_secreta
                    );
                    
          $create = ModeloUsuarios::create("usuarios", $datos);
          
          // echo "<pre>"; print_r($create); echo "</pre>"; 
          // return;

          // respuesta del modelo
          //************************/
          if ($create == "ok") {
               
               $json = array(

                    "status" => 200,
                    "detalle" => "Registro exitoso"
     
               );
     
               echo json_encode($json, true);
     
               return;

          }
     }

     /**************** mostrar un solo usuario ****************************/

     public function show($id){
           /***************************************/
          /* VALIDAR LAS CREDENCIALES DEL USUARIO*/
          /***************************************/
          $usuarios = ModeloUsuarios::index("usuarios");

          if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

               foreach ($usuarios as $key => $valueUsuario) {

                    if ("Basic ".base64_encode($_SERVER['PHP_AUTH_USER']).":".($_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueUsuario["id_cliente"]).":".($valueUsuario["llave_secreta"])) {
                         
                         // mostrar todos los usuarios 
                         //peticion al modelo
                         $usuario = ModeloUsuarios::show("usuarios", $id);

                         if (!empty($usuario)) {
               
                              $json = array(

                                   "status" => 200,
                                   "detalle" => $usuario
     
                              );
     
                              echo json_encode($json, true);
     
                              return;
               
                         } else {
                              
                              $json = array(
               
                                   "status" => 200,
                                   "total_registros" => 0,
                                   "detalle" => "No hay ningún usuario registrado con ese Id"
               
                              );
               
                              echo json_encode($json, true);
               
                              return;
               
                         }

                    }else {
                         
                         $json = array(

                              "status" => 404,
                              "detalle" => "Token inválido"

                         );

                         
                    }
                    
               }
               

          }else {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "Debes tener una autorizacion para esa peticion"

               );

               
          }

          echo json_encode($json, true);

          return;
          
     }

     /**************** editar un usuario ****************************/

     public function update($id, $datos){

          /***************************************/
          /* VALIDAR LAS CREDENCIALES DEL USUARIO*/
          /***************************************/
          $usuarios = ModeloUsuarios::index("usuarios");


          if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

               foreach ($usuarios as $key => $valueUsuario) {

                    if ("Basic ".base64_encode($_SERVER['PHP_AUTH_USER']).":".($_SERVER['PHP_AUTH_PW']) == "Basic ".base64_encode($valueUsuario["id_cliente"]).":".($valueUsuario["llave_secreta"])) {
                         

                         // llevar datos al modelo
                         //************************
                         $datos = array("id"=>$id,
                                        "nombre"=>$datos["nombre"],
                                        "apellido"=>$datos["apellido"],
                                        "correo"=>$datos["correo"],
                                        "password"=>$datos["password"],
                                        "imagenRuta"=>$datos["imagenRuta"],
                                        "tipoUsuario"=>$datos["tipoUsuario"]
                                   );
                         
                         $create = ModeloUsuarios::update("usuarios", $datos);

                         if ($create == "ok") {
                              
                              $json = array(
                                   "status" => 200,
                                   "detalle" => "Actualizacion correcta, la informacion del usuario ha sido modificada"
                              );

                              echo json_encode($json, true);

                              return;

                         }



                    }else {
                         
                         $json = array(

                              "status" => 404,
                              "detalle" => "Token inválido"

                         );
                         
                    }
                    
               }
               

          }else {
               
               $json = array(

                    "status" => 404,
                    "detalle" => "Debes tener una autorizacion para esa peticion"

               );

               
          }

          echo json_encode($json, true);

          return;
          
     }

     /**************** Eliminar un usuario por ID****************************/

     public function delete($id){

          $json = array(

               "detalle" => "usuario eliminado con id ".$id

          );

          echo json_encode($json, true);

          return;
          
     }

}