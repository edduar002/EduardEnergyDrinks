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

        public function home(){
            /*Incluir la vista*/
            require_once "views/administrator/Home.html";
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