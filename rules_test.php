<!--
Las reglas que se añadan desde aqui se guardarán en 'test.rules' y tendrán como 
objetivo probar el correcto funcionamiento de Snort. 
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_REQUEST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noRule = "No has seleccionado ninguna regla.";

if ($_POST) {
    //Abrir el archivo y escribir las reglas correspondientes
    //De momento para ir depurando mostraremos echos de las reglas
    #Regla 1: de detección del ping
    if (($_REQUEST['regla1'] || ($_REQUEST['regla2']) || $_REQUEST['regla3'])) {
	if ($_REQUEST['regla1']) {
	    echo "alert icmp any any -> any any (msg:\"Ping al IDS detection\";sid:20000013;rev:1;)<br>";
	};
	
	if ($_REQUEST['regla2']) {
	    echo "alert tcp any any -> any 22:23 (msg:\"ssh detection(tcp)\";sid:20000015;rev:2;)<br>";
	};
	
	if ($_REQUEST['regla3']) {
	    echo "alert tcp any any -> any 80 (msg:\"http detection(tcp)\";sid:20000016;rev:2;)<br>"
	    .	 "alert tcp any any -> any 443 (msg:\"https detection(tcp)\";sid:20000017;rev:2;)<br>";
	};
	
    } else {
	echo $noRule;
    };

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
    <div>
	<h4>Reglas añadidas actualmente a 'test.rules'</h4>
	<!-- Leer el archivo 'test.rules' -->
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
</div>
