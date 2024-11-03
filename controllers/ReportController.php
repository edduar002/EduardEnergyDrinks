<?php

    /*
    Clase controlador de pago
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class ReportController{

        /*Funcion para abrir el reporte de */
        public function cgpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Comisiones Ganadas por Usuario*/
            $report = $model -> cgpu();
            /*Incluir la vista*/
            require_once "views/reports/Cgpu.html";
        }

        /*Funcion para abrir el reporte de */
        public function cpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Compras por Usuario*/
            $report = $model -> cpu();
            /*Incluir la vista*/
            require_once "views/reports/Cpu.html";
        }

        /*Funcion para abrir el reporte de */
        public function gpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Ganancias por Usuario*/
            $report = $model -> gpu();
            /*Incluir la vista*/
            require_once "views/reports/Gpu.html";
        }

        /*Funcion para abrir el reporte de */
        public function rai(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Referidos Activos vs. Inactivos*/
            $report = $model -> rai();
            /*Incluir la vista*/
            require_once "views/reports/Rai.html";
        }

        /*Funcion para abrir el reporte de */
        public function upn(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Usuarios por Nivel*/
            $report = $model -> upn();
            /*Incluir la vista*/
            require_once "views/reports/Upn.html";
        }

        /*Funcion para abrir el reporte de */
        public function urpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Usuarios Referidos por Usuario*/
            $report = $model -> urpu();
            /*Incluir la vista*/
            require_once "views/reports/Urpu.html";
        }

        /*Funcion para abrir el reporte de */
        public function vcn(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Ventas y Comisiones por Nivel*/
            $report = $model -> vcn();
            /*Incluir la vista*/
            require_once "views/reports/Vcn.html";
        }

        /*Funcion para abrir el reporte de */
        public function vr(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Ventas Realizadas*/
            $report = $model -> vr();
            /*Incluir la vista*/
            require_once "views/reports/Vr.html";
        }

    }

?>