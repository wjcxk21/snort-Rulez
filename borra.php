<?php
// coger el parámetro que nos permitirá identificar el registro

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Registro no encontrado.');
$type = isset($_GET['type']) ? $_GET['type'] : die('ERROR: Registro no encontrado.');

include 'conexion.php';

//'echo' para depuracion
//echo "`".$id."` y `".$type."`";

// Preparación de la consulta de borrado
switch ($type) {
    case 'testRules':
	$query = "DELETE FROM testRules WHERE idTestRule = ?";
	$sql = "SELECT * FROM `testRules`";
	break;
    case 'easyRules':
	$query = "DELETE FROM easyRules WHERE idEasyRule = ?";
	$sql = "SELECT * FROM `easyRules`";
	break;
    case 'customRules':
	$query = "DELETE FROM customRules WHERE idCustomRule = ?";
	$sql = "SELECT * FROM `customRules`";
	break;
};

//'echo' para depuracion
//echo "<br>".$query;

if ($stmt = $conexion->prepare($query)){
	//echo "<div>registro preparado.</div>"; 
    } else {
	die('Imposible preparar el registro.'.$conexion->error); 
    };

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    //header('Location: index.php?action=instrucciones&borra=yes');
    echo "<br>La regla fue borrada<br><br>"
    . "<a href=\"./index.php?action=instrucciones\">Volver al inicio</a>";
} else {
    die('Imposible borrar el registro.');
}
//Cerramos la conexión
$stmt->close();

//Actualizar las reglas añadidas a la tabla correspondiente para añadirlas a su .rules
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

    //Escribir la reglas en *.rules
    switch ($type) {
	case 'testRules':
	    $fp = fopen("test.rules", "w");
	    fputs($fp, $inText);
	    fclose($fp);
	    break;
	case 'easyRules':
	    $fp = fopen("easy.rules", "w");
	    fputs($fp, $inText);
	    fclose($fp);
	    break;
	case 'customRules':
	    $fp = fopen("custom.rules", "w");
	    fputs($fp, $inText);
	    fclose($fp);
	    break;
    };
?>

