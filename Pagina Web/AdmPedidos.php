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
			<!-- TRASLADA LA PAGINA VER PERFIL -->
			function irperfil(){
				location.href="VerPerfil.php";
			}	
			<!-- ACTIVACION DEL FLAG DE CAMBIAR ESTADO A ENVIADO -->
			function Enviado (Fec, DNI, Est, Num){
				if (confirm("Desea cambiar el estado del pedido a enviado?")){
					location.href="AdmPedidos.php?flag=lista&pedido=true&Fec=" + Fec + "&Dn=" + DNI + "&Es=" + Est + "&Num=" + Num;
				}
			}	
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE PEDIDOS SATISFACTORIO -->
			function MensajeMod(Msj){				
				location.href="AdmPedidos.php?flag=lista&respmsg="+Msj;
			}	
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE PEDIDOS ERRONEO -->
			function Error(Msj){
				alert(Msj);
				location.href="AdmPedidos.php?flag=pend";
			}	
			<!-- ACTIVACION DEL FLAG DE LISTAR PEDIDOS -->
			function listarPedidos(){
				location.href="AdmPedidos.php?flag=lista";
			}
			<!-- ACTIVACION DEL FLAG DE LISTAR PEDIDOS PENDIENTES -->
			function pedidosPendientes(){
				location.href="AdmPedidos.php?flag=pend";
			}
			<!-- ACTIVACION DEL FLAG DE LISTAR PEDIDOS ENVIADOS -->
			function pedidosEnviados(){
				location.href="AdmPedidos.php?flag=env";
			}
			<!-- ACTIVACION DEL FLAG DE LISTAR PEDIDOS ENTREGADOS -->
			function pedidosEntregados(){
				location.href="AdmPedidos.php?flag=ent";
			}		
			<!-- VENTANA DE DETALLES -->
			function Verdetalle(Libros){
				Ventana = window.open('','Detalles','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600,height=400');
				Ventana.moveTo(100,100);
				Ventana.document.innerHTML = "";
				Ventana.document.write("<html><head></head><body background='Fondo8.jpg' background-size='cover' style='color:white' onblur='self.close()' ><p>Libros dentro del pedido:</p><p>"+Libros+"</p><p>------------------------------</p></body></html>");
				myWindow.focus();
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function validarbus (){
				if (document.fbus.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbus.BusRap.focus()
				   return 0;
				}			
				document.fbus.submit(); 		
			}			
		</script>
	</head>
	<body>
		<!-- CABECERA -->
		<div id='cabecera'>
			<!-- LOGO COOKBOOK -->
			<div id='imglogo'><a href="index.php"><img src="Logo1.gif" width="85%" height="475%"></a></div> 
			<!-- CONTROL DE SESIONES -->
			<div id='sesiones'>
	<?php
				///CONEXIONES///						
				include 'accion.php';
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);
				// CAMBIAR PEDIDO A ENVIADO, SI PEDIDO = TRUE //
				if (!empty($_GET['pedido']) && $_GET['pedido'] == 'true'){
					$Comp = false;
					PedidoEnviado($_GET['Fec'], $_GET['Es'], $_GET['Dn'], $_GET['Num'], $AltMsg, $Comp);
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
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir()
						echo '</ul>';
					}
				}				
	?>
			</div>
		</div>
		<!-- CUERPO -->
		<div id='cuerpo'>
			<!-- BOTONES DE DESPLAZAMIENTO -->
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="AdmPedidos.php?flag=lista">Listar todos los pedidos</a></li>
					<li><a href="AdmPedidos.php?flag=pend">Pedidos Pendientes</a></li>
					<li><a href="AdmPedidos.php?flag=env">Pedidos Enviados</a></li>
					<li><a href="AdmPedidos.php?flag=ent">Pedidos Entregados</a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM PEDIDOS -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
					// OPCION LISTAR PEDIDOS //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){							
						if(!empty($_GET['respmsg'])){
							echo '<div id="textoadmped"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Listado de todos los pedidos:</samp></div>';
						}
						else{
							echo '<div id="textoadmped"><samp>Listado de todos los pedidos:</samp></div>';
						}
						echo '<div id="barrabusquedaABM" action="Busqueda.php" method="GET">
						<form name="fbus">
							<input size="40" type="text" name="BusRap" placeholder="ISBN, DNI, Estado" required>
							<input type="hidden" name="flag" value="lista" required readonly>							
							<input id="BusRapBotABM" type="button" value="Buscar" onclick="validarbus()">
						</form>
						</div>';
						echo '<div id="TablaPedido">';
						if (!empty($_GET['BusRap'])){
							ConsultarPedidosBus ($restam, $_GET['BusRap']);
						}
						else{
							ConsultarPedidos ($restam);
						}
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($restam);
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
							if (!empty($_GET['BusRap'])){
								ConsultarPedidosPagBus ($res, ($NroPag-1), $_GET['BusRap']);
							}
							else{	
								ConsultarPedidosPag ($res, ($NroPag-1));
							}
							echo 'Pagina Numero: ' .$NroPag;
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun pedido';
							}
							else{
								// GENERAR TABLA //							
								echo "<table border='1'>
									<tr>
										<th>Cliente</th>
										<th>DNI</th>
										<th>Fecha Pedido</th>
										<th>Estado</th>
										<th>Cantidad Libros</th>
										<th>Detalle</th>
									</tr>";									
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";		
									$Nro = $row['Nro_Pedido'];									
									$FecAnt = $row['FechaPedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado'] && $Dni == $row['DNI'] && $Nro == $row['Nro_Pedido']) {	
											if (!$ent){	
												echo "<td>", $row['NombreApellido'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
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
											if ($Est == "Pendiente"){							
			?>																	
												<td><input class="botones" type='button' value='Enviado' onclick='Enviado("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>","<?=$Nro?>")' /></td>
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
								echo '<li><a href="AdmPedidos.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';	
					}
					// OPCION LISTAR PEDIDOS PENDIENTES //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'pend'){	
						echo '<div id="textoadmped"><samp>Listado de todos los pedidos pendientes:</samp></div>';
						echo '<div id="TablaPedido">';
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
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							ConsultarPedidosPendPag ($res, ($NroPag-1));
							echo 'Pagina Numero: ' .$NroPag;
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun pedido';
							}
							else{
								// GENERAR TABLA //							
								echo"<table border='1'>
									<tr>
										<th>Cliente</th>
										<th>DNI</th>
										<th>Fecha Pedido</th>
										<th>Estado</th>
										<th>Cantidad Libros</th>
										<th>Detalle</th>
									</tr>";								
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];
									$Nro = $row['Nro_Pedido'];										
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado'] && $Dni == $row['DNI']  && $Nro == $row['Nro_Pedido']) {	
											if (!$ent){	
												echo "<td>", $row['NombreApellido'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
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
											if ($Est == "Pendiente"){							
			?>																	
												<td><input class="botones" type='button' value='Enviado' onclick='Enviado("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>","<?=$Nro?>")' /></td>
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
								echo '<li><a href="AdmPedidos.php?flag=pend&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';
					}
					// OPCION LISTAR PEDIDOS ENVIADOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'env'){		
						echo '<div id="textoadmped"><samp>Listado de todos los pedidos enviados:</samp></div>';
						echo '<div id="TablaPedido">';
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
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							ConsultarPedidosEnvPag ($res, ($NroPag-1));
							echo 'Pagina Numero: ' .$NroPag;
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun pedido';
							}
							else{
								// GENERAR TABLA //
								echo "<table border='1'>
									<tr>
										<th>Cliente</th>
										<th>DNI</th>
										<th>Fecha Pedido</th>
										<th>Estado</th>
										<th>Cantidad Libros</th>
										<th>Detalle</th>								
									</tr>";								
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Nro = $row['Nro_Pedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado'] && $Dni == $row['DNI'] && $Nro == $row['Nro_Pedido']) {	
											if (!$ent){	
												echo "<td>", $row['NombreApellido'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
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
											if ($Est == "Pendiente"){							
			?>																	
												<td><input class="botones" type='button' value='Enviado' onclick='Enviado("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>","<?=$Nro?>")' /></td>
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
								echo '<li><a href="AdmPedidos.php?flag=env&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';	
					}
					// OPCION LISTAR PEDIDOS ENTREGADOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ent'){		
						echo '<div id="textoadmped"><samp>Listado de todos los pedidos entregados:</samp></div>';
						echo '<div id="TablaPedido">';
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
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							ConsultarPedidosEntPag ($res, ($NroPag-1));
							echo 'Pagina Numero: ' .$NroPag;
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun pedido';
							}
							else{
								// GENERAR TABLA //
								echo "<table border='1'>
									<tr>
										<th>Cliente</th>
										<th>DNI</th>
										<th>Fecha Pedido</th>
										<th>Estado</th>
										<th>Cantidad Libros</th>
										<th>Detalle</th>
									</tr>";								
							$row = mysql_fetch_assoc($res);								
							while($row) {										
									echo "<tr>";									
									$FecAnt = $row['FechaPedido'];	
									$Nro = $row['Nro_Pedido'];	
									$Est = $row['Estado'];
									$ent = false;
									$libros = '';
									$Cant = 0;
									$Dni = $row['DNI'];
										While ($row && $FecAnt == $row['FechaPedido'] && $Est == $row['Estado'] && $Dni == $row['DNI'] && $Nro == $row['Nro_Pedido']) {	
											if (!$ent){	
												echo "<td>", $row['NombreApellido'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
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
											if ($Est == "Pendiente"){							
			?>																	
												<td><input class="botones" type='button' value='Enviado' onclick='Enviado("<?=$FecAnt?>","<?=$Dni?>","<?=$Est?>","<?=$Nro?>")' /></td>
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
								echo '<li><a href="AdmPedidos.php?flag=ent&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';
					}
					// CIERRE SERVIDOR //
					CerrarServidor ($con)
	?>	
				</div>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook � &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>