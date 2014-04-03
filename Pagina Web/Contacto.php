<?php session_start(); ?>
<html>
	<head>
		<title>CookBook</title>
		<link type="text/css" rel="stylesheet" href="style.css">
		<script>
			<!-- VENTANA EMERGENTE DE INICIO DE SESION -->
			function acceso(){
				window.open("InicioSesion.php","myWindow","status = 1, height = 150, width = 350, resizable = no" )	
			}
			<!-- RECARGA LA PAGINA CON EL FLAG EN TRUE -->
			function salir(){
				location.href="index.php?flag=true";
			}			
			function registro(){
				
			}
			function irperfil(){
			
			}
		</script>	
	</head>
	<body>
		<div id='cabecera'>
			<div id='imglogo'> <img src="Logo1.gif" width="85%" height="475%"> </div> 
			<div id='sesiones'>	
				<?php
					include 'accion.php';
					// FLAG = TRUE, INDICA QUE SE PRESIONO SOBRE EL BOTON CERRAR SESION
					if (!empty($_GET['flag']) && $_GET['flag'] == 'true'){
						CerrarSesion();
					}
					// VERIFICA EL ESTADO DE LA SESION
					if(!empty($_SESSION['estado'])){
						//USUARIO LOGEADO CORRECTAMENTE
						if ($_SESSION['estado'] == 'logeado'){
				?>			
							<ul id='bsesiones'>
				<?php
							echo '<li>Usuario, ';
							echo $_SESSION["usuario"];
							echo ' logeado - Categoria:';
							echo $_SESSION['categoria'];
							echo '</li>';
							if ($_SESSION['categoria'] == 'Normal'){
								echo '<li>Carrito de compras:</li>';
							}
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir() 
						}
						//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE ENTRAR A MODO ADMINISTRADOR
						else{
							echo '<li><a onclick="acceso()">&nbsp Iniciar sesion</a></li>';//LLAMA A LA fuction acceso()
							echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';
						}
					}
					//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE ENTRAR A MODO ADMINISTRADOR 
					else{
						echo '<li><a onclick="acceso()">&nbsp Iniciar Sesion</a></li>';//LLAMA A LA fuction acceso()
						echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';
					}
					echo '</ul>';
				?>
			</div>	
		</div>
		<div id='cuerpo'>
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="index.php">Inicio</a></li>
					<li><a href="Busqueda.php">Busqueda</a></li>
					<li><a href="QuienesSomos.php">Quienes Somos?</a></li>
					<li><a href="Contacto.php">Contacto</a></li>
				<?php
					if ($_SESSION['categoria'] == 'Administrador'){
				?>
						<li><a href="Administrador.php">Modo Administrador</a></li>';
				<?php	
					}
				?>
				</ul>
			</div>
			<div id='contenido'> 
				<?php
					if (!empty($_GET['Nombre'])){
				?>				
						<script languaje="javascript"> 
							respuesta("<?=$_GET['Nombre']?>");
						</script> 
				<?php
					}
				?>
				<div id='textcontacto'> 
					Dejenos su consulta: </br></br></br>
					<form action="Contacto.php" method="GET">
						<label class='contacto' for="lNombre">Nombre:</label>
						<input class='contacto' type="text" name="Nombre" required>
						<label class='contacto' for="lMail">E-mail:</label>
						<input class='contacto' type="text" name="e-mail" required>
						<label class='contacto' for="lTelefono">Telefono/Celular:</label>
						<input class='contacto' type="text" name="telefono" required></br>
						<label class='contacto' for="lAusnto">Asunto:</label>
						<input class='contacto' type="text" name="asunto"></br>
						<label class='contacto' for="lConsulta">Consulta:</label></br>
						<textarea class='contacto' name="consulta" rows="10" cols="50" maxlength="255" placeholder="Escriba su consulta aqui..." required></textarea> 
						<input class='contacto' type='submit' value='Enviar'/>
					</form>
				</div>
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |Resolución Óptima 1920 x 1080 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>