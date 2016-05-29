<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<div>
    <h2>Instrucciones de uso de Snort Rulez</h2>
    <div>
	<p>Antes de usar esta aplicación debe:
	<ul>
	    <li>Haber añadido los archivos 'easy.rules', 'custom.rules' y 'test.rules' en la configuración de Snort.</li>
	    <li>Y haber configurado el módulo de 'incron' para la actualizacion automatica del sensor.</li>
	</ul>
	<p>Si le falta alguno de estos pasos consulte la documentación: https://github.com/andoniaf/snort-Rulez </p>

	<h3><u>Tipos de reglas</u></h3>
	<ul>
	    <li><strong>Reglas test</strong>: Se guardarán en 'test.rules' y tendrán como objetivo probar el correcto funcionamiento de Snort. Es recomendable eliminarlas una vez probado el funcionamiento.</li>
	    <li><strong>Reglas preparadas</strong>: Se guardarán en 'easy.rules' y tendrán como objetivo añadir de forma rápida y sencilla reglas básicas a Snort.</li>
	    <li><strong>Reglas personalizadas</strong>: Se guardarán en 'custom.rules' y serán reglas personalizadas para usuarios más avanzados.</li>
	</ul>

	<h3><u>¿Como añado reglas?</u></h3>
	<p>Muy sencillo, accede al tipo de regla que quieres añadir, selecciona en el formulario las opciones que desees y dale a <em>"Enviar"</em>.<br>
	Debajo del formulario podrás ver las reglas que ya esten añadidas al archivo <em>"rules"</em> correspondiente.</p>

	<h3><u>¿Como elimino reglas?</u></h3>
	<p>A la derecha de <em>Contenido del archivo..."</em> hay un enlace a "Ver tabla", desde ahi puedes ir eliminando las reglas que ya no necesites.<br>
	    Estas tablas tambien pueden ser vistas desde el botón de la barra de navegación de <em>"Ver tablas de reglas"</em>.</p>

    </div>
</div>
