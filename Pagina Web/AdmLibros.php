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
			<!-- ACTIVACION DEL FLAG DE LISTA DE LIBROS -->
			function listarlibros(){
				location.href="AdmLibros.php?flag=lista";
			}
			<!-- ACTIVACION DEL FLAG DE ALTA DE LIBROS -->
			function altalibro(){
				location.href="AdmLibros.php?flag=alta";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBROS -->
			function bajalibro(){
				location.href="AdmLibros.php?flag=baja";
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICAR LIBRO -->
			function modlibro(){
				location.href="AdmLibros.php?flag=mod";
			}
			<!-- ACTIVACION DEL FLAG DE TOP 10 DE LIBROS MAS VENDIDOS -->
			function conlibro(){
				location.href="AdmLibros.php?flag=con";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBRO CON ISBN -->
			function bajaUsuario2(ISBN){
				location.href="AdmLibros.php?flag=baja&ISBN=" + ISBN;
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBRO CON ISBN -->
			function modUsuario2(ISBN){
				location.href="AdmLibros.php?flag=mod&ISBN=" + ISBN;
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR AUTOR -->
			function agregarAutor(){
				location.href="AdmLibros.php?flag=AgAu";
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR IDIOMA -->
			function agregarIdioma(){
				location.href="AdmLibros.php?flag=AgId";
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR ETIQUETA -->
			function agregarEtiqueta(){
				location.href="AdmLibros.php?flag=AgEt";
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE LIBROS -->
			function MensajeResp(Msj){
				alert(Msj);
				location.href="AdmLibros.php";
			}
			<!-- MENSAJE DE ALTA DE AUTRO,IDIOMA Y ETIQUETA -->
			function MensajeResp2(Msj){
				alert(Msj);
				location.href="AdmLibros.php?flag=alta";
			}
			<!-- VENTANA DE DETALLES -->
			function Hojear(){
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function NumerosPunto(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
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
				// ACCION = AGREGAR, INDICA QUE SE DARA DE ALTA UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'agregar'){
					AltaLibro($_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// ACCION = BORRAR, INDICA QUE SE DARA DE BAJA UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'borrar'){
					BajaLibro($_GET['ISBN'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA LA ACTIVACION DE UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'activar'){
					ActivarLibro($_GET['ISBN'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA LA MODIFICACION DE UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'modificar'){
					ModLibro ($_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $_GET['Disp'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp("<?=$AltMsg?>");	
					</script>
	<?php				
				}					
				// ACCION = AGREGARAU, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarAu'){
					AgregarAutor ($_GET['AutorNom'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp2("<?=$AltMsg?>");	
					</script>
	<?php				
				}		
				// ACCION = AGREGARIDIO, INDICA QUE SE DARA DE ALTA UN IDIOMA				
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarIdio'){
					AgregarIdioma ($_GET['IdiomaNom'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp2("<?=$AltMsg?>");	
					</script>
	<?php				
				}	
				// ACCION = AGREGARETIQ, INDICA QUE SE DARA DE ALTA UNA ETIQUETA	
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarEtiq'){
					AgregarEtiqueta ($_GET['EtiquetaNom'], $AltMsg);
	?>
					<script languaje="javascript"> 	
						MensajeResp2("<?=$AltMsg?>");	
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
					<li><a href="AdmLibros.php">Administrar Libros</a></li>
					<li><a href="AdmUsuarios.php">Administrar Usuarios</a></li>
					<li><a href="AdmPedidos.php">Administrar Pedidos</a></li>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM LIBRO -->
			<div id='contenidoadm'>
				<!-- BOTONES DE FUCIONALIDADES -->
				<div id='Admfunciones'>	
					<ul>
						<li><a onclick="listarlibros()">Listar todos los libros</a></li>
						<li><a onclick="altalibro()">Dar de alta un libro</a></li>
						<li><a onclick="modlibro()">Modificar un libro</a></li>
						<li><a onclick="bajalibro()">Dar de baja/activar un libro</a></li>
						<li><a onclick="conlibro()">Libros mas vendidos en un periodo</a></li>
					</ul>
				</div>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
	 				// OPCION LISTAR LIBROS //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
						ConsultaPorDefecto ($res);
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}
						$num1 = mysql_num_rows($res);
						if($num1 == 0){
							echo 'No se encontro ningun libro';
						}
						else{
							// GENERAR TABLA //
							echo '<div id="TablaLibros">';
							echo "<table border='1'>
								<tr>
									<th>ISBN</th>
									<th>Titulo</th>
									<th>Autor</th>
									<th>Cantidad</br>Paginas</th>
									<th>Precio</th>
									<th>Idioma</th>
									<th>Fecha</th>
									<th>Disponibilidad</th>
									<th>Detalle</th>
									<th>Estado</th>
								</tr>";
							$ant = ' ';
							while($row = mysql_fetch_assoc($res)) {
								if ($row['ISBN'] != $ant){
									echo "<tr>";
										echo "<td>", $row['ISBN'], "</td>";
										echo "<td>", $row['Titulo'], "</td>";
										echo "<td>", $row['NombreApellido'], "</td>";
										echo "<td>", $row['CantidadPaginas'], "</td>";
										echo "<td>", $row['Precio'], "</td>";
										echo "<td>", $row['Idioma'], "</td>";
										echo "<td>", $row['Fecha'], "</td>";
										echo "<td>", $row['Disponibilidad'], "</td>";
	?>																	
										<td><input class="botones" type='button' value='Detalle' onclick='Hojear()' /></td>
	<?php
										echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
										if ($row['Estado'] == 1){
	?>
											<td><input class="botones" type='button' value='Modificar' onclick='modUsuario2("<?=$row['ISBN']?>")' /></td>
											<td><input class="botones" type='button' value='Eliminar' onclick='bajaUsuario2("<?=$row['ISBN']?>")' /></td>
	<?php
										}
										else{
	?>					
										<td><input class="botones" type='button' value='Modificar' onclick='modUsuario2("<?=$row['ISBN']?>")' disabled /></td>
										<td><input class="botones" type='button' value='ReActivar' onclick='bajaUsuario2("<?=$row['ISBN']?>")' /></td>
	<?php
										}								
									echo "</tr>";
									$ant = $row['ISNB'];
								}
							}
							echo "</table>";
							echo '</div>';
						}	
						mysql_free_result($res);
					}
					// OPCION ALTA LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'alta'){ 
						// CONSULTAS SELECT //
						ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>			
						<div id='ABMDiv'>
							<!-- FROMULARIO DE ALTA -->
							<form class='FAbm' action="" method="GET">
								<label for="ISNB">ISBN:</label>
								<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required><br>
								<label for="Titulo">Titulo:</label>
								<input type="text" name="Titulo" placeholder="Ej: Titulo" maxlength="45" required><br>
								<label for="Autor">Autor:</label>
	<?php						
								echo '<select class="botones" name="Autor" required>
									<option value="">Seleccione un Autor...</option>';
									while($row = mysql_fetch_assoc($resautor)){	
										echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
									}
								echo '</select>';
	?>			
								<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor()"></br>
	<?php		
								echo '<label for="CantPag">Cantidad Paginas:</label>
								<input type="text" name="CantPag" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required><br>
								<label for="Precio">Precio:</label>
								<input type="text" name="Precio" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required><br>
								<label for="Idioma">Idioma:</label>
								<select class="botones" name="Idioma" required>
								  <option value="">Seleccione un idioma...</option>';
								  while($row = mysql_fetch_assoc($residiomas)){	
										echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
									}
								echo '</select>
								<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma()"></br>
								<label for="Fecha">Fecha:</label>
								<input type="text" name="Fecha" required><br>
								<label for="Etiquetas">Etiquetas:</label>
								<div id="AdminEtiq">';								
								while($row = mysql_fetch_assoc($resetiquetas)){	
									echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
								}
								echo '</div>
								<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta()"></br>
								<input type="hidden" name="accion" value="agregar" required readonly>
								<input class="botones" type="submit" value="Cargar">
							</form>
						</div>';
					}
					// OPCION BAJA LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ISBN -->
							<form class='FAbm' action="" method="GET">
								<label for="ISNB">ISBN del Libro a Borrar/Activar:</label>
								<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required>
								<input type="hidden" name="flag" value="baja" required readonly>
								<input class="botones" class="botones" type="submit" value="Buscar">
							</form>
	<?php	
							if (!empty($_GET['ISBN'])){
								ConsultaLibro ($res, $_GET['ISBN']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ningun libro con ese ISBN';
								}
								else{								
									while($row = mysql_fetch_assoc($res)){
										// FORMULARIO DE BAJA //
										echo '<form class="FAbm" action="" method="GET">
											<label for="Visble">Estado:</label>
											<input type="hidden" name="ISBN" value="', $row['ISBN'], '" required readonly>	
											<input type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
											<label for="Titulo">Titulo:</label>
											<input type="text" name="Titulo" value="', $row['Titulo'], '" size="40"  required readonly><br>
											<label for="Autor">Autor:</label>
											<input type="text" name="Autor" value="', $row['NombreApellido'], '" required readonly><br>
											<label for="CantPag">Cantidad Paginas:</label>
											<input type="text" name="CantPag" value="', $row['CantidadPaginas'], '" required readonly><br>
											<label for="Precio">Precio:</label>
											<input type="text" name="Precio" value="', $row['Precio'], '" required readonly><br>
											<label for="Idioma">Idioma:</label>
											<input type="text" name="Idioma" value="', $row['Idioma'], '" required readonly><br>
											<label for="Fecha">Fecha:</label>
											<input type="text" name="Fecha" value="', $row['Fecha'], '" required readonly><br>';							
											if ($row['Estado'] == 1){ 
												echo '<input type="hidden" name="accion" value="borrar" required readonly>	
												<input class="botones" type="submit" value="Borrar">';
											}
											else{ 
												echo '<input type="hidden" name="accion" value="activar" required readonly>	
												  <input class="botones" type="submit" value="Activar">';
											}
										echo '</form>';
									}	
								}
							}
						echo '</div>';
					}
					// OPCION MODIFICAR LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){				
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ISBN -->
							<form class='FAbm' action="" method="GET">
								<label for="ISNB">ISBN del Libro modificar:</label>
								<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required>
								<input type="hidden" name="flag" value="mod" required readonly>
								<input class="botones" class="botones" type="submit" value="Buscar">
							</form>
	<?php		
							if (!empty($_GET['ISBN'])){
								ConsultaLibro ($res, $_GET['ISBN']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ningun libro con ese ISBN';
								}
								else{		
									// CONSULTAS SELECT //
									ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											// FORMULARIO DE MODIFICACION //
											echo '<form class="FAbm" action="" method="GET">	
												<label for="Visble">Estado:</label>
												<input type="hidden" name="ISBN" value="', $row['ISBN'], '" required readonly>	
												<input type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>';
												echo'<label for="Titulo">Titulo:</label>
												<input type="text" name="Titulo" value="', $row['Titulo'], '" size="40" placeholder="Ej: Titulo" maxlength="45" required ><br>
												<label for="Autor">Autor:</label>
												<select class="botones" name="Autor" required>
													<option value="' .$row['NombreApellido']. '">' .$row['NombreApellido']. '</option>';
													while($row2 = mysql_fetch_assoc($resautor)){	
														echo '<option value="', $row2['Autor'], '">', $row2['Autor'], '</option>';
													}
												echo '</select>												
												<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor()"></br>
												<label for="CantPag">Cantidad Paginas:</label>
												<input type="text" name="CantPag" value="', $row['CantidadPaginas'], '" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required ><br>
												<label for="Precio">Precio:</label>
												<input type="text" name="Precio" value="', $row['Precio'], '" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required ><br>
												<label for="Idioma">Idioma:</label>
												<select class="botones" name="Idioma" required>
													<option value="' .$row['Idioma']. '">' .$row['Idioma']. '</option>';
													while($row3 = mysql_fetch_assoc($residiomas)){	
														echo '<option value="', $row3['Idioma'], '">', $row3['Idioma'], '</option>';
													}
												echo '</select>
												<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma()"></br>			
												<label for="Fecha">Fecha:</label>
												<input type="text" name="Fecha" value="', $row['Fecha'], '" required ><br>
												<label for="Dis">Disponibilidad:</label>
												<select class="botones" name="Disp" required>
													<option value="' .$row['Disponibilidad']. '">' .$row['Disponibilidad']. '</option>';
													while($row6 = mysql_fetch_assoc($resdisp)){	
															echo '<option value="', $row6['Disponibilidad'], '">', $row6['Disponibilidad'], '</option>';
													}
												echo '</select></br>';
												echo '<label for="Etiquetas">Etiquetas:</label>
												<div id="AdminEtiq">';
												// CONSULTA DE BUSQUEDA DE ETIQUETAS PARA UN ISBN //
												BuscarEtiq ($row['ISBN'], $LibEtiq);
												$num = mysql_num_rows($LibEtiq);
												while($row4 = mysql_fetch_assoc($resetiquetas)){	
													$entro = false;
													if($num != 0){
														mysql_data_seek ($LibEtiq, 0);
													}												
													while($row5 = mysql_fetch_assoc($LibEtiq)){	
														if ($row4['Etiqueta'] == $row5['Descripcion']){
															echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '" checked="checked" >', $row4['Etiqueta'];
															$entro = true;
														}
													}
													if (!$entro){
														echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '">', $row4['Etiqueta'];
													}
												}
												echo '</div>
												<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta()"></br>';
												if ($row['Estado'] == 1){ 
													echo '<input type="hidden" name="accion" value="modificar" required readonly>	
													<input class="botones" type="submit" value="Modificar">';
												}
												else{ 
													echo '<input class="botones" type="submit" value="Modificar" disabled>';
												}
											echo '</form>';
										}
										else{
											echo 'El libro: ISBN = ' .$_GET['ISBN'] .', Titulo = ' . $row['Titulo'] .'; no es un libro activo y sus datos no son modificables';
										}
									}
								}
							}
						echo '</div>';							
					}
					// OPCION TOP 10 LIBROS MAS VENDIDOS EN UN PERIODO //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'con'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE INGRESO PERIODO -->
							<form class='FAbm' action="" method="GET">
								<label for="periodo">Ingrese periodo de tiempo:</label></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Inicial:</label>
								<input type="date" name="Fini" required></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Final:</label>
								<input type="date" name="Ffin" required></br>
								<input type="hidden" name="flag" value="con" required readonly>
								<input class="botones" type="submit" value="Buscar">
							</form>
	<?php	
							if (!empty($_GET['Fini']) && !empty($_GET['Ffin'])){
								// CONSULTA DE BUSQUEDA DEL TOP 10 //
								LibroPeriodo ($res, $_GET['Fini'], $_GET['Ffin']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se vendio ningun libro en dicho periodo temporal';
								}
								else{
									// GENERADOR DE TABLA RESULTANTE //
									echo '<div id="TablaTopLibros">';
									echo "<table border='1'>
										<tr>
											<th>ISBN</th>
											<th>Titulo</th>
											<th>Autor</th>
											<th>DNI</th>
											<th>Cliente</th>
											<th>FechaPedido</th>
										</tr>";
									$ant = ' ';
									while($row = mysql_fetch_assoc($res)) {
										if ($row['ISBN'] != $ant){
											echo "<tr>";
												echo "<td>", $row['ISBN'], "</td>";
												echo "<td>", $row['Titulo'], "</td>";
												echo "<td>", $row['Autor'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
												echo "<td>", $row['Cliente'], "</td>";
												echo "<td>", $row['FechaPedido'], "</td>";		
											echo "</tr>";
											$ant = $row['ISNB'];
										}
									}
									echo "</table>
									</div>";
								}
							}		
						echo '</div>';
					}
					// OPCION ALTA AUTOR //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgAu'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA AUTOR -->
							<form class='FAbm' action="" method="GET">
								<label for="Nombre">Nombre y Apellido del Autor:</label></br>
								<input type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
								<input type="hidden" name="accion" value="AgregarAu" required readonly>
								<input class="botones" type="submit" value="Agregar">
							</form>
						</div>
	<?php	
					}
					// OPCION ALTA IDIOMA //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgId'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA IDIOMA -->
							<form class='FAbm' action="" method="GET">
								<label for="Nombre">Descripcion del Idioma:</label></br>
								<input type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input type="hidden" name="accion" value="AgregarIdio" required readonly>
								<input class="botones" type="submit" value="Agregar">
							</form>
						</div>	
	<?php		
					}
					// OPCION ALTA ETIQUETA //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgEt'){
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA ETIQUETA -->
							<form class='FAbm' action="" method="GET">							
								<label for="Nombre">Descripcion de la Etiquta:</label></br>
								<input type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input type="hidden" name="accion" value="AgregarEtiq" required readonly>
								<input class="botones" type="submit" value="Agregar">							
							</form>
						</div>
	<?php										
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