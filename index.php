<html> 
    <head>
        <meta charset="UTF-8">
        <title>Snort Rulez</title>
	<link media="all" href="css/style.css" rel="stylesheet" type="text/css"></link>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script type="text/JavaScript" src="js/sha512.js"></script>
	<script type="text/JavaScript" src="js/forms.js"></script>
	<script type="text/JavaScript">
            function borra_cliente(id,type){
        		var answer = confirm('您确定要删除该规则吗？');
        		if (answer) {
			    //如果用户单击“确定”，则执行delete.php borra.php
			    window.location = 'index.php?action=borra&id=' + id + '&type=' + type;
        		}
            }
        </script>
    </head>
    <body>
	<div class="container">
	    <div id="wrapper">
		<div id="header">
		    <div id="logo">
			<img src="img/logo_web.png"></img>
		    </div>
		    <div id="title">
			Snort Rulez
		    </div>
		</div>
		<nav class="melolbar">
		    <div><a href="index.php?action=instrucciones">帮助说明</a></div>
		    <div><a href="index.php?action=rules_test">测试规则</a></div>
		    <div><a href="index.php?action=rules_easy">简易规则</a></div>
		    <div><a href="index.php?action=rules_custom">自定义规则</a></div>
		    <div><a href="index.php?action=rules_view">规则列表</a></div>
		    <div><a href="index.php?action=change_pass">修改口令</a></div>
		</nav>
		<div id="content">
		    <?php
		    // controlador.php 显示相应的内容
			include('controlador.php');
		    ?>
		</div>
		<div id="footer">
		    <div class="pull-left"><kbd>#SnortRulez</kbd></div>
		    <div class="pull-right"><kbd>Andoni Alonso (2016)</kbd></div>
		</div>
	    </div>
	</div>
    </body>
</html>
