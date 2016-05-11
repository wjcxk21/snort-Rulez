<!--
Las reglas que se añadan desde aqui se guardarán en 'custom.rules' y serán reglas
más personalizadas para usuarios avanzados.

No estan disponibles todas la opciones de personalización de las reglas porque no es el objetivo
prioritario del proyecto.
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_REQUEST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noData = "No has rellenado el campo ";

//Sacar el SID mas alto de la BD y sumarle 1
    //Conex con la BD
    include 'conexion.php';

    $query = "SELECT max( `sid` )"
	    .   "FROM `rules;";

    if ($stmt = $conexion->prepare($query)) {

	// ejecutamos la consulta
	$stmt->execute();
	$stmt->bind_result($maxSid);

	// recuperamos la variable
	$stmt->fetch();
    }

    //Si no hay añadida ninguna regla en rules:
    if (empty($maxSid)) {
	$maxSid="40000000";
    };
    //Cerramos la conexión
    $stmt->close(); 
    $maxSid=$maxSid+1;

if ($_POST) {
    //Revisar campos del formulario
    $arr_req = array('ruleType','protocol','originIP','originPort','direction','destinIP','destinPort'); //Hacer el array bidimensional para sacar el error en español
    $arr_opc = array('SIDBox','msgBox','referenceBox', 'classtypeBox','priorityBox','revBox');

    //Revisar que no hay ningun campo obligatorio vacio
    foreach ($arr_req as $r) {
	if (empty($_REQUEST[$r])) {
	    echo "<div class="."\"alert alert-warning  alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "No ha rellenado el campo '".$r."' , revise los datos.<br>"
			. "</div>";
	};
    };

    //Revisar que si se han marcado los campos opcionales se han rellenado los input
    foreach ($arr_opc as $s) {
	if ($_REQUEST[$s] == $s) {
	    $noBox= substr($s,0,-3);
	    if (empty($_REQUEST[$noBox])) {
		echo "<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "Ha marcado el campo opcional '".$s."' , pero no ha rellenado los datos.<br>"
			. "</div>";
	    };
	};
    };

    //Mostrar la regla y las opciones con un echo
    foreach ($arr_req as $ec) {
	if (!empty($_REQUEST[$ec])) {
	    $rule=$rule.$_REQUEST[$ec]." ";
	}else{
	    goto noRule;
	};
    };

    foreach ($arr_opc as $op) {
	if ($_REQUEST[$op] == $op) {
	    $noBox= substr($op,0,-3);
	    if (!empty($_REQUEST[$noBox])) {
		$options=$options.$noBox.":".$_REQUEST[$noBox]."; ";
		$queryOps=$queryOps.", `".$noBox."`";
		$queryInts=$queryInts.", ?";
		$queryS=$queryS."s";

		if (empty($queryPost)){
			$queryPost=" ".$_POST[$noBox]."";
		    }else{
			$queryPost=$queryPost.", ".$_POST[$noBox]."";
		    };
		
		
	    }else{
		//goto noOps;
	    };
	};
    };

    //Echo para mostrar la regla [Modo depuración]
    $ruleZ = $rule."(".$options.")"."\r\n";
    echo $ruleZ."<br>";
    //echo $queryOps."<br>";
    //echo $queryInts."<br>";
    //echo $queryPost."<br>";
    //echo $queryS."<br>";

    //Guardar la regla en la BD
	// INSERT query
	$query2 = "INSERT INTO rules (`ruleType`,`protocol`,`originIP`,`originPort`,`direction`,`destinIP`,`destinPort`".$queryOps.") "
		. "VALUES (?, ?, ?, ?, ?, ?, ?".$queryInts.")";
    
	//$query2="select * from rules";

	echo $query2,"<br>";

	// prepare query for execution -> Aquí se comprueba la sintaxis
	//  de la consulta y se reservan los recursos necesarios
	//  para ejecutarla.
	if ($stmt = $conexion->prepare($query2)){
	/*    echo "<div>registro preparado.</div>"; */
	} else {
	    die('Imposible preparar el registro.'.$stmt->error);
	}

	// asociar los parámetros
	$bindS="sssssss".$queryS; // <-- Esto funciona
	echo "-------<br>".$bindS."-----<br>";
	echo "-------<br>querypost: ".$queryPost." -----<br>"; // <-- El problema debe estar aqui
	
	echo "-------<br>SIDpost: ".$_POST['SID']." -----<br>";
	echo "==================<br>";
	echo $_POST['ruleType'].$_POST['protocol'].$_POST['originIP'].$_POST['originPort'].$_POST['direction'].$_POST['destinIP'].$_POST['originPort'].$queryPost;
	echo "==================<br>";
	
	$stmt->bind_param($bindS,$_POST['ruleType'],$_POST['protocol'],
	$_POST['originIP'],$_POST['originPort'],$_POST['direction'],
	$_POST['destinIP'],$_POST['originPort'],$queryPost);

	// ejecutar la query
	if($stmt->execute()){
	    echo "<div>Registro guardado.</div>";
	} else {
	    die('Imposible guardar el registro:'.$conexion->error);
	};
    //Aqui acaba la escritura en la BD

    /*/Escribir el archivo custom.rules     COMENTADO HASTA QUE SE SOLUCIONE EL ACCESO A LA BD
    $fp = fopen("custom.rules", "a");
    fputs($fp, $ruleZ);
    fclose($fp);*/

    //Salida cuando faltan campos
    noRule:;


}
?>

