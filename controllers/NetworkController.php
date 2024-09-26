<?php

    /*
    Clase controlador de pago
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class NetworkController{

        /*Funcion para abrir ventana de registro*/
        public function windowAddUser(){
            /*Incluir la vista*/
            require_once "views/network/AddUser.html";
        }

        /*Funcion para abrir ventana de registro*/
        public function addUser(){
            var_dump(4);
            die();
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $userId = $_SESSION['loginsucces']['USER_ID'];
                $code = isset($_POST['code']) ? $_POST['code'] : false;
                /*Comprobar si los datos llegan*/
                if ($code) {
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el pago*/  
                    $resultado = $model->addUser($userId, $code);
                    /*Comprobar si el registrado ha sido exitoso*/                  
                    if ($resultado != false) {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registrosucces", "Se ha registrado exitosamente el pago", "?controller=userController&action=managementPays");
                    /*De lo contrario*/  
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro de la direccion", "?controller=payController&action=windowRegister");
                    }
                /*De lo contrario*/  
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro de la direccion", "?controller=payController&action=windowRegister");
                }
            /*De lo contrario*/  
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=payController&action=windowRegister");
            }
        }

    }

?>