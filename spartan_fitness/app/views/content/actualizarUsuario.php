<div class="container is-fluid mb-6">
    <?php

    $id = $insLogin->limpiarCadena($url[1]);

    if ($id == $_SESSION['id']) {
    ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar cuenta</h2>
    <?php } else { ?>
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Actualizar usuario</h2>
    <?php } ?>
</div>
<div class="container pb-6 pt-6">

    <?php
    include "./app/views/inc/btn_back.php";

    $datos = $insLogin->seleccionarDatos(
        "Unico",
        "usuario",
        "usuario_id",
        $id
    );

    if ($datos->rowCount() == 1) {
        $datos = $datos->fetch();
    ?>

        <h2 class="title has-text-centered"><?php echo $datos['usuario_nombre'] . " " . $datos['usuario_apellido']; ?></h2>

        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off">

            <input type="hidden" name="modulo_usuario" value="actualizar">
            <input type="hidden" name="usuario_id" value="<?php echo $datos['usuario_id']; ?>">

            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombre</label>
                        <input class="input" type="text" name="usuario_nombre" value="<?php echo $datos['usuario_nombre']; ?>" placeholder="
                        Ingrese su nombre" maxlength="40" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Apellido</label>
                        <input class="input" type="text" name="usuario_apellido" value="<?php echo $datos['usuario_apellido']; ?>" placeholder="
                        Ingrese su apellido" maxlength="40" required>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Peso</label>
                        <input class="input" type="number" name="usuario_peso" value="<?php echo $datos['usuario_peso']; ?>" placeholder="
                        Ingrese su peso" maxlength="
                        3">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Estatura</label>
                        <input class="input" type="number" name="usuario_estatura" value="<?php echo $datos['usuario_estatura']; ?>" placeholder="
                        Ingrese su estatura" maxlength="
                        3">
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Edad</label>
                        <input class="input" type="number" name="usuario_edad" value="<?php echo $datos['usuario_edad']; ?>" placeholder="
                        Ingrese su edad" maxlength="
                        3">
                    </div>
                </div>
            </div>
            <br><br>
            <p class="has-text-centered">
                SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
            </p>
            <br>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nueva clave</label>
                        <input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Repetir nueva clave</label>
                        <input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100">
                    </div>
                </div>
            </div>
            <br><br><br>
            <p class="has-text-centered">
                Para poder actualizar los datos de este usuario por favor ingrese su NOMBRE y CLAVE con la que ha iniciado sesión
            </p>
            <br>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombre</label>
                        <input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Clave</label>
                        <input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required>
                    </div>
                </div>
            </div>
            <p class="has-text-centered">
                <button type="submit" class="button is-success is-rounded">Actualizar</button>
            </p>
        </form>

    <?php
    } else {
        include "./app/views/inc/error_alert.php";
    }
    ?>
</div>