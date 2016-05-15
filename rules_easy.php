<!--
Las reglas que se añadan desde aqui se guardarán en 'easy.rules' y tendrán como 
objetivo añadir de forma sencilla reglas muy básicas/muy usadas para Snort.
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_POST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noRule="<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "No ha seleccionado ninguna regla.<br>"
			. "</div>";
$noAddr="No ha escrito la direccion de la red o del equipo en la ";

    if($_POST){
	//Abrir el archivo y escribir las reglas correspondientes
	//De momento para ir depurando mostraremos echos de las reglas
	if (($_POST['regla1']) || ($_POST['regla2']) || ($_POST['regla3']) || ($_POST['regla4']) || ($_POST['regla5'])) {
	    
	    // Conexión a la base de datos haciendo uso de conexion.php
	    include 'conexion.php';
	    
	    //Sacar el SID mas alto de la BD y sumarle 1/2/3
	    $query = "SELECT max( `sid` )"
	    .   "FROM `easyRules;";

	    if ($stmt = $conexion->prepare($query)) {
		// ejecutamos la consulta
		$stmt->execute();
		$stmt->bind_result($maxSid);

		// recuperamos la variable
		$stmt->fetch();
	    }
	    
	    //Si no hay añadida ninguna regla en easyRules:
	    if (empty($maxSid)) {
		$maxSid="20000100";
	    };
	    //Cerramos la conexión
	    $stmt->close(); 
	    //Variables para los SID
	    $maxSplus1=$maxSid+1; //Para la regla1
	    $maxSplus2=$maxSid+2; //Para la regla2
	    $maxSplus3=$maxSid+3; //Para la regla3
	    
	    #Regla 1: de detección del ping
	    if($_POST['regla1'] == 'regla1'){
		if (empty($_POST['addr1']) || ($_POST['addr1'] == 'IP o Red')) {
		    echo "<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. $noAddr."primera regla.<br>"
			. "</div>";
		}else {
		    $rule1="'alert icmp any any -> ".$_POST['addr1']." any (msg:\"Detectado PING\"; classtype:misc-activity; rev:1; '";
		    $sid1="'".$maxSplus1."'";
		    //'echo' para depuracion
		    //echo "alert icmp any any -> ".$_POST['addr1']." any (msg:\"Detectado PING\"; classtype:misc-activity; rev:1; sid:".$maxSplus1.";)<br>";
		    //echo $rule1."sid:".$sid1.";)";
		    //echo "<p>----------------------------------</p>";
		    
		    //VALUE de la regla 1
		    $values="($rule1,$sid1)";
		};
	    };

	    #Regla 2: de detección de SSH
	    if($_POST['regla2'] == 'regla2'){
		if (empty($_POST['addr2']) || ($_POST['addr2'] == 'IP o Red')) {
		    echo "<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. $noAddr."segunda regla.<br>"
			. "</div>";
		}else {
		    
		    $rule2="'alert tcp any any -> ".$_POST['addr2']." 22 (msg:\"Detectado SSH\"; classtype:misc-activity; rev:1; '";
		    $sid2="'".$maxSplus2."'";
		    //'echo' para depuracion
		    //echo "alert tcp any any -> ".$_POST['addr2']." 22 (msg:\"Detectado SSH\"; classtype:misc-activity; rev:1; sid:".$maxSplus2.";)<br>";
		    //echo "<p>----------------------------------</p>";
		    
		    //VALUE de la regla 2
		    if (empty($values)){
			$values="($rule2,$sid2)";
		    }else{
			$values=$values.",($rule2,$sid2)";
		    };
		};
	    };

	    #Regla 3: Escaneo de puertos con NMAP
	    if($_POST['regla3'] == 'regla3'){
		if (empty($_POST['addr3']) || ($_POST['addr3'] == 'IP o Red')) {
		    echo "<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. $noAddr."tercera regla.<br>"
			. "</div>";
		}else {
		    
		    $rule3="'alert icmp any any -> ".$_POST['addr3']." any (msg:\"Detectado escaneo NMAP\"; classtype:misc-activity; rev:1; '";
		    $sid3="'".$maxSplus3."'";
		    //'echo' para depuracion
		    //echo "alert icmp any any -> ".$_POST['addr3']." any (msg:\"Detectado escaneo NMAP\"; classtype:misc-activity; rev:1; sid:".$maxSplus3.";)";
		    //echo "<p>----------------------------------</p>";
		    
		    //VALUE de la regla 3
		    if (empty($values)){
			$values="($rule3,$sid3)";
		    }else{
			$values=$values.",($rule3,$sid3)";
		    };
		};
	    };
		
	    #Regla 4: Descarga de .torrent
	    if(isset($_POST['regla4']) && $_POST['regla4'] == 'regla4') {
		//'echo' para depuracion
		//echo 'alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "Detectada descarga torrent"; content:"HTTP/"; content:".torrent"; flow:established,to_server; classtype:policy-violation; rev:1; sid:20000001;)';
		//echo "<p>----------------------------------</p>";
		
		$rule4='\'alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "Detectada descarga torrent"; content:"HTTP/"; content:".torrent"; flow:established,to_server; classtype:policy-violation; rev:1; \'';
		$sid4="'20000001'";
		
		//VALUE de la regla 4
		    if (empty($values)){
			$values="($rule4,$sid4)";
		    }else{
			$values=$values.",($rule4,$sid4)";
		    };
	    };

	    #Regla 5: descarga de .mp3
	    if(isset($_POST['regla5']) && $_POST['regla5'] == 'regla5') {
		//'echo' para depuracion
		//echo 'alert tcp $EXTERNAL_NET any -> $HOME_NET any (msg:"Detectada descarga MP3";flags: AP; content: ".mp3"; classtype:policy-violation; rev:1; sid:20000002;)';
		//echo "<p>----------------------------------</p>";
		
		$rule5='\'alert tcp $EXTERNAL_NET any -> $HOME_NET any (msg:"Detectada descarga MP3";flags: AP; content: ".mp3"; classtype:policy-violation; rev:1; \'';
		$sid5="'20000002'";
		
		//VALUE de la regla 5
		    if (empty($values)){
			$values="($rule5,$sid5)";
		    }else{
			$values=$values.",($rule5,$sid5)";
		    };
	    };
	    
	    //Introducir en la BD las reglas seleccionadas
	    //  INSERT query
		$query = "REPLACE INTO `easyRules` (`rule`,`sid`) "
			. "VALUES $values;";
	
	    //'echo' para depuracion
	    //echo $query,"<br>";


	    // prepare query for execution -> Aquí se comprueba la sintaxis
	    //  de la consulta y se reservan los recursos necesarios
	    //  para ejecutarla.
		if ($stmt = $conexion->prepare($query)){
		/*    echo "<div>registro preparado.</div>"; */
		} else {
		    die('Imposible preparar el registro.'.$conexion->error); 
		};

	    // asociar los parámetros
	    //$stmt->bind_param('ss',$rule1,$sid1);

	    // ejecutar la query
		if($stmt->execute()){
		    echo "<div>Registro guardado.</div>";
		} else {
		    die('Imposible guardar el registro:'.$conexion->error);
		};
	    
	}else{
	    echo $noRule;
	};
	
	//Sacar todas las reglas añadidas a la tabla testRules para añadirlas al .rules
	$sql = "SELECT * FROM `easyRules`";
	$result = $conexion->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		$inText=$inText.$row['rule']."sid:".$row['sid'].";)"."\r\n ";
		//'echo' para depuracion
		//echo $row['rule']."\r\n"."<br>";
	    };
	};    
	$conexion->close();

	//Escribir la reglas en easy.rules
	$fp = fopen("easy.rules", "w");
	fputs($fp, $inText);
	fclose($fp);
    };
