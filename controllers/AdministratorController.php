<?php

    /*
    Clase controlador de administrador
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class AdministratorController{

        /*Funcion para abrir ventana de login*/
        public function windowlogin(){
            /*Incluir la vista*/
            require_once "views/administrator/Login.html";
        }

        /*Funcion para abrir ventana de registro*/
        public function windowRegisterProduct(){
            /*Incluir la vista*/
            require_once "views/administrator/CreateProduct.html";
        }

        public function home(){
            /*Incluir la vista*/
            require_once "views/administrator/Home.html";
        }

        public function windowManagementProducts(){
            $model = new Model();
            $listProducts = $model -> getProducts();   
            $listProductsAdmin = $model -> getProductsAdmin();          
            /*Incluir la vista*/
            require_once "views/administrator/ManagementProducts.html";
        }

        public function windowManagementUsers(){
            $model = new Model();
            $list = $model -> getUsers();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementUsers.html";
        }

        /*Funcion para registrar*/
        public function registerProduct(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $name = isset($_POST['name']) ? $_POST['name'] : false;
                $price = isset($_POST['price']) ? $_POST['price'] : false;
                $units = isset($_POST['units']) ? $_POST['units'] : false;
                $content = isset($_POST['content']) ? $_POST['content'] : false;
                $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
                $description = isset($_POST['description']) ? $_POST['description'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Comprobar si los datos llegan*/
                if ($name && $price && $units && $content && $stock && $description) {
                    /*Comprobar si la foto es valida*/
                    $fotoGuardada = Helps::saveImage($file, "imagesProducts");
                    /*Comprobar si la foto ha sido guardada*/
                    if ($fotoGuardada) {
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion del modelo*/ 
                        $resultado = $model->registerProduct(NULL, 1, $name, $price, $units, $content, $stock, $description, $image, $created_at2);

                        /*Comprobar si el registrado ha sido exitoso*/
                        if ($resultado != -1) {
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("registrosucces", "Se ha registrado exitosamente el producto", "?controller=userController&action=managementProducts");
                        /*De lo contrario*/  
                        } else {
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro del producto", "?controller=productController&action=windowRegister");
                        }
                    /*De lo contrario*/  
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "El archivo no corresponde a una imagen", "?controller=productController&action=windowRegister");
                    }
                /*De lo contrario*/  
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro del producto", "?controller=productController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=productController&action=windowRegister");
            }
        }

        /*Funcion para iniciar de sesion*/
        public function login(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $password = isset($_POST['password']) ? $_POST['password'] : false;
                /*Comprobar si los datos llegan*/
                if ($email && $password) {
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion del modelo que valida las credenciales de acceso*/  
                    $resultado = $model->logina($email, $password);
                    /*Comprobar si el usuario existe*/
                    if ($resultado != NULL) {
                        /*Crear sesion de inicio de sesion exitoso*/
                        $_SESSION['loginsuccesa'] = 'Admin logueado';
                        $_SESSION['loginsuccesam'] = "Has ingresado exitosamente a EDUARD ENERGY DRINKS";
                        /*Redirigir al lugar requerido*/
                        header("Location:" . "http://localhost/EduardEnergyDrinks/?controller=administratorController&action=home");
                    /*De lo contrario*/
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("iniciarsesionerror", "Este usuario no se encuentra registrado", "?controller=userController&action=windowlogin");
                    }
                /*De lo contrario*/
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("iniciarsesionerror", "Ha ocurrido un error al iniciar sesion", "?controller=userController&action=windowlogin");
                }
            /*De lo contrario*/    
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("iniciarsesionerror", "Ha ocurrido un error inesperado", "?controller=userController&action=windowlogin");
            }
        }

    }

?>