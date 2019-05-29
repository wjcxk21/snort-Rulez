<!--
Las reglas que se añadan desde aqui se guardarán en 'test.rules' y tendrán como 
objetivo probar el correcto funcionamiento de Snort. 
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_REQUEST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noRule="<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "您尚未选择任何规则。<br>"
			. "</div>";

if ($_POST) {
    //Abrir el archivo y escribir las reglas correspondientes
    //De momento para ir depurando mostraremos echos de las reglas
    #Regla 1: de detección del ping
    if (($_REQUEST['regla1'] || ($_REQUEST['regla2']) || $_REQUEST['regla3'])) {
	if ($_REQUEST['regla1']) {
	    //echo "alert icmp any any -> any any (msg:\"Ping al IDS detection\";sid:13000000;rev:1;)<br>";	    
	    $values="(1,'alert icmp any any -> any any (msg:\"Ping al IDS detection\";sid:13000000;rev:1;)')";
	};
	
	if ($_REQUEST['regla2']) {
	    //echo "alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)<br>";
	    if (empty($values)){
		$values="(2,'alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)')";
	    }else{
		$values=$values.",(2,'alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)')";
	    }
	};
	
	if ($_REQUEST['regla3']) {
	    //echo "alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)<br>"
	    //.	 "alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)<br>";
	    if (empty($values)){
		$values="(3,'alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)'),"
		      . "(4,'alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)')";
	    }else{
		$values=$values.",(3,'alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)'),"
			       . "(4,'alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)')";
	    }
	    
	};
	
	//INSERT con las reglas seleccionadas
	    // Conexión a la base de datos haciendo uso de conexion.php
	    include 'conexion.php';
	    
	    $query="REPLACE INTO testRules (idTestRule,rule) VALUES $values";
	    
	    //'echo' para depuracion
	    //echo "-------------------------<br>";
	    //echo $query,"<br>";
	    
	    // prepare query for execution -> Aquí se comprueba la sintaxis
	    //  de la consulta y se reservan los recursos necesarios
	    //  para ejecutarla.
	    if ($stmt = $conexion->prepare($query)){
	    /*    echo "<div>registro preparado.</div>"; */
	    } else {
		die('无法准备注册.'.$conexion->error); 
	    };
	    
	    // ejecutar la query
	    if($stmt->execute()){
		echo "<div>保存记录。</div>";
	    } else {
		die('无法保存记录:'.$conexion->error);
	    };
	    
	    //Cerramos la conexión
	    $stmt->close();
	
    } else {
	echo $noRule;
    };
    
    //Sacar todas las reglas añadidas a la tabla testRules para añadirlas al .rules
    $sql = "SELECT * FROM `testRules`";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
	    $inText=$inText.$row['rule']."\n ";
	    //'echo' para depuracion
	    //echo $row['rule']."\n"."<br>";
	};
    };    
    $conexion->close();
    
    //Escribir la reglas en test.rules
    $fp = fopen("test.rules", "w");
    fputs($fp, $inText);
    fclose($fp);

}
?>
<div>
    <h2>测试规则</h2>
    <p>这是一套准备快速证明您的入侵检测器正常工作的规则。</p>
    <p>这些警报仅用于测试IDS的操作，一旦完成测试必须删除。</p>

    <div>
	<form action="index.php?action=rules_test" method='post'>
	    <!-- alert icmp any any -> any any (msg:"Ping al IDS detection";sid:20000013;rev:1;) -->
	    <input type="checkbox" name="regla1" value="Regla aqui">Ping检测（从任何来源到任何目的地）<br>

	    <!-- alert tcp any any -> any 22:23 (msg:"ssh detection(tcp)";sid:20000015;rev:2;)-->
	    <input type="checkbox" name="regla2" value="Regla aqui">检测SSH或Telnet（从任何源到任何目的地）<br>

	    <!-- alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:20000016;rev:2;) -->
	    <!-- alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:20000017;rev:2;) -->
	    <input type="checkbox" name="regla3" value="Regla aqui">检测任何Web连接（端口80和443）<br>

	    <input type="submit" name="save" value="发送" />
	</form> 
    </div>
    <br>
    <!--div> Movido a rules_view.php
	<h4>Reglas añadidas actualmente a 'test.rules':</h4>
	<!-- Leer el archivo 'test.rules'
	<?php /*
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
	}*/
	?>
	<br>
    </div-->
    <div>
	<h4>目前'test.rules'文件的内容(<a href="index.php?action=rules_view#test">查看表格</a>)</h4>
	<!-- Leer el archivo 'test.rules' -->
	<textarea cols="100" rows="25" wrap="hard" readonly="yes">
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
</div>
