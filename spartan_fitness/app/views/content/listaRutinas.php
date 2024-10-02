<div class="container is-fluid mb-6">
	<h1 class="title">Rutinas</h1>
	<h2 class="subtitle">Lista de rutinas</h2>
</div>
<div class="container pb-6 pt-6">
<?php

use app\controllers\routineController;

	$insRutina = new routineController();

	echo $insRutina->listarRutinaControlador($url[1], 15, $url[0], "");
?>

</div>