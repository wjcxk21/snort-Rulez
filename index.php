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
        		var answer = confirm('¿Estás seguro que deseas borrar la regla?');
        		if (answer) {
			    // si el usuario hace click en ok, se ejecuta borra.php
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
		    <div><a href="index.php?action=instrucciones">Inicio</a></div>
		    <div><a href="index.php?action=rules_test">Reglas Test</a></div>
		    <div><a href="index.php?action=rules_easy">Reglas Preparadas</a></div>
		    <div><a href="index.php?action=rules_custom">Reglas Personalizadas</a></div>
		    <div><a href="index.php?action=rules_view">Ver tablas de reglas</a></div>
		    <!--div><a href="index.php?action=change_pass">Cambiar contraseña</a></div-->
		</nav>
		<div id="content">
		    <?php
		    // controlador.php se encargara de mostrar el 'contenido' correspondiente
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
