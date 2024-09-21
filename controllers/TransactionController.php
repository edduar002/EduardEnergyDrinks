<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class TransactionController{

        public function windowCar(){
            /*Instanciar modelo*/
            $model = new Model();
            $total = 0;
            $list = $model -> productsListCar($_SESSION['loginsucces']['USER_ID']);
            require_once "views/transaction/Car.html";
        }

        public function registerCar(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : false;
                $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si todos los datos llegaron*/
                if($idProduct && $cantidad){
                    /*Instanciar modelo*/
                    $model = new Model();
                    $unico = $model -> uniqueCp($_SESSION['loginsucces']['USER_ID'], $idProduct);
                    if($unico == 0){
                        $registro = $model -> registercar($_SESSION['loginsucces']['USER_ID'], 1, $created_at2);
                        if($registro){
                            $id_car = $model -> getLastCar();
                            $registro2 = $model -> registerCarProduct($id_car, $idProduct, 1, $cantidad, 100, $created_at2);
                        }
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("carerror", "Ya has agregado el producto al carrito", "?controller=transactionController&action=windowCar");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=productController&action=detail&id=$idProduct");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=productController&action=windowProducts");
            }
        }

        /*Funcion para abrir ventana de registro*/
        public function windowPurchase(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : false;
                $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : false;
                $vendedor = isset($_POST['vendedor']) ? $_POST['vendedor'] : false;
                /*Comprobar si todos los datos llegaron*/
                if($idProduct && $cantidad && $vendedor){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Obtener lista de direcciones propias*/
                    $listDirections = $model->directionListManagement($_SESSION['loginsucces']['USER_ID']);
                    /*Obtener lista de pagos propias*/
                    $listPays = $model->payListManagement($_SESSION['loginsucces']['USER_ID']);
                    /*Incluir la vista*/
                    require_once "views/transaction/Purchase.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=productController&action=detail&id=$idProduct");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=productController&action=detail&id=$idProduct");
            }
        }

        /*Funcion para ver el detalle de la compra*/
        public function detailShop(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener el detalle de la compra*/
            $detail = $model -> detailShop($_GET['id']);
            /*Incluir la vista*/
            require_once "views/transaction/DetailShop.html";
        }

        /*Funcion para ver el detalle de la venta*/
        public function detailSale(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener el detalle de la venta*/
            $detail = $model -> detailSale($_GET['id']);
            /*Incluir la vista*/
            require_once "views/transaction/DetailSale.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowConfirm(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $idVendedor = isset($_POST['vendedor']) ? $_POST['vendedor'] : false;
                $idPay = isset($_POST['id_pay']) ? $_POST['id_pay'] : false;
                $idDirection = isset($_POST['id_direction']) ? $_POST['id_direction'] : false;
                $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : false;
                /*Comprobar si todos los datos llegaron*/
                if($idVendedor && $idPay && $idDirection && $idProduct){
                    /*Instanciar modelo*/  
                    $model = new Model();
                    /*Obtener cada dato*/
                    $vendedor = $model -> getUser($_POST['vendedor']);
                    $pay = $model -> getPay($_POST['id_pay']);
                    $direction = $model -> getDirection($_POST['id_direction']);
                    $product = $model -> getProduct($_POST['idProduct']);
                    $cantidad = $_POST['cantidad'];
                    /*Incluir la vista*/
                    require_once "views/transaction/Confirm.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=transactionController&action=windowPurchase");
                }
            /*De lo contrario*/     
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("transacionterror", "Ha ocurrido un error al realizar la compra", "?controller=transactionController&action=windowPurchase");
            }
        }

        /*Funcion para guardar la transaccion*/
        public function purchase(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener factura*/
            $number_bill = $model -> getLastTransaction() + 1000;
            /*Obtener comprador*/
            $idBuyer = $_SESSION['loginsucces']['USER_ID'];
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $idDirection = isset($_POST['id_direction']) ? $_POST['id_direction'] : false;
                $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : false;
                $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : false;
                $idPay = isset($_POST['id_pay']) ? $_POST['id_pay'] : false;
                /*Comprobar si los datos llegan*/
                if ($idDirection && $idProduct && $cantidad && $idPay) {
                    /*Obtener datos restantes*/
                    $total = $cantidad * ($model -> getProductDataPu($idProduct)['PRICE']);
                    $date_time = date('Y-m-d');
                    $date_time2 = (new DateTime($date_time))->format('d/m/y');
                    $created_at = date('Y-m-d');
                    $created_at2 = (new DateTime($created_at))->format('d/m/y');
                    /*Llamar la funcion del modelo que registra la transaccion*/  
                    $resultado = $model->registerTransaction($number_bill, $idBuyer, $idDirection, $idPay, $total, $date_time2, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if ($resultado != false) {
                        /*Obtener la ultima transaccion registrada*/
                        $id_transaction = $model -> getLastTransaction();
                        $id_seller = $model -> getProductDataPu($idProduct)['USER_ID'];
                        /*Comprobar si los datos llegan*/
                        if($id_transaction && $idProduct && $id_seller && $cantidad && $created_at2){
                            /*Llamar la funcion del modelo que registra la transaccion del producto*/  
                            $resultado2 = $model->registerTransactionProduct($id_transaction, $idProduct, $id_seller, $cantidad, $created_at2);
                            /*Comprobar si el registrado ha sido exitoso*/   
                            if ($resultado2 != false) {
                                /*Llamar la funcion del modelo que decrementa el inventario*/ 
                                $model -> decreaseInventory($idProduct, $cantidad);
                                /*Llamar la funcion que aumenta las ganancias del vendedor*/
                                $model -> increaseProfits($id_seller, $total);
                                /*Redirigir*/
                                header("Location:"."http://localhost/EduardEnergyDrinks/?controller=productController&action=windowProducts");
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