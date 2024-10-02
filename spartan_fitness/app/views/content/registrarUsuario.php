<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Nuevo usuario</h2>
</div>

<div class="container pb-6 pt-6">

    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/usuarioAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data">

        <input type="hidden" name="modulo_usuario" value="registrar">

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre de Usuario</label>
                    <input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" placeholder="Ingrese su nombre de usuario" maxlength="40" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellido</label>
                    <input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" placeholder="Ingrese su apellido" maxlength="40" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Peso (En Kg)</label>
                    <input class="input" type="number" name="usuario_peso" placeholder="Ingrese su peso" maxlength="3" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Estatura (En Cm)</label>
                    <input class="input" type="number" name="usuario_estatura" placeholder="Ingrese su estatura" maxlength="3" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Edad</label>
                    <input class="input" type="number" name="usuario_edad" placeholder="Ingrese su edad" maxlength="3" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Clave</label>
                    <input class="input" type="password" name="usuario_clave" pattern="[a-zA-Z0-9$@.-]{4,100}" placeholder="Ingrese su clave" maxlength="7" required>
                </div>
            </div>
        </div>

        <p class="has-text-centered">
            <button type="reset" class="button is-link is-light is-rounded">Limpiar</button>
            <button type="submit" class="button is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>