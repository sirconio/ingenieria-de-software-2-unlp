<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
?>
<html>
	<head>
		<title>CookBook</title>
		<link type="text/css" rel="stylesheet" href="style.css">
		<link rel="stylesheet" media="screen" type="text/css" href="css/datepicker.css" />
		<script type="text/javascript" src="js/datepicker.js"></script>
	
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
			$(function() {	
				$('#datepicker').DatePicker();
			});
			function AgregarCarrito(ISBN, ID){
				location.href="Busqueda.php?carrito=true&Is=" + ISBN + "&Dn=" + ID;
			}
			function Hojear(){
			
			}
			function MensajeMod(Msj){
				alert(Msj);
				location.href="Busqueda.php";
			}
			function NumerosPunto(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function determinaautor (autor){
				object = document.getElementById("idautor");
				autor = object.value;
				location.href = 'Busqueda.php?titulo=true&autornom=' + object.value;
			}
			function validarPrecioMayor(){
				object = document.getElementById("idprecio");
				precio = object.value;
				expr = /^([0-9])*[.]?[0-9]*$/;
				if ( !expr.test(precio) ){
					alert("Error: El precio " + precio + " es incorrecto.");
					object.value = "";	
				}
			}
			function validarPrecioMenor(){
				object = document.getElementById("idprecio2");
				precio = object.value;
				expr = /^([0-9])*[.]?[0-9]*$/;
				if ( !expr.test(precio) ){
					alert("Error: El precio " + precio + " es incorrecto.");
					object.value = "";	
				}
			}
			function A () {
					var t=document.getElementById('tablabusq');
					//var f=t.getElementsByTagName('idISBN');
					for(var q=0;q<t.length;++q)
					{
					alert(t.elements[q].value);
					}
					alert('entro');
			}
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
					///CONEXIONES///						
						ConexionServidor ($con);					
						ConexionBaseDatos ($bd);
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
				<?php				echo $_SESSION["CarritoCant"];
								echo '</a></li>';
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
					<li><a href="Busqueda.php">Catalogo</a></li>
					<li><a href="QuienesSomos.php">Quienes Somos?</a></li>
				<?php
					if ($_SESSION['categoria'] == 'Normal'){
				?>
					<li><a href="Contacto.php">Contacto</a></li>
				<?php	
					}
				?>
				<?php
					if ($_SESSION['categoria'] == 'Administrador'){
				?>
						<li><a href="Administrador.php">Modo Administrador</a></li>
				<?php	
					}
				?>
				</ul>
			</div>
	<?php
		// VERIFICACION DEL ESTADO DE LA SESION //
		if (!empty($_SESSION['estado']) && $_SESSION['estado'] == 'logeado'){
			if (!empty($_GET['carrito']) && $_GET['carrito'] == 'true'){
				AgregarCarrito($_GET['Is'], $_GET['Dn'], $AltMsg);
	?>
				<script languaje="javascript"> 	
					MensajeMod("<?=$AltMsg?>");	
				</script>
	<?php
			}
	?>
			<!-- CONTENIDO DE USUARIO REGISTRADO -->	
			<div id='contenido'>  
	<?php		
				///CONEXIONES///						
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);	
				///CONSULTAS///
				ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>	
				<!-- FORMULARIO DE ORDENAMIENTO -->	
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
				<!-- FORMULARIO DE FILTRADO -->	
				<form id='filtros' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
	<?php
					echo '<select name="Idiomas">
						<option value="">Todos los Idiomas...</option>';
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
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="PagInf">Mayor que:</label><input type="text" name="PagInf" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" ></br>
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="PagSup">Menor que:</label><input type="text" name="PagSup" placeholder="Ej: 250" maxlength="10" onkeypress="return Numeros(event);" ></br>
					<label for="CantPre">Precio:</label></br>
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreInf">Mayor que:</label><input type="text" name="PreInf" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMayor()" ></br>
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreSup">Menor que:</label><input type="text" name="PreSup" placeholder="Ej: 97.85" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMenor()" ></br>
					<label for="CantFec">Fecha de Publicacion:</label></br>
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="FechInf">Mayor que:</label><input type="text" name="FecInf" id="datepicker" ></br>
					&nbsp;&nbsp;&nbsp;&nbsp;<label for="FechSup">Menor que:</label><input type="text" name="FecSup" id="datepicker2" ></br>
					<input id="filtrobot" type="submit" value="Filtrar"></br></br>
				</form>';
				// FORMULARIOS DE BUSQUEDA //	
				// BUSQUEDA POR ISBN //
				echo '<form id="buscar2" action="Busqueda.php" method="GET">
					<label for="ISBN">ISBN:</label>
					<select name="ISBN">
						<option value="">Todos los ISBN...</option>';
						while($row = mysql_fetch_assoc($resisbn)){	
							echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
						}
					echo '</select>
					<input type="submit" value="Buscar"></br>
				</form>';	
				// BUSQUEDA POR AUTOR, TITULO, ETIQUETA //
				echo '<form id="buscar3" action="Busqueda.php" method="GET"></br>';
				if (!empty($_GET['titulo']) && $_GET['titulo'] == 'true' && !empty($_GET['autornom'])){
					consultatitulos($restitulo, $_GET['autornom']);
					echo '<label for="Autor">Autor:</label>
					<select name="Autor" id="idautor" onchange="determinaautor()" >
						<option value="' .$_GET['autornom'] .'">' .$_GET['autornom'] .'</option>
						<option value="">Todos los Autores...</option>';
						while($row = mysql_fetch_assoc($resautor)){	
	?>
							<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
						}
					echo '</select></br>
					<label for="Titulo">Titulo:</label>
					<select name="Titulo">
						<option value="">Todos los Titulos de ' .$_GET['autornom'] .' </option>';
						while($row = mysql_fetch_assoc($restitulo)){	
							echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
						}
					echo '</select></br>';
				}
				else{
					echo '<label for="Autor">Autor:</label>
					<select name="Autor" id="idautor" onchange="determinaautor()" >
						<option value="">Todos los Autores...</option>';
						while($row = mysql_fetch_assoc($resautor)){	
	?>
							<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
						}
					echo '</select></br>
					<label for="Titulo">Titulo:</label>
					<select name="Titulo">
						<option value="">Todos los Titulos...</option>';
						while($row = mysql_fetch_assoc($restitulo)){	
							echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
						}
					echo '</select></br>';
				}
					echo '<label for="Etiquetas">Etiquetas:</label></br>
					<div id="Caracteristicas">';
						while($row = mysql_fetch_assoc($resetiquetas)){	
							echo '<input type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
						}
					echo '</div>';
					echo '<input id="buscrobot3" type="submit" value="Buscar">
				</form>';
	?>			
				<!-- TABLA DEL CATALOGO-->
				<div id='listlibros'>
					<?php	
					// CATALOGO COMPLETO //
					ConsultaPorDefecto ($res);
					// CATALOGO BUSQUEDA RAPIDA //
					if	(!empty($_GET['BusRap'])){	
						ConsultaBusquedaRapida ($res, $_GET['BusRap']);	
					}
					// CATALOGO BUSQUEDA AVANZADA //
					if	(!empty($_GET['Autor']) || !empty($_GET['Titulo']) || !empty($_GET['ISBN']) || !empty($_GET['Etiquetas[]'])){	
						ConsultaBusqueda2 ($res, $_GET['Autor'], $_GET['Titulo'], $_GET['ISBN'], $_GET['Etiquetas[]']);
					}
					// CATALOGO ORDENADO //	
					if	(!empty($_GET['Orden'])){	
						ConsultaOrdenamiento ($res, $_GET['Orden']);	
					}
					// CATALOGO FILTRADO //
					if	(!empty($_GET['PreInf']) || !empty($_GET['PreSup']) || !empty($_GET['Idiomas']) || !empty($_GET['Disponibilidades']) || !empty($_GET['PagInf']) || 
					!empty($_GET['PagSup']) || !empty($_GET['FecInf']) || !empty($_GET['FecSup'])){	
						ConsultaFiltros2 ($res, $_GET['PreInf'], $_GET['PreSup'], $_GET['Idiomas'], $_GET['Disponibilidades'], $_GET['PagInf'], $_GET['PagSup'], $_GET['FecInf'], 
						$_GET['FecSup']);
					}
					// CONTROL DE CONSULTA //		
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}	
					// GENERANDO TABLA //	
					echo "<table border='1'>
							<tr>
								<th>ISBN</th>
								<th>Titulo</th>
								<th>Autor</th>
								<th>CantidadPaginas</th>
								<th>Precio</th>
								<th>Idioma</th>
								<th>Fecha</th>
								<th>Disponibilidad</th>
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
								<td><input type='button' value='Detalle' onclick='Hojear()' /></td>
	<?php
								if ($_SESSION['categoria'] == 'Administrador'){
	?>
									<td><input type='button' value='Agregar a Carrito' onclick='AgregarCarrito("<?=$row['ISBN']?>","<?=$_SESSION["ID"]?>")' disabled /></td>
	<?php
								}
								else{
	?>
									<td><input type='button' value='Agregar a Carrito' onclick='AgregarCarrito("<?=$row['ISBN']?>","<?=$_SESSION["ID"]?>")'  /></td>
	<?php
								}							
							echo "</tr>";
							$ant = $row['ISBN'];
						}
					}
					echo "</table>";
					// LIBERAR VARIABLE //
					mysql_free_result($res);
					// CIERRE CONEXION //
					CerrarServidor ($con);				
				echo '</div>	
			</div>';
		}
		else{
	?>
			<!-- CONTENIDO DE USUARIO NO REGISTRADO -->	
			<div id='contenido2'>	
	<?php
				///CONEXIONES///						
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);	
				///GENERAR SELECCIONES///
				ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>
				<!-- FORMULARIO DE ORDENAMIENTO -->	
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
				<!-- FORMULARIO DE FILTRADO -->	
				<script languaje="javascript"> 		

				</script>
				<form id='filtros2' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
	<?php
					$tabphp = '<script> document.write(tabjs) </script>';
					
					echo '<select name="Idiomas">
							<option value="">Todos los Idiomas...</option>';
							while($row = mysql_fetch_assoc($residiomas)){	
								echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
							}
					echo '</select></br></br>
						<label for="CantPag"  onclick="A()">Precio' .$tabphp .':</label></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreInf">Mayor que:</label><input type="text" id="idprecio" name="PreInf" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMayor()" ></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreSup">Menor que:</label><input type="text" id="idprecio2" name="PreSup" placeholder="Ej: 97.85" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMenor()"></br>
						<input id="filtrobot3b" type="submit" value="Filtrar"></br></br>
				</form>';
				// FORMULARIOS DE BUSQUEDA //	
				// BUSQUEDA POR ISBN //
				echo '<form id="buscar2b" action="Busqueda.php" method="GET">
					<label for="ISBN">ISBN:</label>
					<select name="ISBN">
						<option value="">Todos los ISBN...</option>';
						while($row = mysql_fetch_assoc($resisbn)){	
							echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
						}
					echo '</select>
					<input type="submit" value="Buscar"></br>
				</form>';
				// BUSQUEDA POR AUTOR, TITULO //
				echo '<form id="buscar3b" action="Busqueda.php" method="GET"></br>';
					if (!empty($_GET['titulo']) && $_GET['titulo'] == 'true' && !empty($_GET['autornom'])){
						consultatitulos($restitulo, $_GET['autornom']);
						echo '<label for="Autor">Autor:</label>
						<select name="Autor" id="idautor" onchange="determinaautor()" >
							<option value="' .$_GET['autornom'] .'">' .$_GET['autornom'] .'</option>
							<option value="">Todos los Autores...</option>';
							while($row = mysql_fetch_assoc($resautor)){	
	?>
								<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
							}
						echo '</select></br>
						<label for="Titulo">Titulo:</label>
						<select name="Titulo">
							<option value="">Todos los Titulos de ' .$_GET['autornom'] .' </option>';
							while($row = mysql_fetch_assoc($restitulo)){	
								echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
							}
						echo '</select></br>';
					}
					else{
						echo '<label for="Autor">Autor:</label>
						<select name="Autor" id="idautor" onchange="determinaautor()" >
							<option value="">Todos los Autores...</option>';
							while($row = mysql_fetch_assoc($resautor)){	
	?>
								<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
							}
						echo '</select></br>
						<label for="Titulo">Titulo:</label>
						<select name="Titulo">
							<option value="">Todos los Titulos...</option>';
							while($row = mysql_fetch_assoc($restitulo)){	
								echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
							}
						echo '</select></br>';
					}
					echo '<input id="buscrobot3b" type="submit" value="Buscar">';
	?>					 
				</form>
				<!-- TABLA DEL CATALOGO-->
				<div id='listlibros2'>
	<?php			
					// CATALOGO COMPLETO //
					ConsultaPorDefecto ($res);
					// CATALOGO BUSQUEDA RAPIDA //
					if	(!empty($_GET['BusRap'])){	
						ConsultaBusquedaRapida ($res, $_GET['BusRap']);	
					}
					// CATALOGO BUSQUEDA AVANZADA //
					if	(!empty($_GET['Autor']) || !empty($_GET['Titulo']) || !empty($_GET['ISBN'])){	
						ConsultaBusqueda ($res, $_GET['Autor'], $_GET['Titulo'], $_GET['ISBN']);
					}
					// CATALOGO ORDENADO //
					if	(!empty($_GET['Orden'])){	
						ConsultaOrdenamiento ($res, $_GET['Orden']);	
					}
					// CATALOGO FILTRADO //
					if	(!empty($_GET['PreInf']) || !empty($_GET['PreSup']) || !empty($_GET['Idiomas'])){	
						ConsultaFiltros ($res, $_GET['PreInf'], $_GET['PreSup'], $_GET['Idiomas']);
					}
					// CONTROL DE CONSULTA //	
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}		
					// GENERANDO TABLA //	
					echo "<table id='tablabusq' border='1'>
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
								echo "<td id='idISBN'>", $row['ISBN'], "</td>";
								echo "<td>", $row['Titulo'], "</td>";
								echo "<td>", $row['NombreApellido'], "</td>";
								echo "<td>", $row['Precio'], "</td>";										
							echo "</tr>";
							$ant = $row['ISBN'];
						}
					}
					echo "</table>";
					// LIBERAR VARIABLE //
					mysql_free_result($res);
					// CIERRE CONEXION //
					CerrarServidor ($con);
				echo '</div>
			</div>';
		}
	?>					
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>