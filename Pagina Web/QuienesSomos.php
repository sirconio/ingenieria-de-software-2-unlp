<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
?>
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
				location.href="QuienesSomos.php?flag=true";
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
							<ul id='bsesiones'>
				<?php
							echo '<li>Usuario, ';
							echo $_SESSION["usuario"];
							echo ' logeado - Categoria:';
							echo $_SESSION['categoria'];
							echo '</li>';
							if ($_SESSION['categoria'] == 'Normal'){
				?>				
								<li><a id='carrito' href="CarritoCompras.php">Carrito de compras: 
				<?php				echo $_SESSION["CarritoCant"];
								echo '</a></li>';
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
					<li><a href="Busqueda.php">Catalogo</a></li>
					<li><a href="QuienesSomos.php">Quienes Somos?</a></li>
				<?php
					if ($_SESSION['categoria'] == 'Normal'){
				?>
					<li><a href="Contacto.php">Contacto</a></li>
				<?php	
					}
				?>
				<?php
					if ($_SESSION['categoria'] == 'Administrador'){
				?>
						<li><a href="Administrador.php">Modo Administrador</a></li>
				<?php	
					}
				?>
				</ul>
			</div>
			<div id='contenidoQS'> 
				<div id='imgquien'> <img src="imgquien.jpg" width="90%" height="90%" align="right"> </div> 
				<div id='textquien'> 
					<p>CookBook es un empredimiento familiar, cuya trayectoria comercial data desde 2007.</br> 
					   A cargo del misma nos encontramos Norma y quien les escribe, Ruben. Dos experimentados cocineros retirados. </br></br>

				     	Hemos definido como objetivo facilitarle a nuestros clientes sus tareas culinarias diarias.</br>
						Para ello, contamos con una amplia gama de libros de cocina, que le brindaran el apoyo que necesitan.</br></br>

						Queremos ofrecerle nuestra mejor y mayor disposici&oacuten para atenderlo en todo aquello que sea de su inter&eacutes. </br>
						Desde nuestros inicios, nuestro objetivo hacia el futuro ser&aacute brindarle el mejor de los servicios y la m&aacutes c&aacutelida atenci&oacuten. </br>
					</p>	
				</div>
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>