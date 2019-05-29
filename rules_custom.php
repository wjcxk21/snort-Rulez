<!--
从此处添加的规则将保存在“custom.rules”中，并为高级用户提供更加个性化的规则。
并非所有规则的自定义选项都可用，因为它不是项目的优先目标。
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_REQUEST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noData = "你还没有填写这个表域";
//Sacar el SID mas alto de la BD y sumarle 1
    //Conex con la BD
    include 'conexion.php';
    $query = "SELECT max( `sid` )"
	    .   "FROM `customRules;";
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
    //查看表单字段
    $arr_opc = array('msgBox','referenceBox', 'classtypeBox','priorityBox','revBox','SIDBox');
    $arr_req = array(
		    array('ruleType','规则类型'),
		    array('protocol','协议'),
		    array('originIP','源IP'),
		    array('originPort','源端口'),
		    array('direction','方向'),
		    array('destinIP','目的IP'),
		    array('destinPort','目的端口')
		);
    
    //Revisar que no hay ningun campo obligatorio vacio
    //检查非空的必填字段
    foreach ($arr_req as $r) {
	if (empty($_REQUEST[$r[0]])) {
	    echo "<div class="."\"alert alert-warning  alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "你还没有填写这个表域'".$r[1]."' , 查看数据。<br>"
			. "</div>";
	};
    };
    //Revisar que si se han marcado los campos opcionales se han rellenado los input
    //检查是否标记了可选字段，输入已填入
    foreach ($arr_opc as $s) {
	if ($_REQUEST[$s] == $s) {
	    $noBox= substr($s,0,-3);
	    if (empty($_REQUEST[$noBox])) {
		echo "<div class="."\"alert alert-warning alert-dismissable text-center clear fade in\" id=\"formAlert\""
			."><button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>"
			. "您已标记可选字段'".$s."' , 但是还没有填写数据。<br>"
			. "</div>";
	    };
	};
    };
    //Mostrar la regla y las opciones con un echo
    //使用echo显示规则和选项
    foreach ($arr_req as $ec) {
	if (!empty($_REQUEST[$ec[0]])) {
	    $rule=$rule.$_REQUEST[$ec[0]]." ";
	}else{
	    goto noRule;
	};
    };
    foreach ($arr_opc as $op) {
	if ($_REQUEST[$op] == $op) {
	    $noBox= substr($op,0,-3);
	    if (!empty($_REQUEST[$noBox])) {
		$options=$options.$noBox.":".$_REQUEST[$noBox]."; ";
	    };
	};
    };
    //Echo para mostrar la regla [Modo depuración]
    $ruleZ = $rule."(".$options.")"."\n";
    //'echo' para depuracion
    //echo $ruleZ."<br>";
    //Guardar la regla en la BD
	// INSERT query
	$query2 = "INSERT INTO customRules (`rule`,`sid`) "
		. "VALUES (?, ?)";
    
	//$query2="select * from rules";
	//'echo' para depuracion
	//echo $query2,"<br>";
	// prepare query for execution -> Aquí se comprueba la sintaxis
	//  de la consulta y se reservan los recursos necesarios
	//  para ejecutarla.
	if ($stmt = $conexion->prepare($query2)){
	//    echo "<div>registro preparado.</div>"; 
	} else {
	//    die('Imposible preparar el registro.'.$stmt->error);
		die('注册准备是不可能的。'.$stmt->error);
	}
	// asociar los parámetros
	$stmt->bind_param('si',$ruleZ,$_POST['SID']);
	// ejecutar la query
	if($stmt->execute()){
	    echo "<div>保存记录</div>";
	} else {
	    die('无法保存记录：'.$conexion->error);
	};
	//Cerramos la conexión	
	$stmt->close();
	//Aqui acaba la escritura en la BD
					    
	//Sacar todas las reglas añadidas a la tabla customRules para añadirlas al .rules
	$sql = "SELECT * FROM `customRules`";
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
	//Escribir la reglas en custom.rules
	$fp = fopen("custom.rules", "w");
	fputs($fp, $inText);
	fclose($fp);
	
	//Para poder seguir añadiendo reglas sin necesidad de recargar la pagina
	$maxSid=$maxSid+1;
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
		    <td>规则类型</td>		<!-- Añadir que sobre cada campo con un hover te explique brevemente que es -->
		    <td>协议</td>
		    <td>源IP</td>
		    <td>源端口</td>
		    <td>方向</td>
		    <td>目的IP</td>
		    <td>目的端口</td>
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
		    <td colspan="6">添加 msg: <input type="text" name="msg" value="">记得使用英文引号(" ").</td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="referenceBox" value="referenceBox"></td>
		    <td colspan="6">添加 reference: <input type="text" name="reference" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="classtypeBox" value="classtypeBox"></td>
		    <td colspan="6">添加 classtype: <input type="text" name="classtype" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="priorityBox" value="priorityBox"></td>
		    <td colspan="6">添加 priority: <input type="text" name="priority" value=""></td>
		</tr>
		<tr>
		    <td><input type="checkbox" name="revBox" value="revBox"></td>
		    <td colspan="6">添加 rev: <input type="text" name="rev" maxlength="18" value=""></td>
		</tr>
	    </table>
	    <input type="submit" name="save" value="添加" />
	</form>
    </div>
    <br>
    <!--div> Movido a rules_view.php
	<h4>Reglas añadidas actualmente a 'custom.rules':</h4>
	<!-- Leer el archivo 'custom.rules'
	<?php /*
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
	}*/
	?>
	<br>
    </div-->
    <div>
	<h4>当前'custom.rules'文件中的规则内容(<a href="index.php?action=rules_view#custom">查看表格</a>)</h4>
	<!-- Leer el archivo 'custom.rules' -->
	<textarea cols="100" rows="25" wrap="hard" readonly="yes">
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