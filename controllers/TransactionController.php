<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class TransactionController{

        /*Funcion para abrir ventana de registro*/
        public function windowPurchase(){
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

    }

?>