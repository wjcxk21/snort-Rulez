<!--
此页面将用于查看我们从App-web添加的所有规则。
-->
<html>
    <head>
	<title>查看所有规则</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
	<h3>在这里，您可以同时使用 <strong>Snort Rulez</strong> 查看每个修改过的规则表的内容。</h3>
	<br>
	<div>
	    <a name="custom"></a>
	    <h4>目前添加的规则'<a href="index.php?action=rules_custom">custom.rules</a>':</h4>
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
		    echo "<th>规则</th>";
		    echo "<th>SID</th>";
		    echo "<th>删除</th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$customRule</td>";
			echo "<td>$sidBD</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> 清除 </a></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('无法准备查询。 ' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
	<div>
	    <a name="easy"></a>
	    <h4>目前添加的规则'<a href="index.php?action=rules_easy">easy.rules</a>':</h4>
	    <!-- Leer el archivo 'easy.rules' -->
	    <?php 
	    // Elegir los datos que deseamos recuperar de la tabla
	    $query2 = "SELECT idEasyRule, rule, sid "
		. "FROM easyRules ";

	    // Preparamos y ejecutamos la consulta
	    if ($stmt = $conexion->prepare($query2)) {
		if (!$stmt->execute()) {
		    die('查询执行失败。 ' . $conexion->error);
		} 

		// recogemos los datos
		$stmt->bind_result($idRule,$easyRule,$sidBD);
		$type="easyRules";
		//cabecera de los datos mostrados
		echo "<table class=\"table13 table-bordered table-condensed\">";
		//creating our table heading
		echo "<tr>";
		    echo "<th>ID</th>";
		    echo "<th>规则</th>";
		    echo "<th>SID</th>";
		    echo "<th>删除</th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$easyRule"."sid:".$sidBD.";)</td>";
			echo "<td>$sidBD</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> 清除 </a></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('无法准备查询。 ' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
	<div>
	    <a name="test"></a>
	    <h4>目前添加的规则'<a href="index.php?action=rules_test">test.rules</a>':</h4>
	    <!-- Leer el archivo 'test.rules'-->
	    <?php
	    // Elegir los datos que deseamos recuperar de la tabla
	    $query2 = "SELECT idTestRule, rule "
		. "FROM testRules ";

	    // Preparamos y ejecutamos la consulta
	    if ($stmt = $conexion->prepare($query2)) {
		if (!$stmt->execute()) {
		    die('查询执行失败。 ' . $conexion->error);
		} 

		// recogemos los datos
		$stmt->bind_result($idRule,$testRule);
		$type="testRules";
		//cabecera de los datos mostrados
		echo "<table class=\"table13 table-bordered table-condensed\">";
		//creating our table heading
		echo "<tr>";
		    echo "<th>ID</th>";
		    echo "<th>规则</th>";
		    echo "<th>删除</th>";
		echo "</tr>";
		//recorrido por el resultado de la consulta
		while ($stmt->fetch()) {
		    echo "<tr>";
			echo "<td>$idRule</td>";
			echo "<td>$testRule</td>";
			echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> 清除 </a></td>";
    //		    echo "<td><input type=\"checkbox\" name=\"msgBox\" value=\"msgBox\"></td>";
		    echo "</tr>\n";
		}
		// end table
		echo "</table>";
		$stmt->close();
	    } else {
		die('无法准备查询。' . $conexion->error);
	    }
	    ?>
	    <br>
	</div>
    </body>
</html>
