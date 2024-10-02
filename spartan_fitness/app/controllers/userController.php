<?php

namespace app\controllers;

use app\models\mainModel;

class userController extends mainModel
{

    //Controlador de registro de usuarios//

    public function registrarUsuarioControlador()
    {

        //Almacenamiento de datos//
        $nombre = $this->limpiarCadena($_POST['usuario_nombre']);
        $apellido = $this->limpiarCadena($_POST['usuario_apellido']);

        $peso = $this->limpiarCadena($_POST['usuario_peso']);
        $estatura = $this->limpiarCadena($_POST['usuario_estatura']);
        $edad = $this->limpiarCadena($_POST['usuario_edad']);
        $clave = $this->limpiarCadena($_POST['usuario_clave']);


        //Verificacion de datos//
        if ($nombre == "" || $apellido == "" || $peso == "" || $estatura == "" || $edad == "" || $clave == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Integridad de datos//
        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9$@.-]{4,100}", $clave)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Clave incorrecta, intente nuevamente",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        $usuario_datos_reg = [
            [
                "campo_nombre" => "usuario_nombre",
                "campo_marcador" => ":Nombre",
                "campo_valor" => $nombre
            ],
            [
                "campo_nombre" => "usuario_apellido",
                "campo_marcador" => ":Apellido",
                "campo_valor" => $apellido
            ],
            [
                "campo_nombre" => "usuario_peso",
                "campo_marcador" => ":Peso",
                "campo_valor" => $peso
            ],
            [
                "campo_nombre" => "usuario_estatura",
                "campo_marcador" => ":Estatura",
                "campo_valor" => $estatura
            ],
            [
                "campo_nombre" => "usuario_edad",
                "campo_marcador" => ":Edad",
                "campo_valor" => $edad
            ],
            [
                "campo_nombre" => "usuario_clave",
                "campo_marcador" => ":Clave",
                "campo_valor" => $clave
            ]
        ];

        $registrar_usuario = $this->guardarDatos("usuario", $usuario_datos_reg);

        if ($registrar_usuario->rowCount() == 1) {
            $alerta = [
                "tipo" => "limpiar",
                "titulo" => "Usuario registrado",
                "texto" => "El usuario " . $nombre . " " . $apellido . " se registró con éxito ",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo registrar el usuario, por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }

    //Controlador de listado de usuarios//
    public function listarUsuarioControlador($pagina, $registros, $url, $busqueda)
    {

        $pagina = $this->limpiarCadena($pagina);
        $registros = $this->limpiarCadena($registros);

        $url = $this->limpiarCadena($url);
        $url = APP_URL . $url . "/";

        $busqueda = $this->limpiarCadena($busqueda);
        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        if (isset($busqueda) && $busqueda != "") {

            $consulta_datos = "SELECT * FROM usuario WHERE 
            ((usuario_id!='" . $_SESSION['id'] . "' AND usuario_id!='17') 
            AND (usuario_nombre LIKE '%$busqueda%' 
            OR usuario_apellido LIKE '%$busqueda%')) 
            ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE 
            ((usuario_id!='" . $_SESSION['id'] . "' AND usuario_id!='17') 
            AND (usuario_nombre LIKE '%$busqueda%' 
            OR usuario_apellido LIKE '%$busqueda%'))";
        } else {

            $consulta_datos = "SELECT * FROM usuario WHERE 
            usuario_id!='" . $_SESSION['id'] . "' AND usuario_id!='17' 
            ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE 
            usuario_id!='" . $_SESSION['id'] . "' AND usuario_id!='17'";
        }

        $datos = $this->ejecutarConsulta(($consulta_datos));
        $datos = $datos->fetchAll();

        $total = $this->ejecutarConsulta(($consulta_total));
        $total = (int) $total->fetchColumn();

        $numeroPaginas = ceil($total / $registros);

        $tabla .= '
            <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">#</th>
                        <th class="has-text-centered">Nombre</th>
                        <th class="has-text-centered">Apellido</th>
                        <th class="has-text-centered">Peso (En Kg)</th>
                        <th class="has-text-centered">Estatura (En Cm)</th>
                        <th class="has-text-centered">Edad</th>
                        <th class="has-text-centered" colspan="3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
        ';

        if ($total >= 1 && $pagina <= $numeroPaginas) {
            $contador = $inicio + 1;
            $pag_inicio = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                    <tr class="has-text-centered">
					<td>' . $contador . '</td>
					<td>' . $rows['usuario_nombre'] . '</td>
                    <td>' . $rows['usuario_apellido'] . '</td>
					<td>' . $rows['usuario_peso'] . '</td>
					<td>' . $rows['usuario_estatura'] . '</td>
					<td>' . $rows['usuario_edad'] . '</td>
	                <td>
	                    <a href="' . APP_URL . 'actualizarUsuario/' . $rows['usuario_id'] . '/" class="button is-success 
                        is-rounded is-small">Actualizar</a>
	                </td>
	                <td>
	                	<form class="FormularioAjax" action=
                        "' . APP_URL . 'app/ajax/usuarioAjax.php" 
                        method="POST" autocomplete="off">

	                		<input type="hidden" 
                            name="modulo_usuario" value="eliminar">
	                		<input type="hidden" name="usuario_id" 
                            value="' . $rows['usuario_id'] . '">

	                    	<button type="submit" class="button 
                            is-danger is-rounded is-small">Eliminar</
                            button>
	                    </form>
	                </td>
				</tr>

                ';
                $contador++;
            }
            $pag_final = $contador - 1;
        } else {
            if ($total >= 1) {
                $tabla .= '
                    <tr class="has-text-centered" >
	                    <td colspan="7">
	                        <a href="' . $url . '1/" class="button is-link 
                            is-rounded is-small mt-4 mb-4">
	                            Haga clic acá para recargar el listado
	                        </a>
	                    </td>
	                </tr>
                ';
            } else {
                $tabla .= '
                    <tr class="has-text-centered" >
                    <td colspan="7">
                        No hay registros en el sistema
                    </td>
                </tr>
                    ';
            }
        }

        $tabla .= '</tbody></table></div>';

        //Paginacion//

        if ($total >= 1 && $pagina <= $numeroPaginas) {

            $tabla .= '<p class="has-text-right">Mostrando usuarios 
                <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un 
                <strong>total de ' . $total . '</strong></p>';

            $tabla .= $this->paginadorTablas(
                $pagina,
                $numeroPaginas,
                $url,
                7
            );
        }

        return $tabla;
    }

    //Controlador para eliminar usuarios//
    public function eliminarUsuarioControlador()
    {

        $id = $this->limpiarCadena(($_POST['usuario_id']));

        if ($id == 1) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No es posible eliminar el usuario principal",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificar usuario//
        $datos = $this->ejecutarConsulta("SELECT * FROM usuario WHERE 
        usuario_id='$id'");

        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El usuario no ha sido encontrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        $eliminarUsuario = $this->eliminarRegistro("usuario", "usuario_id", $id);

        if ($eliminarUsuario->rowCount() == 1) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Usuario eliminado",
                "texto" => "El usuario " . $datos['usuario_nombre'] . " " .
                    $datos['usuario_apellido'] . " se eliminó con éxito",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El usuario " . $datos['usuario_nombre'] . " 
                " . $datos['usuario_apellido'] . " no pudo ser eliminado",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }

    //Controlador para actualizar usuarios//
    public function actualizarUsuarioControlador()
    {
        $id = $this->limpiarCadena($_POST['usuario_id']);

        //Verificando usuario//
        $datos = $this->ejecutarConsulta("SELECT * FROM usuario WHERE 
            usuario_id='$id'");
        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos encontrado el usuario en el sistema",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        $admin_usuario = $this->limpiarCadena($_POST['administrador_usuario']);
        $admin_clave = $this->limpiarCadena($_POST['administrador_clave']);

        //Verificacion de campos obligatorios//
        if ($admin_usuario == "" || $admin_clave == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios, 
                que corresponden con su USUARIO Y CLAVE",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Su USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9$@.-]{4,100}", $admin_clave)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Su CLAVE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificacion de admin//
        $check_admin = $this->ejecutarConsulta("SELECT * FROM 
            usuario WHERE usuario_nombre='$admin_usuario' AND usuario_id='" .
            $_SESSION['id'] . "'");

        if ($check_admin->rowCount() == 1) {

            $check_admin = $check_admin->fetch();

            if ($check_admin['usuario_nombre'] != $admin_usuario || $check_admin['usuario_clave'] != $admin_clave) {

                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "USUARIO o CLAVE de administrador incorrectos",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "USUARIO o CLAVE de administrador incorrectos",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Almacenamiento de datos//
        $nombre = $this->limpiarCadena($_POST['usuario_nombre']);
        $apellido = $this->limpiarCadena($_POST['usuario_apellido']);

        $peso = $this->limpiarCadena($_POST['usuario_peso']);
        $estatura = $this->limpiarCadena($_POST['usuario_estatura']);
        $edad = $this->limpiarCadena($_POST['usuario_edad']);
        $clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
        $clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

        //Verificacion de datos//
        if ($nombre == "" || $apellido == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificando integridad de los datos//
        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El APELLIDO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificando claves//
        if ($clave1 != "" || $clave2 != "") {

            if ($this->verificarDatos("[a-zA-Z0-9$@.-]{4,100}", $clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{4,100}", $clave2)) {

                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Las CLAVES no coinciden con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            } else {

                if ($clave1 != $clave2) {
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                } else {
                    $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
                }
            }
        } else {
            $clave = $datos['usuario_clave'];
        }

        $usuario_datos_ac = [
            [
                "campo_nombre" => "usuario_nombre",
                "campo_marcador" => ":Nombre",
                "campo_valor" => $nombre
            ],
            [
                "campo_nombre" => "usuario_apellido",
                "campo_marcador" => ":Apellido",
                "campo_valor" => $apellido
            ],
            [
                "campo_nombre" => "usuario_peso",
                "campo_marcador" => ":Peso",
                "campo_valor" => $peso
            ],
            [
                "campo_nombre" => "usuario_estatura",
                "campo_marcador" => ":Estatura",
                "campo_valor" => $estatura
            ],
            [
                "campo_nombre" => "usuario_edad",
                "campo_marcador" => ":Edad",
                "campo_valor" => $edad
            ],
            [
                "campo_nombre" => "usuario_clave",
                "campo_marcador" => ":Clave",
                "campo_valor" => $clave
            ]
        ];

        $condicion = [
            "condicion_campo" => "usuario_id",
            "condicion_marcador" => ":ID",
            "condicion_valor" => $id
        ];

        if ($this->actualizarDatos("usuario", $usuario_datos_ac, $condicion)) {

            if ($id == $_SESSION['id']) {
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['apellido'] = $apellido;
            }

            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Usuario actualizado",
                "texto" => "Los datos del usuario " . $datos['usuario_nombre'] . " " . $datos['usuario_apellido'] . " se actualizaron correctamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido actualizar los datos del usuario " . $datos['usuario_nombre'] . " " . $datos['usuario_apellido'] . ", por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }
}
