<?php

/*
    Clase controlador de usuario
    */

/*Incluir el modelo*/
require_once 'models/Model.php';

class UserController
{

    /*Funcion para abrir ventana de login*/
    public function windowlogin()
    {
        /*Incluir la vista*/
        require_once "views/user/Login.html";
    }

    /*Funcion para abrir ventana de cambiar clave*/
    public function windowChangePassword()
    {
        /*Incluir la vista*/
        require_once "views/user/ChangePassword.html";
    }

    /*Funcion para abrir ventana de registro*/
    public function windowRegister()
    {
        /*Incluir la vista*/
        require_once "views/user/Register.html";
    }

    /*Funcion para abrir ventana de gestion de productos*/
    public function managementProducts()
    {
        $model = new Model();
        $listProducts = $model->productsListManagement();
        /*Incluir la vista*/
        require_once "views/user/ManagementProducts.html";
    }

    /*Funcion para abrir ventana de gestion de productos*/
    public function managementDirections()
    {
        $model = new Model();
        $listDirections = $model->directionListManagement();
        /*Incluir la vista*/
        require_once "views/user/ManagementDirections.html";
    }
    
    /*Funcion para abrir ventana de gestion de productos*/
    public function managementPays()
    {
        $model = new Model();
        $listPays = $model->payListManagement();
        /*Incluir la vista*/
        require_once "views/user/ManagementPays.html";
    }

    /*Funcion para abrir ventana de mi perfil*/
    public function myProfile()
    {
        /*Incluir la vista*/
        require_once "views/user/MyProfile.html";
    }

    /*Funcion para abrir ventana de mis compras*/
    public function myShops()
    {
        /*Incluir la vista*/
        require_once "views/user/MyShops.html";
    }

    /*Funcion para abrir ventana de mis ventas*/
    public function mySales()
    {
        /*Incluir la vista*/
        require_once "views/user/MySales.html";
    }

    /*Funcion para cerrar sesion*/
    public function logout()
    {
        /*Llamar al archivo de ayudas*/
        Helps::deleteSession("loginsucces");
        /*Redirigir*/
        header("Location:" . "http://localhost/EduardEnergyDrinks/?controller=userController&action=windowlogin");
        /*Crear sesion*/
        $_SESSION['logoutm'] = "Sesión cerrada con exito";
    }

    /*Funcion para iniciar de sesion*/
    public function login()
    {
        /*Comprobar si llegan los datos del formulario enviados por post*/
        if (isset($_POST)) {
            /*Asignar los datos si llegan*/
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $password = isset($_POST['password']) ? $_POST['password'] : false;
            /*Comprobar si los datos llegan*/
            if ($email && $password) {
                /*Instanciar modelo*/
                $model = new Model();
                /*Llamar la funcion del modelo*/
                $resultado = $model->login($email, $password);
                /*Comprobar si el usuario existe*/
                if ($resultado != NULL) {
                    /*Crear sesion de inicio de sesion exitoso*/
                    $_SESSION['loginsucces'] = $resultado;
                    $_SESSION['loginsuccesm'] = "Has ingresado exitosamente a EDUARD ENERGY DRINKS";
                    /*Redirigir al lugar requerido*/
                    header("Location:" . "http://localhost/EduardEnergyDrinks/?controller=productController&action=windowProducts");
                } else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("iniciarsesionerror", "Este usuario no se encuentra registrado", "?controller=userController&action=windowlogin");
                }
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("iniciarsesionerror", "Ha ocurrido un error al iniciar sesion", "?controller=userController&action=windowlogin");
            }
        } else {
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("iniciarsesionerror", "Ha ocurrido un error inesperado", "?controller=userController&action=windowlogin");
        }
    }

    /*Funcion para que un usuario se registre*/
    public function register(){
        /*Comprobar si llegan los datos del formulario enviados por post*/
        if (isset($_POST)) {
            /*Asignar los datos si llegan*/
            $code = helps::generateRandomCode();
            $name = isset($_POST['name']) ? $_POST['name'] : false;
            $surname = isset($_POST['surname']) ? $_POST['surname'] : false;
            $genre = isset($_POST['genre']) ? $_POST['genre'] : false;
            $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : false;
            $phone = isset($_POST['phone']) ? $_POST['phone'] : false;
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $password = isset($_POST['password']) ? $_POST['password'] : false;
            $created_at = date('Y-m-d');
            $birthdate2 = (new DateTime($birthdate))->format('d/m/y');
            $created_at2 = (new DateTime($created_at))->format('d/m/y');
            /*Establecer archivo de foto*/
            $file = $_FILES['image'];
            /*Establecer nombre del archivo de la foto*/
            $image = $file['name'];
            /*Comprobar si los datos llegan*/
            if ($code && $name && $surname && $birthdate && $genre && $email && $password && $file) {
                if(helps::validatePassword($password)){
                    /*Comprobar si la foto es valida y ha sido guardada*/
                    $fotoGuardada = Helps::saveImage($file, "imagesUsers");
                    if ($fotoGuardada) {
                        $model = new Model();
                        /*Instanciar modelo*/
                        $resultado = $model->registerUser(1, $code, $name, $surname, $birthdate2, $genre, $phone, $email, $password, $image, $created_at2);
                        if ($resultado != false) {
                            /*Crear sesion de inicio de sesion exitoso*/
                            $_SESSION['loginsucces'] = $resultado;
                            $_SESSION['loginsuccesm'] = "Bienvenido a EDUARD ENERGY DRINKS";
                            /*Redirigir al lugar requerido*/
                            header("Location:" . "http://localhost/EduardEnergyDrinks/?controller=productController&action=windowProducts");
                        } else {
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro1", "?controller=userController&action=windowRegister");
                        }
                    } else {
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error al realizar el registro2", "?controller=userController&action=windowRegister");
                    }
                }else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado1", "?controller=userController&action=windowRegister");
                }
            } else {
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado2", "?controller=userController&action=windowRegister");
            }
        }else {
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("registroerror", "Ha ocurrido un error inesperado3", "?controller=userController&action=windowRegister");
        }
    }
}
