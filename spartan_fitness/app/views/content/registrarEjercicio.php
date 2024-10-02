<div class="container is-fluid mb-6">
    <h1 class="title">Ejercicios</h1>
    <h2 class="subtitle">Nuevo ejercicio</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/ejercicioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">

        <input type="hidden" name="modulo_ejercicio" value="registrar">

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre de Ejercicio</label>
                    <input class="input" type="text" name="ejercicio_nombre" placeholder="Ingrese su nombre de ejercicio" maxlength="45" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Descripcion</label>
                    <input class="input" type="text" name="ejercicio_descripcion" placeholder="Ingrese su descripcion" maxlength="200" required>
                </div>
            </div>
        </div>
        
        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
            <button type="submit" class="button is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>