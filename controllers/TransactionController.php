<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class TransactionController{

        /*Funcion para abrir ventana de registro*/
        public function windowPurchase(){
            /*Instanciar modelo*/
            $model = new Model();
            $listDirections = $model->directionListManagement($_SESSION['loginsucces']['ID']);
            $listPays = $model->payListManagement($_SESSION['loginsucces']['ID']);
            /*Incluir la vista*/
            require_once "views/transaction/Purchase.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowConfirm(){
            /*Incluir la vista*/
            require_once "views/transaction/Confirm.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowSuccess(){
            /*Incluir la vista*/
            require_once "views/transaction/Success.html";
        }

        public function purchase(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $number_bill = 1000;
                $idBuyer = $_SESSION['loginsucces']['ID'];
                $idDirection = 1;
                $idPay = 1;
                $total = 22;
                $date_time = date('Y-m-d');
                $date_time2 = (new DateTime($date_time))->format('d/m/y');
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if ($number_bill && $idBuyer && $idDirection && $idPay && $total && $date_time2 && $created_at2) {
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el pago*/  
                    $resultado = $model->registerTransaction($number_bill, $idBuyer, $idDirection, $idPay, $total, $date_time2, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if ($resultado != false) {
                        $id_transaction = 5;
                        $id_product = 1;
                        $id_seller = 1;
                        $units = 3;
                        $created_at = date('Y-m-d');
                        $created_at2 = (new DateTime($created_at))->format('d/m/y');
                        if($id_transaction && $id_product && $id_seller && $units && $created_at2){
                            $resultado2 = $model->registerTransactionProduct($id_transaction, $id_product, $id_seller, $units, $created_at2);
                            if ($resultado2 != false) {
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowSuccess");
                            }
                        }
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