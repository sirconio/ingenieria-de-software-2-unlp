<?php session_start();
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
			function registro(){
				location.href="Registrarme.php";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function busqueda (bus){
				location.href="Busqueda.php?BusRap=" + bus;
			}
			function MensajeAlta(Msj){
				alert(Msj);
				location.href="index.php?flag=true";
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
							MensajeAlta("<?=$AltMsg?>");
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
								echo '<li>Carrito de compras:</li>';
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
						MensajeAlta("<?=$AltMsg?>");
					</script>
				<?php }
					if	(!empty($_POST['PassActual']) && !empty($_POST['Pass1']) && !empty($_POST['Pass2'])){	
								ModClave($_SESSION["ID"], $_POST['PassActual'], $_POST['Pass1'], $_POST['Pass2'], $AltMsg);
					?>		
						<script languaje="javascript"> 
							MensajeAlta("<?=$AltMsg?>");
						</script>
					<?php }
					DatosUsuario($res, $_SESSION["ID"]);     
					while($row = mysql_fetch_assoc($res)){
						echo '<form id="FRegPerfil" action="" method="POST">
								<label class="Reginput" for="ModPers">Modificar Datos Personales:</label></br>
								<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
								<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" required><br>
								<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
								<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" required><br>
								<label class="Reginput" for="DNI">DNI:</label>
								<input class="Reginput" type="text" name="DNI" value="', $row['DNI'], '" required readonly><br>
								<label class="Reginput" for="Telefono">Telefono:</label>
								<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" required><br>
								<label class="Reginput" for="Direccion">Direccion:</label>
								<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" required><br>
								<label class="Reginput" for="Mail">Mail:</label>
								<input class="Reginput" type="text" name="Mail" value="', $row['Contacto'], '" required><br>
								<input class="Reginput" type="submit" value="Modificar">
						</form>';	
					}
					echo '<form id="FRegPerfil2" action="" method="POST">
							<label class="Reginput" for="Modcontra">Modificar Contraseña:</label></br>
							<label class="Reginput" for="Contraseña1">Contraseña Actual:</label>
							<input class="Reginput" type="password" name="PassActual" required><br>
							<label class="Reginput" for="Contraseña2">Nueva Contraseña:</label>
							<input class="Reginput" type="password" name="Pass1" required><br>
							<label class="Reginput" for="Contraseña2">Comfirme Contraseña:</label>
							<input class="Reginput" type="password" name="Pass2" required><br>
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