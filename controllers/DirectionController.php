<?php

/*
    Clase controlador de direccion
    */

/*Incluir el modelo*/
require_once 'models/Model.php';

class DirectionController
{

    /*Funcion para abrir ventana de registro*/
    public function windowRegister()
    {
        /*Incluir la vista*/
        require_once "views/direction/Create.html";
    }

    /*Funcion para abrir ventana de editar*/
    public function windowUpdate()
    {
        if (isset($_GET)) {
            $direction_id = isset($_GET['id']) ? $_GET['id'] : false;
            if ($direction_id){
                $model = new Model();
                $direction = $model -> getDirection($direction_id);
                /*Incluir la vista*/
                require_once "views/direction/Update.html";
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
            $carrer = isset($_POST['carrer']) ? $_POST['carrer'] : false;
            $street = isset($_POST['street']) ? $_POST['street'] : false;
            $postal_code = isset($_POST['postalCode']) ? $_POST['postalCode'] : false;
            $direction = isset($_POST['direction']) ? $_POST['direction'] : false;
            $created_at = date('Y-m-d');
            $created_at2 = (new DateTime($created_at))->format('d/m/y');
            /*Comprobar si los datos llegan*/
            if ($user_id && $carrer && $street && $postal_code && $direction) {
                $model = new Model();
                /*Instanciar modelo*/
                $resultado = $model->registerDirection($user_id, 1, $carrer, $street, $postal_code, $direction, $created_at2);
                if ($resultado != false) {
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro1", "?controller=userController&action=managementDirections");
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro1", "?controller=userController&action=managementDirections");
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
