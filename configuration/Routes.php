<?php

    /*
    Clase con todas las rutas utilizadas
    */

    /*Ruta de alojamiento local*/

    define("init", "http://localhost/EduardEnergyDrinks");

    /*Ruta para usuario*/

    define("user", "/?controller=userController");
    define("windowLogin", "&action=windowlogin");
    define("windowRegisterUser", "&action=windowRegister");
    define("login", "&action=login");
    define("registerUser", "&action=register");
    define("logoutUser", "&action=logout");
    define("myProfile", "&action=myProfile");
    define("myShops", "&action=myShops");
    define("mySales", "&action=mySales");
    define("windowChangePassword", "&action=windowChangePassword");
    define("managementProducts", "&action=managementProducts");
    define("managementDirections", "&action=managementDirections");
    define("managementPays", "&action=managementPays");
    define("deleteUser", "&action=delete");
    define("updateUser", "&action=update");

    /*Ruta para producto*/

    define("product", "/?controller=productController");
    define("windowRegisterProduct", "&action=windowRegister");
    define("windowProducts", "&action=windowProducts");
    define("registerProduct", "&action=register");
    define("windowUpdateProduct", "&action=windowUpdate");
    define("detailProduct", "&action=detail");
    define("deleteProduct", "&action=delete");
    define("updateProduct", "&action=update");

    /*Rutas para pago*/

    define("pay", "/?controller=payController");
    define("windowRegisterPay", "&action=windowRegister");
    define("registerPay", "&action=register");
    define("windowUpdatePay", "&action=windowUpdate");
    define("deletePay", "&action=delete");
    define("updatePay", "&action=update");

    /*Rutas para envio*/

    define("direction", "/?controller=directionController");
    define("windowRegisterDirection", "&action=windowRegister");
    define("registerDirection", "&action=register");
    define("windowUpdateDirection", "&action=windowUpdate");
    define("deleteDirection", "&action=delete");
    define("updateDirection", "&action=update");

    /*Rutas para transaccion*/

    define("transaction", "/?controller=transactionController");
    define("windowPurchase", "&action=windowPurchase");

?>