<?php
// coger el parámetro que nos permitirá identificar el registro

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Registro no encontrado.');
$type = isset($_GET['type']) ? $_GET['type'] : die('ERROR: Registro no encontrado.');

include 'conexion.php';

echo "`".$id."` y `".$type."`";

// Preparación de la consulta de borrado
switch ($type) {
    case 'testRules':
	$query = "DELETE FROM testRules WHERE idTestRule = ?";
	break;
    case 'easyRules':
	$query = "DELETE FROM easyRules WHERE idEasyRule = ?";
	break;
    case 'customRules':
	$query = "DELETE FROM customRules WHERE idCustomRule = ?";
	break;
};

echo "<br>".$query;

if ($stmt = $conexion->prepare($query)){
	echo "<div>registro preparado.</div>"; 
    } else {
	die('Imposible preparar el registro.'.$conexion->error); 
    };

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo "<br>La regla fue borrada<br><br>"
    . "<a href=\"./index.php\">Volver al inicio</a>";
} else {
    die('Imposible borrar el registro.');
}
?>

