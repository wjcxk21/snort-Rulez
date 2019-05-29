<?php
// coger el parámetro que nos permitirá identificar el registro
//获取将允许我们识别记录的参数

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: 记录未找到.');
$type = isset($_GET['type']) ? $_GET['type'] : die('ERROR: 记录未找到.');

include 'conexion.php';

//'echo' 用于调试
//echo "`".$id."` y `".$type."`";

// 准备删除查询
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

//'echo' 调试
//echo "<br>".$query;

if ($stmt = $conexion->prepare($query)){
	//echo "<div>准备注册.</div>"; 
    } else {
	die('无法准备注册.'.$conexion->error); 
    };

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    //header('Location: index.php?action=instrucciones&borra=yes');
    echo "<br>该规则已删除<br><br>"
    . "<a href=\"./index.php?action=instrucciones\">回到主页</a>";
} else {
    die('无法删除记录');
}
//关闭连接
$stmt->close();

//Actualizar las reglas añadidas a la tabla correspondiente para añadirlas a su .rules
//更新添加到相应表格的规则，将它们添加到.rules
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

