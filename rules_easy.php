<!--
Las reglas que se añadan desde aqui se guardarán en 'easy.rules' y tendrán como 
objetivo añadir de forma sencilla reglas muy básicas/muy usadas para Snort.
-->
<?php
/* Si se llama desde el form, contendra datos de 'input' y al no ser
 * $_POST falso se ejecutara primero el PHP, si no hubiese datos y fuese
 * falso se ignoraria el PHP y mostraria el formulario.
 */
$noRule="No has seleccionado ninguna regla.<br>";
$noAddr="No has escrito la direccion de la red o del equipo en la ";

    if($_POST){
	//Abrir el archivo y escribir las reglas correspondientes
	//De momento para ir depurando mostraremos echos de las reglas
	if (($_POST['regla1']) || ($_POST['regla2']) || ($_POST['regla3']) || ($_POST['regla4']) || ($_POST['regla5'])) {
	    #Regla 1: de detección del ping
	    if($_POST['regla1'] == 'regla1'){
		if (empty($_POST['addr1']) || ($_POST['addr1'] == 'IP o Red')) {
		    echo $noAddr."primera regla.<br>";
		}else {
		    echo "alert icmp any any -> ".$_POST['addr1']." any (msg:\"Detectado PING\"; classtype:misc-activity; sid:2130001; rev:1;)";
		    echo "<p>----------------------------------</p>";
		};
	    };

	    #Regla 2: de detección de SSH
	    if($_POST['regla2'] == 'regla2'){
		if (empty($_POST['addr2']) || ($_POST['addr2'] == 'IP o Red')) {
		    echo $noAddr."segunda regla.<br>"; 
		}else {
		    echo "alert tcp any any -> ".$_POST['addr2']." 22 (msg:\"Detectado SSH\"; classtype:misc-activity; sid:2130002; rev:1;)";
		    echo "<p>----------------------------------</p>";
		};
	    };

	    #Regla 3: Escaneo de puertos con NMAP
	    if($_POST['regla3'] == 'regla3'){
		if (empty($_POST['addr3']) || ($_POST['addr3'] == 'IP o Red')) {
		    echo $noAddr."tercera regla.<br>"; 
		}else {
		    echo "alert icmp any any -> ".$_POST['addr3']." any (msg:\"Detectado escaneo NMAP\"; classtype:misc-activity; sid:2130003; rev:1;)";
		    echo "<p>----------------------------------</p>";
		};
	    };
		
	    #Regla 4: Descarga de .torrent
	    if(isset($_POST['regla4']) && $_POST['regla4'] == 'regla4') {
		echo 'alert tcp $HOME_NET any -> $EXTERNAL_NET any (msg: "Detectada descarga torrent"; content:"HTTP/"; content:".torrent"; flow:established,to_server; classtype:policy-violation; sid:2130004; rev:1;)';
		echo "<p>----------------------------------</p>";
	    };

	    #Regla 5: descarga de .mp3
	    if(isset($_POST['regla5']) && $_POST['regla5'] == 'regla5') {
		echo 'alert tcp $EXTERNAL_NET any -> $HOME_NET any (msg:"Detectada descarga MP3";flags: AP; content: ".mp3"; classtype:policy-violation; sid:2130005; rev:1;)';
		echo "<p>----------------------------------</p>";
	    };
	    
	}else{
	    echo $noRule;
	};
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
</div>
