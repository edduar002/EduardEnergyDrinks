<?php

/*
    Clase controlador de producto
    */

/*Incluir el modelo*/
require_once 'models/Model.php';

class ProductController
{

    /*Funcion para abrir ventana de registro*/
    public function windowRegister()
    {
        /*Incluir la vista*/
        require_once "views/product/Create.html";
    }

    /*Funcion para abrir ventana de catalogo*/
    public function windowProducts()
    {
        $model = new Model();
        $listProducts = $model->productsList();
        /*Incluir la vista*/
        require_once "views/layout/Products.html";
    }

    /*Funcion para abrir ventana de editar*/
    public function windowUpdate()
    {
        if (isset($_GET)) {
            $product_id = isset($_GET['id']) ? $_GET['id'] : false;
            if ($product_id){
                $model = new Model();
                $product = $model -> getProduct($product_id);
                /*Incluir la vista*/
                require_once "views/product/Update.html";
            }
        }
    }

    /*Funcion para que registrar un producto*/
    public function register()
    {
        /*Comprobar si llegan los datos del formulario enviados por post*/
        if (isset($_POST)) {
            /*Asignar los datos si llegan*/
            $user_id = $_SESSION['loginsucces']['ID'];
            $name = isset($_POST['name']) ? $_POST['name'] : false;
            $price = isset($_POST['price']) ? $_POST['price'] : false;
            $units = isset($_POST['units']) ? $_POST['units'] : false;
            $content = isset($_POST['content']) ? $_POST['content'] : false;
            $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
            $description = isset($_POST['description']) ? $_POST['description'] : false;
            $created_at = date('Y-m-d');
            $created_at2 = (new DateTime($created_at))->format('d/m/y');
            /*Establecer archivo de foto*/
            $file = $_FILES['image'];
            /*Establecer nombre del archivo de la foto*/
            $image = $file['name'];
            /*Comprobar si los datos llegan*/
            if ($user_id && $name && $price && $units && $content && $stock && $description) {
                /*Comprobar si la foto es valida y ha sido guardada*/
                $fotoGuardada = Helps::saveImage($file, "imagesProducts");
                if ($fotoGuardada) {
                    $model = new Model();
                    /*Instanciar modelo*/
                    $resultado = $model->registerProduct($user_id, 1, $name, $price, $units, $content, $stock, $description, $image, $created_at2);
                    if ($resultado == 1) {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro", "?controller=userController&action=managementProducts");
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro", "?controller=productController&action=windowRegister");
                    }
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro", "?controller=productController&action=windowRegister");
                }
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=productController&action=windowRegister");
            }
        }else {
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado", "?controller=productController&action=windowRegister");
        }
    }

    public function detail()
    {
        /*Comprobar si el dato está llegando*/
        if (isset($_GET)) {
            /*Comprobar si el dato existe*/
            $id = isset($_GET['id']) ? $_GET['id'] : false;
            /*Si el dato existe*/
            if ($id) {
                $model = new Model();
                /*Llamar funcion que trae un videojuego en especifico*/
                $resultado = $model->detailProduct($id);
                /*Comprobar si el videojuego ha llegado*/
                if ($resultado) {
                    /*Incluir la vista*/
                    require_once 'views/product/Detail.html';
                    /*De lo contrario*/
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
                }
                /*De lo contrario*/
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
            /*De lo contrario*/
        } else {
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("errorinesperado", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
        }
    }
}
