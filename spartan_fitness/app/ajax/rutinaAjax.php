<?php
    require_once "../../config/app.php";
    require_once "../views/inc/session_start.php";
    require_once "../../autoload.php";

use app\controllers\routineController;

    if(isset($_POST['modulo_rutina'])) {

        $insRutina = new routineController();

        if($_POST['modulo_rutina'] == "registrar") {
            echo $insRutina->registrarRutinaControlador();
        }

        if($_POST['modulo_rutina'] == "eliminar") {
            echo $insRutina->eliminarRutinaControlador();
        }

        if($_POST['modulo_rutina'] == "actualizar") {
            echo $insRutina->actualizarRutinaControlador();
        }
         
    } else {
        session_destroy();
        header("Location: ".APP_URL. "login/");
    }