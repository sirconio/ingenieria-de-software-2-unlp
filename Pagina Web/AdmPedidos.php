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
			function listarPedidos(){
				location.href="AdmPedidos.php?flag=lista";
			}
			function altaPedido(){
				location.href="AdmPedidos.php?flag=alta";
			}
			function bajaPedido(){
				location.href="AdmPedidos.php?flag=baja";
			}
			function modPedido(){
				location.href="AdmPedidos.php?flag=mod";
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
						<li><a onclick="listarPedidos()">Listar todos los Pedidos</a></li>
						<li><a onclick="altaPedido()">Dar de alta un Pedido</a></li>
						<li><a onclick="bajaPedido()">Dar de baja un Pedido</a></li>
						<li><a onclick="modPedido()">Modificar un Pedido</a></li>
					</ul>
				</div>
				<div id='libros'>
					<?php	
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
					
						ConsultaLibros ($res);
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
						mysql_free_result($res);
					}
						///CIERRE///
						CerrarServidor ($con);
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