<div>
    <h2>Reglas personalizadas</h2>
    <p>Aqui puedes añadir las reglas personalizadas que quieras con la ayuda de este formulario:</p>

    <div>
	<form action="index.php?action=rules_custom" method='post'>
	    <table class="table13 table-bordered table-condensed ">
		<tr>
		    <td>Tipo</td>		<!-- Añadir que sobre cada campo con un hover te explique brevemente que es -->
		    <td>Protocolo</td>
		    <td>IP Origen</td>
		    <td>Puerto Origen</td>
		    <td>Dirección</td>
		    <td>IP Destino</td>
		    <td>Puerto Destino</td>
		</tr>
		<tr>
		    <td>
		      <select name="ruleType">
			<option value="alert">alert</option>
			<option value="log">log</option>
			<option value="drop">drop</option>
			<option value="pass">pass</option>
		      </select>
		    </td>
		    <td>
		      <select name="protocol">
			<option value="UDP">UDP</option>
			<option value="TCP">TCP</option>
			<option value="ICMP">ICMP</option>
			<option value="IP">IP</option>
		      </select>
		    </td>
		    <td >
		      <input type="text" name="originIP" maxlength="18" size="15" value="">
		    </td>
		    <td >
		      <input type="text" name="originPort" maxlength="18" size="15" value="">
		    </td>
		    <td>
		      <select name="direction">
			<option value="->">-></option>
			<option value="<-"><-</option>
			<option value="<>"><></option>
		      </select>
		    </td>
		    <td >
		      <input type="text" name="destinIP" maxlength="18" size="15" value="">
		    </td>
		    <td >
		      <input type="text" name="destinPort" maxlength="18" size="15" value="">
		    </td>
		</tr>
		<tr>
		    <td><input type="checkbox" style="display:none" name="SIDBox" value="SIDBox" checked=""></td>
		    <td colspan="6">SID: <input type="text" name="SID" value=<?php echo "\"".$maxSid."\"" ?> readonly="readonly"><!--Con una consulta sacar el valor max de SID y sumarle 1--></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="msgBox" value="msgBox"></td>
		    <td colspan="6">Añadir mensaje: <input type="text" name="msg" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="referenceBox" value="referenceBox"></td>
		    <td colspan="6">Añadir referencia: <input type="text" name="reference" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="classtypeBox" value="classtypeBox"></td>
		    <td colspan="6">Añadir classtype: <input type="text" name="classtype" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="priorityBox" value="priorityBox"></td>
		    <td colspan="6">Añadir prioridad: <input type="text" name="priority" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="revBox" value="revBox"></td>
		    <td colspan="6">Añadir nº de rev: <input type="text" name="rev" maxlength="18" value=""></td>
		</tr>
	    </table>
	    <input type="submit" name="save" value="Añadir" />
	</form>
    </div>
    <br>
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
</div>
