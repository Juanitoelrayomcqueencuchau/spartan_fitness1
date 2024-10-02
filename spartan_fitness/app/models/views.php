<?php

namespace app\models;

class views
{

    protected function obtenerVistasModelo($vista)
    {

        $listaBlanca = ["inicio", "registrarUsuario", "registrarRutina", "listaRutinas", "actualizarRutina", "listaUsuarios", "actualizarUsuario", "listaEjercicios", "actualizarEjercicio", "registrarEjercicio",
                        "cerrarSesion"];

        if (in_array($vista, $listaBlanca)) {
            if (is_file("./app/views/content/" . $vista . ".php")) {
                $contenido = "./app/views/content/" . $vista . ".php";
            } else {
                $contenido = "404";
            }
        } elseif ($vista == "login" || $vista == "index") {
            $contenido = "login";
        } else {
            $contenido = "404";
        }
        return $contenido;
    }
}
