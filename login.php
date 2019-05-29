<h1>登录</h1>
<form action = "index.php?action=login" method = "post" name = "login_form">
    <div>
	<div id="inputlogin">用户名: &nbsp;&nbsp;&nbsp;<input type = "text" name = "username" /></div>
	<div id="inputlogin">口令: <input type = "password" name = "password" id = "password"/></div>
    
    
    <input class="margXL" type = "button" value = "Login"
           onclick = "formhash(this.form, this.form.password);" />
    </div>
</form>

