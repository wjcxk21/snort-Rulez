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
			. "No ha seleccionado ninguna regla.<br>"
			. "</div>";

if ($_POST) {
    //Abrir el archivo y escribir las reglas correspondientes
    //De momento para ir depurando mostraremos echos de las reglas
    #Regla 1: de detección del ping
    if (($_REQUEST['regla1'] || ($_REQUEST['regla2']) || $_REQUEST['regla3'])) {
	if ($_REQUEST['regla1']) {
	    //echo "alert icmp any any -> any any (msg:\"Ping al IDS detection\";sid:13000000;rev:1;)<br>";	    
	    $values="('alert icmp any any -> any any (msg:\"Ping al IDS detection\";sid:13000000;rev:1;)')";
	};
	
	if ($_REQUEST['regla2']) {
	    //echo "alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)<br>";
	    if (empty($values)){
		$values="('alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)')";
	    }else{
		$values=$values.",('alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:13000001;rev:2;)')";
	    }
	};
	
	if ($_REQUEST['regla3']) {
	    //echo "alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)<br>"
	    //.	 "alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)<br>";
	    if (empty($values)){
		$values="('alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)'),"
		      . "('alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)')";
	    }else{
		$values=$values.",('alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:13000002;rev:2;)'),"
			       . "('alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:13000003;rev:2;)')";
	    }
	    
	};
	
	//INSERT con las reglas seleccionadas
	    // Conexión a la base de datos haciendo uso de conexion.php
	    include 'conexion.php';
	    
	    $query="INSERT INTO testRules (rule) VALUES $values";
	    
	    //'echo' para depuracion
	    //echo "-------------------------<br>";
	    //echo $query,"<br>";
	    
	    // prepare query for execution -> Aquí se comprueba la sintaxis
	    //  de la consulta y se reservan los recursos necesarios
	    //  para ejecutarla.
	    if ($stmt = $conexion->prepare($query)){
	    /*    echo "<div>registro preparado.</div>"; */
	    } else {
		die('Imposible preparar el registro.'.$conexion->error); 
	    };
	    
	    // ejecutar la query
	    if($stmt->execute()){
		echo "<div>Registro guardado.</div>";
	    } else {
		die('Imposible guardar el registro:'.$conexion->error);
	    };
	    
	    //Cerramos la conexión
	    $stmt->close();
	
    } else {
	echo $noRule;
    };
    
    //Sacar todas las reglas añadidas a la tabla testRules para añadirlas al .rules
    $sql = "SELECT * FROM `testRules` GROUP BY 2";
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
    <h2>Reglas de prueba</h2>
    <p>Aqui tiene una serie de reglas preparadas para probar rapidamente
    que su detector de intrusos fuinciona correctamente.</p>
    <p>Estas alertas solo sirven a la hora de probar el funcionamiento del
	IDS, una vez termine de realizar las pruebas deben eliminarse.</p>

    <div>
	<form action="index.php?action=rules_test" method='post'>
	    <!-- alert icmp any any -> any any (msg:"Ping al IDS detection";sid:20000013;rev:1;) -->
	    <input type="checkbox" name="regla1" value="Regla aqui">Detección de ping (desde cualquier origen a qualquier destino) <br>

	    <!-- alert tcp any any -> any 22:23 (msg:"ssh detection(tcp)";sid:20000015;rev:2;)-->
	    <input type="checkbox" name="regla2" value="Regla aqui">Detección de SSH o Telnet (desde cualquier origen a qualquier destino)<br>

	    <!-- alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:20000016;rev:2;) -->
	    <!-- alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:20000017;rev:2;) -->
	    <input type="checkbox" name="regla3" value="Regla aqui">Detección de cualquier conexión Web (puertos 80 y 443)<br>

	    <input type="submit" name="save" value="Save" />
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
	<h4>Contenido del archivo 'test.rules' actualmente (<a href="index.php?action=rules_view#test">Ver tabla</a>)</h4>
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
