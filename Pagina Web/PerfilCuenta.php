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
			<!-- MENSAJE DE RESPUESTA A BAJA DE USUARIO SATISFACTORIAMENTE -->
			function MensajeBaja(Msj){				
				location.href="index.php?flag=true";
			}
			<!-- MENSAJE DE RESPUESTA A BAJA DE USUARIO ERRONEA -->
			function ErrorBaja(Msj){	
				alert(Msj);
				location.href="PerfilCuenta.php";
			}
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE USUARIO SATISFACTORIA -->
			function MensajeMod(Msj){
				location.href="PerfilCuenta.php?respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA A MODIFICACION DE USUARIO ERRONEA -->
			function Error(Msj){
				alert(Msj);
				location.href="PerfilCuenta.php";
			}
			<!-- ACTIVACION DEL FLAG DE ELIMNAR CUENTA -->
			function Eliminar(){
				if (confirm("Desea elimnar su cuenta?")){
					location.href="PerfilCuenta.php?borrar=true";
				}
			}
			<!-- MENSAJE DE ERROR -->	
			function MensajeErr(Msj,ID ,NomApe, NomUs, DNI, Tel, Dir, Mail ){
				alert(Msj);
				location.href="PerfilCuenta.php?ID="+ID+"&NomUs="+NomUs+"&NomApe="+NomApe+"&DNI="+DNI+"&Tel="+Tel+"&Dir="+Dir+"&Mail="+Mail+"&tip=err";
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
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
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
				if (document.fpass.PassActual.value.length==0){
				   	msg = msg + "Contraseña Actual; "
					entro = true;
				}	
				if (document.fpass.Pass1.value.length==0){
				   	msg = msg + "Contraseña Nueva; "
					entro = true;
				}	
				if (document.fpass.Pass2.value.length==0){
				   	msg = msg + "Comfirme Contraseña; "
					entro = true;
				}					
				if(entro){
				   alert(msg)
				   document.fpass.PassActual.focus()
				   return 0;
				}			
				document.fpass.submit(); 		
			}
			function validardatos (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fdatos.NomUs.value.length==0){
				   	msg = msg + "Nombre de Usuario; "
					entro = true;
				}	
				if (document.fdatos.NomApe.value.length==0){
				   	msg = msg + "Nombre y Apellido; "
					entro = true;
				}	
				if (document.fdatos.Mail.value.length==0){
				   	msg = msg + "Mail; "
					entro = true;
				}					
				if(entro){
				   alert(msg)
				   document.fdatos.NomUs.focus()
				   return 0;
				}			
				document.fdatos.submit(); 		
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
				// ELIMINAR USUARIO, SI ELIMINAR = TRUE //
				if (!empty($_GET['eliminar']) && $_GET['eliminar'] == 'true'){
	?>		
					<script languaje="javascript"> 
						Eliminar();
					</script>
	<?php 		}
				if (!empty($_GET['borrar']) && $_GET['borrar'] == 'true'){
					$Comp = false;
					BajaUsuario($_SESSION["ID"], $AltMsg, $Comp);
					if ($Comp){
	?>		
						<script languaje="javascript"> 
							MensajeBaja("<?=$AltMsg?>");
						</script>				
	<?php	
					}
					else{
		?>		
						<script languaje="javascript"> 
							ErrorBaja("<?=$AltMsg?>");
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
			<!-- CONTENIDO PERFIL CUENTA -->
			<div id='contenidoperfil'> 
				<!-- TEXTO -->				
	<?php
				if(!empty($_GET['respmsg'])){
					echo '<div id="textoperfil"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Informacion de la cuenta:</samp></div>';
				}
				else{
					echo '<div id="textoperfil"><samp>Informacion de la cuenta:</samp></div>';
				}
				// MODIFICAR DATOS PERSONALES //
				if	(!empty($_POST['NomApe']) && !empty($_POST['NomUs']) && !empty($_POST['DNI']) && !empty($_POST['Mail'])){	
					$Cons = false;
					ModUsuario($Cons, $_SESSION["ID"], $_POST['NomApe'], $_POST['NomUs'], $_POST['DNI'], $_POST['Tel'], $_POST['Dir'], $_POST['Mail'], $AltMsg);
					if ( $Cons ){
	?>
						<script languaje="javascript"> 	
							MensajeMod("<?=$AltMsg?>");	
						</script>
	<?php				
					}
					else{
	?>
						<script languaje="javascript"> 	
							MensajeErr("<?=$AltMsg?>", "<?=$_SESSION['ID']?>", "<?=$_POST['NomApe']?>", "<?=$_POST['NomUs']?>", "<?=$_POST['DNI']?>", "<?=$_POST['Tel']?>", "<?=$_POST['Dir']?>", "<?=$_POST['Mail']?>" );	
						</script>
	<?php				
					}	
				}
				// MODIFICAR CLAVE //
				if	(!empty($_POST['PassActual']) && !empty($_POST['Pass1']) && !empty($_POST['Pass2'])){	
					$Comp = false;
					ModClaves($Comp, $_SESSION["ID"], $_POST['PassActual'], $_POST['Pass1'], $_POST['Pass2'], $AltMsg);
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
				// DATOS DEL USUARIO ON LINE //
				if (!empty($_GET['tip']) && $_GET['tip'] == 'err'){
					// FORMULARIO DATOS PERSONALES //
						echo '<form id="FRegPerfilPersonal" name="fdatos" action="" method="POST">
							<label class="Reginput" for="ModPers">Modificar Datos Personales:</label></br>
							<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
							<input class="Reginput" type="text" name="NomUs" value="', $_GET['NomUs'], '" placeholder="Usuario" maxlength="10" required><br>
							<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
							<input class="Reginput" type="text" name="NomApe"  value="', $_GET['NomApe'], '" placeholder="Nombre Apellido" maxlength="30" onkeypress="return LetrasEspacio(event)" required><br>
							<label class="Reginput" for="DNI">DNI:</label>
							<input class="Reginput" type="text" name="DNI" value="', $_GET['DNI'], '" name="DNI" placeholder="Ej: 37148135" maxlength="8" onkeypress="return Numeros(event);" required readonly><br>
							<label class="Reginput" for="Telefono">Telefono:</label>
							<input class="Reginput" type="text" name="Tel" value="', $_GET['Tel'], '" placeholder="Ej: 0114189054" maxlength="8" onkeypress="return NumerosGuion(event);"><br>
							<label class="Reginput" for="Direccion">Direccion:</label>
							<input class="Reginput" type="text" name="Dir" value="', $_GET['Dir'], '" placeholder="Ej: Calle #Numero" maxlength="30"><br>
							<label class="Reginput" for="Mail">Mail:</label>
							<input class="Reginput" id="id_mail3" type="text" name="Mail" value="', $_GET['Mail'], '" placeholder="Ej: nombre@correo.com" maxlength="30" onblur="validarEmail()" required><br>
							<input class="botones" type="button" value="Modificar" onclick="validardatos()">
						</form>';	
				
				}
				else{
					DatosUsuarioID($res, $_SESSION["ID"]);     
					while($row = mysql_fetch_assoc($res)){
						// FORMULARIO DATOS PERSONALES //
						echo '<form id="FRegPerfilPersonal" name="fdatos" action="" method="POST">
							<label class="Reginput" for="ModPers">Modificar Datos Personales:</label></br>
							<label class="Reginput" for="NombreUsuario">Nombre Usuario:</label>
							<input class="Reginput" type="text" name="NomUs" value="', $row['Nombre'], '" placeholder="Usuario" maxlength="10" required><br>
							<label class="Reginput" for="NombreApellido">Nombre y Apellido:</label>
							<input class="Reginput" type="text" name="NomApe"  value="', $row['NombreApellido'], '" placeholder="Nombre Apellido" maxlength="30" onkeypress="return LetrasEspacio(event)" required><br>
							<label class="Reginput" for="DNI">DNI:</label>
							<input class="Reginput" type="text" name="DNI" value="', $row['DNI'], '" name="DNI" placeholder="Ej: 37148135" maxlength="8" onkeypress="return Numeros(event);" required readonly><br>
							<label class="Reginput" for="Telefono">Telefono:</label>
							<input class="Reginput" type="text" name="Tel" value="', $row['Telefono'], '" placeholder="Ej: 0114189054" maxlength="8" onkeypress="return NumerosGuion(event);"required><br>
							<label class="Reginput" for="Direccion">Direccion:</label>
							<input class="Reginput" type="text" name="Dir" value="', $row['Direccion'], '" placeholder="Ej: Calle #Numero" maxlength="30" required><br>
							<label class="Reginput" for="Mail">Mail:</label>
							<input class="Reginput" id="id_mail3" type="text" name="Mail" value="', $row['Contacto'], '" placeholder="Ej: nombre@correo.com" maxlength="30" onblur="validarEmail()" required><br>
							<input class="botones" type="button" value="Modificar" onclick="validardatos()">
						</form>';	
					}
				}	
				// FORMULARIO DE CLAVE //	
				echo '<form id="FRegPerfilClave" name="fpass" action="" method="POST">
					<label class="Reginput" for="Modcontra">Modificar Contraseña:</label></br>
					<label class="Reginput" for="Contraseña1">Contraseña Actual:</label>
					<input class="Reginput" type="password" name="PassActual" placeholder="Contraseña Actual" maxlength="30" required><br>
					<label class="Reginput" for="Contraseña2">Nueva Contraseña:</label>
					<input class="Reginput" id="id_pass1"type="password" name="Pass1" placeholder="Nueva Contraseña" maxlength="30" required><br>
					<label class="Reginput" for="Contraseña2">Comfirme Contraseña:</label>
					<input class="Reginput" id="id_pass2" size= "20" type="password" name="Pass2" placeholder="Repita Nueva Contraseña" maxlength="30" onblur="validarPass()" required><br>				
					<input class="botones" type="button" value="Modificar" onclick="validarpass()">
				</form>';	
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