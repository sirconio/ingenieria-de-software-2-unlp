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
			function registro(){
				location.href="Registrarme.php";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
			}
			function Retirar (ISBN, ID){
				location.href="CarritoCompras.php?retirar=true&Is=" + ISBN + "&Dn=" + ID;
			}
			function Vaciar (ID){
				location.href="CarritoCompras.php?vaciar=true&Dn=" + ID;
			}
			function Comprar (ID){
				location.href="CarritoCompras.php?comprar=true&Dn=" + ID;
			}
			function MensajeRet(Msj){
				alert(Msj);
				location.href="CarritoCompras.php";
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
					///CONEXIONES///						
					ConexionServidor ($con);					
					ConexionBaseDatos ($bd);
					if (!empty($_GET['retirar']) && $_GET['retirar'] == 'true'){
						RetirarCarrito($_GET['Is'], $_GET['Dn'], $AltMsg);
						?>
							<script languaje="javascript"> 	
								MensajeRet("<?=$AltMsg?>");	
							</script>
						<?php
					}
					if (!empty($_GET['vaciar']) && $_GET['vaciar'] == 'true'){
						VaciarCarrito($_GET['Dn'], $AltMsg);
						?>
							<script languaje="javascript"> 	
								MensajeRet("<?=$AltMsg?>");	
							</script>
						<?php
					}
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
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<div id='contenido'> 
				<div id='textoindex'><samp>Lista Carrito de Compras:</samp></div>
				<?php
					ConsultaCarrito ($res, $_SESSION["ID"]);
					echo '<div id="tablapedidos">';
						echo	"<table border='1'>
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
								$cadena= ' ';
						?>																	
								<td><input type='button' value='Retirar' onclick='Retirar("<?=$row['ISBN']?>","<?=$_SESSION['ID']?>")' /></td>
						<?php													
								echo "</tr>";
								$ant = $row['ISBN'];
						
							}
						}
						echo "</table>";
						echo '</div>';
						mysql_free_result($res);
						///CIERRE///
						CerrarServidor ($con);
				?>
				<input id='carritobot1' type='button' value='Vaciar' onclick='Vaciar("<?=$_SESSION['ID']?>")' />
				<input id='carritobot2' type='button' value='Comprar' onclick='Comprar("<?=$_SESSION['ID']?>")' />
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>