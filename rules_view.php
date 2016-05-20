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
	<h3>Aqui puedes ver el contenido de cada tabla de reglas modificada con <strong>Snort Rulez</strong> a la vez.</h3>
	<br>
	<div>
	    <a name="custom"></a>
	    <h4>Reglas añadidas actualmente a '<a href="index.php?action=rules_custom">custom.rules</a>':</h4>
	    <!-- Leer el archivo 'custom.rules'-->
	    <?php 
	    // Elegir los datos que deseamos recuperar de la tabla
	    $query2 = "SELECT idCustomRule, rule, sid "
		. "FROM customRules ";

	    // Preparamos y ejecutamos la consulta
	    if ($stmt = $conexion->prepare($query2)) {
		if (!$stmt->execute()) {
		    die('Error de ejecución de la consulta. ' . $conexion->error);
		} 

		// recogemos los datos
		$stmt->bind_result($idRule,$customRule,$sidBD);
		$type="customRules";
		//cabecera de los datos mostrados
		echo "<table class=\"table13 table-bordered table-condensed\">";
		//creating our table heading
		echo "<tr>";
		    echo "<th>ID</th>";
		    echo "<th>Regla</th>";
		    echo "<th>SID</th>";
		    echo "<th>Borrar</th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$customRule</td>";
			echo "<td>$sidBD</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> Elimina </a></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('Imposible preparar la consulta. ' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
	<div>
	    <a name="easy"></a>
	    <h4>Reglas añadidas actualmente a '<a href="index.php?action=rules_easy">easy.rules</a>':</h4>
	    <!-- Leer el archivo 'easy.rules' -->
	    <?php 
	    // Elegir los datos que deseamos recuperar de la tabla
	    $query2 = "SELECT idEasyRule, rule, sid "
		. "FROM easyRules ";

	    // Preparamos y ejecutamos la consulta
	    if ($stmt = $conexion->prepare($query2)) {
		if (!$stmt->execute()) {
		    die('Error de ejecución de la consulta. ' . $conexion->error);
		} 

		// recogemos los datos
		$stmt->bind_result($idRule,$easyRule,$sidBD);
		$type="easyRules";
		//cabecera de los datos mostrados
		echo "<table class=\"table13 table-bordered table-condensed\">";
		//creating our table heading
		echo "<tr>";
		    echo "<th>ID</th>";
		    echo "<th>Regla</th>";
		    echo "<th>SID</th>";
		    echo "<th>Borrar</th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$easyRule"."sid:".$sidBD.";)</td>";
			echo "<td>$sidBD</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> Elimina </a></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('Imposible preparar la consulta: ' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
	<div>
	    <a name="test"></a>
	    <h4>Reglas añadidas actualmente a '<a href="index.php?action=rules_test">test.rules</a>':</h4>
	    <!-- Leer el archivo 'test.rules'-->
	    <?php
	    // Elegir los datos que deseamos recuperar de la tabla
	    $query2 = "SELECT idTestRule, rule "
		. "FROM testRules ";

	    // Preparamos y ejecutamos la consulta
	    if ($stmt = $conexion->prepare($query2)) {
		if (!$stmt->execute()) {
		    die('Error de ejecución de la consulta. ' . $conexion->error);
		} 

		// recogemos los datos
		$stmt->bind_result($idRule,$testRule);
		$type="testRules";
		//cabecera de los datos mostrados
		echo "<table class=\"table13 table-bordered table-condensed\">";
		//creating our table heading
		echo "<tr>";
		    echo "<th>ID</th>";
		    echo "<th>Regla</th>";
		    echo "<th> </th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$testRule</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> Elimina </a></td>";
    //		    echo "<td><input type=\"checkbox\" name=\"msgBox\" value=\"msgBox\"></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('Imposible preparar la consulta. ' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
    </body>
</html>
