<?php
function sec_session_start() {
    $session_name = 'snort_session_id'; //Asignamos un nombre de sesión
    $secure = false; //mejor en config.php Lo ideal sería true para trabajar con https
    $httponly = true;

    // 强制会话使用cookie.
    // Habilitar este ajuste previene ataques que impican pasar el id de sesión en la URL.
    //启用此设置可防止意味着在URL中传递会话ID的攻击。
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
    $action = "error";
    //$error="No puedo iniciar una sesion segura (ini_set)";
    $error="无法启动安全会话（ini_set）";
    }

    // Obtener los parámetros de la cookie de sesión
    //获取会话cookie的参数
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"],
	$cookieParams["domain"],
	$secure, //如果为true，则cookie将仅通过安全连接发送
	$httponly); //将cookie标记为只能通过HTTP协议访问。
    
     //这意味着脚本语言无法访问cookie，
     //比如JavaScript。
     //这种调整可以有效地帮助减少盗窃
     //通过攻击识别身份
   
     //启动PHP会话
    session_name($session_name);
    session_start();
    //使用较新的生成会话ID更新当前会话ID
     //帮助避免会话固定攻击
    session_regenerate_id(true);
} 

//此函数将检查相应的用户名和密码
//在数据库中。如果一切顺利，它将返回true。
function login($username, $password, $conexion) {
    // Usar consultas preparadas previene de los ataques SQL injection.
    //使用准备好的查询可以防止SQL注入攻击
    if ($stmt = $conexion->prepare("SELECT idUser, username, password
    FROM users
    WHERE username = ?
    LIMIT 1")) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    // recogemos el resultado de la consulta
     //我们收集查询的结果
    $stmt->bind_result($id, $username, $db_password); //password de la bd
    $stmt->fetch();
    
    //我们计算密码sha512
    if ($stmt->num_rows == 1) {
	//如果用户存在，我们会检查该帐户是否被阻止
//因为做了太多尝试。
	if (checkbrute($id, $conexion) == true) { //功能如下
	    //帐户被屏蔽了。这里写警告动作
//相关用户：发送电子邮件
	    $error = "被冻结的用户";
	    echo $error;
	    return false;
	} else {
	    //检查bd的密码是否与用户发送的密码匹配
	    if ($db_password == $password) { //las dos en sha512
		//密码正确：我们从用户的浏览器中获取用户代理字符串
//例如Mozilla / 4.0（兼容，MSIE 6.0，Windows NT 5.1）
		$user_browser = $_SERVER['HTTP_USER_AGENT'];

		//这些是针对XSS攻击的保护：
//删除不是数字的字符
		$user_id = preg_replace("/[^0-9]+/", "", $id);
		$_SESSION['id'] = $id;

		//删除不是数字，字母或_，\， - 的字符
		$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
		$_SESSION['username'] = $username;

		//所以没有人冒充我们，这可能是客户的IP。
		$_SESSION['login_string'] = hash('sha512', $password . $user_browser);

		//验证成功
		return true;
	    } else {
		// 口令不对。尝试注册
		$now = time();
		$conexion->query("INSERT INTO login_attempts(id, time)
				    VALUES ('$id', '$now')");
		return false;
	    }
	}
    } else {
	//用户不存在
	return false;
    }
    }
}
	
function checkbrute($id, $conexion) {
     //拿当前时间
    $now = time();
    //计算最后2小时的尝试次数
    $valid_attempts = $now - (2 * 60 * 60); //luego $tiempo_fuerzabruta = 2;
    if ($stmt = $conexion->prepare("SELECT time
    FROM login_attempts
    WHERE id = ?
    AND time > '$valid_attempts'")) {
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->store_result();
	//如果登录次数超过5次
	if ($stmt->num_rows >= 5) { //luego $intentos_login = 5;
	    return true;
	} else {
	    return false;
	}
    }
}

function login_check($conexion) {
//检查是否已初始化所有会话变量
    if (isset($_SESSION['id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $id = $_SESSION['id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
// 获取user-agent 字符串.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        if ($stmt = $conexion->prepare("SELECT password 
                                      FROM users 
                                      WHERE idUser = ? LIMIT 1")) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
                if ($login_check == $login_string) {
                    // 吻合 
                    return true;
                } else {
                    // 未登录
                    return false;
                }
            } else {
// 未记录或用户名不存在
                return false;
            }
        } else {
// 未登录
            return false;
        }
    } else {
// 未登录
        return false;
    }
}

function logout() {
    // Unset all session values
    $_SESSION = array();
    
    // get session parameters
    $params = session_get_cookie_params();

    // Delete the actual cookie.
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]);
    
    // Destroy session 
    session_destroy();
}
?>