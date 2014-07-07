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
			function Recibido (FEC, DNI, Est){				
				if (confirm("Desea cambiar el estado del pedido a entregado?")){
					location.href="PerfilPedidos.php?pedido=true&Fec=" + FEC + "&Dn=" + DNI + "&Es=" + Est;
				}
			}
			<!-- VENTANA DE DETALLES -->
			function Verdetalle(Libros){
				Ventana = window.open('','Detalles','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600,height=400');
				Ventana.moveTo(100,100);
				Ventana.document.innerHTML = "";
				Ventana.document.write("<html><head></head><body background='Fondo8.jpg' background-size='cover' style='color:white' onblur='self.close()' ><p>Libros dentro del pedido:</p><p>"+Libros+"</p><p>------------------------------</p></body></html>");
				myWindow.focus();
			}
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE PEDIDOS SATISFACTORIA -->
			function MensajeMod(Msj){				
				location.href="PerfilPedidos.php?flag=lista&respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE PEDIDOS ERRONEA -->
			function Error(Msj){
				alert(Msj);
				location.href="PerfilPedidos.php?flag=env";
			}
			<!-- ACTIVACION DEL FLAG DE ELIMNAR CUENTA -->
			function Eliminar(){
				if (confirm("Desea elimnar su cuenta?")){
					location.href="PerfilCuenta.php?borrar=true";
				}
			}
			<!--VALIDACIONES DE CAMPOS -->
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
				///CONEXIONES///						
				include 'accion.php';
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);
				// CAMBIAR PEDIDO A RECIBIDO, SI PEDIDO = TRUE //
				if (!empty($_GET['pedido']) && $_GET['pedido'] == 'true'){
					$Comp = false;
					PedidoEntregado($_GET['Fec'], $_GET['Es'], $_GET['Dn'], $AltMsg, $Comp);
					if ($Comp){
	?>
					<script languaje="javascript"> 	
						MensajeMod("<?=$AltMsg?>");	
					</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							Error("<?=$AltMsg?>");	
						</script>
	<?php						
					}
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
	<?php
					if(!empty($_GET['respmsg'])){
						echo '<div id="textoperfil"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Lista de todos los pedidos:</samp></div>';
					}
					else{
						echo '<div id="textoperfil"><samp>Lista de todos los pedidos:</samp></div>';
					}
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
									<th>Fecha Pedido</th>
									<th>Estado</th>
									<th>Cantidad Libros</th>
									<th>Detalle</th>
								</tr>";
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado']) {	
											if (!$ent){	
												echo "<td>", $row['FechaPedido'], "</td>";
												echo "<td>", $row['Estado'], "</td>";
												$ent = true;	
											}
											$Cant = $Cant + 1;	
											$libros = $libros .' ISBN: ' .$row['ISBN']. ' - Titulo: ' .$row['Titulo'] .';</br>';
											$row = mysql_fetch_assoc($res);
										}	
											echo "<td>", $Cant, "</td>";
			?>																	
											<td><input class="botones" type='button' value='Detalle' onclick='Verdetalle("<?=$libros?>")' /></td>
			<?php		
											if ($Est == "Enviado"){							
			?>																	
												<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>")' /></td>
			<?php								
											}
									echo "</tr>";									
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
									<th>Fecha Pedido</th>
									<th>Estado</th>
									<th>Cantidad Libros</th>
									<th>Detalle</th>	
								</tr>";							
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado']) {	
											if (!$ent){	
												echo "<td>", $row['FechaPedido'], "</td>";
												echo "<td>", $row['Estado'], "</td>";
												$ent = true;	
											}
											$Cant = $Cant + 1;	
											$libros = $libros .' ISBN: ' .$row['ISBN']. ' - Titulo: ' .$row['Titulo'] .';</br>';
											$row = mysql_fetch_assoc($res);
										}	
											echo "<td>", $Cant, "</td>";
			?>																	
											<td><input class="botones" type='button' value='Detalle' onclick='Verdetalle("<?=$libros?>")' /></td>
			<?php		
											if ($Est == "Enviado"){							
			?>																	
												<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>")' /></td>
			<?php								
											}
									echo "</tr>";									
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
									<th>Fecha Pedido</th>
									<th>Estado</th>
									<th>Cantidad Libros</th>
									<th>Detalle</th>								
								</tr>";							
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado']) {	
											if (!$ent){	
												echo "<td>", $row['FechaPedido'], "</td>";
												echo "<td>", $row['Estado'], "</td>";
												$ent = true;	
											}
											$Cant = $Cant + 1;	
											$libros = $libros .' ISBN: ' .$row['ISBN']. ' - Titulo: ' .$row['Titulo'] .';</br>';
											$row = mysql_fetch_assoc($res);
										}	
											echo "<td>", $Cant, "</td>";
			?>																	
											<td><input class="botones" type='button' value='Detalle' onclick='Verdetalle("<?=$libros?>")' /></td>
			<?php		
											if ($Est == "Enviado"){							
			?>																	
												<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>")' /></td>
			<?php								
											}
									echo "</tr>";									
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
									<th>Fecha Pedido</th>
									<th>Estado</th>
									<th>Cantidad Libros</th>
									<th>Detalle</th>
								</tr>";							
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado']) {	
											if (!$ent){	
												echo "<td>", $row['FechaPedido'], "</td>";
												echo "<td>", $row['Estado'], "</td>";
												$ent = true;	
											}
											$Cant = $Cant + 1;	
											$libros = $libros .' ISBN: ' .$row['ISBN']. ' - Titulo: ' .$row['Titulo'] .';</br>';
											$row = mysql_fetch_assoc($res);
										}	
											echo "<td>", $Cant, "</td>";
			?>																	
											<td><input class="botones" type='button' value='Detalle' onclick='Verdetalle("<?=$libros?>")' /></td>
			<?php		
											if ($Est == "Enviado"){							
			?>																	
												<td><input class="botones" type='button' value='Recibido' onclick='Recibido("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>")' /></td>
			<?php								
											}
									echo "</tr>";									
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
			<samp>Copyright &copy 2014 CookBook – &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>