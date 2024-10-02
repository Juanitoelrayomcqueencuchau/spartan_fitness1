<nav class="navbar">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo APP_URL; ?>inicio/">
            <img src="#" alt="Spartan Fitness" width="112" height="28">
        </a>
        <div class="navbar-burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">

        <div class="navbar-start">
            <a class="navbar-item" href="<?php echo APP_URL; ?>inicio/">
                Inicio
            </a>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="">
                    Usuarios
                </a>
                <div class="navbar-dropdown is-boxed">

                    <a class="navbar-item" href="<?php echo APP_URL; ?>registrarUsuario/">
                        Registrar Usuario
                    </a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>listaUsuarios/">
                        Lista de Usuarios
                    </a>

                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="">
                    Rutinas
                </a>
                <div class="navbar-dropdown is-boxed">

                    <a class="navbar-item" href="<?php echo APP_URL; ?>registrarRutina/">
                        Registrar Rutina
                    </a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>listaRutinas/">
                        Lista de Rutinas
                    </a>

                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" href="">
                    Ejercicios
                </a>
                <div class="navbar-dropdown is-boxed">

                    <a class="navbar-item" href="<?php echo APP_URL; ?>registrarEjercicio/">
                        Registrar Ejercicio
                    </a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>listaEjercicios/">
                        Lista de Ejercicios
                    </a>

                </div>
            </div>
        </div>

        <div class="navbar-end">
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    ** <?php echo $_SESSION['nombre']?> **
                </a>
                <div class="navbar-dropdown is-boxed">

                    <a class="navbar-item" href="<?php echo APP_URL; ?>cerrarSesion/" id="btn_exit" >
                        Cerrar Sesion
                    </a>

                </div>
            </div>
        </div>

    </div>
</nav>