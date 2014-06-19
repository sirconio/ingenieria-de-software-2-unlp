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
			function Retirar (ID){
				if (confirm("Desea retirar ese libro del carrito de compras?")){
					location.href="CarritoCompras.php?retirar=true&ID=" + ID;
				}					
			}
			<!-- ACTIVACION DEL FLAG DE VACIAR CARRITO -->
			function Vaciar (ID){
				if (confirm("Desea vaciar el carrito de compras?")){
					location.href="CarritoCompras.php?vaciar=true&Dn=" + ID;
				}			
			}
			<!-- ACTIVACION DEL FLAG DE COMPRAR CARRITO -->
			function Comprar (ID){
				location.href="CarritoCompras.php?flag=compra";
			}		
			<!-- TRASLADA LA PAGINA ANTERIOR -->
			function Atras (){
				location.href="CarritoCompras.php";
			}
			<!-- MENSAJE DE RESPUESTA A ACCIONES CON EL CARRITO SATISFACTORIA -->
			function MensajeRet(Msj){				
				location.href="CarritoCompras.php?respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA A ACCIONES CON EL CARRITO ERRONEA -->
			function ErrorRet(Msj){
				alert(Msj);
				location.href="CarritoCompras.php";
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function validarbusrap (){
				if (document.fbusrap.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbusrap.BusRap.focus()
				   return 0;
				}			
				document.fbusrap.submit(); 		
			}
			function validarpass (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fpass.NumTarj.value.length==0){
				   	msg = msg + "Numero de Tarjeta; "
					entro = true;
				}	
				if (document.fpass.Pass.value.length==0){
				   	msg = msg + "Clave; "
					entro = true;
				}						
				if(entro){
				   alert(msg)
				   document.fpass.PassActual.focus()
				   return 0;
				}
				else{
					location.href="CarritoCompras.php?comprar=true";
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
				// RETIRAR DEL CARRITO, SI RETIRAR = TRUE //
				if (!empty($_GET['retirar']) && $_GET['retirar'] == 'true'){
					$Comp = false;
					RetirarCarrito($_GET['ID'], $_SESSION['ID'], $AltMsg , $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeRet("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							ErrorRet("<?=$AltMsg?>");	
						</script>
	<?php
					}
				}
				// VACIAR CARRITO, SI VACIAR = TRUE //
				if (!empty($_GET['vaciar']) && $_GET['vaciar'] == 'true'){
					$Comp = false;
					VaciarCarrito($_GET['Dn'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeRet("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							ErrorRet("<?=$AltMsg?>");	
						</script>
	<?php
					}
				}
				// CAMPRAR CARRITO, SI COMPRAR = TRUE //
				if (!empty($_GET['comprar']) && $_GET['comprar'] == 'true'){
					$Comp = false;
					ComprarCarrito($_SESSION['ID'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeRet("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							ErrorRet("<?=$AltMsg?>");	
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
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO CARRITO COMPRAS -->
			<div id='contenidocarrito'> 
				<!-- TEXTO -->				
	<?php
				if (!empty($_GET['flag']) && $_GET['flag'] == 'compra'){
					$Comp = false;
					verificarCarrito($Comp, $_SESSION['ID'], $Msj);
					if ($Comp){
						echo '<div id="textocarrito"><samp>Verificando su tarjeta:</samp></div>
						<form id="FRegPerfilClave" name="fpass" action="" method="POST">						
							<label class="Reginput" for="NroTarjeta">Numero de Tarjeta</label>
							<input class="Reginput" type="text" name="NumTarj" placeholder="Ej: 1234567890" maxlength="8"  onkeypress="return Numeros(event);" required><br>
							<label class="Reginput" for="PassTarjeta">Clave:</label>
							<input class="Reginput" type="password" name="Pass" placeholder="Ej: Clave" maxlength="30" required><br>
							<input class="botones" type="button" value="Enviar" onclick="validarpass()">
							<input class="botones" type="button" value="Atras" onclick="Atras()">
						</form>';	
					}
					else{
	?>
						<script languaje="javascript"> 	
							ErrorRet("<?=$Msj?>");	
						</script>
	<?php					
					}
				}
				else{
					if(!empty($_GET['respmsg'])){
						echo '<div id="textocarrito"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Lista Carrito de Compras:</samp></div>';
					}
					else{
						echo '<div id="textocarrito"><samp>Lista Carrito de Compras:</samp></div>';
					}
					ConsultaCarrito ($res, $_SESSION["ID"]);
					echo '<div id="TablaCarrito">';
					if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun libro en el carrito';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							ConsultaCarritoPag ($res, $_SESSION["ID"], ($NroPag-1) );
							echo 'Pagina Numero: ' .$NroPag;
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun libro en el carrito';
							}
							else{
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
								$Total = 0;
								while($row = mysql_fetch_assoc($res)) {								
									echo "<tr>";
										echo "<td>", $row['DNI'], "</td>";
										echo "<td>", $row['Nombre'], "</td>";
										echo "<td>", $row['ISBN'], "</td>";
										echo "<td>", $row['Titulo'], "</td>";
										echo "<td>", $row['NombreApellido'], "</td>";
										echo "<td>", $row['Precio'], "</td>";
				?>																	
										<td><input class="botones" type='button' value='Retirar' onclick='Retirar("<?=$row['ID']?>")' /></td>
				<?php													
									echo "</tr>";
									$Total = $Total + $row['Precio'];		
								}
								echo '</br>Monto Total: $' .$Total;
								echo "</table>";
							}	
						}	
						echo '</div>';					
						echo '<div id="PaginasPed">';						
							$pag = 1;
							echo 'Paginas: ';
							while ( $num1 > 0 ) {
								echo '<li><a href="CarritoCompras.php?numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';
		?>
					<input class="botones" id='carritobot1' type='button' value='Vaciar' onclick='Vaciar("<?=$_SESSION['ID']?>")' />
					<input class="botones" id='carritobot2' type='button' value='Comprar' onclick='Comprar()' />				
	<?php
				}
			echo '</div>';
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