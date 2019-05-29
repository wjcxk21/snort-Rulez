<h2>修改口令</h2>
<?php
// coger el parámetro que nos permitirá identificar el registro
$id = $_SESSION['id'];

//'echo' para depuracion
//echo $_SESSION['id']; 

//include 'conexion.php';

// hacer que las modificaciones hechas en edita.php sean guardadas en la BD
if ($_POST) {
    // escribir en la tabla users
    $query = "UPDATE users "
    . "SET password=SHA2(?,512) "
    . "WHERE idUser = ?";
    
    $stmt = $conexion->prepare($query);
    
    $stmt->bind_param('si', $_POST['password'], $id);
    
    if ($stmt->execute()) {
    echo "更新口令";
    } else {
    echo '更新时错误.';
    }
}

?>
<form action='index.php?action=change_pass&id=<?php echo htmlspecialchars($id); ?>' method='post'
border='0'>
    <table>
	<tr>
	    <td>Password: </td>
	    <td><input type='password' name='password' value="<?php echo htmlspecialchars($password, ENT_QUOTES); ?>" /></td>
	</tr>
	<tr>
	    <td></td>
	    <td>
		<input type='submit' value='保存' />
	    </td>
	</tr>
    </table>
    <br>
    <a href="./index.php?action=<?php echo $default_action ?>">回到主页</a>
</form>


