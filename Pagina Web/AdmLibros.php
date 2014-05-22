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
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function listarlibros(){
				location.href="AdmLibros.php?flag=lista";
			}
			function altalibro(){
				location.href="AdmLibros.php?flag=alta";
			}
			function bajalibro(){
				location.href="AdmLibros.php?flag=baja";
			}
			function modlibro(){
				location.href="AdmLibros.php?flag=mod";
			}
			function conlibro(){
				location.href="AdmLibros.php?flag=con";
			}
			function bajaUsuario2(ISBN){
				location.href="AdmLibros.php?flag=baja&ISBN=" + ISBN;
			}
			function modUsuario2(ISBN){
				location.href="AdmLibros.php?flag=mod&ISBN=" + ISBN;
			}
			function agregarAutor(){
				location.href="AdmLibros.php?flag=AgAu";
			}
			function agregarIdioma(){
				location.href="AdmLibros.php?flag=AgId";
			}
			function agregarEtiqueta(){
				location.href="AdmLibros.php?flag=AgEt";
			}
			function MensajeAlta(Msj){
				alert(Msj);
				location.href="AdmLibros.php";
			}
			function MensajeAlta2(Msj){
				alert(Msj);
				location.href="AdmLibros.php?flag=alta";
			}
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
			function Hojear(){
			}
		</script>
	</head>
	<body>
		<div id='cabecera'>
			<div id='imglogo'><img src="Logo1.gif" width="85%" height="475%"> </div>
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
					if (!empty($_GET['accion']) && $_GET['accion'] == 'agregar'){
						AltaLibro($_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta("<?=$AltMsg?>");	
						</script>
				<?php
					}
					if (!empty($_GET['accion']) && $_GET['accion'] == 'borrar'){
						BajaLibro($_GET['ISBN'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta("<?=$AltMsg?>");	
						</script>
				<?php
					}
					if (!empty($_GET['accion']) && $_GET['accion'] == 'activar'){
						ActivarLibro($_GET['ISBN'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta("<?=$AltMsg?>");	
						</script>
				<?php
					}
					if (!empty($_GET['accion']) && $_GET['accion'] == 'modificar'){
						ModLibro ($_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $_GET['Disp'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta("<?=$AltMsg?>");	
						</script>
				<?php				
					}					
					if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarAu'){
						AgregarAutor ($_GET['AutorNom'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta2("<?=$AltMsg?>");	
						</script>
				<?php				
					}				
					if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarIdio'){
						AgregarIdioma ($_GET['IdiomaNom'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta2("<?=$AltMsg?>");	
						</script>
				<?php				
					}	
					if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarEtiq'){
						AgregarEtiqueta ($_GET['EtiquetaNom'], $AltMsg);
				?>
						<script languaje="javascript"> 	
							MensajeAlta2("<?=$AltMsg?>");	
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
						}
					}
					echo '</ul>';
				?>
			</div>
		</div>
		<div id='cuerpo'>
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="AdmLibros.php">Administrar Libros</a></li>
					<li><a href="AdmUsuarios.php">Administrar Usuarios</a></li>
					<li><a href="AdmPedidos.php">Administrar Pedidos</a></li>
					<li><a href="index.php">Volver al Inicio</a></li>
				</ul>
			</div>
			<div id='contenido'>
					<?php
						///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);	
					?>
				<div id='Admfunciones'>	
					<ul>
						<li><a onclick="listarlibros()">Listar todos los libros</a></li>
						<li><a onclick="altalibro()">Dar de alta un libro</a></li>
						<li><a onclick="bajalibro()">Dar de baja/activar un libro</a></li>
						<li><a onclick="modlibro()">Modificar un libro</a></li>
						<li><a onclick="conlibro()">Libros mas vendidos en un periodo</a></li>
					</ul>
				</div>
				<div id='libros'>
					<?php	
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
							echo '<div id="tablapedidos2">';
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
									<td><input type='button' value='Hojear' onclick='Hojear()' /></td>
						<?php
									echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
								if ($row['Estado'] == 1){
						?>
									<td><input type='button' value='Modificar' onclick='modUsuario2("<?=$row['ISBN']?>")' /></td>
									<td><input type='button' value='Eliminar' onclick='bajaUsuario2("<?=$row['ISBN']?>")' /></td>
						<?php
								}
								else{
						?>					
									<td><input type='button' value='Modificar' onclick='modUsuario2("<?=$row['ISBN']?>")' disabled /></td>
									<td><input type='button' value='ReActivar' onclick='bajaUsuario2("<?=$row['ISBN']?>")' /></td>
						<?php
								}								
									echo "</tr>";
									$ant = $row['ISNB'];
								}
							}
							echo "</table>";
						}	
						echo '</div>';
						mysql_free_result($res);
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'alta'){ 
					?>			
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="ISNB">ISBN:</label>
								<input class="AVinput" type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required><br>
								<label class="AVinput" for="Titulo">Titulo:</label>
								<input class="AVinput" type="text" name="Titulo" placeholder="Ej: Titulo" maxlength="45" required><br>
								<label  class="AVinput" for="Autor">Autor:</label>
					<?php
								///CONSULTAS///
								ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
								///GENERAR SELECCIONES///
								echo '<select class="AVinput" name="Autor" required>
										<option value="">Seleccione un Autor...</option>';
									while($row = mysql_fetch_assoc($resautor)){	
										echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
									}
								echo '</select>';
					?>			
								<input type="button" value="Agregar Autor" onclick="agregarAutor()"></br>
					<?php		
								echo '<label class="AVinput" for="CantPag">Cantidad Paginas:</label>
								<input class="AVinput" type="text" name="CantPag" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required><br>
								<label class="AVinput" for="Precio">Precio:</label>
								<input class="AVinput" type="text" name="Precio" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required><br>
								<label class="AVinput" for="Idioma">Idioma:</label>
								<select class="AVinput" name="Idioma" required>
								  <option value="">Seleccione un idioma...</option>';
								  while($row = mysql_fetch_assoc($residiomas)){	
										echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
									}
									echo '</select>
								<input type="button" value="Agregar Idioma" onclick="agregarIdioma()"></br>
								<label class="AVinput" for="Fecha">Fecha:</label>
								<input class="AVinput" type="text" name="Fecha" required><br>
								<label class="AVinput" for="Etiquetas">Etiquetas:</label>
								<div class="AVinput" id="AdminEtiq">';								
								while($row = mysql_fetch_assoc($resetiquetas)){	
									echo '<input type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
								}
								echo '</div>
								<input type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta()"></br>
								<input class="Reginput" type="hidden" name="accion" value="agregar" required readonly>
								<input class="AVinput" type="submit" value="Cargar">
							</form>
						</div>';
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="ISNB">ISBN del Libro a Borrar/Activar:</label>
								<input class="AVinput" type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required>
								<input class="Reginput" type="hidden" name="flag" value="baja" required readonly>
								<input class="AVinput" type="submit" value="Buscar">
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
									echo '<form class="FLib" action="" method="GET">
										<label class="AVginput" for="Visble">Estado:</label>
										<input class="AVinput" type="hidden" name="ISBN" value="', $row['ISBN'], '" required readonly>	
										<input class="AVinput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
										<label class="AVinput" for="Titulo">Titulo:</label>
										<input class="AVinput" type="text" name="Titulo" value="', $row['Titulo'], '" size="40"  required readonly><br>
										<label  class="AVinput" for="Autor">Autor:</label>
										<input class="AVinput" type="text" name="Autor" value="', $row['NombreApellido'], '" required readonly><br>
										<label class="AVinput" for="CantPag">Cantidad Paginas:</label>
										<input class="AVinput" type="text" name="CantPag" value="', $row['CantidadPaginas'], '" required readonly><br>
										<label class="AVinput" for="Precio">Precio:</label>
										<input class="AVinput" type="text" name="Precio" value="', $row['Precio'], '" required readonly><br>
										<label class="AVinput" for="Idioma">Idioma:</label>
										<input class="AVinput" type="text" name="Idioma" value="', $row['Idioma'], '" required readonly><br>
										<label class="AVinput" for="Fecha">Fecha:</label>
										<input class="AVinput" type="text" name="Fecha" value="', $row['Fecha'], '" required readonly><br>';							
										if ($row['Estado'] == 1){ 
											echo '<input class="Reginput" type="hidden" name="accion" value="borrar" required readonly>	
											  <input class="AVinput" type="submit" value="Borrar">';
										}
										else{ 
											echo '<input class="Reginput" type="hidden" name="accion" value="activar" required readonly>	
											  <input class="AVinput" type="submit" value="Activar">';
										}
									echo '</form>';
								}	
							}
						}
						echo '</div>';
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){				
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="ISNB">ISBN del Libro modificar:</label>
								<input class="AVinput" type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="15" onkeypress="return Numeros(event);" required>
								<input class="Reginput" type="hidden" name="flag" value="mod" required readonly>
								<input class="AVinput" type="submit" value="Buscar">
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
								///CONSULTAS///
								ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
								while($row = mysql_fetch_assoc($res)){
									if ($row['Estado'] == 1){
										echo '<form class="FLib" action="" method="GET">	
											<label class="AVginput" for="Visble">Estado:</label>
											<input class="AVinput" type="hidden" name="ISBN" value="', $row['ISBN'], '" required readonly>	
											<input class="AVinput" type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>';
												echo'<label class="AVinput" for="Titulo">Titulo:</label>
												<input class="AVinput" type="text" name="Titulo" value="', $row['Titulo'], '" size="40" placeholder="Ej: Titulo" maxlength="45" required ><br>
												<label  class="AVinput" for="Autor">Autor:</label>
												<select class="AVinput" name="Autor" required>
													<option value="' .$row['NombreApellido']. '">' .$row['NombreApellido']. '</option>';
													while($row2 = mysql_fetch_assoc($resautor)){	
														echo '<option value="', $row2['Autor'], '">', $row2['Autor'], '</option>';
													}
												echo '</select>												
												<input type="button" value="Agregar Autor" onclick="agregarAutor()"></br>
												<label class="AVinput" for="CantPag">Cantidad Paginas:</label>
												<input class="AVinput" type="text" name="CantPag" value="', $row['CantidadPaginas'], '" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required ><br>
												<label class="AVinput" for="Precio">Precio:</label>
												<input class="AVinput" type="text" name="Precio" value="', $row['Precio'], '" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required ><br>
												<label class="AVinput" for="Idioma">Idioma:</label>
												<select class="AVinput" name="Idioma" required>
												  <option value="' .$row['Idioma']. '">' .$row['Idioma']. '</option>';
												  while($row3 = mysql_fetch_assoc($residiomas)){	
														echo '<option value="', $row3['Idioma'], '">', $row3['Idioma'], '</option>';
													}
												echo '</select>
												<input type="button" value="Agregar Idioma" onclick="agregarIdioma()"></br>			
												<label class="AVinput" for="Fecha">Fecha:</label>
												<input class="AVinput" type="text" name="Fecha" value="', $row['Fecha'], '" required ><br>
												<label class="AVinput" for="Dis">Disponibilidad:</label>
												<select class="AVinput" name="Disp" required>
												  <option value="' .$row['Disponibilidad']. '">' .$row['Disponibilidad']. '</option>';
												  while($row6 = mysql_fetch_assoc($resdisp)){	
														echo '<option value="', $row6['Disponibilidad'], '">', $row6['Disponibilidad'], '</option>';
													}
												echo '</select></br>';
											echo '<label class="AVinput" for="Etiquetas">Etiquetas:</label>
											<div class="AVinput" id="AdminEtiq">';
											BuscarEtiq ($row['ISBN'], $LibEtiq);
											$num = mysql_num_rows($LibEtiq);
											while($row4 = mysql_fetch_assoc($resetiquetas)){	
												$entro = false;
												if($num != 0){
													mysql_data_seek ($LibEtiq, 0);
												}												
												while($row5 = mysql_fetch_assoc($LibEtiq)){	
													if ($row4['Etiqueta'] == $row5['Descripcion']){
														echo '<input type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '" checked="checked" >', $row4['Etiqueta'];
														$entro = true;
													}
												}
												if (!$entro){
													echo '<input type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '">', $row4['Etiqueta'];
												}
											}
											echo '</div>
											<input type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta()"></br>';
											if ($row['Estado'] == 1){ 
												echo '<input class="AVginput" type="hidden" name="accion" value="modificar" required readonly>	
													  <input class="AVinput" type="submit" value="Modificar">';
											}
											else{ 
												echo '<input class="AVinput" type="submit" value="Modificar" disabled>';
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
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'con'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="periodo">Ingrese periodo de tiempo:</label></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Inicial:</label>
								<input class="AVinput" type="date" name="Fini" required></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Final:</label>
								<input class="AVinput" type="date" name="Ffin" required></br>
								<input class="Reginput" type="hidden" name="flag" value="con" required readonly>
								<input class="AVinput" type="submit" value="Buscar">
							</form>
					<?php	
						if (!empty($_GET['Fini']) && !empty($_GET['Ffin'])){
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
								echo '<div id="tablauser1">';
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
										$cadena= ' ';		
										echo "</tr>";
										$ant = $row['ISNB'];
									}
								}
								echo "</table>";
								echo '</div>';
							}
						}		
						echo '</div>';
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgAu'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="Nombre">Nombre y Apellido del Autor:</label></br>
								<input class="AVinput" type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
								<input class="Reginput" type="hidden" name="accion" value="AgregarAu" required readonly>
								<input class="AVinput" type="submit" value="Agregar">
							</form>
						</div>
					<?php	
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgId'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
								<label class="AVinput" for="Nombre">Descripcion del Idioma:</label></br>
								<input class="AVinput" type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input class="Reginput" type="hidden" name="accion" value="AgregarIdio" required readonly>
								<input class="AVinput" type="submit" value="Agregar">
							</form>
						</div>	
					<?php		
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgEt'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">							
								<label class="AVinput" for="Nombre">Descripcion de la Etiquta:</label></br>
								<input class="AVinput" type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input class="Reginput" type="hidden" name="accion" value="AgregarEtiq" required readonly>
								<input class="AVinput" type="submit" value="Agregar">							
							</form>
						</div>
					<?php										
					}
						///CIERRE///
						CerrarServidor ($con);
					?>	
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>