?>
<div>
    <h2>Reglas preparadas</h2>
    <p>Aqui tiene una serie de reglas preparadas para añadir rapidamente
    a su detector de intrusos.</p>

    <div>
	<form action="index.php?action=rules_easy" method='post'>
	    <!-- XX -->
	    <input type="checkbox" name="regla1" value="regla1">Detección de ping en <input type="text" name="addr1" value="IP o Red"> <br>

	    <!-- XX -->
	    <input type="checkbox" name="regla2" value="regla2">Detección de SSH en <input type="text" name="addr2" value="IP o Red"> <br>

	    <!-- XX -->
	    <input type="checkbox" name="regla3" value="regla3">Escaneo de puertos con NMAP <input type="text" name="addr3" value="IP o Red"> <br>

	    <!-- probar esta: alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "P2P .torrent metafile"; content:"HTTP/"; content:".torrent"; flow:established,to_server;classtype:policy-violation;) -->
	    <input type="checkbox" name="regla4" value="regla4">Detección descarga de archivos .torrent <br>

	    <!-- Probar esta: alert tcp $EXTERNAL_NET any -> $HOME_NET any //(msg: "Cuidado, están descargando MP3";flags: AP; content: ".mp3";) -->
	    <input type="checkbox" name="regla5" value="regla5">Detección descarga de archivos .mp3 <br>
	    <input type="submit" name="save" value="Save" />
	</form> 
    </div>
    <br>
    <div>
	<h4>Reglas añadidas actualmente a 'easy.rules':</h4>
	<!-- Leer el archivo 'easy.rules'-->
	<?php
	// Elegir los datos que deseamos recuperar de la tabla
	$query = "SELECT idEasyRule, rule, sid "
	    . "FROM easyRules ";

	// Preparamos y ejecutamos la consulta
	if ($stmt = $conexion->prepare($query)) {
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
		    echo "<td>$easyRule</td>";
		    echo "<td>$sidBD</td>";
		    echo "<td><a href='javascript:borra_cliente(\"$idRule\",\"$type\")'> Elimina </a></td>";
		echo "</tr>\n";
	    }
	    // end table
	    echo "</table>";
	    echo "<input type=\"submit\" name=\"save\" value=\"Save\" /><br>";
	    $stmt->close();
	} else {
	    die('Imposible preparar la consulta. ' . $conexion->error);
	}
	?>
	<br>
    </div>
</div>
