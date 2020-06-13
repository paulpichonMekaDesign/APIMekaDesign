<?php 

// echo "<pre>"; print_r($_SERVER['REQUEST_URI']); echo "</pre>";

// separar en indices
$arrayRutas = explode("/", $_SERVER["REQUEST_URI"]);
// echo "<pre>"; print_r($arrayRutas); echo "</pre>";
// return;

// echo "<pre>"; print_r(count($arrayRutas)); echo "</pre>";
// return;

// (array_filter) -->  quita los indices vacios de un array
if (count(array_filter($arrayRutas)) == 0) {

     /********* * CUANDO NO SE HACE NINGUNA PETICION A LA API
     *********************************************************/

     $json = array(

          "detalle" => "no encontrado"
     
     );
     
     echo json_encode($json, true);

     return;

}else {

     // preguntamos si viene un solo indice
     if (count(array_filter($arrayRutas)) == 1) {
                    
          /********* * cuando se hace peticion desde usuarios
          *********************************************************/
          
          if (array_filter($arrayRutas)[1] == "usuarios") {

                // peticiones de tipo "GET"
                //mostrando todos los registros de usuarios
          
               if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
               
                    $mostrarUsuarios = new ControladorCliente();
                    $mostrarUsuarios -> index();

               }

               // peticiones de tipo "POST"
               // registro de usuario
          
               if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

                    
                    // capturar datos para registrar usuario
                    $datos = array("nombre" => $_POST["nombre"],
                                   "apellido" => $_POST["apellido"],
                                   "correo" => $_POST["correo"],
                                   "password" => $_POST["password"],
                                   "imagenRuta" => $_POST["imagenRuta"],
                                   "tipoUsuario" => $_POST["tipoUsuario"],
                                   "hash" => $_POST["hash"]

                    );

               
                    $registroUsuario = new ControladorCliente();
                    $registroUsuario -> create($datos);

               }

          }

          /********* * cuando se hacen peticiones desde suscriptores
          *********************************************************/
          // validando rutas
          else if (array_filter($arrayRutas)[1] == "suscriptores") {
               
               $json = array(

                    "detalle" => "estoy en suscriptores"

               );

               echo json_encode($json, true);

               return;

          } else {
               // en caso de no encontrar ninguna ruta valida nos muestra un mensaje de no encontrado
               
               $json = array(

                    "detalle" => "detalle no encontrado"

               );

               echo json_encode($json, true);

               return;
               
          }

     }else {
          
          /* cuando se hace peticion desde un solo usuario ********
          *********************************************************/

          // para poder comparar el hash del id_cliente se quitao la siguiente funcion para que no solo permita numeros is_numeric()
          //is_numeric(array_filter($arrayRutas)[2])
          if (array_filter($arrayRutas)[1] == "usuarios" && array_filter($arrayRutas)[2]) {

               // peticiones de tipo "GET"
                //mostrando todos los registros de usuarios
          
                if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
               
                    $mostrarUsuario = new ControladorCliente();
                    $mostrarUsuario -> show(array_filter($arrayRutas)[2]);

               }
               
               // peticiones de tipo "PUT"
                //Editar un usuario por ID
          
                else if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "PUT") {

                    //Capturar datos
                    //---------------------
                    // se nesecita de funciones especiales para poder parcear la cadena que  viene en la URL
                    $datos = array();

                    parse_str(file_get_contents('php://input'), $datos);
                    /* print_r($datos);
                    return; */
                    $editarUsuarios = new ControladorCliente();
                    $editarUsuarios -> update(array_filter($arrayRutas)[2], $datos);

               }

               // peticiones de tipo "DELETE"
                //Eliminar un usuario por ID
          
               else if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "DELETE") {
               
                    $eliminarUsuario = new ControladorCliente();
                    $eliminarUsuario -> delete(array_filter($arrayRutas)[2]);

               }else {
               
                    $json = array(
     
                         "detalle" => "no encontrado"
                    
                    );
                    
                    echo json_encode($json, true);
               
                    return;
                    
               }

          }else {
               
               $json = array(

                    "detalle" => "no encontrado"
               
               );
               
               echo json_encode($json, true);
          
               return;
               
          }
          
     }
     
}