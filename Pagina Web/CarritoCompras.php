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
				location.href="index.php?flag=true";
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
			<!-- ACTIVACION DEL FLAG DE RETIRAR DE CARRITO -->
			function Retirar (ISBN, ID){
				location.href="CarritoCompras.php?retirar=true&Is=" + ISBN + "&Dn=" + ID;
			}
			<!-- ACTIVACION DEL FLAG DE VACIAR CARRITO -->
			function Vaciar (ID){
				location.href="CarritoCompras.php?vaciar=true&Dn=" + ID;
			}
			<!-- ACTIVACION DEL FLAG DE COMPRAR CARRITO -->
			function Comprar (ID){
				location.href="CarritoCompras.php?comprar=true&Dn=" + ID;
			}
			<!-- MENSAJE DE RESPUESTA A ACCIONES CON EL CARRITO -->
			function MensajeRet(Msj){
				alert(Msj);
				location.href="CarritoCompras.php";
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
				<form>
					<input size="40" type="text" name="BusRap" placeholder="Autor, Titulo, ISBN" required>
					<input id="BusRapBot"  type='submit' value='Busqueda Rapida'/>
				</form>
			</div>
			<!-- CONTROL DE SESIONES -->
			<div id='sesiones'>	
	<?php
				///CONEXIONES///						
				include 'accion.php';
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);
				// RETIRAR DEL CARRITO, SI RETIRAR = TRUE //
				if (!empty($_GET['retirar']) && $_GET['retirar'] == 'true'){
					RetirarCarrito($_GET['Is'], $_GET['Dn'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeRet("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// VACIAR CARRITO, SI VACIAR = TRUE //
				if (!empty($_GET['vaciar']) && $_GET['vaciar'] == 'true'){
					VaciarCarrito($_GET['Dn'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeRet("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// CAMPRAR CARRITO, SI COMPRAR = TRUE //
				if (!empty($_GET['comprar']) && $_GET['comprar'] == 'true'){
					ComprarCarrito($_GET['Dn'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeRet("<?=$AltMsg?>");	
					</script>
	<?php
				}
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
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO CARRITO COMPRAS -->
			<div id='contenido'> 
				<!-- TEXTO -->
				<div id='textoindex'><samp>Lista Carrito de Compras:</samp></div>
	<?php
				ConsultaCarrito ($res, $_SESSION["ID"]);
				echo '<div id="tablapedidos">';
					// GENERAR TABLA //
					echo"<table border='1'>
						<tr>
							<th>DNI</th>
							<th>Nombre</th>
							<th>ISBN</th>
							<th>Titulo</th>							
							<th>Autor</th>
							<th>Precio</th>
						</tr>";
					$ant = ' ';
					while($row = mysql_fetch_assoc($res)) {
						if ($row['ISBN'] != $ant){
							echo "<tr>";
								echo "<td>", $row['DNI'], "</td>";
								echo "<td>", $row['Nombre'], "</td>";
								echo "<td>", $row['ISBN'], "</td>";
								echo "<td>", $row['Titulo'], "</td>";
								echo "<td>", $row['NombreApellido'], "</td>";
								echo "<td>", $row['Precio'], "</td>";
	?>																	
								<td><input type='button' value='Retirar' onclick='Retirar("<?=$row['ISBN']?>","<?=$_SESSION['ID']?>")' /></td>
	<?php													
							echo "</tr>";
							$ant = $row['ISBN'];							
						}
					}
					echo "</table>";
					echo '</div>';
	?>
				<input id='carritobot1' type='button' value='Vaciar' onclick='Vaciar("<?=$_SESSION['ID']?>")' />
				<input id='carritobot2' type='button' value='Comprar' onclick='Comprar("<?=$_SESSION['ID']?>")' />
			</div>
	<?php
			mysql_free_result($res);
			// CIERRE SERVIDOR //
			CerrarServidor ($con);
	?>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook – &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>