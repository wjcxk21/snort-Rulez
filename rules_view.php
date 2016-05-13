<!--
Esta página se usará para ver desde la App-web todas las raglas que hemos añadido.
-->
<html>
    <head>
	<title>Ver todas las reglas</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
	<h2>Aqui puedes ver el contenido de los archivos modificados con <strong>Snort Rulez</strong> a la vez.</h2>
	<div>
	    <h4>Reglas añadidas actualmente a 'custom.rules'</h4>
	    <!-- Leer el archivo 'custom.rules' -->
	    <textarea cols="100" rows="25" wrap="hard" readonly="yes">
		Aqui irá el contentido de custom rules:
		<?php
		    $fp = fopen("custom.rules", "r");

		    while(!feof($fp)) {
			$linea = fgets($fp);
			echo $linea ;
		    }

		    fclose($fp);
		?>
	    </textarea>
	</div>
	<div>
	    <h4>Reglas añadidas actualmente a 'easy.rules'</h4>
	    <!-- Leer el archivo 'easy.rules' -->
	    <textarea cols="100" rows="25" wrap="hard" readonly="yes">
		Aqui irá el contentido de easy rules:
		<?php
		    $fp = fopen("easy.rules", "r");

		    while(!feof($fp)) {
			$linea = fgets($fp);
			echo $linea ;
		    }

		    fclose($fp);
		?>
	    </textarea>
	</div>
	<div>
	    <h4>Reglas añadidas actualmente a 'test.rules'</h4>
	    <!-- Leer el archivo 'easy.rules' -->
	    <textarea cols="100" rows="25" wrap="hard" readonly="yes">
		Aqui irá el contentido de test rules:
		<?php
		    $fp = fopen("test.rules", "r");

		    while(!feof($fp)) {
			$linea = fgets($fp);
			echo $linea ;
		    }

		    fclose($fp);
		?>
	    </textarea>
	</div>
    </body>
</html>
