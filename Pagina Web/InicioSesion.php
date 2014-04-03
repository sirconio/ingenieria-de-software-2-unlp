<?php session_start(); ?>
<html>
	<head>
		<title>Iniciar Sesion</title>
		<link type="text/css" rel="stylesheet" href="styleIS.css">
		<script>
			function Entrar(){
				window.open("index.php");
				window.close();
			}
		</script>
	</head>
	<body id='Sesion'> 
		<?php 
			include 'accion.php';
			ConexionServidor ($con);					
			ConexionBaseDatos ($bd);
			$msg = ' ';
			if	(!empty($_POST['usuario']) && !empty($_POST['clave'])){
				IniciarSesion($_POST['usuario'], $_POST['clave'], $msg);
			}
			if ($msg == ' '){
		?>
			<form method="post" action="">
				<samp>Por favor, ingrese:<samp/></br></br>
				<label for="Usuario">Usuario:</label><input type="text" name="usuario" required /></br></br>
				<label for="Clave">Clave:</label><input type="password" name="clave" required /></br></br>
				<input type="submit" value="Enviar">
			</form>
		<?php
			}
			else{ 
				echo $msg;
			}
			///CIERRE///
			CerrarServidor ($con);
		?>	
	</body>
</html>