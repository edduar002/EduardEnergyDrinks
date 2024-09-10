<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class TransactionController{

        /*Funcion para abrir ventana de registro*/
        public function windowPurchase(){
            $idProduct = $_POST['idProduct'];
            $cantidad = $_POST['cantidad'];
            $vendedor = $_POST['vendedor'];
            /*Instanciar modelo*/
            $model = new Model();
            $listDirections = $model->directionListManagement($_SESSION['loginsucces']['ID']);
            $listPays = $model->payListManagement($_SESSION['loginsucces']['ID']);
            /*Incluir la vista*/
            require_once "views/transaction/Purchase.html";
        }

        public function detailShop(){
            /*Instanciar modelo*/      
            $model = new Model();
            $detail = $model -> detailShop($_GET['id']);
            /*Incluir la vista*/
            require_once "views/transaction/DetailShop.html";
        }

        public function detailSale(){
            /*Instanciar modelo*/      
            $model = new Model();
            $detail = $model -> detailSale($_GET['id']);
            /*Incluir la vista*/
            require_once "views/transaction/DetailSale.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowConfirm(){
            $model = new Model();
            $var = $model -> getUser($_POST['vendedor']);
            $var2 = $model -> getPay($_POST['id_pay']);
            $var3 = $model -> getDirection($_POST['id_direction']);
            $var4 = $model -> getProduct($_POST['idProduct']);
            $cantidad = $_POST['cantidad'];
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
                $number_bill = $model -> getLastTransaction() + 1000;
                $idBuyer = $_SESSION['loginsucces']['ID'];
                $idDirection = $_POST['id_direction'];
                $idPay = $_POST['id_pay'];
                $total = $_POST['cantidad'] * ($model -> getProductDataPu($_POST['idProduct'])['PRICE']);
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
                        $id_transaction = $model -> getLastTransaction();
                        $id_product = $_POST['idProduct'];
                        $id_seller = $model -> getProductDataPu($_POST['idProduct'])['USER_ID'];
                        $units = $_POST['cantidad'];
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