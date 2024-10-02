<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

use app\controllers\exerciseController;

    if(isset($_POST['modulo_ejercicio'])) {

        $insEjercicio = new exerciseController();

        if($_POST['modulo_ejercicio'] == "registrar") {
            echo $insEjercicio->registrarEjercicioControlador();
        }

        if($_POST['modulo_ejercicio'] == "eliminar") {
            echo $insEjercicio->eliminarEjercicioControlador();
        }

        if($_POST['modulo_ejercicio'] == "actualizar") {
            echo $insEjercicio->actualizarEjercicioControlador();
        }
         
    } else {
        session_destroy();
        header("Location: ".APP_URL. "login/");
    }