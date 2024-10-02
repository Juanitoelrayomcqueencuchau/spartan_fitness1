<?php

namespace app\controllers;

use app\models\mainModel;

class routineController extends mainModel
{

    //Controlador de registro de rutinas//

    public function registrarRutinaControlador()
{
    // Almacenamiento de datos
    $rutinaNombre = $this->limpiarCadena($_POST['rutina_nombre']);
    $rutinaDescripcion = $this->limpiarCadena($_POST['rutina_descripcion']);

    // Verificación de datos
    if ($rutinaNombre == "" || $rutinaDescripcion == "") {
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No has llenado todos los campos que son obligatorios",
            "icono" => "error"
        ];
        return json_encode($alerta);
        exit();
    }

    $rutina_datos_reg = [
        [
            "campo_nombre_rutina" => "rutina_nombre",
            "campo_marcador_rutina" => ":Rutina",
            "campo_valor_rutina" => $rutinaNombre
        ],
        [
            "campo_nombre_rutina" => "rutina_descripcion",
            "campo_marcador_rutina" => ":Descripcion",
            "campo_valor_rutina" => $rutinaDescripcion
        ]
    ];

    $registrar_rutina = $this->guardarDatosRutina("rutina", $rutina_datos_reg);

    if ($registrar_rutina->rowCount() == 1) {
        $alerta = [
            "tipo" => "limpiar",
            "titulo" => "Rutina registrada",
            "texto" => "La rutina " . $rutinaNombre . " se registró con éxito ",
            "icono" => "success"
        ];
    } else {
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No se pudo registrar la rutina, por favor intente nuevamente",
            "icono" => "error"
        ];
    }

    return json_encode($alerta);
}


    //Controlador de listado de rutinas//
    public function listarRutinaControlador($pagina, $registros, $url, $busqueda)
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

            $consulta_datos = "SELECT * FROM rutina WHERE 
            ((rutina_id!='" . $_SESSION['id'] . "' AND rutina_id!='17') 
            AND (rutina_nombre LIKE '%$busqueda%' 
            OR rutina_descripcion LIKE '%$busqueda%')) 
            ORDER BY rutina_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(rutina_id) FROM rutina WHERE 
            ((rutina_id!='" . $_SESSION['id'] . "' AND rutina_id!='17') 
            AND (rutina_nombre LIKE '%$busqueda%' 
            OR rutina_descripcion LIKE '%$busqueda%'))";
        } else {

            $consulta_datos = "SELECT * FROM rutina WHERE 
            rutina_id!='" . $_SESSION['id'] . "' AND rutina_id!='17' 
            ORDER BY rutina_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(rutina_id) FROM rutina WHERE 
            rutina_id!='" . $_SESSION['id'] . "' AND rutina_id!='17'";
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
                        <th class="has-text-centered">Nombre de Rutina</th>
                        <th class="has-text-centered">Descripcion</th>
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
					<td>' . $rows['rutina_nombre'] . '</td>
                    <td>' . $rows['rutina_descripcion'] . '</td>
	                <td>
	                    <a href="' . APP_URL . 'actualizarRutina/' . $rows['rutina_id'] . '/" class="button is-success 
                        is-rounded is-small">Actualizar</a>
	                </td>
	                <td>
	                	<form class="FormularioAjax" action=
                        "' . APP_URL . 'app/ajax/rutinaAjax.php" 
                        method="POST" autocomplete="off">

	                		<input type="hidden" 
                            name="modulo_rutina" value="eliminar">
	                		<input type="hidden" name="rutina_id" 
                            value="' . $rows['rutina_id'] . '">

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

            $tabla .= '<p class="has-text-right">Mostrando rutinas 
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

    //Controlador para eliminar rutinas//
    public function eliminarRutinaControlador()
    {

        $id = $this->limpiarCadena(($_POST['rutina_id']));

        if ($id == 1) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No es posible eliminar la rutina principal",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificar rutina//
        $datos = $this->ejecutarConsulta("SELECT * FROM rutina WHERE 
        rutina_id='$id'");

        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La rutina no ha sido encontrada",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        $eliminarRutina = $this->eliminarRegistro("rutina", "rutina_id", $id);

        if ($eliminarRutina->rowCount() == 1) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Rutina eliminada",
                "texto" => "La rutina " . $datos['rutina_nombre'] . "  se eliminó con éxito",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La rutina " . $datos['rutina_nombre'] . " no pudo ser eliminada",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }

    //Controlador para actualizar rutinas//
    public function actualizarRutinaControlador()
    {
        $id = $this->limpiarCadena($_POST['rutina_id']);

        //Verificando rutina//
        $datos = $this->ejecutarConsulta("SELECT * FROM rutina WHERE 
            rutina_id='$id'");
        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos encontrado la rutina en el sistema",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        //Almacenamiento de datos//
        $rutinaNombre = $this->limpiarCadena($_POST['rutina_nombre']);
        $rutinaDescripcion = $this->limpiarCadena($_POST['rutina_descripcion']);

        //Verificacion de datos//
        if ($rutinaNombre == "" || $rutinaDescripcion == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        $rutina_datos_ac = [
            [
                "campo_nombre_rutina" => "rutina_nombre",
                "campo_marcador_rutina" => ":Rutina",
                "campo_valor_rutina" => $rutinaNombre
            ],
            [
                "campo_nombre_rutina" => "rutina_descripcion",
                "campo_marcador_rutina" => ":Descripcion",
                "campo_valor_rutina" => $rutinaDescripcion
            ]
        ];

        $condicion = [
            "condicion_campo_rutina" => "rutina_id",
            "condicion_marcador_rutina" => ":ID",
            "condicion_valor_rutina" => $id
        ];

        if ($this->actualizarDatosRutina("rutina", $rutina_datos_ac, $condicion)) {

            if ($id == $_SESSION['id']) {
                $_SESSION['rutina_nombre'] = $rutinaNombre;
                $_SESSION['rutina_descripcion'] = $rutinaDescripcion;
            }

            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Rutina actualizada",
                "texto" => "Los datos de la rutina " . $datos['rutina_nombre'] . " se actualizaron correctamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido actualizar los datos del rutina " . $datos['rutina_nombre'] . ", por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }
}
