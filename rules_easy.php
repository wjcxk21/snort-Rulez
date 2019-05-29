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
			. "您尚未选择任何规则。<br>"
			. "</div>";
$noAddr="您还没有写过网络或计算机地址";

    if($_POST){
	//打开文件并编写相应的规则
//在进行调试的那一刻，我们将展示规则的回声
	if (($_POST['regla1']) || ($_POST['regla2']) || ($_POST['regla3']) || ($_POST['regla4']) || ($_POST['regla5'])) {
	    
	    // 使用conexion.php 链接数据库
	    include 'conexion.php';
	    
	    //Sacar el SID mas alto de la BD y sumarle 1/2/3
	    //从DB中删除最高SID并添加1/2/3
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
			. $noAddr."第一条规则。<br>"
			. "</div>";
		}else {
		    $sid1=$maxSplus1;
		    $rule1="'alert icmp any any -> ".$_POST['addr1']." any (msg:\"Detect PING\"; classtype:misc-activity; rev:1; sid:".$sid1.';)\'';
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
			. $noAddr."第二条规则。<br>"
			. "</div>";
		}else {
		    $sid2=$maxSplus2;		    
		    $rule2="'alert tcp any any -> ".$_POST['addr2']." 22 (msg:\"Detect SSH\"; classtype:misc-activity; rev:1; sid:".$sid2.';)\'';
		    $sid2="'".$maxSplus2."'";
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
		    $sid3=$maxSplus3;		    
		    $rule3="'alert icmp any any -> ".$_POST['addr3']." any (msg:\"Detectado escaneo NMAP\"; classtype:misc-activity; rev:1; sid:".$sid3.';)\'';
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
		$sid4="'20000001'";
		$rule4='\'alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "Detectada descarga torrent"; content:"HTTP/"; content:".torrent"; flow:established,to_server; classtype:policy-violation; rev:1; sid:20000001;)\'';
					
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
		$sid5="'20000002'";
		$rule5='\'alert tcp $EXTERNAL_NET any -> $HOME_NET any (msg:"Detectada descarga MP3";flags: AP; content: ".mp3"; classtype:policy-violation; rev:1; sid:20000002;)\'';

		
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
		    echo "<div>保存记录。</div>";
		} else {
		    die('无法保存记录：'.$conexion->error);
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
		$inText=$inText.$row['rule']."\n ";
		//'echo' para depuracion
		//echo $row['rule']."\n"."<br>";
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
    <h2>简易规则</h2>
    <p>在这里，您可以使用一组规则快速添加到入侵检测器中。</p>

    <div>
	<form action="index.php?action=rules_easy" method='post'>
	    <!-- XX -->
	    <input type="checkbox" name="regla1" value="regla1">检测 ping<input type="text" name="addr1" value="IP o Red"> <br>

	    <!-- XX -->
	    <input type="checkbox" name="regla2" value="regla2">检测 SSH<input type="text" name="addr2" value="IP o Red"> <br>

	    <!-- XX -->
	    <input type="checkbox" name="regla3" value="regla3">使用NMAP扫描端口 <input type="text" name="addr3" value="IP o Red"> <br>

	    <!-- probar esta: alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "P2P .torrent metafile"; content:"HTTP/"; content:".torrent"; flow:established,to_server;classtype:policy-violation;) -->
	    <input type="checkbox" name="regla4" value="regla4">检测.torrent文件的下载<br>

	    <!-- Probar esta: alert tcp $EXTERNAL_NET any -> $HOME_NET any //(msg: "Cuidado, están descargando MP3";flags: AP; content: ".mp3";) -->
	    <input type="checkbox" name="regla5" value="regla5">检测.mp3文件的下载<br>
	    <input type="submit" name="save" value="发送" />
	</form> 
    </div>
    <br>  
    <!--div> Movido a rules_view.php
	<h4>Reglas añadidas actualmente a 'easy.rules':</h4>
	<!-- Leer el archivo 'easy.rules'
	<?php /*
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
	}*/
	?>
	<br>
    </div-->
    <div>
	<h4>当前'easy.rules'文件的规则内容 (<a href="index.php?action=rules_view#easy">查看表格</a>)</h4>
	<!-- Leer el archivo 'easy.rules' -->
	<textarea cols="100" rows="25" wrap="hard" readonly="yes">
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
</div>
