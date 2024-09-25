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
            
        }

    }

?>