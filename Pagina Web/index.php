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
				location.href="Registrarme.php";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
			}
			function recargar () {
				<!-- location.reload(); --> 
			}
		</script>	
	</head>
	<body>
		<?php
			if (!empty($_GET['BusRap'])){
		?>
				<script languaje="javascript"> 					
					busqueda("<?=$_GET['BusRap']?>");	
				</script>
		<?php
			}
		?> 
		<div id='cabecera'>
			<div id='imglogo'> <img src="Logo1.gif" width="85%" height="475%"> </div> 
			<div id='barrabusqueda' action="Busqueda.php" method="GET">
				<form>
					<input class='contacto' size="40" type="text" name="BusRap" placeholder="Autor, Titulo, ISBN" required>
					<input class='contacto' type='submit' value='Busqueda Rapida'/>
				</form>
			</div>
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
						<script languaje="javascript"> 					
							recargar();	
						</script>
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
						<li><a href="Administrador.php">Modo Administrador</a></li>
				<?php	
					}
				?>
				</ul>
			</div>
			<div id='contenidoindex'> 
				<div id='imgindex'><img src="cocinero.gif" width="25%" height="75%"></div> 
				<div id='imgindex2'><img src="cocinera.gif" width="50%" height="75%"></div> 
				<div id='textoindex'><samp>�Encontra las recetas que buscar! Hay millones de libros publicados, los mejores autores y los precios m�s bajos</samp></div>
			</div>
		</div>
		<div id='pie'>
			<samp> Direcci�n : Calle 30 N 416  - La Plata - Argentina | Tel�fono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci�n Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright � 2014 CookBook � Todos los derechos reservados.</samp>
		</div>
	</body>
</html>