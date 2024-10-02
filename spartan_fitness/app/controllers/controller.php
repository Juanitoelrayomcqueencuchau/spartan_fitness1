<?php

namespace app\controllers;

use app\models\views;

class controller extends views
{

    public function obtenerVistasControlador($vista)
    {
        if ($vista != "") {
            $respuesta = $this->obtenerVistasModelo($vista);
        } else {
            $respuesta = "login";
        }
        return $respuesta;
    }
}
