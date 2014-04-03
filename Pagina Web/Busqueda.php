<?php session_start(); ?>
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
				
			}
			function irperfil(){
			
			}
			function MostarDetalle(){
			
			}
		</script>	
	</head>
	<body>
		<div id='cabecera'>
			<div id='imglogo'> <img src="Logo1.gif" width="85%" height="475%"> </div> 
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
						<li><a href="Administrador.php">Modo Administrador</a></li>';
				<?php	
					}
				?>
				</ul>
			</div>
			<div id='contenido'>  
				
				<div id='listautos'>
					<?php	
						///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);				
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
								echo "<td>", $row['idioma.Descripcion'], "</td>";
								echo "<td>", $row['Fecha'], "</td>";
								echo "<td>", $row['disponibilidad.Descripcion'], "</td>";
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
						mysql_free_result($res);
						///CIERRE///
						CerrarServidor ($con);
					?>	
				</div>
			</div>
		</div>
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |Resolución Óptima 1920 x 1080 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>