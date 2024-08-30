<?php

/*
    Clase controlador de pago
    */

/*Incluir el modelo*/
require_once 'models/Model.php';

class PayController
{

    /*Funcion para abrir ventana de registro*/
    public function windowRegister()
    {
        /*Incluir la vista*/
        require_once "views/pay/Create.html";
    }

    /*Funcion para abrir ventana de editar*/
    public function windowUpdate()
    {
        if (isset($_GET)) {
            $pay_id = isset($_GET['id']) ? $_GET['id'] : false;
            if ($pay_id){
                $model = new Model();
                $pay = $model -> getPay($pay_id);
                /*Incluir la vista*/
                require_once "views/pay/Update.html";
            }
        }
    }

    /*Funcion para que un usuario se registre*/
    public function register()
    {
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
                $model = new Model();
                /*Instanciar modelo*/
                $resultado = $model->registerPay($user_id, 1, $election, $electionNumber, $created_at2);
                if ($resultado != false) {
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro1", "?controller=userController&action=managementPays");
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro1", "?controller=userController&action=windowRegister");
                }
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro2", "?controller=userController&action=windowRegister");
            }
        } else {
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado3", "?controller=userController&action=windowRegister");
        }
    }
}
