<html>
<head>
	<title>PRUEBA CONECCION</title>
</head>
<body>
Trato de conectar:
	<?php 
		$con = mysql_connect('127.0.0.1', 'Fernando', 'Gimnasia13.'); 
			if (!$con){
				?>	AAAAAAAAAAAAAAABBBBB<?php
			}			
		$bd = mysql_select_db('mydb') or die('La Base de Datos no se pudo seleccionar');
		$restipos = mysql_query('Select * From socios');
		echo $restipos
	?>
</body>
</html>