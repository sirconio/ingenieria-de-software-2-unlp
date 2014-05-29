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
			<!-- ACTIVACION DEL FLAG DE LISTAR USUARIOS -->
			function listarUsuarios(){
				location.href="AdmUsuarios.php?flag=lista";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE USUARIO -->
			function bajaUsuario(){
				location.href="AdmUsuarios.php?flag=baja";
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICAR USUARIO -->
			function modUsuario(){
				location.href="AdmUsuarios.php?flag=mod";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE USUARIO CON ID -->
			function bajaUsuario2(ID){
				location.href="AdmUsuarios.php?flag=baja&ID=" + ID;
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICAR USUARIO CON ID -->
			function modUsuario2(ID){
				location.href="AdmUsuarios.php?flag=mod&ID=" + ID;
			}
			<!-- ACTIVACION DEL FLAG DE USUARIOS REGISTRADOS EN UN PERIODO -->
			function conUsuario(){
				location.href="AdmUsuarios.php?flag=con";
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE USUARIOS -->
			function MensajeResp(Msj){
				alert(Msj);
				location.href="AdmUsuarios.php";
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function LetrasEspacio(e) {
				tecla = (document.all) ? e.keyCode : e.which;
				if ((tecla==8) || (tecla == 32)) return true;
				patron =/[A-Za-z]/;
				te = String.fromCharCode(tecla);
				return patron.test(te);
			}
			function NumerosPunto(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function NumerosGuion(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 45))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function validarEmail() {
				object = document.getElementById("id_mail4");
				email = object.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ){
					alert("Error: La dirección de correo " + email + " es incorrecta.");
					object.value = "";	
				}
			}
			<!-- FIN VALIDACIONES DE CAMPOS -->
		</script>
	</head>
	<body
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
				// FLAG = TRUE, INDICA QUE SE PRESIONO SOBRE EL BOTON CERRAR SESION
				if (!empty($_GET['flag']) && $_GET['flag'] == 'true'){
					CerrarSesion();
				}
				// ACCION = BORRAR, INDICA QUE SE DARA DE BAJA UN USUARIO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'borrar'){
					BajaUsuario($_GET['ID'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA DE LA ACTIVACION DE UN USUARIO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'activar'){
					ActivarUsuario($_GET['ID'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA DE LA MODIFICACION DE UN USUARIO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'modificar'){
					ModUsuario ($_GET['ID'], $_GET['NomApe'], $_GET['NomUs'], $_GET['DNI'], $_GET['Tel'], $_GET['Dir'], $_GET['Mail'], &$AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php				
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
					<li><a href="AdmLibros.php">Administrar Libros</a></li>
					<li><a href="AdmUsuarios.php">Administrar Usuarios</a></li>
					<li><a href="AdmPedidos.php">Administrar Pedidos</a></li>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM USUARIO -->
			<div id='contenidoadm'>
				<!-- BOTONES DE FUCIONALIDADES -->
				<div id='Admfunciones'>	
					<ul>
						<li><a onclick="listarUsuarios()">Listar todos los Usuarios</a></li>
						<li><a onclick="bajaUsuario()">Dar de baja/activar un Usuario</a></li>
						<li><a onclick="modUsuario()">Modificar un Usuario</a></li>
						<li><a onclick="conUsuario()">Usuario registrados en un periodo</a></li>
					</ul>
				</div>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
					// OPCION LISTAR USUARIOS //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){	
						ConsultarUsuarios ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se localizo ningun usuario';
						}
						else{
							// GENERAR TABLA //
							echo '<div id="tablauser">';
							echo "<table border='1' >
								<tr>
									<th>ID</th>
									<th>Usuario</th>
									<th>DNI</th>
									<th>NombreApellido</th>
									<th>FechaAlta</th>
									<th>Telefono</th>
									<th>Direccion</th>
									<th>Contacto</th>
									<th>Estado</th>
								</tr>";
							$ant = ' ';
							while($row = mysql_fetch_assoc($res)) {
								if ($row['DNI'] != $ant){
									echo "<tr>";
										echo "<td>", $row['ID'], "</td>";
										echo "<td>", $row['Usuario'], "</td>";
										echo "<td>", $row['DNI'], "</td>";
										echo "<td>", $row['NombreApellido'], "</td>";
										echo "<td>", $row['FechaAlta'], "</td>";
										echo "<td>", $row['Telefono'], "</td>";
										echo "<td>", $row['Direccion'], "</td>";
										echo "<td>", $row['Contacto'], "</td>";
										echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
										if ($row['Estado'] == 1){
	?>
											<td><input type='button' value='Modificar' onclick='modUsuario2("<?=$row['ID']?>")' /></td>
											<td><input type='button' value='Eliminar' onclick='bajaUsuario2("<?=$row['ID']?>")' /></td>
	<?php
										}
										else{
	?>					
											<td><input type='button' value='Modificar' onclick='modUsuario2("<?=$row['ID']?>")' disabled /></td>
											<td><input type='button' value='ReActivar' onclick='bajaUsuario2("<?=$row['ID']?>")' /></td>
	<?php
										}
									echo "</tr>";
									$ant = $row['DNI'];
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
					}
					// OPCION BAJA/ACTIVAR USUARIOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ID -->
							<form class='FAbm' action="" method="GET">
									<label class="AVinput" for="ID">Id del Usuario a Borrar/Activar:</label>
									<input class="AVinput" type="text" name="ID" placeholder="Ej: 9" maxlength="10" onkeypress="return Numeros(event);" required>
									<input class="Reginput" type="hidden" name="flag" value="baja" required readonly>
									<input class="AVinput" type="submit" value="Buscar">
							</form>
	<?php	
							if (!empty($_GET['ID'])){
								if ($_GET['ID'] == 0){
									echo 'No se localizo ningun usuario con esa ID';
								}
								else{
									DatosUsuario ($res, $_GET['ID']);
									$num1 = mysql_num_rows($res);
									if($num1 == 0){
										echo 'No se localizo ningun usuario con esa ID';
									}
									else{
										while($row = mysql_fetch_assoc($res)){
											// FORMULARIO DE BAJA/ACTIVAR //	
											echo '<form class="FAbm" action="" method="GET">
												<input class="Reginput" type="hidden" name="ID" value="', $row['ID'], '" required readonly>	
												<label class="Reginput" for="Visble">Estado:</label>
												<input class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
												<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
												<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" required readonly><br>
												<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
												<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" required readonly><br>
												<label class="Reginput" for="DNI">DNI:</label>
												<input class="Reginput" type="text" name="DNI" value="', $row['DNI'], '" required readonly><br>
												<label class="Reginput" for="Telefono">Telefono:</label>
												<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" required readonly><br>
												<label class="Reginput" for="Direccion">Direccion:</label>
												<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" required readonly><br>
												<label class="Reginput" for="Mail">Mail:</label>
												<input class="Reginput" type="text" name="Mail" value="', $row['Contacto'], '" required readonly><br>';	
												if ($row['Estado'] == 1){ 
													echo '<input class="Reginput" type="hidden" name="accion" value="borrar" required readonly>	
													<input class="AVinput" type="submit" value="Borrar">';
												}
												else{ 
													echo '<input class="Reginput" type="hidden" name="accion" value="activar" required readonly>	
													<input class="AVinput" type="submit" value="Activar">';
												}	
											echo '</form>';
										}					
									}	
								}		
							}
						echo '</div>';
					}
					// OPCION MODIFICAR USUARIOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){					
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ID -->
							<form class='FAbm' action="" method="GET">
									<label class="AVinput" for="ID">Id del Usuario a modificar:</label>
									<input class="AVinput" type="text" name="ID" placeholder="Ej: 9" maxlength="10" onkeypress="return Numeros(event);" required>
									<input class="Reginput" type="hidden" name="flag" value="mod" required readonly>
									<input class="AVinput" type="submit" value="Buscar">
							</form>
	<?php	
						if (!empty($_GET['ID'])){
							if ($_GET['ID'] == 0){
								echo 'No se localizo ningun usuario con esa ID';
							}
							else{
								DatosUsuario ($res, $_GET['ID']);
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se localizo ningun usuario con esa ID';
								}
								else{
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											// FORMULARIO DE MODIFICACION //
											echo '<form class="FAbm" action="" method="GET">
												<label class="Reginput" for="Visble">Estado:</label>
												<input class="Reginput" type="hidden" name="ID" value="', $row['ID'], '" required readonly>	
												<input class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
												<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
												<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" placeholder="Usuario" maxlength="10" required><br>
												<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
												<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required><br>
												<label class="Reginput" for="DNI">DNI:</label>
												<input class="Reginput" type="text" name="DNI" value="', $row['DNI'], '" placeholder="Ej: 37.148.135" maxlength="10" onkeypress="return NumerosPunto(event);"  required readonly><br>
												<label class="Reginput" for="Telefono">Telefono:</label>
												<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" placeholder="Ej: 011-4189054" maxlength="10" onkeypress="return NumerosGuion(event);" required><br>
												<label class="Reginput" for="Direccion">Direccion:</label>
												<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" placeholder="Ej: Calle #Numero" maxlength="45" required><br>
												<label class="Reginput" for="Mail">Mail:</label>
												<input class="Reginput" id="id_mail4" type="text" name="Mail" value="', $row['Contacto'], '" placeholder="Ej: nombre@correo.com" maxlength="45" onblur="validarEmail()" required><br>';									
												if ($row['Estado'] == 1){ 
													echo '<input class="Reginput" type="hidden" name="accion" value="modificar" required readonly>	
													<input class="AVinput" type="submit" value="Modificar">';
												}
												else{ 
													echo '<input class="AVinput" type="submit" value="Modificar" disabled>';
												}	
											echo '</form>';
										}
										else{
											echo 'El usuario: Id = ' .$_GET['ID'] .', Nombre = ' . $row['Nombre'] .'; no es un usuario activo y sus datos no son modificables';
										}
									}	
								}
							}	
						}	
						echo '</div>';
					}
					// OPCION USUARIOS REGISTRADOS EN UN PERIODO //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'con'){
					
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE INGRESO PERIODO -->
							<form class='FAbm' action="" method="GET">
									<label class="AVinput" for="periodo">Ingrese periodo de tiempo:</label></br>
									&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Inicial:</label>
									<input class="AVinput" type="date" name="Fini" required></br>
									&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Final:</label>
									<input class="AVinput" type="date" name="Ffin" required></br>
									<input class="Reginput" type="hidden" name="flag" value="con" required readonly>
									<input class="AVinput" type="submit" value="Buscar">
							</form>
	<?php	
							if (!empty($_GET['Fini']) && !empty($_GET['Ffin'])){
								// CONSULTA DE USUARIOS REGISTRADOS //
								UsuarioPeriodo ($res, $_GET['Fini'], $_GET['Ffin']);
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se registro ningun usuario en dicho periodo temporal';
								}
								else{
									// GENERADOR DE TABLA RESULTANTE //
									echo '<div id="tablauser1">';
									echo"<table border='1'>
										<tr>
											<th>ID</th>
											<th>Usuario</th>
											<th>DNI</th>
											<th>NombreApellido</th>
											<th>FechaAlta</th>
											<th>Estado</th>
										</tr>";
									$ant = ' ';
									while($row = mysql_fetch_assoc($res)){
										if ($row['DNI'] != $ant){
												echo "<tr>";
													echo "<td>", $row['ID'], "</td>";
													echo "<td>", $row['Usuario'], "</td>";
													echo "<td>", $row['DNI'], "</td>";
													echo "<td>", $row['NombreApellido'], "</td>";
													echo "<td>", $row['FechaAlta'], "</td>";
													echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
												echo "</tr>";
												$ant = $row['DNI'];
										}
									}
									echo '</table>';
									echo '</div>';
								}	
							}
						echo '</div>';
					}
					// CIERRE SERVIDOR //
					CerrarServidor ($con);
	?>	
				</div>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook – &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>