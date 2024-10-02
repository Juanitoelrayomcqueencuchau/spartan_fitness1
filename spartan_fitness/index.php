<?php

require_once "./config/app.php";
require_once "./autoload.php";
require_once "./app/views/inc/session_start.php";

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ["login"];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <?php require_once "./app/views/inc/head.php"; ?>

</head>

<body>
    <?php

    use app\controllers\controller;
    use app\controllers\loginController;

    $insLogin = new loginController();

    $controller = new controller();
    $vista = $controller->obtenerVistasControlador($url[0]);

    if ($vista == "login" || $vista == "404") {
        require_once "./app/views/content/" . $vista . ".php";
    } else {

        //Cerrar sesion//
        if(!isset($_SESSION['id']) || !isset($_SESSION['nombre']) || !isset($_SESSION['apellido']) ||
        $_SESSION['id'] == "" || $_SESSION['nombre'] == "" || $_SESSION['apellido'] == ""){
            $insLogin->cerrarSesionControlador();
            exit();
        }

        require_once "./app/views/inc/navbar.php";
        require_once $vista;
    }

    require_once "./app/views/inc/script.php";

    ?>
</body>

</html>