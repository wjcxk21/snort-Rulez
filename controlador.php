<?php
include 'conexion.php';
include_once 'inc/functions.php';
sec_session_start();

$username = filter_input(INPUT_POST, 'username', $filter = FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'psha', $filter = FILTER_SANITIZE_STRING); // The hashed password.

if (!login_check($conexion)) { //未授权
    if (isset($username, $password)) {
	if (login($username, $password, $conexion) == true) {
	    // 成功
	    $action = $default_action; //默认操作
	    
	    echo "<div class=\"logout\"> <a href=\"index.php?action=logout\"> 断开
		{$_SESSION['username']} </a></div>";
	    
	} else {
	    //登录错误：用户和密码不匹配
	    $action = "login";
	    echo "<div class="."\"alert alert-danger alert-dismissable text-center clear\" id=\"login_fail\"".">
		<button type="."button"." class="."close"." data-dismiss="."alert".">&times;</button>
		    登录不正确！请检查数据。
		</div>";
	}
    } else {
	//significa que aún no has valores para usuario y password
    	//表示您仍然没有用户名和密码的值
	$action = "login";
    }
} else { // si estas autorizado //如果你被授权
    
    $action = basename(filter_input(INPUT_GET, 'action', $filter = FILTER_SANITIZE_STRING));
    
    // In case para definir la accion default segun 'login/logout'
    switch ($action){
    case 'login': $action = $default_action;break;		    
    case 'logout':logout();$action='login';
    }
    if ($action != "login"){
	    echo "<div class=\"logout\"> <a href=\"index.php?action=logout\"> "
	    . "断开 {$_SESSION['username']} "
	    . "</a></div><br>";
    }
    if (!isset($action)) {
	$action = $default_action; //acción por defecto 
    }
    if (!file_exists($action . '.php')) { //comprobamos que el fichero exista
	$action = $default_action; //si no existe mostramos la página por defecto
	echo "操作不支持: 404 [测试: 默认为 ". $default_action ." ] and action= ". $action ."!";
    }
}

include( $action . '.php');//y ahora mostramos la pagina llamada



