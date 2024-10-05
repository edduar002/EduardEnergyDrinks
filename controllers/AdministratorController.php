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

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $product_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if($product_id){
                    /*Instanciar modelo*/ 
                    $model = new Model();
                    /*Llamar la funcion del modelo*/ 
                    $product = $model -> getProduct($product_id);
                    /*Incluir la vista*/
                    require_once "views/product/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=windowManagementProducts");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementProducts");
            }
        }

        /*Funcion para abrir ventana de registro de producto*/
        public function windowRegisterProduct(){
            /*Incluir la vista*/
            require_once "views/administrator/CreateProduct.html";
        }

        /*Funcion para abrir la pantalla de inicio*/
        public function home(){
            /*Incluir la vista*/
            require_once "views/administrator/Home.html";
        }

        /*Funcion para abrir la ventana de gestion de los productos*/
        public function windowManagementProducts(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener la lista de los productos creados por los usuarios*/
            $listProducts = $model -> getProducts(); 
            /*Obtener la lista de los productos creados por el administrador*/  
            $listProductsAdmin = $model -> getProductsAdmin();          
            /*Incluir la vista*/
            require_once "views/administrator/ManagementProducts.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementUsers(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getUsers();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementUsers.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementGenres(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getGenres();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementGenres.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementDepartments(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getDepartments();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementDepartments.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementPurchasingStatues(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getPurchasingStatues();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementPurchasingStatues.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementNews(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getSNews();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementNews.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementBankEntities(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getBankEntities();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementBankEntities.html";
        }

        /*Funcion para abrir ventana para la asignacion de usuarios fundadores*/
        public function windowAddUser(){
            /*Incluir la vista*/
            require_once "views/administrator/AddUser.html";
        }

        /*Funcion para agregar usuarios fundadores*/
        public function addUser(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $code = isset($_POST['code']) ? $_POST['code'] : false;
                /*Comprobar si el dato llega*/
                if($code){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que asigna los usuarios fundadores*/  
                    $resultado = $model->addUser(NULL, 1, $code);
                    /*Comprobar si la asignacion ha sido exitosa*/                  
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoasignacion", "Se ha asignado el usuario fundador exitosamente", "?controller=administratorController&action=windowAddUser");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error al asignar el usuario fundador", "?controller=administratorController&action=windowAddUser");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowAddUser");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowAddUser");
            }
        }

        /*Funcion para registrar un producto*/
        public function registerProduct(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
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
                if($name && $price && $units && $content && $stock && $description){
                    /*Comprobar si la foto es valida*/
                    $fotoGuardada = Helps::saveImage($file, "imagesProducts");
                    /*Comprobar si la foto ha sido guardada*/
                    if($fotoGuardada){
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion del modelo*/ 
                        $resultado = $model->registerProduct(NULL, 1, $name, $price, $units, $content, $stock, $description, $image, $created_at2);
                        /*Comprobar si el registrado ha sido exitoso*/
                        if($resultado != -1){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el producto", "?controller=administratorController&action=windowManagementProducts");
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del producto", "?controller=administratorController&action=windowRegisterProduct");
                        }
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "El archivo no corresponde a una imagen", "?controller=administratorController&action=windowRegisterProduct");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegisterProduct");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegisterProduct");
            }
        }

        /*Funcion para iniciar de sesion*/
        public function login(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $password = isset($_POST['password']) ? $_POST['password'] : false;
                /*Comprobar si los datos llegan*/
                if($email && $password){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion del modelo que valida las credenciales de acceso*/  
                    $resultado = $model->logina($email, $password);
                    /*Comprobar si el usuario existe*/
                    if($resultado != NULL){
                        /*Crear sesion de inicio de sesion exitoso*/
                        $_SESSION['loginsuccesa'] = 'Admin logueado';
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoiniciarsesion", "Bienvenido a EduardEnergyDrinks", "?controller=administratorController&action=home");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroriniciarsesion", "Este administrador no se encuentra registrado", "?controller=administratorController&action=windowlogin");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowlogin");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowlogin");
            }
        }

    }

?>