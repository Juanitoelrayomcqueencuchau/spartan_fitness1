<div class="container is-fluid mb-6">
	<h1 class="title">Ejercicios</h1>
	<h2 class="subtitle">Lista de ejercicios</h2>
</div>
<div class="container pb-6 pt-6">
<?php

use app\controllers\exerciseController;

	$insEjercicio = new exerciseController();

	echo $insEjercicio->listarEjercicioControlador($url[1], 15, $url[0], "");
?>

</div>