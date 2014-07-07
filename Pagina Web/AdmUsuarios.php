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
		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="/resources/demos/style.css">
		
		<script>
			<!-- DATAPICKER LIMITE INF -->
			$(function() {	
				$("#datepickerLimInf").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
			<!-- DATAPICKER LIMITE SUP -->
			$(function() {	
				$("#datepickerLimSup").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
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
				if (confirm("Desea dar de baja este usuario?")){
					location.href="AdmUsuarios.php?accion=borrar&ID=" + ID;
				}			
			} 
			<!-- ACTIVACION DEL FLAG DE ACTIVACION DE USUARIO CON ID -->
			function activarUsuario2(ID){
				if (confirm("Desea reactivar este usuario?")){
					location.href="AdmUsuarios.php?accion=activar&ID=" + ID;
				}
			} 
			<!-- ACTIVACION DEL FLAG DE MODIFICAR USUARIO CON ID -->
			function modUsuario2(ID){				
				location.href="AdmUsuarios.php?flag=mod&NomUs=" + ID;
			}
			<!-- ACTIVACION DEL FLAG DE USUARIOS REGISTRADOS EN UN PERIODO -->
			function conUsuario(){
				location.href="AdmUsuarios.php?flag=con";
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE USUARIOS SATISFACTORIA -->
			function MensajeResp(Msj){
				location.href="AdmUsuarios.php?flag=lista&respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE USUARIOS ERROR -->
			function Error(Msj, ID){
				alert(Msj);
				location.href="AdmUsuarios.php?flag=baja&ID="+ID;
			}
			<!-- MENSAJE DE ERROR -->	
			function MensajeErr(Msj,ID ,Estad ,NomApe, NomUs, DNI, Tel, Dir, Mail ){
				alert(Msj);
				location.href="AdmUsuarios.php?ID="+ID+"&Estad="+Estad+"&NomUs="+NomUs+"&NomApe="+NomApe+"&DNI="+DNI+"&Tel="+Tel+"&Dir="+Dir+"&Mail="+Mail+"&flag=mod&tip=err";
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
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
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
			function validarbus (){
				if (document.fbus.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbus.BusRap.focus()
				   return 0;
				}			
				document.fbus.submit(); 		
			}
			function validarbusmod (){
				if (document.fbusmod.NomUs.value.length==0){
				   alert("Tiene que completar el campo de Nombre de Usuario ha modificar")
				   document.fbusmod.NomUs.focus()
				   return 0;
				}			
				document.fbusmod.submit(); 		
			}					
			function validarbusbaja (){
				if (document.fbusbaja.NomUs.value.length==0){
				   alert("Tiene que completar el campo de Nombre de Usuario ha borrar/activar")
				   document.fbusbaja.NomUs.focus()
				   return 0;
				}			
				document.fbusbaja.submit(); 		
			}			
			function validarperiodo(){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fperiodo.Fini.value.length==0){
					msg = msg + "Fecha Inicial; "
					entro = true;
				}
				if (document.fperiodo.Ffin.value.length==0){
					msg = msg + "Fecha Final; "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.fperiodo.NomUs.focus()
				   return 0;
				}			
				document.fperiodo.submit(); 		
			}	
			function validarmod (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fmod.NomUs.value.length==0){
				   	msg = msg + "Nombre de Usuario; "
					entro = true;
				}	
				if (document.fmod.NomApe.value.length==0){
				   	msg = msg + "Nombre y Apellido; "
					entro = true;
				}	
				if (document.fmod.Mail.value.length==0){
				   	msg = msg + "Mail; "
					entro = true;
				}					
				if(entro){
				   alert(msg)
				   document.fmod.NomUs.focus()
				   return 0;
				}			
				document.fmod.submit(); 		
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
					$Comp = false;
					BajaUsuario($_GET['ID'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							Error("<?=$AltMsg?>", "<?=$_GET['ID']?>");	
						</script>
	<?php					
					}
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA DE LA ACTIVACION DE UN USUARIO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'activar'){
					$Comp = false;
					ActivarUsuario($_GET['ID'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							Error("<?=$AltMsg?>", "<?=$_GET['ID']?>");	
						</script>
	<?php					
					}
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA DE LA MODIFICACION DE UN USUARIO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'modificar'){
					$Cons = false;
					ModUsuario ($Cons, $_GET['ID'], $_GET['NomApe'], $_GET['NomUs'], $_GET['DNI'], $_GET['Tel'], $_GET['Dir'], $_GET['Mail'], &$AltMsg);
					if ( $Cons ){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php				
					}
					else{
	?>
						<script languaje="javascript"> 	
							MensajeErr("<?=$AltMsg?>", "<?=$_GET['ID']?>", "<?=$_GET['Estad']?>" , "<?=$_GET['NomApe']?>", "<?=$_GET['NomUs']?>", "<?=$_GET['DNI']?>", "<?=$_GET['Tel']?>", "<?=$_GET['Dir']?>", "<?=$_GET['Mail']?>" );	
						</script>
	<?php				
					}
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
					<li><a href="AdmUsuarios.php?flag=lista">Listar todos los usuarios</a></li>
					<li><a href="AdmUsuarios.php?flag=baja">Dar de baja/activar un Usuario</a></li>
					<li><a href="AdmUsuarios.php?flag=mod">Modificar un Usuario</a></li>
					<li><a href="AdmUsuarios.php?flag=con">Usuarios registrados en un periodo</a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM USUARIO -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
					// OPCION LISTAR USUARIOS //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){							
						if(!empty($_GET['respmsg'])){
							echo '<div id="textoadmped"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Listado de todos los usuarios:</samp></div>';
						}
						else{
							echo '<div id="textoadmped"><samp>Listado de todos los usuarios:</samp></div>';
						}
						echo '<div id="barrabusquedaABM" action="Busqueda.php" method="GET">
						<form name="fbus">
							<input size="40" type="text" name="BusRap" placeholder="Usuario, Nombre Apellido, DNI" required>
							<input type="hidden" name="flag" value="lista" required readonly>							
							<input id="BusRapBotABM" type="button" value="Buscar" onclick="validarbus()">
						</form>
						</div>';
						echo '<div id="TablaUser">';
						if (!empty($_GET['BusRap'])){
							ConsultarUsuariosBus ($restam, $_GET['BusRap']);
						}
						else{
							ConsultarUsuarios ($restam);
						}
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						$num1 = mysql_num_rows($restam);
						if($num1 == 0){
							echo 'No se localizo ningun usuario';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							if (!empty($_GET['BusRap'])){
								ConsultarUsuariosPagBus ($res, ($NroPag-1), $_GET['BusRap']);
							}
							else{
								ConsultarUsuariosPag ($res, ($NroPag-1));
							}
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ningun usuario';
							}
							else{
								// GENERAR TABLA //
								echo 'Pagina Numero: ' .$NroPag;	
								echo "<table border='1' >
									<tr>										
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
												<td><input class="botones" type='button' value='Modificar' onclick='modUsuario2("<?=$row['Usuario']?>")' /></td>
												<td><input class="botones" type='button' value='Eliminar' onclick='bajaUsuario2("<?=$row['ID']?>")' /></td>
		<?php
											}
											else{
		?>																	
												<td><input class="botones" type='button' value='ReActivar' onclick='activarUsuario2("<?=$row['ID']?>")' /></td>
		<?php
											}
										echo "</tr>";
										$ant = $row['DNI'];
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
								echo '<li><a href="AdmUsuarios.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';		
					}
					// OPCION BAJA/ACTIVAR USUARIOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
						echo '<div id="textoadmped"><samp>Baja/Activacion de usuarios:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR NOMUS -->
							<form class='FAbm' name="fbusbaja" action="" method="GET">
									<label for="ID">Nombre de Usuario a Borrar/Activar:</label>
									<input type="text" name="NomUs" placeholder="Ej: Usuario" maxlength="10"  required>
									<input type="hidden" name="flag" value="baja" required readonly>									
									<input class="botones" type="button" value="Buscar" onclick="validarbusbaja()">	
							</form>
	<?php	
							if (!empty($_GET['NomUs'])){
								DatosUsuario ($res, $_GET['NomUs']);
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se localizo ningun usuario con ese Nombre';
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
												echo '<input class="Reginput" type="hidden" name="accion" value="borrar" required readonly>';	
?>
												<input class="botones" type="button" value="Borrar" onclick='bajaUsuario2("<?=$row['ID']?>")' />
<?php												
											}
											else{ 
												echo '<input class="Reginput" type="hidden" name="accion" value="activar" required readonly>';	
?>
												<input class="botones" type="button" value="Activar" onclick='activarUsuario2("<?=$row['ID']?>")' />
<?php	
											}	
										echo '</form>';
									}					
								}	
							}
						echo '</div>';
					}
					// OPCION MODIFICAR USUARIOS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){		
						echo '<div id="textoadmped"><samp>Modificacion de usuarios:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR NomUs -->
							<form class='FAbm' name="fbusmod" action="" method="GET">
									<label for="ID">Nombe de Usuario a modificar:</label>
									<input type="text" name="NomUs" placeholder="Ej: Usuario" maxlength="10" required>
									<input type="hidden" name="flag" value="mod" required readonly>
									<input class="botones" type="button" value="Buscar" onclick="validarbusmod()">	
							</form>
	<?php	
						if (!empty($_GET['tip']) && $_GET['tip'] == 'err'){
							if($_GET['Estad'] == 'Activo'){
								echo '<form class="FAbm" name="fmod" action="" method="GET">
									<label class="Reginput" for="Visble">Estado: ', $_GET['Estad'], '</label>
									<input class="Reginput" type="hidden" name="ID" value="', $_GET['ID'], '" required readonly>	
									<input class="Reginput" type="hidden" name="Estad" value="', $_GET['Estad'], '" required readonly><br>
									<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
									<input class="Reginput" type="text" name="NomUs" value="', $_GET['NomUs'], '" placeholder="Usuario" maxlength="10" required><br>
									<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
									<input class="Reginput" type="text" name="NomApe"  value="', $_GET['NomApe'], '" placeholder="Nombre Apellido" maxlength="30" onkeypress="return LetrasEspacio(event)"  required><br>
									<label class="Reginput" for="DNI">DNI: ', $_GET['DNI'], '</label>
									<input class="Reginput" type="hidden" name="DNI" value="', $_GET['DNI'], '" placeholder="Ej: 37148135" maxlength="8" onkeypress="return Numeros(event);"  required readonly><br>
									<label class="Reginput" for="Telefono">Telefono:</label>
									<input class="Reginput" type="text" name="Tel" value="', $_GET['Tel'], '" placeholder="Ej: 0114189054" maxlength="8" onkeypress="return Numeros(event);" required><br>
									<label class="Reginput" for="Direccion">Direccion:</label>
									<input class="Reginput" type="text" name="Dir" value="', $_GET['Dir'], '" placeholder="Ej: Calle #Numero" maxlength="30" required><br>
									<label class="Reginput" for="Mail">Mail:</label>
									<input class="Reginput" id="id_mail4" type="text" name="Mail" value="', $_GET['Mail'], '" placeholder="Ej: nombre@correo.com" maxlength="30" onblur="validarEmail()" required><br>';									
									if ($_GET['Estad'] == 'Activo' ){ 
										echo '<input class="Reginput" type="hidden" name="accion" value="modificar" required readonly>	
										<input id="BusRapBotABM" type="button" value="Modificar" onclick="validarmod()">';
									}
									else{ 
										echo '<input class="botones" type="submit" value="Modificar" disabled>';
									}	
								echo '</form>';
							}
							else{
								echo 'El usuario: Nombre = ' . $_Get['NomUs'] .'; no es un usuario activo y sus datos no son modificables';
							}
						}
						else{
							if (!empty($_GET['NomUs'])){
								DatosUsuario ($res, $_GET['NomUs']);
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se localizo ningun usuario con ese Nombre';
								}
								else{
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											// FORMULARIO DE MODIFICACION //
											echo '<form class="FAbm" name="fmod" action="" method="GET">
												<label class="Reginput" for="Visble">Estado: '; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</label>
												<input class="Reginput" type="hidden" name="ID" value="', $row['ID'], '" required readonly>	
												<input class="Reginput" type="hidden" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
												<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
												<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" placeholder="Usuario" maxlength="10" required><br>
												<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
												<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" placeholder="Nombre Apellido" maxlength="30" onkeypress="return LetrasEspacio(event)"  required><br>
												<label class="Reginput" for="DNI">DNI: ', $row['DNI'], '</label>
												<input class="Reginput" type="hidden" name="DNI" value="', $row['DNI'], '" placeholder="Ej: 37148135" maxlength="8" onkeypress="return Numeros(event);"  required readonly><br>
												<label class="Reginput" for="Telefono">Telefono:</label>
												<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" placeholder="Ej: 0114189054" maxlength="8" onkeypress="return Numeros(event);"><br>
												<label class="Reginput" for="Direccion">Direccion:</label>
												<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" placeholder="Ej: Calle #Numero" maxlength="30"><br>
												<label class="Reginput" for="Mail">Mail:</label>
												<input class="Reginput" id="id_mail4" type="text" name="Mail" value="', $row['Contacto'], '" placeholder="Ej: nombre@correo.com" maxlength="30" onblur="validarEmail()" required><br>';									
												if ($row['Estado'] == 1){ 
													echo '<input class="Reginput" type="hidden" name="accion" value="modificar" required readonly>	
													<input id="BusRapBotABM" type="button" value="Modificar" onclick="validarmod()">';
												}
												else{ 
													echo '<input class="botones" type="submit" value="Modificar" disabled>';
												}	
											echo '</form>';
										}
										else{
											echo 'El usuario: Nombre = ' . $row['Nombre'] .'; no es un usuario activo y sus datos no son modificables';
										}
									}	
								}								
							}	
						}	
						echo '</div>';
					}
					// OPCION USUARIOS REGISTRADOS EN UN PERIODO //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'con'){
						echo '<div id="textoadmped"><samp>Usuarios registrados en un periodo:</samp></div>';
					
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE INGRESO PERIODO -->
							<form class='FAbm' name="fperiodo" action="" method="GET">
									<label for="periodo">Ingrese periodo de tiempo:</label></br>
									&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Inicial:</label>
									<input type="date" name="Fini" id="datepickerLimInf" required></br>
									&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Final:</label>
									<input type="date" name="Ffin" id="datepickerLimSup" required></br>
									<input type="hidden" name="flag" value="con" required readonly>									
									<input class="botones" type="button" value="Buscar" onclick="validarperiodo()">	
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
									echo '<div id="TablaUserPeriodo">';
									echo"<table border='1'>
										<tr>										
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