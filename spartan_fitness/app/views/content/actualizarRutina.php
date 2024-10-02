<div class="container is-fluid mb-6">
    <?php

    $id = $insLogin->limpiarCadena($url[1]);

    if ($id == $_SESSION['id']) {
    ?>
        <h1 class="title">Mi cuenta</h1>
        <h2 class="subtitle">Actualizar cuenta</h2>
    <?php } else { ?>
        <h1 class="title">Rutinas</h1>
        <h2 class="subtitle">Actualizar rutina</h2>
    <?php } ?>
</div>
<div class="container pb-6 pt-6">

    <?php
    include "./app/views/inc/btn_back.php";

    $datos = $insLogin->seleccionarDatos(
        "Unico",
        "rutina",
        "rutina_id",
        $id
    );

    if ($datos->rowCount() == 1) {
        $datos = $datos->fetch();
    ?>

        <h2 class="title has-text-centered"><?php echo $datos['rutina_nombre'] . " " . $datos['rutina_descripcion']; ?></h2>

        <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/rutinaAjax.php" method="POST" autocomplete="off">

            <input type="hidden" name="modulo_rutina" value="actualizar">
            <input type="hidden" name="rutina_id" value="<?php echo $datos['rutina_id']; ?>">

            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Nombre de Rutina</label>
                        <input class="input" type="text" name="rutina_nombre" value="<?php echo $datos['rutina_nombre']; ?>" placeholder="
                        Ingrese su nombre de rutina" maxlength="40" required>
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Descripcion</label>
                        <input class="input" type="text" name="rutina_descripcion" value="<?php echo $datos['rutina_descripcion']; ?>" placeholder="
                        Ingrese su descripcion" maxlength="200" required>
                    </div>
                </div>
            </div>
            <br><br>
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