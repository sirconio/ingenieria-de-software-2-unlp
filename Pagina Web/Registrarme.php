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
			function registro(){
				location.href="Registrarme.php";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function MensajeAlta(Msj){
				alert(Msj);
				location.href="Registrarme.php";
			}
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
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
				precio = object.value;
				expr = /^([0-9])*[-]?[0-9]*$/;
				if ( !expr.test(precio) ){
					alert("Error: El telefono " + precio + " es incorrecto.");
					object.value = "";	
				}
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
				<div id='textoreg'><samp>Registrate, en un simple paso!</samp></div>
				<div id='registro'>
					<form id='FReg' action="" method="POST">
							<label class="Reginput" for="NombreUsuario">*Nombre Usuario:</label>
							<input class="Reginput" type="text" name="NomUs" placeholder="Usuario" maxlength="10" required><br>
							<label class="Reginput" for="NombreApellido">*Nombre y Apellido:</label>
							<input class="Reginput" type="text" name="NomApe" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" required><br>
							<label class="Reginput" for="DNI">*DNI:</label>
							<input class="Reginput" type="text" name="DNI" placeholder="Ej: 37.148.135" maxlength="10" onkeypress="return NumerosPunto(event);" required><br>
							<label class="Reginput" for="Telefono">Telefono:</label>
							<input class="Reginput" type="tel" id="idtel" name="Tel" placeholder="Ej: 011-4189054" maxlength="10" onkeypress="return NumerosGuion(event);" onblur="validarTelefono()" ><br>
							<label class="Reginput" for="Direccion">Direccion:</label>
							<input class="Reginput" type="text" size="30" name="Dir" placeholder="Ej: Calle #Numero" maxlength="45" ><br>
							<label class="Reginput" for="Mail">*Mail:</label>
							<input class="Reginput" id="id_mail" type="text" size="30" name="Mail" placeholder="Ej: nombre@correo.com" maxlength="45" onblur="validarEmail()" required><br>
							<label class="Reginput" for="Contraseña1">*Contraseña:</label>
							<input class="Reginput" type="password" name="Pass1" placeholder="Contraseña" maxlength="30" required><br>
							<label class="Reginput" for="Contraseña2">*Comfirme Contraseña:</label>
							<input class="Reginput" type="password" name="Pass2" placeholder="Contraseña" maxlength="30" required><br>
							<input class="Reginput" type="submit" value="Confirmar"></br></br>
							<label for="obligatorios">Los * son campos obligatorios</label>
					</form>	
					<?php
					if	(!empty($_POST['NomApe']) && !empty($_POST['NomUs']) && !empty($_POST['DNI']) && !empty($_POST['Mail']) && !empty($_POST['Pass1']) && !empty($_POST['Pass2'])){	
							AltaUsuario($_POST['NomApe'], $_POST['NomUs'], $_POST['DNI'], $_POST['Tel'], $_POST['Dir'], $_POST['Mail'], $_POST['Pass1'], $_POST['Pass2'], $AltMsg);
					?>		
					<script languaje="javascript"> 
						MensajeAlta("<?=$AltMsg?>");
					</script>
					<?php }
						///CIERRE///
						CerrarServidor ($con);
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