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
				location.href="PerfilPedidos.php?flag=true";
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
			<!-- ACTIVACION DEL FLAG DE CAMBIAR ESTADO A RECIBIDO -->
			function Recibido (ISBN, DNI){				
				if (confirm("Desea cambiar el estado del pedido a recibido?")){
					location.href="PerfilPedidos.php?pedido=true&Is=" + ISBN + "&Dn=" + DNI;
				}
				else{
					alert("La operacion no se realizo");
				}
			}
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE PEDIDOS -->
			function MensajeMod(Msj){
				alert(Msj);
				location.href="PerfilPedidos.php";
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
				// CAMBIAR PEDIDO A RECIBIDO, SI PEDIDO = TRUE //
				if (!empty($_GET['pedido']) && $_GET['pedido'] == 'true'){
					PedidoEntregado($_GET['Is'], $_GET['Dn'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeMod("<?=$AltMsg?>");	
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
					<li><a href="PerfilCuenta.php">Informacion de Cuenta</a></li>
	<?php
						if ($_SESSION['categoria'] == 'Normal'){
	?>
							<li><a href="PerfilPedidos.php">Mis Pedidos</a></li>
							<li><a href="PerfilCuenta.php?elminar=true">Eliminar Cuenta</a><li>
	<?php	
						}
	?>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO PERFIL PEDIDOS -->
			<div id='contenidoperfil'> 
				<!-- TEXTO -->
				<div id='textoperfil'><samp>Lista de pedidos:</samp></div>
	<?php
				ConsultaPedidos ($res, $_SESSION["ID"]);
				// GENERAR TABLA //	
				echo '<div id="PedidosUsuario">';
					echo "<table border='1'>
						<tr>
							<th>ISBN</th>
							<th>Titulo</th>
							<th>DNI</th>
							<th>NombreApellido</th>
							<th>FechaPedido</th>
							<th>Estado</th>
							
						</tr>";
					$ant = ' ';
					while($row = mysql_fetch_assoc($res)) {
						if ($row['ISBN'] != $ant){
							echo "<tr>";
								echo "<td>", $row['ISBN'], "</td>";
								echo "<td>", $row['Titulo'], "</td>";
								echo "<td>", $row['DNI'], "</td>";
								echo "<td>", $row['NombreApellido'], "</td>";
								echo "<td>", $row['FechaPedido'], "</td>";
								echo "<td>", $row['Estado'], "</td>";
								if ($row['Estado'] == "Enviado"){							
	?>																	
									<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$row['ISBN']?>","<?=$row['DNI']?>")' /></td>
	<?php		
								}
								else{
	?>		
									<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$row['ISBN']?>","<?=$row['DNI']?>")' disabled /></td>
	<?php
								}
							echo "</tr>";
							$ant = $row['ISBN'];
						}
					}
					echo "</table>";
				echo '</div>';
				mysql_free_result($res);
				// CIERRE  SERVIDOR//
				CerrarServidor ($con);
	?>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook – &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>