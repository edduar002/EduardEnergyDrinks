<?php

    /*
    Clase de archivo de ayudas
    */

    class Helps{

        /*Funcion para crear sesiones y redigirir a paginas*/
        public static function createSessionAndRedirect($nombreSesion, $contenidoSesion, $ruta){
            /*Crear sesion*/
            $_SESSION[$nombreSesion] = $contenidoSesion;
            /*Redirigir*/
            header("Location:"."http://localhost/EduardEnergyDrinks/".$ruta);
        }

        /*Funcion para eliminar sesiones*/
        public static function deleteSession($nombreSesion){
            /*Comprobar si la sesion existe*/
            if(isset($_SESSION[$nombreSesion])){
                /*Eliminar sesion*/
                unset($_SESSION[$nombreSesion]);
            }
        }

        /*Funcion para comprobar el tipo de un archivo*/
        public static function comprobeImage($archivo){
            /*Comprobar si el archivo cumple las condiciones de formato de imagen*/
            if($archivo == "image/jpg" || $archivo == "image/jpeg" || $archivo == "image/png" || $archivo == "image/gif"){
                /*Retornar el resultado*/
                return 1;
            /*Comprobar si el archivo esta vacio*/
            }else if($archivo == ''){
                /*Retornar el resultado*/
                return 2;
            /*De lo contrario*/
            }else{
                /*Retornar el resultado*/
                return 3;
            }
        }

        /*Funcion para guardar la imagen del administrador en los archivos*/
        public static function saveImage($archivo, $carpetaGuardada){
            /*Comprobar si existe el archivo o este llega*/
            if(isset($archivo)){
                /*Extraer el tipo de archivo de la imagen*/
                $tipoArchivo = $archivo['type'];
                /*Comprobar si el archivo tiene la extensión de una imagen*/
                if(Helps::comprobeImage($tipoArchivo) == 1){
                    /*Extraer nombre del archivo de imagen*/
                    $nombreArchivo = $archivo['name'];
                    /*Comprobar si no existe un directorio para las imagenes a subir*/
                    if(!is_dir('resources/'.$carpetaGuardada)){
                        /*Crear el directorio*/
                        mkdir('resources/'.$carpetaGuardada, 0777, true);
                    }
                    /*Mover la foto subida a la ruta temporal del servidor y luego a la de la carpeta de las imagenes*/
                    move_uploaded_file($archivo['tmp_name'], 'resources/'.$carpetaGuardada.'/'.$nombreArchivo);
                }
            }
            /*Retornar el resultado*/
            return $nombreArchivo;
        }

        /*Funcion para validar la clave y que sea segura*/
        public static function validatePassword($contrasena) {
            /*Verificar todos los patrones*/
            if (strlen($contrasena) >= 5 && preg_match('/[A-Z]/', $contrasena) && preg_match('/[a-z]/', $contrasena) && preg_match('/\d/', $contrasena) && preg_match('/[\W_]/', $contrasena)) {
                /*Retornar el resultado*/
                return true;
            }
        }

        /*Funcion para generar un codigo aleatorio*/
        public static function generateRandomCode() {
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+[]{}|;:,.<>?';
            $codigo = '';
            $maxIndex = strlen($caracteres) - 1;
            /*Recorrer la palabra*/
            for ($i = 0; $i < 10; $i++) {
                $indiceAleatorio = random_int(0, $maxIndex);
                $codigo .= $caracteres[$indiceAleatorio];
            }
            /*Retornar el resultado*/
            return $codigo;
        }

    }

?>    