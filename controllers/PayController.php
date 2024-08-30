<?php

    /*
    Clase controlador de pago
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class PayController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/pay/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if (isset($_GET)) {
                /*Asignar el dato si llega*/                
                $pay_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($pay_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene el pago*/                    
                    $pay = $model -> getPay($pay_id);
                    /*Incluir la vista*/
                    require_once "views/pay/Update.html";
                    /*De lo contrario*/     
                }else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("updateerror", "Ha ocurrido un error al cargar la ventana", "?controller=userController&action=managementPays");
                }
            /*De lo contrario*/      
            }else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("updateerror", "Ha ocurrido un error inesperado", "controller=userController&action=managementPays");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $user_id = $_SESSION['loginsucces']['ID'];
                $election = isset($_POST['election']) ? $_POST['election'] : false;
                $electionNumber = isset($_POST['electionNumber']) ? $_POST['electionNumber'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if ($user_id && $election && $electionNumber) {
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el pago*/  
                    $resultado = $model->registerPay($user_id, 1, $election, $electionNumber, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if ($resultado != false) {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registrosucces", "Se ha registrado exitosamente el pago", "?controller=userController&action=managementPays");
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro de la direccion", "?controller=payController&action=windowRegister");
                    }
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro de la direccion", "?controller=payController&action=windowRegister");
                }
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=payController&action=windowRegister");
            }
        }

    }

?>