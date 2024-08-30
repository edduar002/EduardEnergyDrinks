<?php

    /*Incluir la configuracion de la base de datos*/
    require_once 'configuration/Db.php';

    /*Clase modelo*/

    class Model {

        /*Atributo de conexion*/
        private $conn;

        /*Funcion constructor*/

        public function __construct() {
            $this->conn = connectDb();
        }

        /*Funcion para que el usuario se loguee, comproando desde la base de datos si los datos son validos*/

        public function login($email, $password) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := LOGIN(:email); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $cursor = oci_new_cursor($this->conn);
        
            // Asignar los valores de entrada y el cursor de salida
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($cursor);
        
            // Obtener el resultado como un arreglo asociativo
            $userData = oci_fetch_assoc($cursor);
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($cursor);

            // Verificar si el usuario fue encontrado y si la contraseña es correcta
            if ($userData && password_verify($password, $userData['PASSWORD'])) {
                // Contraseña correcta, retornar los datos del usuario
                return $userData;
            } else {
                // Usuario no encontrado o contraseña incorrecta
                return null;
            }
        }                      

        /*Funcion para registrar el usuario en la base de datos*/

        function registerUser($active, $code, $name, $surname, $birthdate, $genre, $phone, $email, $password1, $image, $created_at) {
            /*Encriptar la clave*/
            $password = password_hash($password1, PASSWORD_BCRYPT, ['cost'=>4]);
            /* Preparar la consulta que llama a la función de Oracle */
            $sql = 'BEGIN :resultado := REGISTER_USER(:active, :code, :name, :surname, TO_DATE(:birthdate, \'DD/MM/YY\'), :genre, :phone, :email, :password, :image, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
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
            $respuesta = false;
            if($resultado = 1){
                $respuesta = $this->login($email, $password1);
            }
        
            /* Liberar recursos */
            oci_free_statement($stmt);
            oci_close($this->conn);
        
            /* Retornar el resultado */
            return $respuesta;
        }      
        
        /*Funcion para registrar el producto en la base de datos*/

        function registerProduct($user_id, $active, $name, $price, $units, $content, $stock, $description, $image, $created_at) {
            /* Preparar la consulta que llama a la función de Oracle */
            $sql = 'BEGIN :resultado := REGISTER_PRODUCT(:user_id, :active, :name, :price, :units, :content, :stock, :description, :image, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
        
            /* Asignar los valores de entrada y salida */
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
        
            /* Liberar recursos */
            oci_free_statement($stmt);
            oci_close($this->conn);
        
            /* Retornar el resultado */
            return $resultado;
        }  

        public function detailProduct($id) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := DETAIL_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);

            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Obtener el resultado como un arreglo asociativo
            $productData = oci_fetch_assoc($resultado);
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            // Retornar el resultado
            return $productData;
        }
        
        public function productsList() {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := PRODUCTS_LIST(); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);
            
            // Asignar el cursor como el valor de salida
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Crear un array para almacenar todos los productos
            $products = [];
        
            // Obtener todos los registros como un arreglo asociativo
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            
            // Retornar el arreglo con todos los productos
            return $products;
        }        

        public function productsListManagement() {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := PRODUCTS_LIST_MANAGEMENT(); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);
            
            // Asignar el cursor como el valor de salida
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Crear un array para almacenar todos los productos
            $products = [];
        
            // Obtener todos los registros como un arreglo asociativo
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            
            // Retornar el arreglo con todos los productos
            return $products;
        } 

        function registerPay($user_id, $active, $election, $numberElection, $created_at) {
            /* Preparar la consulta que llama a la función de Oracle */
            $sql = 'BEGIN :resultado := REGISTER_PAY(:user_id, :active, :election, :number_election, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
        
            /* Asignar los valores de entrada y salida */
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':election', $election);
            oci_bind_by_name($stmt, ':number_election', $numberElection);         
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
        
            /* Liberar recursos */
            oci_free_statement($stmt);
            oci_close($this->conn);
        
            /* Retornar el resultado */
            return $resultado;
        }   

        function registerDirection($user_id, $active, $carrer, $street, $postal_code, $direction, $created_at) {
            /* Preparar la consulta que llama a la función de Oracle */
            $sql = 'BEGIN :resultado := REGISTER_DIRECTION(:user_id, :active, :carrer, :street, :postal_code, :direction, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
        
            /* Asignar los valores de entrada y salida */
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':carrer', $carrer);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':postal_code', $postal_code);
            oci_bind_by_name($stmt, ':direction', $direction);         
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
        
            /* Liberar recursos */
            oci_free_statement($stmt);
            oci_close($this->conn);
        
            /* Retornar el resultado */
            return $resultado;
        }   

        public function directionListManagement() {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := DIRECTION_LIST_MANAGEMENT(); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);
            
            // Asignar el cursor como el valor de salida
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Crear un array para almacenar todos los productos
            $products = [];
        
            // Obtener todos los registros como un arreglo asociativo
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            
            // Retornar el arreglo con todos los productos
            return $products;
        }   

        public function payListManagement() {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := PAY_LIST_MANAGEMENT(); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);
            
            // Asignar el cursor como el valor de salida
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Crear un array para almacenar todos los productos
            $products = [];
        
            // Obtener todos los registros como un arreglo asociativo
            while (($row = oci_fetch_assoc($resultado)) != false) {
                $products[] = $row;
            }
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            
            // Retornar el arreglo con todos los productos
            return $products;
        }   

        public function getPassword($email) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := GET_PASSWORD(:email); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear una variable para almacenar el resultado
            $password = '';
        
            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $password, 255); // Ajustar tamaño según la longitud esperada
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Liberar recursos
            oci_free_statement($stid);
        
            // Retornar el resultado (la contraseña)
            return $password;
        }

        public function getProduct($id) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := GET_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);

            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Obtener el resultado como un arreglo asociativo
            $productData = oci_fetch_assoc($resultado);
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            // Retornar el resultado
            return $productData;
        }

        public function getPay($id) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := GET_PAY(:id); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);

            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Obtener el resultado como un arreglo asociativo
            $productData = oci_fetch_assoc($resultado);
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            // Retornar el resultado
            return $productData;
        }

        public function getDirection($id) {
            // Preparar la consulta que llama a la función de Oracle
            $query = 'BEGIN :resultado := GET_DIRECTION(:id); END;';
            $stid = oci_parse($this->conn, $query);
        
            // Crear un cursor para obtener el resultado
            $resultado = oci_new_cursor($this->conn);

            // Asignar el valor de entrada y salida
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT); // Especifica el tipo de datos
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
        
            // Ejecutar la consulta
            oci_execute($stid);
        
            // Ejecutar el cursor para obtener los datos
            oci_execute($resultado);
        
            // Obtener el resultado como un arreglo asociativo
            $productData = oci_fetch_assoc($resultado);
        
            // Liberar recursos
            oci_free_statement($stid);
            oci_free_statement($resultado);
            // Retornar el resultado
            return $productData;
        }
        
    }

?>