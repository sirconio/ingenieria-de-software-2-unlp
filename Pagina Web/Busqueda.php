<?php session_start(); ?>
<html>
	<head>
		<title>CookBook</title>
		<link type="text/css" rel="stylesheet" href="style.css">
		 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
		<script>
			<!-- VENTANA EMERGENTE DE INICIO DE SESION -->
			function acceso(){
				window.open("InicioSesion.php","myWindow","status = 1, height = 150, width = 350, resizable = no" )	
			}
			<!-- RECARGA LA PAGINA CON EL FLAG EN TRUE -->
			function salir(){
				location.href="Busqueda.php?flag=true";
			}			
			function registro(){
				location.href="Registrarme.php";
			}
			function irperfil(){
				location.href="VerPerfil.php";
			}
			function MostarDetalle(){
			
			}
			 $(function() {
				$( "#datepicker" ).datepicker();
			});
		</script>	
	</head>
	<body>
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
					<li><a href="index.php">Inicio</a></li>
					<li><a href="Busqueda.php">Busqueda</a></li>
					<li><a href="QuienesSomos.php">Quienes Somos?</a></li>
					<li><a href="Contacto.php">Contacto</a></li>
				<?php
					if ($_SESSION['categoria'] == 'Administrador'){
				?>
						<li><a href="Administrador.php">Modo Administrador</a></li>
				<?php	
					}
				?>
				</ul>
			</div>
			<div id='contenido'>  
				<?php
				// VERIFICA EL ESTADO DE LA SESION
					if(!empty($_SESSION['estado'])){
						//USUARIO LOGEADO CORRECTAMENTE
						if ($_SESSION['estado'] == 'logeado'){
				?>
				<form id='ordenamiento' action="Busqueda.php" method="GET">
					<label for="Ordenar">Ordenar:</label>
					<select name="Orden">
						<option value="">Seleccione...</option>
						<option value="PrecAsc">Precio Ascendiente</option>
						<option value="PrecDes">Precio Descendiente</option>
						<option value="TitAsc">Titulo Ascendente</option>
						<option value="TitDes">Titulo Descendiente</option>
						<option value="AutAsc">Autor Ascendente</option>
						<option value="AutDes">Autor Descendiente</option>
						<option value="CPAsc">Cantidad Paginas Ascendente</option>
						<option value="CPDes">Cantidad Paginas Descendiente</option>
						<option value="ISBNAsc">ISBN Ascendente</option>
						<option value="ISBNDes">ISBN Descendiente</option>
						<option value="FecAsc">Fecha Publicacion Ascendente</option>
						<option value="FecDes">Fecha Publicacion Descendiente</option>
						<option value="IdioAsc">Idioma Ascendente</option>
						<option value="IdioDes">Idioma Descendiente</option>
					</select>
					<input type="submit" value="Recargar">
				</form>
				<form id='filtros' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
					<?php
						///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);	
						///CONSULTAS///
						ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
						///GENERAR SELECCIONES///
						echo '<select name="Idiomas">';
							echo '<option value="">Todos los Idiomas...</option>';
							while($row = mysql_fetch_assoc($residiomas)){	
								echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
							}
						echo '</select>	
						<label for="Marca">Disponibilidad:</label>
						<select name="Disponibilidades">
						  <option value="">Todos....</option>';
						  while($row = mysql_fetch_assoc($resdisp)){	
								echo '<option value="', $row['Disponibilidad'], '">', $row['Disponibilidad'], '</option>';
							}
						echo '</select></br>
						<label for="CantPag">Cantidad de Paginas:</label></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PagInf">Mayor que:</label><input type="text" name="PagInf"></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PagSup">Menor que:</label><input type="text" name="PagSup"></br>
						<label for="CantPre">Precio:</label></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreInf">Mayor que:</label><input type="text" name="PreInf"></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreSup">Menor que:</label><input type="text" name="PreSup"></br>
						<label for="CantFec">Fecha de Publicacion:</label></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="FechInf">Mayor que:</label><input type="text" id="datepicker" ></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="FechSup">Menor que:</label><input type="text" name="FechSup"></br>
						<input id="filtrobot" type="submit" value="Filtrar"></br></br>
						</form>';
						echo '<form id="buscar" action="Busqueda.php" method="GET">
						<label for="Autor">Autor:</label>
						<select name="Autor">
						  <option value="">Todos los Autores...</option>';
						  while($row = mysql_fetch_assoc($resautor)){	
								echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
							}
						echo '</select></br>
						<label for="Titulo">Titulo:</label>
						<select name="Titulo">
						  <option value="">Todos los Titulos...</option>';
						  while($row = mysql_fetch_assoc($restitulo)){	
								echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
							}
						echo '</select></br>
						<label for="ISBN">ISBN:</label>
						<select name="ISBN">
						  <option value="">Todos los ISBN...</option>';
						  while($row = mysql_fetch_assoc($resisbn)){	
								echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
							}
						echo '</select></br>
						<label for="Etiquetas">Etiquetas:</label></br>
						<div id="Caracteristicas">';
						  while($row = mysql_fetch_assoc($resetiquetas)){	
								echo '<input type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
							}
						echo '</div>';
						echo '	
					<input id="buscrobot" type="submit" value="Buscar">';
					?>					 
				</form>
				<div id='listlibros'>
					<?php	
				
						ConsultaPorDefecto ($res);
						
						if	(!empty($_GET['BusRap'])){	
							ConsultaBusquedaRapida ($res, $_GET['BusRap']);	
						}
						
						if	(!empty($_GET['Orden'])){	
							ConsultaOrdenamiento ($res, $_GET['Orden']);	
						}

						if	(!empty($_GET['Autor']) || !empty($_GET['Titulo']) || !empty($_GET['ISBN']) || !empty($_GET['Etiquetas[]'])){	
							ConsultaBusqueda2 ($res, $_GET['Autor'], $_GET['Titulo'], $_GET['ISBN'], $_GET['Etiquetas[]']);
						}
						
						if	(!empty($_GET['PreInf']) || !empty($_GET['PreSup']) || !empty($_GET['Idiomas']) || !empty($_GET['Disponibilidades']) 
						|| !empty($_GET['PagInf']) || !empty($_GET['PagSup']) || !empty($_GET['FecInf']) || !empty($_GET['FecSup'])){	
							ConsultaFiltros2 ($res, $_GET['PreInf'], $_GET['PreSup'], $_GET['Idiomas'], $_GET['Disponibilidades'], $_GET['PagInf'], $_GET['PagSup']
							, $_GET['FecInf'], $_GET['FecSup']);
						}
						
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						echo	"<table border='1'>
								<tr>
									<th>ISBN</th>
									<th>Titulo</th>
									<th>Autor</th>
									<th>CantidadPaginas</th>
									<th>Precio</th>
									<th>Idioma</th>
									<th>Fecha</th>
									<th>Disponibilidad</th>
									<th>Detalles</th>
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
								$cadena= ' ';
								//ConsultarAuto ($cadena, $row['Dominio'], $row['Anio']);			
					?>																	
								<td><input type='button' value='Detalles' onclick='MostarDetalle()' /></td>
					<?php
								echo "</tr>";
								$ant = $row['ISBN'];
							}
						}
						echo "</table>";
						mysql_free_result($res);
						///CIERRE///
						CerrarServidor ($con);
					}
				}
					else{
					?>
				</div>
			</div>	
			<div id='contenido2'>	
				<form id='ordenamiento2' action="Busqueda.php" method="GET">
					<label for="Ordenar">Ordenar:</label>
					<select name="Orden">
						<option value="">Seleccione...</option>
						<option value="PrecAsc">Precio Ascendiente</option>
						<option value="PrecDes">Precio Descendiente</option>
						<option value="TitAsc">Titulo Ascendente</option>
						<option value="TitDes">Titulo Descendiente</option>
						<option value="AutAsc">Autor Ascendente</option>
						<option value="AutDes">Autor Descendiente</option>
						<option value="ISBNAsc">ISBN Ascendente</option>
						<option value="ISBNDes">ISBN Descendiente</option>
					</select>
					<input type="submit" value="Recargar">
				</form>
				<form id='filtros2' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
					<?php
						///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);	
						///GENERAR SELECCIONES///
						ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
						echo '</br>
						<label for="CantPag">Precio:</label>
						<label for="PreInf">Mayor que:</label><input type="text" name="PreInf"></br>
						<label for="PreSup">Menor que:</label><input type="text" name="PreSup"></br>
						<input id="filtrobot2" type="submit" value="Filtrar"></br></br>
						</form>';
							echo '<form id="buscar2" action="Busqueda.php" method="GET">
							<label for="Autor">Autor:</label>
							<select name="Autor">
							  <option value="">Todos los Autores...</option>';
							  while($row = mysql_fetch_assoc($resautor)){	
									echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
								}
							echo '</select></br>
							<label for="Titulo">Titulo:</label>
							<select name="Titulo">
							  <option value="">Todos los Titulos...</option>';
							  while($row = mysql_fetch_assoc($restitulo)){	
									echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
								}
							echo '</select></br>
							<label for="ISBN">ISBN:</label>
							<select name="ISBN">
							  <option value="">Todos los ISBN...</option>';
							  while($row = mysql_fetch_assoc($resisbn)){	
									echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
								}
							echo '</select>	
						<input id="buscrobot2" type="submit" value="Buscar">';
					?>					 
				</form>
				<div id='listlibros2'>
					<?php	
				
						ConsultaPorDefecto ($res);
						
						if	(!empty($_GET['BusRap'])){	
							ConsultaBusquedaRapida ($res, $_GET['BusRap']);	
						}
						
						if	(!empty($_GET['Orden'])){	
							ConsultaOrdenamiento ($res, $_GET['Orden']);	
						}

						if	(!empty($_GET['Autor']) || !empty($_GET['Titulo']) || !empty($_GET['ISBN'])){	
							ConsultaBusqueda ($res, $_GET['Autor'], $_GET['Titulo'], $_GET['ISBN']);
						}
						
						if	(!empty($_GET['PreInf']) || !empty($_GET['PreSup'])){	
							ConsultaFiltros ($res, $_GET['PreInf'], $_GET['PreSup']);
						}
						
						if(!$res) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}		
						
						echo	"<table border='1'>
								<tr>
									<th>ISBN</th>
									<th>Titulo</th>
									<th>Autor</th>
									<th>Precio</th>
								</tr>";
						$ant = ' ';
						while($row = mysql_fetch_assoc($res)) {
							if ($row['ISBN'] != $ant){
								echo "<tr>";
								echo "<td>", $row['ISBN'], "</td>";
								echo "<td>", $row['Titulo'], "</td>";
								echo "<td>", $row['NombreApellido'], "</td>";
								echo "<td>", $row['Precio'], "</td>";
								$cadena= ' ';
								//ConsultarAuto ($cadena, $row['Dominio'], $row['Anio']);			
								echo "</tr>";
								$ant = $row['ISBN'];
							}
						}
						echo "</table>";
						mysql_free_result($res);
						///CIERRE///
						CerrarServidor ($con);
					}
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