<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
	  if(!empty($_SESSION['categoria']) && $_SESSION['categoria'] != 'Administrador'){
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
				location.href="Contacto.php?flag=true";
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
			function respuesta(Nom){				
				location.href="Contacto.php?respnom="+Nom;
			}	
			<!-- VALIDACIONES DE CAMPOS -->
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
				object = document.getElementById("id_mail2");
				email = object.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ){
					alert("Error: La dirección de correo " + email + " es incorrecta.");
					object.value = "";
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
			function validarenvio (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fenvio.Nombre.value.length==0){
				   	msg = msg + "Nombre; "
					entro = true;
				}	
				if (document.fenvio.mail.value.length==0){
				   	msg = msg + "Mail; "
					entro = true;
				}	
				if (document.fenvio.telefono.value.length==0){
				   	msg = msg + "Telefono/Celular; "
					entro = true;
				}	
				if (document.fenvio.consulta.value.length==0){
				   	msg = msg + "Consulta; "
					entro = true;
				}					
				if(entro){
				   alert(msg)
				   document.fenvio.NomUs.focus()
				   return 0;
				}			
				document.fenvio.submit(); 		
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
				include 'accion.php';
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
					<li><a href="index.php">Inicio</a></li>
					<li><a href="Busqueda.php">Catalogo</a></li>
					<li><a href="QuienesSomos.php">Quienes Somos?</a></li>
	<?php
					if ($_SESSION['categoria'] != 'Administrador'){
	?>
					<li><a href="Contacto.php">Contacto</a></li>
	<?php	
					}
					if ($_SESSION['categoria'] == 'Administrador'){
	?>
						<li><a href="Administrador.php">Modo Administrador</a></li>
	<?php	
					}
					if (empty($_SESSION['estado'])){
	?>
						<li><a href="Registrarme.php">Registrate</a></li>
	<?php
					}
	?>				
				</ul>
			</div>
			<!-- CONTENIDO CONTACTO -->
			<div id='contenidocontac'> 
			<!-- RESPUESTA AL MENSAJE -->
	<?php
				if (!empty($_GET['Nombre'])){
	?>				
					<script languaje="javascript"> 
						respuesta("<?=$_GET['Nombre']?>");
					</script> 
	<?php
				}					
				if(!empty($_GET['respnom'])){
					echo '<div id="textoreg"><samp>>>>>>>' .$_GET['respnom'] .' : Su mensaje fue enviado con exito!<<<<<<</samp></div>';
				}
	?>
				<!-- RECTANGULO DE CONTACTO -->	
				<div id='textcontacto'> 
					Dejenos su consulta: </br></br>
					<!-- FORMULARIO CONTACTO -->
					<form name="fenvio" action="Contacto.php" method="GET">
						<label class='contacto' for="lNombre">*Nombre:</label>
						<input class='contacto' type="text" name="Nombre" placeholder="Usuario" maxlength="10" required>
						<label class='contacto' for="lMail">*E-mail:</label>
						<input class='contacto' id="id_mail2" type="text" size="20" name="mail" placeholder="Ej: nombr@corr.com" maxlength="30" onblur="validarEmail()" required>
						<label class='contacto' for="lTelefono">*Telefono/Celular:</label>
						<input class='contacto' type="text" name="telefono" placeholder="Ej: 0114189054" maxlength="8" onkeypress="return Numeros(event);" required></br>
						<label class='contacto' for="lAusnto">Asunto:</label>
						<input class='contacto' type="text" name="asunto" placeholder="Asunto" maxlength="10"></br>
						<label class='contacto' for="lConsulta">*Consulta:</label></br>
						<textarea class='contacto' name="consulta" rows="10" cols="50" maxlength="255" placeholder="Escriba su consulta aqui..." required></textarea> 						
						<input class='contacto' type="button" value="Enviar" onclick="validarenvio()"></br>
						<label for="obligatorios">Los * son campos obligatorios</label>
					</form>
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