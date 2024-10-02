<?php

namespace app\controllers;

use app\models\mainModel;

class exerciseController extends mainModel
{

    //Controlador de registro de ejercicios//

    public function registrarEjercicioControlador()
    {
        // Almacenamiento de datos
        $ejercicioNombre = $this->limpiarCadena($_POST['ejercicio_nombre']);
        $ejercicioDescripcion = $this->limpiarCadena($_POST['ejercicio_descripcion']);

        // Verificación de datos
        if ($ejercicioNombre == "" || $ejercicioDescripcion == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        $ejercicio_datos_reg = [
            [
                "campo_nombre_ejercicio" => "ejercicio_nombre",
                "campo_marcador_ejercicio" => ":Ejercicio",
                "campo_valor_ejercicio" => $ejercicioNombre
            ],
            [
                "campo_nombre_ejercicio" => "ejercicio_descripcion",
                "campo_marcador_ejercicio" => ":Descripcion",
                "campo_valor_ejercicio" => $ejercicioDescripcion
            ]
        ];

        $registrar_ejercicio = $this->guardarDatosEjercicio("ejercicio", $ejercicio_datos_reg);

        if ($registrar_ejercicio->rowCount() == 1) {
            $alerta = [
                "tipo" => "limpiar",
                "titulo" => "Ejercicio registrada",
                "texto" => "El ejercicio " . $ejercicioNombre . " se registró con éxito ",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo registrar el ejercicio, por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }

    //Controlador de listado de ejercicios//
    public function listarEjercicioControlador($pagina, $registros, $url, $busqueda)
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

            $consulta_datos = "SELECT * FROM ejercicio WHERE 
            ((ejercicio_id!='" . $_SESSION['id'] . "' AND ejercicio_id!='17') 
            AND (ejercicio_nombre LIKE '%$busqueda%' 
            OR ejercicio_descripcion LIKE '%$busqueda%')) 
            ORDER BY ejercicio_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(ejercicio_id) FROM ejercicio WHERE 
            ((ejercicio_id!='" . $_SESSION['id'] . "' AND ejercicio_id!='17') 
            AND (ejercicio_nombre LIKE '%$busqueda%' 
            OR ejercicio_descripcion LIKE '%$busqueda%'))";
        } else {

            $consulta_datos = "SELECT * FROM ejercicio WHERE 
            ejercicio_id!='" . $_SESSION['id'] . "' AND ejercicio_id!='17' 
            ORDER BY ejercicio_nombre ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(ejercicio_id) FROM ejercicio WHERE 
            ejercicio_id!='" . $_SESSION['id'] . "' AND ejercicio_id!='17'";
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
                        <th class="has-text-centered">Nombre de Ejercicio</th>
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
					<td>' . $rows['ejercicio_nombre'] . '</td>
                    <td>' . $rows['ejercicio_descripcion'] . '</td>
	                <td>
	                    <a href="' . APP_URL . 'actualizarEjercicio/' . $rows['ejercicio_id'] . '/" class="button is-success 
                        is-rounded is-small">Actualizar</a>
	                </td>
	                <td>
	                	<form class="FormularioAjax" action=
                        "' . APP_URL . 'app/ajax/ejercicioAjax.php" 
                        method="POST" autocomplete="off">

	                		<input type="hidden" 
                            name="modulo_ejercicio" value="eliminar">
	                		<input type="hidden" name="ejercicio_id" 
                            value="' . $rows['ejercicio_id'] . '">

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

            $tabla .= '<p class="has-text-right">Mostrando ejercicios 
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

    //Controlador para eliminar ejercicios//
    public function eliminarEjercicioControlador()
    {

        $id = $this->limpiarCadena(($_POST['ejercicio_id']));

        if ($id == 1) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No es posible eliminar el ejercicio principal",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        //Verificar ejercicio//
        $datos = $this->ejecutarConsulta("SELECT * FROM ejercicio WHERE 
        ejercicio_id='$id'");

        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El ejercicio no ha sido encontrado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        $eliminarEjercicio = $this->eliminarRegistro("ejercicio", "ejercicio_id", $id);

        if ($eliminarEjercicio->rowCount() == 1) {
            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Ejercicio eliminado",
                "texto" => "El ejercicio " . $datos['ejercicio_nombre'] . "  se eliminó con éxito",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El ejercicio " . $datos['ejercicio_nombre'] . " no pudo ser eliminado",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }

    //Controlador para actualizar ejercicios//
    public function actualizarEjercicioControlador()
    {
        $id = $this->limpiarCadena($_POST['ejercicio_id']);

        //Verificando ejercicio//
        $datos = $this->ejecutarConsulta("SELECT * FROM ejercicio WHERE 
            ejercicio_id='$id'");
        if ($datos->rowCount() <= 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos encontrado el ejercicio en el sistema",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $datos = $datos->fetch();
        }

        //Almacenamiento de datos//
        $ejercicioNombre = $this->limpiarCadena($_POST['ejercicio_nombre']);
        $ejercicioDescripcion = $this->limpiarCadena($_POST['ejercicio_descripcion']);

        //Verificacion de datos//
        if ($ejercicioNombre == "" || $ejercicioDescripcion == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        $ejercicio_datos_ac = [
            [
                "campo_nombre_ejercicio" => "ejercicio_nombre",
                "campo_marcador_ejercicio" => ":Ejercicio",
                "campo_valor_ejercicio" => $ejercicioNombre
            ],
            [
                "campo_nombre_ejercicio" => "ejercicio_descripcion",
                "campo_marcador_ejercicio" => ":Descripcion",
                "campo_valor_ejercicio" => $ejercicioDescripcion
            ]
        ];

        $condicion = [
            "condicion_campo_ejercicio" => "ejercicio_id",
            "condicion_marcador_ejercicio" => ":ID",
            "condicion_valor_ejercicio" => $id
        ];

        if ($this->actualizarDatosEjercicio("ejercicio", $ejercicio_datos_ac, $condicion)) {

            if ($id == $_SESSION['id']) {
                $_SESSION['ejercicio_nombre'] = $ejercicioNombre;
                $_SESSION['ejercicio_descripcion'] = $ejercicioDescripcion;
            }

            $alerta = [
                "tipo" => "recargar",
                "titulo" => "Ejercicio actualizado",
                "texto" => "Los datos del ejercicio " . $datos['ejercicio_nombre'] . " se actualizaron correctamente",
                "icono" => "success"
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido actualizar los datos del ejercicio " . $datos['ejercicio_nombre'] . ", por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }
}
