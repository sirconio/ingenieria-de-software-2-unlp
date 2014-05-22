<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
	if(empty($_SESSION['estado'])){
		header ("Location: index.php");
	}
	else{
		if ($_SESSION['categoria'] == 'Normal'){
			header ("Location: index.php");
		}
	}
?>
<html>
	<head>
		<title>CookBook</title>
		<link type="text/css" rel="stylesheet" href="style.css">
		<script>
			<!-- VENTANA EMERGENTE DE INICIO DE SESION -->
			function acceso(){
				window.open("InicioSesion.php","myWindow","status = 1, height = 150, width = 350, resizable = no" );
			}
			<!-- RECARGA LA PAGINA CON EL FLAG EN TRUE -->
			function salir(){
				location.href="index.php?flag=true";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function listarPedidos(){
				location.href="AdmPedidos.php?flag=lista";
			}	
			function Enviado (ISBN, DNI){
				if (confirm("Desea cambiar el estado del pedido a enviado?")){
					location.href="AdmPedidos.php?pedido=true&Is=" + ISBN + "&Dn=" + DNI;
				}
				else{
					alert("La operacion no se realizo");
				}
			}	
			function MensajeMod(Msj){
				alert(Msj);
				location.href="AdmPedidos.php";
			}	
			function listarPedidos(){
				location.href="AdmPedidos.php?flag=lista";
			}
			function pedidosPendientes(){
				location.href="AdmPedidos.php?flag=pend";
			}
			function pedidosEnviados(){
				location.href="AdmPedidos.php?flag=env";
			}
			function pedidosEntregados(){
				location.href="AdmPedidos.php?flag=ent";
			}			
		</script>
	</head>
	<body>
		<div id='cabecera'>
			<div id='imglogo'><img src="Logo1.gif" width="85%" height="475%"> </div>
			<div id='sesiones'>
				<?php
					include 'accion.php';
					///CONEXIONES///						
					ConexionServidor ($con);					
					ConexionBaseDatos ($bd);
					if (!empty($_GET['pedido']) && $_GET['pedido'] == 'true'){
						PedidoEnviado($_GET['Is'], $_GET['Dn'], $AltMsg);
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
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir()
						}
					}
					echo '</ul>';
				?>
			</div>
		</div>
		<div id='cuerpo'>
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="AdmLibros.php">Administrar Libros</a></li>
					<li><a href="AdmUsuarios.php">Administrar Usuarios</a></li>
					<li><a href="AdmPedidos.php">Administrar Pedidos</a></li>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<div id='contenido'>
					<?php
						///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);	
					?>
				<div id='Admfunciones'>	
					<ul>
						<li><a onclick="listarPedidos()">Listar todos los Pedidos</a></li>
						<li><a onclick="pedidosPendientes()">Pedidos Pendientes</a></li>
						<li><a onclick="pedidosEnviados()">Pedidos Enviados</a></li>
						<li><a onclick="pedidosEntregados()">Pedidos Entregados</a></li>
					</ul>
				</div>
				<div id='libros'>
					<?php	
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
						echo '<div id="tablapedidos">';
						ConsultarPedidos ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun pedido';
						}
						else{
							echo '<div id="ListaPedidos">';
							echo	"<table border='1'>
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
									$cadena= ' ';
								if ($row['Estado'] == "Pendiente"){	
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' /></td>
							<?php		
								}
								else {
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' disabled /></td>
							<?php		
								}
									echo "</tr>";
									$ant = $row['ISBN'];
							
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
				}
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'pend'){		
						echo '<div id="tablapedidos">';
						ConsultarPedidosPend ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun pedido pendiente';
						}
						else{
							echo '<div id="ListaPedidos">';
							echo	"<table border='1'>
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
									$cadena= ' ';
								if ($row['Estado'] == "Pendiente"){	
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' /></td>
							<?php		
								}
								else {
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' disabled /></td>
							<?php		
								}
									echo "</tr>";
									$ant = $row['ISBN'];
							
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
				}
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'env'){		
						echo '<div id="tablapedidos">';
						ConsultarPedidosEnv ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}	
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun pedido enviado';
						}
						else{
							echo '<div id="ListaPedidos">';
							echo	"<table border='1'>
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
									$cadena= ' ';
								if ($row['Estado'] == "Pendiente"){	
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' /></td>
							<?php		
								}
								else {
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' disabled /></td>
							<?php		
								}
									echo "</tr>";
									$ant = $row['ISBN'];
							
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
				}
				elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ent'){		
						echo '<div id="tablapedidos">';
						ConsultarPedidosEnt ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun pedido entregado';
						}
						else{
							echo '<div id="ListaPedidos">';
							echo	"<table border='1'>
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
									$cadena= ' ';
								if ($row['Estado'] == "Pendiente"){	
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' /></td>
							<?php		
								}
								else {
							?>													
									<td><input type='button' value='Enviado' onclick='Enviado("<?=$row['ISBN']?>","<?=$row['DNI']?>")' disabled /></td>
							<?php		
								}
									echo "</tr>";
									$ant = $row['ISBN'];
							
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
				}
					?>	
			</div>
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>