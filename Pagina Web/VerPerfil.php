<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
	if(empty($_SESSION['estado'])){
		header ("Location: index.php");
	}
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
				location.href="VerPerfil.php?flag=true";
			}			
			<!-- TRASLADA A LA PAGINA REGISTRAME -->
			function registro(){
				location.href="Registrarme.php";
			}
			<!-- TRASLADA LA PAGINA VER PERFIL -->
			function irperfil(){
				location.href="VerPerfil.php";
			}
			<!-- ACTIVACION DEL FLAG DE BUSQUEDA RAPIDA -->
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
			}
			<!-- ACTIVACION DEL FLAG DE ELIMNAR CUENTA -->
			function Elimnar(){
				if (confirm("Desea elimnar su cuenta?")){
					location.href="PerfilCuenta.php?eliminar=true";
				}
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function validarbusrap (){
				if (document.fbusrap.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbusrap.BusRap.focus()
				   return 0;
				}			
				document.fbusrap.submit(); 		
			}
		</script>	
	</head>
	<body>
		<!-- COMPROBACION DE BUSQUEDA RAPIDA -->
	<?php
		if (!empty($_GET['BusRap'])){
	?>
			<script languaje="javascript"> 					
				busqueda("<?=$_GET['BusRap']?>");//LLAMA A LA fuction busqueda()	
			</script>
	<?php
		}
	?>
		<!-- CABECERA -->
		<div id='cabecera'>
			<!-- LOGO COOKBOOK -->
			<div id='imglogo'><a href="index.php"><img src="Logo1.gif" width="85%" height="475%"></a></div> 
			<!-- BARRA DE BUSQUEDA RAPIDA -->
			<div id='barrabusqueda' action="Busqueda.php" method="GET">
				<form name="fbusrap">
					<input size="40" type="text" name="BusRap" placeholder="Autor, Titulo, ISBN" required>
					<input id="BusRapBot" type="button" value="Busqueda Rapida" onclick="validarbusrap()">
				</form>
			</div>
			<!-- CONTROL DE SESIONES -->
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
	<?php				
								echo $_SESSION["CarritoCant"];
								echo '</a></li>';
							}
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir() 
					}
					//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE LOGEARSE
					else{
						echo '<li><a onclick="acceso()">&nbsp Iniciar sesion</a></li>';//LLAMA A LA fuction acceso()
						echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';//LLAMA A LA fuction registro()
					}
				}
				//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE LOGEARSE 
				else{
					echo '<li><a onclick="acceso()">&nbsp Iniciar Sesion</a></li>';//LLAMA A LA fuction acceso()
					echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';//LLAMA A LA fuction registro()
				}
						echo '</ul>';
	?>
			</div>	
		</div>
		<!-- CUERPO -->
		<div id='cuerpo'>
			<!-- BOTONES DE DESPLAZAMIENTO -->
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="PerfilCuenta.php">Informacion de Cuenta</a></li>
	<?php
						if ($_SESSION['categoria'] == 'Normal'){
	?>
							<li><a href="PerfilPedidos.php?flag=lista">Todos mis Pedidos</a></li>
							<li><a href="PerfilPedidos.php?flag=pend">Mis Pedidos Pendientes</a></li>
							<li><a href="PerfilPedidos.php?flag=env">Mis Pedidos Enviados</a></li>
							<li><a href="PerfilPedidos.php?flag=ent">Mis Pedidos Entregados</a></li>
							<li><a href="PerfilCuenta.php?eliminar=true">Eliminar Cuenta</a><li>
	<?php	
						}
	?>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO VER PERFIL -->
			<div id='contenidoperfil'> 
				<!-- TEXTO -->
				<div id='textoperfil'><samp>Bienvenido a tu perfil <?php echo $_SESSION["usuario"] ?></samp></div>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook � &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>