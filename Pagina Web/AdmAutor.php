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
			<!-- ACTIVACION DEL FLAG DE BAJA DE AUTOR CON ID -->
			function bajaAutor(ID, Autor){				
				if (confirm("Desea dar de baja este autor?")){
					location.href="AdmAutor.php?accion=Borrar&ID=" + ID + "&AutorNomBorr=" + Autor;
				}
			}
			<!-- ACTIVACION DEL FLAG DE ACTIVAR AUTOR CON ID -->
			function activarAutor(ID, Autor){				
				if (confirm("Desea reactivar este autor?")){
					location.href="AdmAutor.php?accion=Activar&ID=" + ID + "&AutorNomBorr=" + Autor;
				}
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICACION DE AUTOR -->
			function modAutor(AutorNomMod){			
				location.href="AdmAutor.php?flag=ABM&AutorNomMod=" + AutorNomMod;				
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE AUTOR SATISFACTORIA-->
			function MensajeResp(Msj){				
				location.href="AdmAutor.php?flag=lista&respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA ALTA SOBRE AUTOR ERRONEA -->
			function AltaError(Msj){
				alert(Msj);
				location.href="AdmAutor.php?flag=ABM";
			}
			<!-- MENSAJE DE RESPUESTA MODIFICACION SOBRE AUTOR ERRONEA -->
			function ModError(Msj, Autor){
				alert(Msj);
				location.href="AdmAutor.php?flag=ABM&AutorNomMod="+Autor;
			}
			<!-- MENSAJE DE RESPUESTA BAJA/ACTIVAR SOBRE AUTOR ERRONEA -->
			function Error(Msj, Autor){
				alert(Msj);
				location.href="AdmAutor.php?flag=ABM&AutorNomBorr="+Autor;
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function LetrasEspacio(e) {
				tecla = (document.all) ? e.keyCode : e.which;
				if ((tecla==8) || (tecla == 32)) return true;
				patron =/[A-Za-z]/;
				te = String.fromCharCode(tecla);
				return patron.test(te);
			}
			function validarbus (){
				if (document.fbus.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbus.BusRap.focus()
				   return 0;
				}			
				document.fbus.submit(); 		
			}			
			function validarmod (){
				if (document.fmod.AutorNom.value.length==0){
				   alert("Tiene que completar el campo de Nombre y Apellido del autor")
				   document.fmod.AutorNom.focus()
				   return 0;
				}			
				document.fmod.submit(); 		
			}					
			function validarbusmod (){
				if (document.fbusmod.AutorNomMod.value.length==0){
				   alert("Tiene que completar el campo de Nombre y Apellido del autor ha modificar")
				   document.fbusmod.AutorNomMod.focus()
				   return 0;
				}			
				document.fbusmod.submit(); 		
			}					
			function validarbusbaja (){
				if (document.fbusbaja.AutorNomBorr.value.length==0){
				   alert("Tiene que completar el campo de Nombre y Apellido del autor ha borrar")
				   document.fbusbaja.AutorNomBorr.focus()
				   return 0;
				}			
				document.fbusbaja.submit(); 		
			}			
			function validaralta (){
				if (document.falta.AutorNom.value.length==0){
				   alert("Tiene que completar el campo de Nombre y Apellido del autor")
				   document.falta.AutorNom.focus()
				   return 0;
				}			
				document.falta.submit(); 		
			}
			<!-- FIN VALIDACIONES DE CAMPOS -->
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
				// FLAG = TRUE, INDICA QUE SE PRESIONO SOBRE EL BOTON CERRAR SESION
				if (!empty($_GET['flag']) && $_GET['flag'] == 'true'){
					CerrarSesion();
				}
				// ACCION = AGREGAR, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Agregar'){
					$Comp = false;
					AgregarAutor ($_GET['AutorNom'], $AltMsg, $Comp);		
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
							AltaError("<?=$AltMsg?>");	
						</script>
	<?php					
					}
				}
				// ACCION = BORRAR, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Borrar'){
					$Comp = false;
					BajaAutor ($_GET['ID'], $AltMsg, $Comp);		
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
							Error("<?=$AltMsg?>", "<?=$_GET['AutorNomBorr']?>");
						</script>
	<?php							
					}
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA LA ACTIVACION DE UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Activar'){
					$Comp = false;
					ActivarAutor ($_GET['ID'], $AltMsg, $Comp);		
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
							Error("<?=$AltMsg?>", "<?=$_GET['AutorNomBorr']?>");	
						</script>
	<?php						
					}		
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Modificar'){
					$Comp = false;
					ModAutor ($_GET['ID'], $_GET['AutorNom'], $AltMsg, $Comp);		
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
							ModError("<?=$AltMsg?>", "<?=$_GET['AutorNomMod']?>");	
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
							echo ' logeado - Categor&iacutea:';
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
					<li><a href="AdmAutor.php?flag=lista">Listar todos los autores</a></li>
					<li><a href="AdmAutor.php?flag=ABM">ABM autores</a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM AUTORES -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
	 				// OPCION LISTAR AUTORES //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){					
						if(!empty($_GET['respmsg'])){
							echo '<div id="textoadmped"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Listado de todos los Autores:</samp></div>';
						}
						else{
								echo '<div id="textoadmped"><samp>Listado de todos los Autores:</samp></div>';
						}
						echo '<div id="barrabusquedaABM" action="Busqueda.php" method="GET">
						<form name="fbus">
							<input size="40" type="text" name="BusRap" placeholder="Autor" required>
							<input type="hidden" name="flag" value="lista" required readonly>							
							<input id="BusRapBotABM" type="button" value="Buscar" onclick="validarbus()">
						</form>
						</div>';
						echo '<div id="TablaLibros">';
						if (!empty($_GET['BusRap'])){
							ConsultaAutoresBus ($restam, $_GET['BusRap']);
						}
						else{
							ConsultaAutores ($restam);
						}
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}
						$num1 = mysql_num_rows($restam);
						if($num1 == 0){
							echo 'No se encontro ning&uacuten autor';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							if (!empty($_GET['BusRap'])){
								ConsultaAutoresPagBus ($res, ($NroPag-1), $_GET['BusRap']);
							}
							else{
								ConsultaAutoresPag ($res, ($NroPag-1));
							}
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ning&uacuten Autor';
							}
							else{	
								// GENERAR TABLA //								
								echo 'Pagina Numero: ' .$NroPag;
								echo "<table border='1'>
									<tr>
										<th>Autor</th>
										<th>Estado</th>										
									</tr>";
								$ant = ' ';
								while($row = mysql_fetch_assoc($res)) {
									if ($row['ID'] != $ant){
										echo "<tr>";											
											echo "<td>", $row['Autor'], "</td>";
											echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
											if ($row['Estado'] == 1){											
		?>					
												<td><input class="botones" type='button' value='Modificar' onclick='modAutor("<?=$row['Autor']?>")' /></td>
												<td><input class="botones" type='button' value='Eliminar' onclick='bajaAutor("<?=$row['ID']?>", "<?=$row['Autor']?>")' /></td>
		<?php								
											}
											else{
		?>									
												<td><input class="botones" type='button' value='ReActivar' onclick='activarAutor("<?=$row['ID']?>", "<?=$row['Autor']?>")' /></td>
		<?php
											}
										echo "</tr>";
										$ant = $row['ID'];
									}
								}
								echo "</table>";
							}	
						}	
						echo '</div>';
						echo '<div id="PaginasPed">';
							$pag = 1;
							echo 'P&aacuteginas: ';
							while ( $num1 > 0 ) {
								echo '<li><a href="AdmAutor.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';		
					}
					// OPCION ABM AUTOR //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ABM'){ 
						echo '<div id="textoadmped"><samp>ABM de autores:</samp></div>';
	?>			
						<div id='ABMAlta'>
	<?php
							echo '<div id="textoadmped"><samp>Alta de autores:</samp></div></br></br>';
							echo '<form class="FAbm" name="falta" action="" method="GET">
									<label for="Nombre">Nombre y Apellido del Autor:</label>
									<input type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="accion" value="Agregar" required readonly>
									<input class="botones" type="button" value="Agregar" onclick="validaralta()">
							</form>';		
						echo '</div>';
	?>			
						<div id='ABMBaja'>
	<?php
							echo '<div id="textoadmped"><samp>Baja de autores:</samp></div></br></br>';
							echo '<form class="FAbm" name="fbusbaja" action="" method="GET">
									<label for="Nombre">Nombre y Apellido del Autor ha borrar:</label></br>
									<input type="text" name="AutorNomBorr" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" type="button" value="Buscar" onclick="validarbusbaja()">		
							</form>';		
						if (!empty($_GET['AutorNomBorr'])){ 
							ConsultaAutor ($res, $_GET['AutorNomBorr']);
							if(!$res) {
								$message= 'Consulta invalida: ' .mysql_error() ."\n";
								die($message);
							}
							$num1 = mysql_num_rows($res);
							if($num1 == 0){
								echo 'No se encontro ning&uacuten autor con ese Nombre';
							}
							else{	
								while($row = mysql_fetch_assoc($res)){
									echo '<form class="FAbm" action="" method="GET">									
										<input type="hidden" name="ID" value="', $row['ID'], '" required readonly>
										<label class="Reginput" for="Visble">Estado:</label>		
										<input class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
										<label for="Nombre">Nombre y Apellido del Autor:</label>
										<input type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Autor'], '" required readonly></br>';						
										if ($row['Estado'] == 1){ 
											echo '<input type="hidden" name="accion" value="Borrar" required readonly>';	
		?>
											<input class="botones" type="button" value="Borrar" onclick='bajaAutor("<?=$row['ID']?>", "<?=$_GET['AutorNomBorr']?>")' />
		<?php												
										}
										else{ 
											echo '<input type="hidden" name="accion" value="Activar" required readonly>';
		?>
											<input class="botones" type="button" value="Activar" onclick='activarAutor("<?=$row['ID']?>", "<?=$_GET['AutorNomBorr']?>")' />
		<?php	
										}	
										echo '</form>';	
								}	
							}
						}	
						echo '</div>';	
	?>			
						<div id='ABMMod'>
	<?php
							echo '<div id="textoadmped"><samp>Modificacion de autores:</samp></div></br></br></br>';
							echo '<form class="FAbm" name="fbusmod" action="" method="GET">
									<label for="Nombre">Nombre y Apellido del Autor ha modificar:</label></br>
									<input type="text" name="AutorNomMod" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" type="button" value="Buscar" onclick="validarbusmod()">
							</form>';	
							if (!empty($_GET['AutorNomMod'])){ 
								ConsultaAutor ($res, $_GET['AutorNomMod']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ning&uacuten autor con ese Nombre';
								}
								else{	
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											echo '<form class="FAbm"  name="fmod" action="" method="GET">										
												<input type="hidden" name="ID" value="', $row['ID'], '" required readonly>																					
												<input hidden class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
												<label for="Nombre">Nombre y Apellido del Autor:</label>
												<input type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Autor'], '" required></br>
												<input type="hidden" name="accion" value="Modificar" required readonly>
												<input type="hidden" name="AutorNomMod" value="' .$_GET['AutorNomMod'] .'" required readonly>
												<input class="botones" type="button" value="Modificar" onclick="validarmod()">
											</form>';
										}
										else{
											echo 'El autor: Nombre = ' . $row['Autor'] .'; no es un autor activo y sus datos no son modificables';
										}										
									}	
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