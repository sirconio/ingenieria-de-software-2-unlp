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
			<!-- ACTIVACION DEL FLAG DE BAJA DE IDIOMA CON ID -->
			function bajaIdioma(ID){				
				if (confirm("Desea dar de baja este idioma?")){
					location.href="AdmIdioma.php?accion=Borrar&ID=" + ID;
				}
			}
			<!-- ACTIVACION DEL FLAG DE ACTIVAR IDIOMA CON ID -->
			function activarIdioma(ID){				
				if (confirm("Desea reactivar este idioma?")){
					location.href="AdmIdioma.php?accion=Activar&ID=" + ID;
				}
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICACION DE IDIOMA -->
			function modIdioma(IdiomaNomMod){				
				location.href="AdmIdioma.php?flag=ABM&IdiomaNomMod=" + IdiomaNomMod;				
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE IDIOMA -->
			function MensajeResp(Msj){			
				location.href="AdmIdioma.php?flag=lista&respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA ALTA SOBRE IDIOMA ERRONEA -->
			function AltaError(Msj){
				alert(Msj);
				location.href="AdmIdioma.php?flag=ABM";
			}
			<!-- MENSAJE DE RESPUESTA MODIFICACION SOBRE IDIOMA ERRONEA -->
			function ModError(Msj, Idio){
				alert(Msj);
				location.href="AdmIdioma.php?flag=ABM&IdiomaNomMod="+Idio;
			}
			<!-- MENSAJE DE RESPUESTA BAJA/ACTIVAR SOBRE IDIOMA ERRONEA -->
			function Error(Msj, Idio){
				alert(Msj);
				location.href="AdmIdioma.php?flag=ABM&IdiomaNomBorr="+Idio;
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
				if (document.fmod.IdiomaNom.value.length==0){
				   alert("Tiene que completar el campo de Descripcion del idioma")
				   document.fmod.IdiomaNom.focus()
				   return 0;
				}			
				document.fmod.submit(); 		
			}					
			function validarbusmod (){
				if (document.fbusmod.IdiomaNomMod.value.length==0){
				   alert("Tiene que completar el campo de Descripcion del idioma ha modificar")
				   document.fbusmod.IdiomaNomMod.focus()
				   return 0;
				}			
				document.fbusmod.submit(); 		
			}					
			function validarbusbaja (){
				if (document.fbusbaja.IdiomaNomBorr.value.length==0){
				   alert("Tiene que completar el campo de Descripcion del idioma ha borrar")
				   document.fbusbaja.IdiomaNomBorr.focus()
				   return 0;
				}			
				document.fbusbaja.submit(); 		
			}			
			function validaralta (){
				if (document.falta.IdiomaNom.value.length==0){
				   alert("Tiene que completar el campo de Descripcion del idioma")
				   document.falta.IdiomaNom.focus()
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
				// ACCION = AGREGAR, INDICA QUE SE DARA DE ALTA UN IDIOMA
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Agregar'){
					$Comp = false;
					AgregarIdioma ($_GET['IdiomaNom'], $AltMsg, $Comp);		
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
				// ACCION = BORRAR, INDICA QUE SE DARA DE ALTA UN IDIOMA
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Borrar'){
					$Comp = false;
					BajaIdioma ($_GET['ID'], $AltMsg, $Comp);		
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
							Error("<?=$AltMsg?>", "<?=$_GET['IdiomaNomBorr']?>");
						</script>
	<?php						
					}	
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA LA ACTIVACION DE UN IDIOMA
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Activar'){
					$Comp = false;
					ActivarIdioma ($_GET['ID'], $AltMsg, $Comp);		
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
							Error("<?=$AltMsg?>", "<?=$_GET['IdiomaNomBorr']?>");
						</script>
	<?php						
					}	
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA DE ALTA UN IDIOMA
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Modificar'){
					$Comp = false;
					ModIdioma ($_GET['ID'], $_GET['IdiomaNom'], $AltMsg, $Comp);	
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
							ModError("<?=$AltMsg?>", "<?=$_GET['IdiomaNomMod']?>");	
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
					<li><a href="AdmIdioma.php?flag=lista">Listar todos los idiomas</a></li>
					<li><a href="AdmIdioma.php?flag=ABM">ABM idiomas</a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM IDIOMA -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
	 				// OPCION LISTAR IDIOMA //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){				
						if(!empty($_GET['respmsg'])){
							echo '<div id="textoadmped"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Listado de todos los idiomas:</samp></div>';
						}
						else{
							echo '<div id="textoadmped"><samp>Listado de todos los idiomas:</samp></div>';
						}
						echo '<div id="barrabusquedaABM" action="Busqueda.php" method="GET">
						<form name="fbus">
							<input size="40" type="text" name="BusRap" placeholder="Idioma" required>
							<input type="hidden" name="flag" value="lista" required readonly>							
							<input id="BusRapBotABM" type="button" value="Buscar" onclick="validarbus()">
						</form>
						</div>';
						echo '<div id="TablaLibros">';
						if (!empty($_GET['BusRap'])){
							ConsultaIdiomaBus ($restam, $_GET['BusRap']);
						}
						else{
							ConsultaIdioma ($restam);
						}	
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}
						$num1 = mysql_num_rows($restam);
						if($num1 == 0){
							echo 'No se encontro ning&uacuten idioma';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							if (!empty($_GET['BusRap'])){
								ConsultaIdiomaPagBus ($res, ($NroPag-1), $_GET['BusRap']);
							}
							else{
								ConsultaIdiomaPag ($res, ($NroPag-1));
							}
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ning&uacuten idioma';
							}
							else{	
								// GENERAR TABLA //								
								echo 'Pagina Numero: ' .$NroPag;
								echo "<table border='1'>
									<tr>										
										<th>Idioma</th>
										<th>Estado</th>
									</tr>";
								$ant = ' ';
								while($row = mysql_fetch_assoc($res)) {
									if ($row['ID'] != $ant){
										echo "<tr>";											
											echo "<td>", $row['Idioma'], "</td>";
											echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
											if ($row['Estado'] == 1){											
		?>
												<td><input class="botones" type='button' value='Modificar' onclick='modIdioma("<?=$row['Idioma']?>")' /></td>
												<td><input class="botones" type='button' value='Eliminar' onclick='bajaIdioma("<?=$row['ID']?>", "<?=$row['Idioma']?>")' /></td>
		<?php									
											}
											else{
		?>
												<td><input class="botones" type='button' value='ReActivar' onclick='activarIdioma("<?=$row['ID']?>", "<?=$row['Idioma']?>")' /></td>
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
								echo '<li><a href="AdmIdioma.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';		
					}
					// OPCION ABM IDIOMA //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ABM'){ 
						echo '<div id="textoadmped"><samp>ABM de idiomas:</samp></div>';
	?>			
						<div id='ABMAlta'>
	<?php
							echo '<div id="textoadmped"><samp>Alta de idiomas:</samp></div></br></br>';
							echo '<form class="FAbm" name="falta" action="" method="GET">
									<label for="Nombre">Descripcion del idioma</label>
									<input type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="accion" value="Agregar" required readonly>
									<input class="botones" type="button" value="Agregar" onclick="validaralta()">
							</form>';		
						echo '</div>';
	?>			
						<div id='ABMBaja'>
	<?php
							echo '<div id="textoadmped"><samp>Baja de idiomas:</samp></div></br></br>';
							echo '<form class="FAbm" name="fbusbaja" action="" method="GET">
									<label for="Nombre">Descripcion del Idioma ha borrar:</label></br>
									<input type="text" name="IdiomaNomBorr" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" type="button" value="Buscar" onclick="validarbusbaja()">
							</form>';		
						if (!empty($_GET['IdiomaNomBorr'])){ 
							ConsultaIdio ($res, $_GET['IdiomaNomBorr']);
							if(!$res) {
								$message= 'Consulta invalida: ' .mysql_error() ."\n";
								die($message);
							}
							$num1 = mysql_num_rows($res);
							if($num1 == 0){
								echo 'No se encontro ning&uacuten idioma con esa Descripcion';
							}
							else{	
								while($row = mysql_fetch_assoc($res)){
									echo '<form class="FAbm" action="" method="GET">
										<input type="hidden" name="ID" value="', $row['ID'], '" required readonly>
										<label class="Reginput" for="Visble">Estado:</label>		
										<input class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
										<label for="Nombre">Descripcion del Idioma:</label>
										<input type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Idioma'], '" required readonly></br>';													
										if ($row['Estado'] == 1){ 
											echo '<input type="hidden" name="accion" value="Borrar" required readonly>';	
	?>
											<input class="botones" type="button" value="Borrar" onclick='bajaIdioma("<?=$row['ID']?>", "<?=$_GET['IdiomaNomBorr']?>")' />
	<?php												
										}
										else{ 
											echo '<input type="hidden" name="accion" value="Activar" required readonly>';
	?>
											<input class="botones" type="button" value="Activar" onclick='activarIdioma("<?=$row['ID']?>", "<?=$_GET['IdiomaNomBorr']?>")' />
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
							echo '<div id="textoadmped"><samp>Modificacion de idiomas:</samp></div></br></br></br>';
							echo '<form class="FAbm" name="fbusmod" action="" method="GET">
									<label for="Nombre">Descripcion del Idioma ha modificar:</label></br>
									<input type="text" name="IdiomaNomMod" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" type="button" value="Buscar" onclick="validarbusmod()">
							</form>';	
							if (!empty($_GET['IdiomaNomMod'])){ 
								ConsultaIdio ($res, $_GET['IdiomaNomMod']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ning&uacuten idioma con esa Descripcion';
								}
								else{	
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											echo '<form class="FAbm"  name="fmod" action="" method="GET">											
												<input type="hidden" name="ID" value="', $row['ID'], '" required readonly>
												<label class="Reginput" for="Visble">Estado:</label>		
												<input class="Reginput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
												<label for="Nombre">Descripcion del Idioma:</label>
												<input type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Idioma'], '" required></br>
												<input type="hidden" name="accion" value="Modificar" required readonly>
												<input type="hidden" name="IdiomaNomMod" value="' .$_GET['IdiomaNomMod'] .'" required readonly>												
												<input class="botones" type="button" value="Modificar" onclick="validarmod()">
											</form>';
										}
										else{
											echo 'El idioma: Descripcion = ' . $row['Idioma'] .'; no es un idioma activo y sus datos no son modificables';
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
			<samp>Copyright &copy 2014 CookBook � &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>