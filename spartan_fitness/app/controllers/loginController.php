<?php

namespace app\controllers;

use app\models\mainModel;

class loginController extends mainModel
{

    //Controlador de inicio de sesion//
    public function iniciarSesionControlador()
    {

        //Almacenamiento de datos//
        $usuario = $this->limpiarCadena($_POST['login_usuario']);
        $clave = $this->limpiarCadena($_POST['login_clave']);

        //Verificacion de datos//
        if ($usuario == "" || $clave == "") {
            echo "
                <script>
	                Swal.fire({
		                icon: 'error',
		                title: 'Ocurrió un error inesperado',
		                text: 'No has llenado todos los campos',
		                confirmButtonText: 'Aceptar'
	                });
                </script>
            ";

        } else {

            //Integridad de datos//
            if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $usuario)) {
                echo "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Ocurrió un error inesperado',
                            text: 'El USUARIO no coincide con el formato solicitado',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>
                ";
            } else {
                if ($this->verificarDatos("[a-zA-Z0-9$@.-]{4,100}", $clave)) {
                    echo "
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Ocurrió un error inesperado',
                                text: 'La CLAVE no coincide con el formato solicitado',
                                confirmButtonText: 'Aceptar'
                            });
                        </script>
                    ";
                } else {

                    //Verificacion de usuarios//
                    $check_usuario = $this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario_nombre = '$usuario'");

                    if ($check_usuario->rowCount() == 1) {

                        $check_usuario = $check_usuario->fetch();

                        if (
                            $check_usuario['usuario_nombre']
                            == $usuario &&
                            $check_usuario['usuario_clave'] == $clave
                        ) {

                            $_SESSION['id']=$check_usuario
                            ['usuario_id'];
                            $_SESSION['nombre']=$check_usuario
                            ['usuario_nombre'];
                            $_SESSION['apellido']=$check_usuario
                            ['usuario_apellido'];

                            if (headers_sent()) {
                                echo "<script> window.location.href = '" . APP_URL . "inicio/';
                                        </script>";
                            } else {
                                header("Location: " . APP_URL . "inicio/");
                            }
                        } else {
                            echo "
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Ocurrió un error inesperado',
                                        text: 'Usuario o clave incorrectos',
                                        confirmButtonText: 'Aceptar'
                                    });
                                </script>
                            ";
                        }
                    } else {
                        echo "
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ocurrió un error inesperado',
                                    text: 'Usuario o clave incorrectos',
                                    confirmButtonText: 'Aceptar'
                                });
                            </script>
                        ";
                    }
                }
            }
        }
    }

    //Controlador de cierre de sesion//
    public function cerrarSesionControlador(){

        session_destroy();

        if (headers_sent()) {
            echo "<script> window.location.href = '" . APP_URL . "login/';
                    </script>";
        } else {
            header("Location: " . APP_URL . "login/");
        }
    }
}
