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
				location.href="PerfilCuenta.php?flag=true";
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
			function MensajeBaja(Msj){
				alert(Msj);
				location.href="index.php?flag=true";
			}
			function MensajeMod(Msj){
				alert(Msj);
				location.href="PerfilCuenta.php";
			}function LetrasEspacio(e) {
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
				object = document.getElementById("id_mail3");
				email = object.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ){
					alert("Error: La dirección de correo " + email + " es incorrecta.");
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
				
					if (!empty($_GET['elminar']) && $_GET['elminar'] == 'true'){
						BajaUsuario($_SESSION["ID"], $AltMsg);
					?>		
						<script languaje="javascript"> 
							MensajeBaja("<?=$AltMsg?>");
						</script>
					<?php }
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
					<li><a href="PerfilCuenta.php">Informacion de Cuenta</a></li>
					<?php
						if ($_SESSION['categoria'] == 'Normal'){
					?>
							<li><a href="PerfilPedidos.php">Mis Pedidos</a></li>
							<li><a href="PerfilCuenta.php?elminar=true">Eliminar Cuenta</a><li>
					<?php	
						}
					?>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<div id='contenido'> 
				<div id='textoindex'><samp>Informacion de la cuenta:</samp></div>
				<?php
					if	(!empty($_POST['NomApe']) && !empty($_POST['NomUs']) && !empty($_POST['DNI']) && !empty($_POST['Tel']) && !empty($_POST['Dir']) && !empty($_POST['Mail'])){	
							ModUsuario($_SESSION["ID"], $_POST['NomApe'], $_POST['NomUs'], $_POST['DNI'], $_POST['Tel'], $_POST['Dir'], $_POST['Mail'], $AltMsg);
				?>		
					<script languaje="javascript"> 
						MensajeMod("<?=$AltMsg?>");
					</script>
				<?php }
					if	(!empty($_POST['PassActual']) && !empty($_POST['Pass1']) && !empty($_POST['Pass2'])){	
								ModClaves($_SESSION["ID"], $_POST['PassActual'], $_POST['Pass1'], $_POST['Pass2'], $AltMsg);
					?>		
						<script languaje="javascript"> 
							MensajeMod("<?=$AltMsg?>");
						</script>
					<?php }
					DatosUsuario($res, $_SESSION["ID"]);     
					while($row = mysql_fetch_assoc($res)){
						echo '<form id="FRegPerfil" action="" method="POST">
								<label class="Reginput" for="ModPers">Modificar Datos Personales:</label></br>
								<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
								<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" placeholder="Usuario" maxlength="10" required><br>
								<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
								<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" required><br>
								<label class="Reginput" for="DNI">DNI:</label>
								<input class="Reginput" type="text" name="DNI" value="', $row['DNI'], '" name="DNI" placeholder="Ej: 37.148.135" maxlength="10" onkeypress="return NumerosPunto(event);" required readonly><br>
								<label class="Reginput" for="Telefono">Telefono:</label>
								<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" placeholder="Ej: 011-4189054" maxlength="10" onkeypress="return NumerosGuion(event);"required><br>
								<label class="Reginput" for="Direccion">Direccion:</label>
								<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" placeholder="Ej: Calle #Numero" maxlength="45" required><br>
								<label class="Reginput" for="Mail">Mail:</label>
								<input class="Reginput" id="id_mail3" type="text" name="Mail" value="', $row['Contacto'], '" placeholder="Ej: nombre@correo.com" maxlength="45" onblur="validarEmail()" required><br>
								<input class="Reginput" type="submit" value="Modificar">
						</form>';	
					}
					echo '<form id="FRegPerfil2" action="" method="POST">
							<label class="Reginput" for="Modcontra">Modificar Contraseña:</label></br>
							<label class="Reginput" for="Contraseña1">Contraseña Actual:</label>
							<input class="Reginput" type="password" name="PassActual" placeholder="Contraseña Actual" maxlength="30" required><br>
							<label class="Reginput" for="Contraseña2">Nueva Contraseña:</label>
							<input class="Reginput" type="password" name="Pass1" placeholder="Nueva Contraseña" maxlength="30" required><br>
							<label class="Reginput" for="Contraseña2">Comfirme Contraseña:</label>
							<input class="Reginput"  size= "20" type="password" name="Pass2" placeholder="Repita Nueva Contraseña" maxlength="30" required><br>
							<input class="Reginput" type="submit" value="Modificar">
						</form>';	
				?>
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>