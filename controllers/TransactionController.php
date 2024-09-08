<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class TransactionController{

        /*Funcion para abrir ventana de registro*/
        public function windowPurchase(){
            $idProduct = $_GET['idProduct'];
            $cantidad = $_POST['cantidad'];
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
                /*Instanciar modelo*/      
                $model = new Model();
                /*Asignar los datos si llegan*/
                $number_bill = 1000;
                $idBuyer = $_SESSION['loginsucces']['ID'];
                $idDirection = $_POST['id_direction'];
                $idPay = $_POST['id_pay'];
                $total = $_GET['cantidad'] * ($model -> getProductDataPu($_GET['idProduct'])['PRICE']);
                $date_time = date('Y-m-d');
                $date_time2 = (new DateTime($date_time))->format('d/m/y');
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if ($number_bill && $idBuyer && $idDirection && $idPay && $total && $date_time2 && $created_at2) {
                    /*Llamar la funcion del modelo que registra el pago*/  
                    $resultado = $model->registerTransaction($number_bill, $idBuyer, $idDirection, $idPay, $total, $date_time2, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if ($resultado != false) {
                        $id_transaction = 5;
                        $id_product = $_GET['idProduct'];
                        $id_seller = $model -> getProductDataPu($_GET['idProduct'])['USER_ID'];
                        $units = $_GET['cantidad'];
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