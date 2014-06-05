<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
	if(!empty($_SESSION['estado'])){
		header ("Location: index.php");
	}
	else{
		if ($_SESSION['estado'] == 'logeado'){
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
			<!-- MENSAJE DE ALTA -->
			function MensajeAlta(Msj){
				alert(Msj);
				location.href="Registrarme.php";
			}
			function MensajeErr(Msj, NomApe, NomUs , DNI, Mail, Tel, Dir){
				alert(Msj);
				location.href="Registrarme.php?flag=err&NomApe="+NomApe+"&NomUs="+NomUs+"&DNI="+DNI+"&Mail="+Mail+"&Tel="+Tel+"&Dir="+Dir;
			}				
			<!-- ACTIVACION DEL FLAG DE BUSQUEDA RAPIDA -->
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
			}
			<!-- VALIDACIONES DE CAMPOS -->
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
				object = document.getElementById("id_mail");
				email = object.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ){
					alert("Error: La dirección de correo " + email + " es incorrecta.");
					object.value = "";	
				}
			}
			function validarTelefono(){
				object = document.getElementById("idtel");
				telef = object.value;
				expr = /^([0-9])*[-]?[0-9]*$/;
				if ( !expr.test(telef) ){
					alert("Error: El telefono " + telef + " es incorrecto.");
					object.value = "";	
				}
			}
			function validarDNI(){
				object = document.getElementById("id_Dni");
				dni = object.value;
				expr = /^([0-9])*[.]([0-9])*[.]?[0-9]*$/;
				if ( !expr.test(dni) ){
					alert("Error: El DNI " + dni + " es incorrecto.");
					object.value = "";	
				}
			}
			function validarPass(){
				object1 = document.getElementById("id_pass1");
				pass1 = object1.value;
				object2 = document.getElementById("id_pass2");
				pass2 = object2.value;
				if (pass1 != pass2){
					alert("Las Contraseña no coinciden");
					object1.value = "";	
					object2.value = "";	
				}
			}
			<!-- FIN VALIDACIONES DE CAMPOS -->
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
					<input id="BusRapBot" type='submit' value='Busqueda Rapida'/>
				</form>
			</div>
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
			<!-- CONTENIDO REGISTRARTE -->
			<div id='contenidoreg'>
				<!-- TEXTO -->
				<div id='textoreg'><samp>Registrate, en un simple paso!</samp></div>
				<!-- RECTANGULO DE REGISTRO -->
				<div id='registro'>
					<!-- FORMULARIO DE REGISTRO -->
	<?php
					if (!empty($_GET['flag']) && $_GET['flag'] == 'err'){
						echo '<form action="" method="POST">
								<label class="Reginput" for="NombreUsuario">*Nombre Usuario:</label>
								<input class="Reginput" type="text" name="NomUs" value="', $_GET['NomUs'], '" placeholder="Usuario" maxlength="10" required><br>
								<label class="Reginput" for="NombreApellido">*Nombre y Apellido:</label>
								<input class="Reginput" type="text" name="NomApe" value="', $_GET['NomApe'], '" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" required><br>
								<label class="Reginput" for="DNI">*DNI:</label>
								<input class="Reginput" id="id_Dni"type="text" name="DNI" value="', $_GET['DNI'], '" placeholder="Ej: 37.148.135" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarDNI()" required><br>	
								<label class="Reginput" for="Telefono">Telefono:</label>
								<input class="Reginput" type="tel" id="idtel" name="Tel" value="', $_GET['Tel'], '" placeholder="Ej: 011-4189054" maxlength="10" onkeypress="return NumerosGuion(event);" onblur="validarTelefono()" ><br>
								<label class="Reginput" for="Direccion">Direccion:</label>
								<input class="Reginput" type="text" size="30" name="Dir" value="', $_GET['Dir'], '" placeholder="Ej: Calle #Numero" maxlength="45" ><br>
								<label class="Reginput" for="Mail">*Mail:</label>
								<input class="Reginput" id="id_mail" type="text" size="30" name="Mail" value="', $_GET['Mail'], '" placeholder="Ej: nombre@correo.com" maxlength="45" onblur="validarEmail()" required><br>
								<label class="Reginput" for="Contraseña1">*Contraseña:</label>
								<input class="Reginput" id="id_pass1" type="password" name="Pass1" placeholder="Contraseña" maxlength="30" required><br>
								<label class="Reginput" for="Contraseña2">*Comfirme Contraseña:</label>
								<input class="Reginput" id="id_pass2" type="password" name="Pass2" placeholder="Contraseña" maxlength="30" onblur="validarPass()" required><br>
								<input id="RegBoton" class="Reginput" type="submit" value="Confirmar"></br></br>
								<label for="obligatorios">Los * son campos obligatorios</label>
						</form>';
					}
					else{
	?>
						<form action="" method="POST">
								<label class="Reginput" for="NombreUsuario">*Nombre Usuario:</label>
								<input class="Reginput" type="text" name="NomUs" placeholder="Usuario" maxlength="10" required><br>
								<label class="Reginput" for="NombreApellido">*Nombre y Apellido:</label>
								<input class="Reginput" type="text" name="NomApe" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" required><br>
								<label class="Reginput" for="DNI">*DNI:</label>
								<input class="Reginput" id="id_Dni"type="text" name="DNI" placeholder="Ej: 37.148.135" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarDNI()" required><br>	
								<label class="Reginput" for="Telefono">Telefono:</label>
								<input class="Reginput" type="tel" id="idtel" name="Tel" placeholder="Ej: 011-4189054" maxlength="10" onkeypress="return NumerosGuion(event);" onblur="validarTelefono()" ><br>
								<label class="Reginput" for="Direccion">Direccion:</label>
								<input class="Reginput" type="text" size="30" name="Dir" placeholder="Ej: Calle #Numero" maxlength="45" ><br>
								<label class="Reginput" for="Mail">*Mail:</label>
								<input class="Reginput" id="id_mail" type="text" size="30" name="Mail" placeholder="Ej: nombre@correo.com" maxlength="45" onblur="validarEmail()" required><br>
								<label class="Reginput" for="Contraseña1">*Contraseña:</label>
								<input class="Reginput" id="id_pass1" type="password" name="Pass1" placeholder="Contraseña" maxlength="30" required><br>
								<label class="Reginput" for="Contraseña2">*Comfirme Contraseña:</label>
								<input class="Reginput" id="id_pass2" type="password" name="Pass2" placeholder="Contraseña" maxlength="30" onblur="validarPass()" required><br>
								<input id="RegBoton" class="Reginput" type="submit" value="Confirmar"></br></br>
								<label for="obligatorios">Los * son campos obligatorios</label>
						</form>	
	<?php
					}
					if	(!empty($_POST['NomApe']) && !empty($_POST['NomUs']) && !empty($_POST['DNI']) && !empty($_POST['Mail']) && !empty($_POST['Pass1']) && !empty($_POST['Pass2'])){	
							$Cons = false;
							AltaUsuario($Cons, $_POST['NomApe'], $_POST['NomUs'], $_POST['DNI'], $_POST['Tel'], $_POST['Dir'], $_POST['Mail'], $_POST['Pass1'], $_POST['Pass2'], $AltMsg);
							if ($Cons){
	?>		
								<script languaje="javascript"> 
									MensajeAlta("<?=$AltMsg?>");
								</script>
	<?php			
							}
							else{
	?>
								<script languaje="javascript">
									MensajeErr("<?=$AltMsg?>", "<?=$_POST['NomApe']?>", "<?=$_POST['NomUs']?>" , "<?=$_POST['DNI']?>", "<?=$_POST['Mail']?>", "<?=$_POST['Tel']?>", "<?=$_POST['Dir']?>");	
								</script>
	<?php						
							}
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