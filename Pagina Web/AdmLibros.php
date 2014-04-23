<?php session_start();
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
		</script>
	</head>
	<body>
		<div id='cabecera'>
			<div id='imglogo'><img src="Logo1.gif" width="85%" height="475%"> </div>
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
						<li><a onclick="bajalibro()">Dar de baja un libro</a></li>
						<li><a onclick="modlibro()">Modificar un libro</a></li>
					</ul>
				</div>
				<div id='libros'>
					<?php	
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
						echo '<div id="tablapedidos">';
						ConsultaPorDefecto ($res);
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
								$ant = $row['ISNB'];
							}
						}
						echo "</table>";
						echo '</div>';
						mysql_free_result($res);
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'alta'){ 
					?>			
							<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
									<label class="AVinput" for="ISNB">ISBN:</label>
									<input class="AVinput" type="text" name="ISBN" required><br>
									<label class="AVinput" for="Titulo">Titulo:</label>
									<input class="AVinput" type="text" name="Titulo" required><br>
								<label  class="AVinput" for="Autor">Autor:</label>
								<?php
									///CONSULTAS///
									ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
									///GENERAR SELECCIONES///
									echo '<select class="AVinput" name="Autor" required>';
										echo '<option value="">Seleccione un Autor...</option>';
										while($row = mysql_fetch_assoc($resautor)){	
											echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
										}
									echo '</select></br>
									<label class="AVinput" for="CantPag">Cantidad Paginas:</label>
									<input class="AVinput" type="text" name="CantPag" required><br>
									<label class="AVinput" for="Precio">Precio:</label>
									<input class="AVinput" type="text" name="Precio" required><br>
									<label class="AVinput" for="Idioma">Idioma:</label>
									<select class="AVinput" name="Idioma" required>
									  <option value="">Seleccione un idioma...</option>';
									  while($row = mysql_fetch_assoc($residiomas)){	
											echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
										}
									echo '</select><br>
									<label class="AVinput" for="Fecha">Fecha:</label>
									<input class="AVinput" type="text" name="Fecha" required><br>
									<label class="AVinput" for="Etiquetas">Etiquetas:</label>
									<div class="AVinput" id="AdminEtiq">';
									while($row = mysql_fetch_assoc($resetiquetas)){	
										echo '<input type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
									}
									echo '</div>
									<input class="AVinput" type="submit" value="Cargar">
							</form>
							</div>';
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
									<label class="AVinput" for="ISNB">ISBN del Libro a borrar:</label>
									<input class="AVinput" type="text" name="ISBN" required>
									<input class="Reginput" type="hidden" name="flag" value="baja" required readonly>
									<input class="AVinput" type="submit" value="Buscar">
							</form>
					<?php	
						if (!empty($_GET['ISBN'])){
							ConsultaLibro ($res, $_GET['ISBN']);
						
						echo	'<form class="FLib" action="" method="GET">';
							
							while($row = mysql_fetch_assoc($res)){
							echo	'<label class="AVinput" for="Titulo">Titulo:</label>
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
		                    }	
								echo '<input class="AVinput" type="submit" value="Borrar">
							</form>	';							
						}
						echo '<div>';
					}
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){
					
					?>
						<div id='AltaLibro'>
							<form class='FLib' action="" method="GET">
									<label class="AVinput" for="ISNB">ISBN del Libro modificar:</label>
									<input class="AVinput" type="text" name="ISBN" required>
									<input class="Reginput" type="hidden" name="flag" value="mod" required readonly>
									<input class="AVinput" type="submit" value="Buscar">
							</form>
					<?php		
								if (!empty($_GET['ISBN'])){
								ConsultaLibro ($res, $_GET['ISBN']);
							
								echo '<form class="FLib" action="" method="GET">';			
							///CONSULTAS///
							ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
							while($row = mysql_fetch_assoc($res)){
							echo	'<label class="AVinput" for="Titulo">Titulo:</label>
									<input class="AVinput" type="text" name="Titulo" value="', $row['Titulo'], '" size="40" required readonly><br>
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
		                    }	
								echo '<label class="AVinput" for="Etiquetas">Etiquetas:</label>
									<div class="AVinput" id="AdminEtiq">';
									while($row = mysql_fetch_assoc($resetiquetas)){	
										echo '<input type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
									}
								echo '</div>
									<input class="AVinput" type="submit" value="Modificar">	
									</form>';
							}
								echo '</div>';							
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