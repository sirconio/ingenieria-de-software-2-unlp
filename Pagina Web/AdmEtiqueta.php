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
			<!-- ACTIVACION DEL FLAG DE BAJA DE ETIQUETA CON ID -->
			function bajaEtiqueta(ID){				
				if (confirm("Desea dar de baja este etiqueta?")){
					location.href="AdmEtiqueta.php?accion=Borrar&ID=" + ID;
				}
				else{
					alert("La operacion no se realizo");
				}
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICACION DE UNA ETIQUETA -->
			function modEtiqueta(EtiquetaNomMod){
				if (confirm("Desea modificar este etiqueta?")){
					location.href="AdmEtiqueta.php?flag=ABM&EtiquetaNomMod=" + EtiquetaNomMod;
				}
				else{
					alert("La operacion no se realizo");
				}
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE UNA ETIQUETA -->
			function MensajeResp(Msj){
				alert(Msj);
				location.href="AdmEtiqueta.php?flag=lista";
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function LetrasEspacio(e) {
				tecla = (document.all) ? e.keyCode : e.which;
				if ((tecla==8) || (tecla == 32)) return true;
				patron =/[A-Za-z]/;
				te = String.fromCharCode(tecla);
				return patron.test(te);
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
					AgregarEtiqueta ($_GET['EtiquetaNom'], $AltMsg);		
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php		
				}
				// ACCION = BORRAR, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Borrar'){
					BajaEtiqueta ($_GET['ID'], $AltMsg);		
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php		
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'Modificar'){
					ModEtiqueta ($_GET['ID'], $_GET['EtiquetaNom'], $AltMsg);		
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
					<li><a href="AdmEtiqueta.php?flag=lista">Listar todas los etiquetas</a></li>
					<li><a href="AdmEtiqueta.php?flag=ABM">ABM etiquetas</a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM ETIQUETA -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
	 				// OPCION LISTAR ETIQUETA //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
						echo '<div id="textoadmped"><samp>Listado de todos las etiquetas:</samp></div>';
						ConsultaEtiqueta ($restam);
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}
						$num1 = mysql_num_rows($restam);
						if($num1 == 0){
							echo 'No se encontro ning&uacuten etiqueta';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							ConsultaEtiquetaPag ($res, ($NroPag-1));
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ning&uacuten etiqueta';
							}
							else{	
								// GENERAR TABLA //
								echo '<div id="TablaLibros">';
								echo 'Pagina Numero: ' .$NroPag;
								echo "<table border='1'>
									<tr>
										<th>ID</th>
										<th>Etiqueta</th>
									</tr>";
								$ant = ' ';
								while($row = mysql_fetch_assoc($res)) {
									if ($row['ID'] != $ant){
										echo "<tr>";
											echo "<td>", $row['ID'], "</td>";
											echo "<td>", $row['Etiqueta'], "</td>";
		?>																	
											<td><input class="botones" type='button' value='Modificar' onclick='modEtiqueta("<?=$row['Etiqueta']?>")' /></td>
											<td><input class="botones" type='button' value='Eliminar' onclick='bajaEtiqueta("<?=$row['ID']?>")' /></td>
		<?php							
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
								echo '<li><a href="AdmEtiqueta.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';		
						mysql_free_result($res);
					}
					// OPCION ABM AUTOR //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'ABM'){ 
						echo '<div id="textoadmped"><samp>ABM de etiquetas:</samp></div>';
	?>			
						<div id='ABMAlta'>
	<?php
							echo '<div id="textoadmped"><samp>Alta de etiquetas:</samp></div></br></br></br>';
							echo '<form class="FAbm" action="" method="GET">
									<label for="Nombre">Descripcion de la Etiqueta:</label>
									<input type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="accion" value="Agregar" required readonly>
									<input class="botones" class="botones" type="submit" value="Agregar">
							</form>';		
						echo '</div>';
	?>			
						<div id='ABMBaja'>
	<?php
							echo '<div id="textoadmped"><samp>Baja de etiquetas:</samp></div></br></br></br>';
							echo '<form class="FAbm" action="" method="GET">
									<label for="Nombre">Descripcion de la Etiqueta ha borrar:</label></br>
									<input type="text" name="EtiquetaNomBorr" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" class="botones" type="submit" value="Buscar">
							</form>';		
						if (!empty($_GET['EtiquetaNomBorr'])){ 
							ConsultaEtiq ($res, $_GET['EtiquetaNomBorr']);
							if(!$res) {
								$message= 'Consulta invalida: ' .mysql_error() ."\n";
								die($message);
							}
							$num1 = mysql_num_rows($res);
							if($num1 == 0){
								echo 'No se encontro ninguna Etiqueta con esa Descripcion';
							}
							else{	
								while($row = mysql_fetch_assoc($res)){
									echo '<form class="FAbm" action="" method="GET">
										<label for="Nombre">Identificador:</label>
										<input type="text" name="ID" value="', $row['ID'], '" required readonly></br>
										<label for="Nombre">Descripcion de la Etiqueta:</label>
										<input type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Etiqueta'], '" required readonly></br>							
										<input type="hidden" name="accion" value="Borrar" required readonly>';
	?>
													<input class="botones" type="button" value="Borrar" onclick='bajaEtiqueta ("<?=$row['ID']?>")' />
	<?php							
									echo '</form>';	
								}	
							}
						}	
						echo '</div>';	
	?>			
						<div id='ABMMod'>
	<?php
							echo '<div id="textoadmped"><samp>Modificacion de autores:</samp></div></br></br></br>';
							echo '<form class="FAbm" action="" method="GET">
									<label for="Nombre">Descripcion de la Etiqueta ha modificar:</label></br>
									<input type="text" name="EtiquetaNomMod" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
									<input type="hidden" name="flag" value="ABM" required readonly>
									<input class="botones" class="botones" type="submit" value="Buscar">
							</form>';	
							if (!empty($_GET['EtiquetaNomMod'])){ 
								ConsultaEtiq ($res, $_GET['EtiquetaNomMod']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ninguna Etiqueta con esa Descripcion';
								}
								else{	
									while($row = mysql_fetch_assoc($res)){
										echo '<form class="FAbm" action="" method="GET">
											<label for="Nombre">Identificador:</label>
											<input type="text" name="ID" value="', $row['ID'], '" required readonly></br>
											<label for="Nombre">Descripcion de la Etiqueta:</label>
											<input type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" value="', $row['Etiqueta'], '" required></br>
											<input type="hidden" name="accion" value="Modificar" required readonly>
											<input class="botones" class="botones" type="submit" value="Modificar">
										</form>';	
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