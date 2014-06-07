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
				if (confirm("Desea cambiar el estado del pedido a entregado?")){
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
			<!-- ACTIVACION DEL FLAG DE ELIMNAR CUENTA -->
			function Eliminar(){
				if (confirm("Desea elimnar su cuenta?")){
					location.href="PerfilCuenta.php?borrar=true";
				}
				else{
					alert("La operacion no se realizo");
				}
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
			<!-- CONTENIDO PERFIL PEDIDOS -->
			<div id='contenidoperfil'> 
	<?php		
				if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
	?>
					<!-- TEXTO -->
					<div id='textoperfil'><samp>Lista de todos los pedidos:</samp></div>
		<?php
					echo '<div id="PedidosUsuario">';
					ConsultaPedidos ($res, $_SESSION["ID"]);
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}		
					$num1 = mysql_num_rows($res);
					if($num1 == 0){
						echo 'No se localizo ningun pedido';
					}
					else{
						if (empty($_GET['numpag'])){
							$NroPag = 1;
						}
						else{
							$NroPag = $_GET['numpag'];
						}
						ConsultaPedidosPag ($res, ($NroPag-1), $_SESSION["ID"]);
						echo 'Pagina Numero: ' .$NroPag;
						$num2 = mysql_num_rows($res);
						if($num2 == 0){
							echo 'No se localizo ningun pedido';
						}
						else{
							// GENERAR TABLA //	
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
						}	
					}	
					echo '</div>';
					echo '<div id="PaginasPed">';
						$pag = 1;
						echo 'Paginas: ';
						while ( $num1 > 0 ) {
							echo '<li><a href="PerfilPedidos.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
							<li> - </li>';
							$pag ++;
							$num1 = $num1-10;
						}
					echo '</div>';
				}
				// OPCION LISTAR PEDIDOS PENDIENTES //
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'pend'){	
					echo '<div id="textoadmped"><samp>Listado de los pedidos pendientes:</samp></div>';
					echo '<div id="TablaPedido">';
					ConsultarPedidosPendId ($res, $_SESSION["ID"]);
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}		
					$num1 = mysql_num_rows($res);
					if($num1 == 0){
						echo 'No se localizo ningun pedido pendiente';
					}
					else{
						if (empty($_GET['numpag'])){
							$NroPag = 1;
						}
						else{
							$NroPag = $_GET['numpag'];
						}
						ConsultarPedidosPendPagId ($res, ($NroPag-1), $_SESSION["ID"]);
						echo 'Pagina Numero: ' .$NroPag;
						$num2 = mysql_num_rows($res);
						if($num2 == 0){
							echo 'No se localizo ningun pedido pendiente';
						}
						else{
							// GENERAR TABLA //							
							echo"<table border='1'>
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
						}	
					}	
					echo '</div>';
					echo '<div id="PaginasPed">';
						$pag = 1;
						echo 'Paginas: ';
						while ( $num1 > 0 ) {
							echo '<li><a href="PerfilPedidos.php?flag=pend&numpag=' .$pag .'">' .$pag .'</a></li>
							<li> - </li>';
							$pag ++;
							$num1 = $num1-10;
						}
					echo '</div>';
				}
				// OPCION LISTAR PEDIDOS ENVIADOS //
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'env'){		
					echo '<div id="textoadmped"><samp>Listado de los pedidos enviados:</samp></div>';
					echo '<div id="TablaPedido">';
					ConsultarPedidosEnvId ($res, $_SESSION["ID"]);
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}	
					$num1 = mysql_num_rows($res);
					if($num1 == 0){
						echo 'No se localizo ningun pedido enviado';
					}
					else{
						if (empty($_GET['numpag'])){
							$NroPag = 1;
						}
						else{
							$NroPag = $_GET['numpag'];
						}
						ConsultarPedidosEnvPagId ($res, ($NroPag-1), $_SESSION["ID"]);
						echo 'Pagina Numero: ' .$NroPag;
						$num2 = mysql_num_rows($res);
						if($num2 == 0){
							echo 'No se localizo ningun pedido enviado';
						}
						else{
							// GENERAR TABLA //
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
						}	
					}	
					echo '</div>';
					echo '<div id="PaginasPed">';
						$pag = 1;
						echo 'Paginas: ';
						while ( $num1 > 0 ) {
							echo '<li><a href="PerfilPedidos.php?flag=env&numpag=' .$pag .'">' .$pag .'</a></li>
							<li> - </li>';
							$pag ++;
							$num1 = $num1-10;
						}
					echo '</div>';	
				}
				// OPCION LISTAR PEDIDOS ENTREGADOS //
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ent'){		
					echo '<div id="textoadmped"><samp>Listado de los pedidos entregados:</samp></div>';
					echo '<div id="TablaPedido">';
					ConsultarPedidosEntId ($res, $_SESSION["ID"]);
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}		
					$num1 = mysql_num_rows($res);
					if($num1 == 0){
						echo 'No se localizo ningun pedido entregado';
					}
					else{
						if (empty($_GET['numpag'])){
							$NroPag = 1;
						}
						else{
							$NroPag = $_GET['numpag'];
						}
						ConsultarPedidosEntPagId ($res, ($NroPag-1),$_SESSION["ID"]);
						echo 'Pagina Numero: ' .$NroPag;
						$num2 = mysql_num_rows($res);
						if($num2 == 0){
							echo 'No se localizo ningun pedido entregado';
						}
						else{
							// GENERAR TABLA //
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
						}	
					}	
					echo '</div>';
					echo '<div id="PaginasPed">';
						$pag = 1;
						echo 'Paginas: ';
						while ( $num1 > 0 ) {
							echo '<li><a href="PerfilPedidos.php?flag=ent&numpag=' .$pag .'">' .$pag .'</a></li>
							<li> - </li>';
							$pag ++;
							$num1 = $num1-10;
						}
					echo '</div>';
				}
				// CIERRE  SERVIDOR//
				CerrarServidor ($con);
	?>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook � &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>