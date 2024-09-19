<?php

    /*Incluir la configuracion de la base de datos*/
    require_once 'configuration/Db.php';

    /*
    Clase modelo
    */

    class Model {

        /*Atributo de conexion*/
        private $conn;

        /*Funcion constructor*/
        public function __construct() {
            $this->conn = connectDb();
        }

        /*Funcion para que el usuario se loguee, comprobando desde la base de datos si los datos son validos*/
        public function login($email, $password) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := LOGIN(:email); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $cursor = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($cursor);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $userData = oci_fetch_assoc($cursor);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /*Verificar si el usuario fue encontrado y si la contraseña es correcta*/ 
            if ($userData && password_verify($password, $userData['PASSWORD'])) {
                /*Retornar el resultado*/
                return $userData;
            } else {
                /*Retornar el resultado*/
                return null;
            }
        }                      

        /*Funcion para registrar el usuario en la base de datos*/
        function registerUser($active, $code, $name, $surname, $birthdate, $genre, $phone, $email, $password1, $image, $earnings, $created_at) {
            /*Encriptar la clave*/
            $password = password_hash($password1, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_USER(:active, :code, :name, :surname, TO_DATE(:birthdate, \'DD/MM/YY\'), :genre, :phone, :email, :password, :image, :earnings, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /* Asignar los valores de entrada y salida */
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':code', $code);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':surname', $surname);
            oci_bind_by_name($stmt, ':birthdate', $birthdate);
            oci_bind_by_name($stmt, ':genre', $genre);
            oci_bind_by_name($stmt, ':phone', $phone);
            oci_bind_by_name($stmt, ':email', $email);
            oci_bind_by_name($stmt, ':password', $password);
            oci_bind_by_name($stmt, ':image', $image); 
            oci_bind_by_name($stmt, ':earnings', $earnings);                          
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /* Variable bandera para asignar el resultado */
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /* Ejecutar la consulta */
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Retornar el resultado*/
            $respuesta = false;
            if($resultado = 1){
                $respuesta = $this->login($email, $password1);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $respuesta;
        }      
        
        /*Funcion para registrar el producto en la base de datos*/
        function registerProduct($user_id, $active, $name, $price, $units, $content, $stock, $description, $image, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_PRODUCT(:user_id, :active, :name, :price, :units, :content, :stock, :description, :image, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':price', $price);
            oci_bind_by_name($stmt, ':units', $units);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':stock', $stock);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':image', $image);            
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }  

        /*Funcion para obtener el detalle del producto*/
        public function detailProduct($id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DETAIL_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }
        
        /*Funcion para obtener la lista de todos los productos*/
        public function myProductsList($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := MY_PRODUCTS_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }   
        
        /*Funcion para obtener la lista de todos los productos*/
        public function productsList($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := PRODUCTS_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
        
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
        
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
        
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
        
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
        
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
        
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
        
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
        
            /*Retornar el resultado*/ 
            return $products;
        }        

        /*Funcion para obtener la lista de todos los productos en el apartado de gestion*/
        public function productsListManagement($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PRODUCTS_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        } 

        /*Funcion para registrar el pago*/
        function registerPay($user_id, $active, $election, $numberElection, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_PAY(:user_id, :active, :election, :number_election, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':election', $election);
            oci_bind_by_name($stmt, ':number_election', $numberElection);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para registrar la direccion*/
        function registerDirection($user_id, $active, $carrer, $street, $postal_code, $direction, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_DIRECTION(:user_id, :active, :carrer, :street, :postal_code, :direction, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':carrer', $carrer);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':postal_code', $postal_code);
            oci_bind_by_name($stmt, ':direction', $direction);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para obtener la lista de todos las direcciones en el apartado de gestion*/
        public function directionListManagement($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DIRECTION_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el arreglo con todos los productos*/ 
            return $products;
        }   

        /*Funcion para obtener la lista de todos los pagos en el apartado de gestion*/
        public function payListManagement($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PAY_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el arreglo con todos los productos*/ 
            return $products;
        }   

        /*Funcion para obtener la contraseña de un usuario a traves de su correo*/
        public function getPassword($email) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PASSWORD(:email); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear una variable para almacenar el resultado*/ 
            $password = '';
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $password, 255); // Ajustar tamaño según la longitud esperada
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            /*Retornar el resultado*/ 
            return $password;
        }

        /*Funcion para obtener un producto en concreto*/
        public function getProduct($id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }

        /*Funcion para obtener un pago en concreto*/
        public function getPay($id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PAY(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }

        /*Funcion para obtener una direccion en concreto*/
        public function getDirection($id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_DIRECTION(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retonar el resultado*/ 
            return $productData;
        }

        /*Funcion para eliminar un producto*/
        public function deleteProduct($product_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PRODUCT(:product_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':product_id', $product_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Funcion para eliminar un pago*/
        public function deletePay($pay_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PAY(:pay_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':pay_id', $pay_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Funcion para eliminar una direccion*/
        public function deleteDirection($direction_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_DIRECTION(:direction_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':direction_id', $direction_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Funcion para eliminar un usuario*/
        public function deleteUser($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_USER(:user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':user_id', $user_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Función para comprobar si el email ya existe*/
        function validateUniqueEmail($email) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := VALIDATE_UNIQUE_EMAIL(:email); END;';
            $stid = oci_parse($this->conn, $query);
            // Crear una variable para almacenar el resultado
            $resultado = 0;
            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $resultado);
            // Ejecutar la consulta
            oci_execute($stid);
            // Liberar recursos
            oci_free_statement($stid);
            // Retornar el resultado (1 o 0)
            return $resultado;
        }

        /*Funcion para actualizar un usuario*/
        function updateUser($id, $name, $surname, $phone, $email, $image = null) {
            // Preparar la consulta que llama a la función de Oracle
            $sql = 'BEGIN :resultado := UPDATE_USER(:id, :name, :surname, :phone, :email, :image); END;';
            // Parsear la consulta
            $stmt = oci_parse($this->conn, $sql);
            // Asignar los valores de entrada y salida
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':surname', $surname);
            oci_bind_by_name($stmt, ':phone', $phone);
            oci_bind_by_name($stmt, ':email', $email);
            oci_bind_by_name($stmt, ':image', $image);
            // Variable bandera para asignar el resultado
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            // Ejecutar la consulta
            $success = oci_execute($stmt);
            // Manejar errores si la ejecución falla
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            // Liberar recursos
            oci_free_statement($stmt);
            oci_close($this->conn);
            // Retornar el resultado
            return $resultado;
        }        
        
        /*Funcion para actualizar un pago*/
        function updatePay($id, $election, $number_election) {
            // Preparar la consulta que llama a la función de Oracle
            $sql = 'BEGIN :resultado := UPDATE_PAY(:id, :election, :number_election); END;';
            // Parsear la consulta
            $stmt = oci_parse($this->conn, $sql);
            // Asignar los valores de entrada y salida
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':election', $election);
            oci_bind_by_name($stmt, ':number_election', $number_election);
            // Variable bandera para asignar el resultado
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            // Ejecutar la consulta
            $success = oci_execute($stmt);
            // Manejar errores si la ejecución falla
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            // Liberar recursos
            oci_free_statement($stmt);
            oci_close($this->conn);
            // Retornar el resultado
            return $resultado;
        }

        /*Funcion para actualizar una direccion*/
        function updateDirection($id, $carrer, $street, $postal_code, $direction) {
            // Preparar la consulta que llama a la función de Oracle
            $sql = 'BEGIN :resultado := UPDATE_DIRECTION(:id, :carrer, :street, :postal_code, :direction); END;';
            // Parsear la consulta
            $stmt = oci_parse($this->conn, $sql);
            // Asignar los valores de entrada y salida
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':carrer', $carrer);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':postal_code', $postal_code);
            oci_bind_by_name($stmt, ':direction', $direction);
            // Variable bandera para asignar el resultado
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            // Ejecutar la consulta
            $success = oci_execute($stmt);
            // Manejar errores si la ejecución falla
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            // Liberar recursos
            oci_free_statement($stmt);
            oci_close($this->conn);
            // Retornar el resultado
            return $resultado;
        }

        /*Funcion para actualizar un producto*/
        function updateProduct($id, $name, $price, $units, $content, $stock, $description, $image = null) {
            // Preparar la consulta que llama a la función de Oracle
            $sql = 'BEGIN :resultado := UPDATE_PRODUCT(:id, :name, :price, :units, :content, :stock, :description, :image); END;';
            // Parsear la consulta
            $stmt = oci_parse($this->conn, $sql);
            // Asignar los valores de entrada y salida
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':price', $price);
            oci_bind_by_name($stmt, ':units', $units);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':stock', $stock);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':image', $image);
            // Variable bandera para asignar el resultado
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            // Ejecutar la consulta
            $success = oci_execute($stmt);
            // Manejar errores si la ejecución falla
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            // Liberar recursos
            oci_free_statement($stmt);
            oci_close($this->conn);
            // Retornar el resultado
            return $resultado;
        }

        /*Funcion para obtener un usuario en concreto*/
        function getUser($id){
            /* Preparar la consulta que llama a la función de Oracle */
            $query = 'BEGIN :resultado := GET_USER(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /* Crear un cursor para obtener el resultado */
            $cursor = oci_new_cursor($this->conn);
            /* Asignar los valores de entrada y el cursor de salida */
            oci_bind_by_name($stid, ':id', $id);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /* Ejecutar la consulta */
            oci_execute($stid);
            /* Ejecutar el cursor para obtener los datos */
            oci_execute($cursor);
            /* Obtener el resultado como un arreglo asociativo */
            $userData = oci_fetch_assoc($cursor);
            /* Liberar recursos */
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /* Retornar el resultado */
            return $userData;
        }

        /*Funcion para registrar la transaccion en la base de datos*/
        function registerTransaction($number_bill, $id_buyer, $id_direction, $id_pay, $total, $date_time, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_TRANSACTION(:number_bill, :id_buyer, :id_direction, :id_pay, :total, TO_DATE(:date_time, \'DD/MM/YY\'), TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':number_bill', $number_bill);
            oci_bind_by_name($stmt, ':id_buyer', $id_buyer);
            oci_bind_by_name($stmt, ':id_direction', $id_direction);
            oci_bind_by_name($stmt, ':id_pay', $id_pay);
            oci_bind_by_name($stmt, ':total', $total);
            oci_bind_by_name($stmt, ':date_time', $date_time);          
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para registrar la transaccion del producto en la base de datos*/
        function registerTransactionProduct($id_transaction, $id_product, $id_seller, $units, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_TP(:id_transaction, :id_product, :id_seller, :units, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id_transaction', $id_transaction);
            oci_bind_by_name($stmt, ':id_product', $id_product);
            oci_bind_by_name($stmt, ':id_seller', $id_seller);
            oci_bind_by_name($stmt, ':units', $units);          
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para obtener los datos del producto para la compra*/
        public function getProductDataPu($id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_DATA_PRODUCT_P(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $cursor = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':id', $id);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($cursor);
            /* Obtener el resultado como un arreglo asociativo */
            $userData = oci_fetch_assoc($cursor);
            /* Liberar recursos */
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /* Retornar el resultado */
            return $userData;
        }    

        /*Funcion para obtener la ultima transacion registrada*/
        public function getLastTransaction() {
            // Preparar la consulta para ejecutar la función de Oracle
            $query = "BEGIN :cursor := GET_LAST_TRANSACTION; END;";
            $stmt = oci_parse($this->conn, $query);
            // Declarar un cursor como parámetro de salida
            $cursor = oci_new_cursor($this->conn);
            // Asociar el cursor con el parámetro de salida
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            // Ejecutar la función
            oci_execute($stmt);
            // Ejecutar el cursor
            oci_execute($cursor);
            // Obtener los datos del cursor
            $row = oci_fetch_assoc($cursor);
            // Cerrar cursor y statement
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            // Retornar el ID de la última transacción
            return $row['ID'];
        }

        /*Funcion para obtener la lista de todos los pagos en el apartado de gestion*/
        public function shoppingList($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SHOPPING_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el arreglo con todos los productos*/ 
            return $products;
        }  

        /*Funcion para obtener la lista de todos los pagos en el apartado de gestion*/
        public function salesList($user_id) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SALES_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el arreglo con todos los productos*/ 
            return $products;
        } 

        /*Funcion para obtener el detalle de la venta*/
        public function detailSale($t_transaction_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DETAIL_SALE(:t_transaction_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':t_transaction_id', $t_transaction_id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }

        /*Funcion para obtener el resumen de la transaccion*/
        public function summaryTransaction($pr_product_id, $u_user_id, $pa_pay_id, $d_direction_id){
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := SUMMARY_TRANSACTION(:pr_product_id, :u_user_id, :pa_pay_id, :d_direction_id); END;';
            $stid = oci_parse($this->conn, $query);
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);
            // Asignar los valores de entrada y salida
            oci_bind_by_name($stid, ':pr_product_id', $pr_product_id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':u_user_id', $u_user_id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':pa_pay_id', $pa_pay_id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':d_direction_id', $d_direction_id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            // Ejecutar la consulta
            oci_execute($stid);
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
            // Obtener los resultados como un arreglo asociativo
            $data = [];
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $data[] = $row;
            }
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            // Retornar los datos obtenidos
            return $data;
        }

        /*Funcion para obtener el detalle de la compra*/
        public function detailShop($t_transaction_id) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DETAIL_SHOP(:t_transaction_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':t_transaction_id', $t_transaction_id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }

        /*Funcion para decrementar el inventario*/
        public function decreaseInventory($product_id, $cantidad) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DECREASE_INVENTORY(:p_product_id, :t_cantidad); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':p_product_id', $product_id);
            oci_bind_by_name($stmt, ':t_cantidad', $cantidad);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Funcion para aumentar las ganancias*/
        public function increaseProfits($id_seller, $total) {
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := INCREASE_PROFITS(:t_id_seller, :t_total); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':t_id_seller', $id_seller);
            oci_bind_by_name($stmt, ':t_total', $total);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado si es una función*/ 
            return $resultado;
        }

        /*Funcion para registrar la transaccion en la base de datos*/
        function registerCar($user_id, $active, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_CAR(:user_id, :active, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para registrar la transaccion del producto en la base de datos*/
        function registerCarProduct($car_id, $product_id, $active, $units, $price, $created_at) {
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_CP(:car_id, :product_id, :active, :units, :price, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':car_id', $car_id);
            oci_bind_by_name($stmt, ':product_id', $product_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':units', $units);     
            oci_bind_by_name($stmt, ':price', $price);        
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /* Manejar errores si la ejecución falla */
            if (!$success) {
                $e = oci_error($stmt);
                oci_free_statement($stmt);
                oci_close($this->conn);
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 
        
        /*Funcion para obtener la ultima transacion registrada*/
        public function getLastCar() {
            // Preparar la consulta para ejecutar la función de Oracle
            $query = "BEGIN :cursor := GET_LAST_CAR; END;";
            $stmt = oci_parse($this->conn, $query);
            // Declarar un cursor como parámetro de salida
            $cursor = oci_new_cursor($this->conn);
            // Asociar el cursor con el parámetro de salida
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            // Ejecutar la función
            oci_execute($stmt);
            // Ejecutar el cursor
            oci_execute($cursor);
            // Obtener los datos del cursor
            $row = oci_fetch_assoc($cursor);
            // Cerrar cursor y statement
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            // Retornar el ID de la última transacción
            return $row['ID'];
        }

    }

?>