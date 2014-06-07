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
			<!-- TRASLADA A LA PAGINA REGISTRAME -->
			function registro(){
				location.href="Registrarme.php";
			}
			<!-- TRASLADA LA PAGINA VER PERFIL -->
			function irperfil(){
				location.href="VerPerfil.php";
			}
			$(function() {	
				$('#datepicker').DatePicker();
			});
			<!-- ACTIVACION DEL FLAG DE AGREAGAR AL CARRITO -->
			function AgregarCarrito(ISBN, ID){
				location.href="Busqueda.php?carrito=true&Is=" + ISBN + "&Dn=" + ID;
			}
			<!-- VENTANA DE DETALLES -->
			function Hojear(ISBN, Titulo, NombreApellido, Precio, CantPag, Idioma, Fecha, Disp, Etiq, Ind){
				Ventana = window.open('','Detalles','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600,height=400');
				Ventana.moveTo(100,100);
				Ventana.document.innerHTML = "";
				Ventana.document.write("<html><head></head><body background='Fondo8.jpg' background-size='cover' style='color:white' onblur='self.close()' ><p>ISBN: " + ISBN + " - Autor: " + NombreApellido + " - Titulo: " + Titulo + "</p><p>Idioma: " + Idioma + " - Cantidad de Paginas: " + CantPag + " - Fecha Publicacion: " + Fecha + "</p><p>Precio: $" + Precio + "</p><p>Etiquetas: " + Etiq + "</p><p>Indice: <p>&nbsp;&nbsp;&nbsp;&nbsp;" + Ind + "</p></p><p>------------------------------</p></body></html>");
				myWindow.focus();
			}
			<!-- MENSAJE DE ALTA AL CARRITO -->
			function MensajeAlta(Msj){
				alert(Msj);
				location.href="Busqueda.php";
			}
			<!-- VALIDACIONES DE CAMPOS -->
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
			<!-- FIN VALIDACIONES DE CAMPOS -->
		</script>	
	</head>
	<body>
	<!-- CABECERA -->
		<div id='cabecera'>
			<!-- LOGO COOKBOOK -->
			<div id='imglogo'><a href="index.php"><img src="Logo1.gif" width="85%" height="475%"></a></div>  
			<!-- BARRA DE BUSQUEDA RAPIDA -->
			<div id='barrabusqueda' action="Busqueda.php" method="GET">
				<form>
					<input size="40" type="text" name="BusRap" placeholder="Autor, Titulo, ISBN" required>
					<input id="BusRapBot" type='submit' value='Busqueda Rapida'/>
				</form>
			</div>
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
	<?php						
								echo $_SESSION["CarritoCant"];
								echo '</a></li>';
							}
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir() 
					}
					//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE LOGEARSE
					else{
						echo '<li><a onclick="acceso()">&nbsp Iniciar sesion</a></li>';//LLAMA A LA fuction acceso()
						echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';//LLAMA A LA fuction registro()
					}
				}
				//USUARIO NO LOGEADO, SE LE OFRECE LA OPCION DE LOGEARSE
				else{
					echo '<li><a onclick="acceso()">&nbsp Iniciar Sesion</a></li>';//LLAMA A LA fuction acceso()
					echo '<li><a onclick="registro()">&nbsp Registrate</a></li>';//LLAMA A LA fuction registro()
				}
						echo '</ul>';
				?>
			</div>	
		</div>
		<!-- CUERPO -->
		<div id='cuerpo'>
			<!-- BOTONES DE DESPLAZAMIENTO -->
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
					if ($_SESSION['categoria'] == 'Administrador'){
	?>
						<li><a href="Administrador.php">Modo Administrador</a></li>
	<?php	
					}
					if (empty($_SESSION['estado'])){
	?>
						<li><a href="Registrarme.php">Registrate</a></li>
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
					MensajeAlta("<?=$AltMsg?>");	
				</script>
	<?php
			}
	?>
			<!-- CONTENIDO DE USUARIO REGISTRADO -->	
			<div id='ContCatReg'>  
	<?php		
				///CONEXIONES///						
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);	
				///CONSULTAS///
				ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>				
				<!-- TABLA DEL CATALOGO-->
				<div id='TabCatReg'>
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
								BuscarEtiquetas($row['ISBN'], $Etiq);
		?>																	
								<td><input class="botones" type='button' value='Detalle' onclick='Hojear("<?=$row['ISBN']?>", "<?=$row['Titulo']?>", "<?=$row['NombreApellido']?>", "<?=$row['Precio']?>", "<?=$row['CantidadPaginas']?>", "<?=$row['Idioma']?>", "<?=$row['Fecha']?>", "<?=$row['Disponibilidad']?>", "<?=$Etiq?>", "<?=$row['Indice']?>")' /></td>
		<?php
								if ($_SESSION['categoria'] == 'Administrador'){
	?>
									<td><input class="botones" type='button' value='Agregar a Carrito' onclick='AgregarCarrito("<?=$row['ISBN']?>","<?=$_SESSION["ID"]?>")' disabled /></td>
	<?php
								}
								else{
	?>
									<td><input class="botones" type='button' value='Agregar a Carrito' onclick='AgregarCarrito("<?=$row['ISBN']?>","<?=$_SESSION["ID"]?>")'  /></td>
	<?php
								}							
							echo "</tr>";
							$ant = $row['ISBN'];
						}
					}
					echo "</table>
				</div>";
	?>	
				<!-- FORMULARIO DE ORDENAMIENTO -->	
				<form id='OrdCatReg' action="Busqueda.php" method="GET">
					<label for="Ordenar">Ordenar:</label>
					<select class="botones" name="Orden">
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
					<input class="botones "type="submit" value="Recargar">
				</form>
				<!-- FORMULARIO DE FILTRADO -->	
				<form id='FilCatReg' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
	<?php
					echo '<select class="botones" name="Idiomas">
						<option value="">Todos los Idiomas...</option>';
						while($row = mysql_fetch_assoc($residiomas)){	
							echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
						}
					echo '</select>	
					<label class="botones" for="Marca">Disponibilidad:</label>
					<select class="botones" name="Disponibilidades">
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
					<input class="botones" id="FilCatRegBot" type="submit" value="Filtrar"></br></br>
				</form>';
				// FORMULARIOS DE BUSQUEDA //	
				// BUSQUEDA POR ISBN //
				echo '<form id="BusISCatReg" action="Busqueda.php" method="GET">
					<label for="ISBN">ISBN:</label>
					<select class="botones" name="ISBN">
						<option value="">Todos los ISBN...</option>';
						while($row = mysql_fetch_assoc($resisbn)){	
							echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
						}
					echo '</select>
					<input class="botones" type="submit" value="Buscar">
				</form>';	
				// BUSQUEDA POR AUTOR, TITULO, ETIQUETA //
				echo '<form id="BusCatReg" action="Busqueda.php" method="GET">';
				if (!empty($_GET['titulo']) && $_GET['titulo'] == 'true' && !empty($_GET['autornom'])){
					consultatitulos($restitulo, $_GET['autornom']);
					echo '<label for="Autor">Autor:</label>
					<select class="botones" name="Autor" id="idautor" onchange="determinaautor()" >
						<option value="' .$_GET['autornom'] .'">' .$_GET['autornom'] .'</option>
						<option value="">Todos los Autores...</option>';
						while($row = mysql_fetch_assoc($resautor)){	
	?>
							<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
						}
					echo '</select></br>
					<label for="Titulo">Titulo:</label>
					<select class="botones" name="Titulo">
						<option value="">Todos los Titulos de ' .$_GET['autornom'] .' </option>';
						while($row = mysql_fetch_assoc($restitulo)){	
							echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
						}
					echo '</select></br>';
				}
				else{
					echo '<label for="Autor">Autor:</label>
					<select class="botones" name="Autor" id="idautor" onchange="determinaautor()" >
						<option value="">Todos los Autores...</option>';
						while($row = mysql_fetch_assoc($resautor)){	
	?>
							<option value="<?=$row['Autor']?>"><?=$row['Autor']?></option>
	<?php	
						}
					echo '</select></br>
					<label for="Titulo">Titulo:</label>
					<select class="botones "name="Titulo">
						<option value="">Todos los Titulos...</option>';
						while($row = mysql_fetch_assoc($restitulo)){	
							echo '<option value="', $row['Titulo'], '">', $row['Titulo'], '</option>';
						}
					echo '</select></br>';
				}
					echo '<label for="Etiquetas">Etiquetas:</label></br>
					<div id="Caracteristicas">';
						while($row = mysql_fetch_assoc($resetiquetas)){	
							echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
						}
					echo '</div>';
					echo '<input class="botones" id="BusCatRegBot" type="submit" value="Buscar">
				</form>';		
				// CIERRE CONEXION //
				CerrarServidor ($con);				
			echo '</div>';
		}
		else{
	?>
			<!-- CONTENIDO DE USUARIO NO REGISTRADO -->	
			<div id='ContCatNoReg'>	
	<?php
				///CONEXIONES///						
				ConexionServidor ($con);					
				ConexionBaseDatos ($bd);	
				///GENERAR SELECCIONES///
				ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>
				<!-- TABLA DEL CATALOGO-->
				<div id='TabCatNoReg'>
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
						ConsultaOrdenamiento ($res, $_GET['Orden'], $_GET['Tabla']);	
					}
					// CATALOGO FILTRADO //
					if	(!empty($_GET['PreInf']) || !empty($_GET['PreSup']) || !empty($_GET['Idiomas'])){
						ConsultaFiltros ($res, $_GET['PreInf'], $_GET['PreSup'], $_GET['Idiomas'], $_GET['Tabla']);
					}
					// CONTROL DE CONSULTA //	
					if(!$res) {
						$message= 'Consulta invalida: ' .mysql_error() ."\n";
						die($message);
					}		
					// GENERANDO TABLA //
					$num1 = mysql_num_rows($res);
					if($num1 == 0){
						echo 'No se encontro ningun libro con dichas caracteristicas';
						$Tab = array();
					}
					else{	
						echo "<table>
								<tr>
									<th>ISBN</th>
									<th>Titulo</th>
									<th>Autor</th>
									<th>Precio</th>
								</tr>";
						$ant = ' ';
						$Tab = array();
						while($row = mysql_fetch_assoc($res)) {
							if ($row['ISBN'] != $ant){
								echo "<tr>";
									echo "<td>", $row['ISBN'], "</td>";
									echo "<td>", $row['Titulo'], "</td>";
									echo "<td>", $row['NombreApellido'], "</td>";
									echo "<td>", $row['Precio'], "</td>";										
								echo "</tr>";
								$ant = $row['ISBN'];
								array_push($Tab, $row['ISBN']);
							}
						}
						echo "</table>";		
					}	
				echo "</div>";
	?>				
				<!-- FORMULARIO DE ORDENAMIENTO -->	
				<form id='OrdCatNoReg' action="Busqueda.php" method="GET">
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
	<?php				
					foreach ($Tab as &$valor){	
							echo '<input hidden type="checkbox" name="Tabla[]" value="', $valor, '" checked="checked" required readonly >';
					}
	?>
					<input id="OrdCatNoRegBot" type="submit" value="Recargar">
				</form>
				<!-- FORMULARIO DE FILTRADO -->				
				<form id='FilCatNoReg' action="Busqueda.php" method="GET">
					<label for="Tipo">Idiomas:</label>
	<?php		
					echo '<select name="Idiomas">
							<option value="">Todos los Idiomas...</option>';
							while($row = mysql_fetch_assoc($residiomas)){	
								echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
							}
					echo '</select></br></br>
						<label for="CantPag">Precio:</label></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreInf">Mayor que:</label><input type="text" id="IdPrecioInf" name="PreInf" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMayor()" ></br>
						&nbsp;&nbsp;&nbsp;&nbsp;<label for="PreSup">Menor que:</label><input type="text" id="IdPrecioSup" name="PreSup" placeholder="Ej: 97.85" maxlength="10" onkeypress="return NumerosPunto(event);" onblur="validarPrecioMenor()" ></br>';					
						foreach ($Tab as &$valor){	
							echo '<input hidden type="checkbox" name="Tabla[]" value="', $valor, '" checked="checked" required readonly >';
						}
						echo '<input id="FilCatNoRegBot" type="submit" value="Filtrar"></br></br>
				</form>';
				// FORMULARIOS DE BUSQUEDA //	
				// BUSQUEDA POR ISBN //
				echo '<form id="BusISCatNoReg" action="Busqueda.php" method="GET">
					<label for="ISBN">ISBN:</label>
					<select name="ISBN">
						<option value="">Todos los ISBN...</option>';
						while($row = mysql_fetch_assoc($resisbn)){	
							echo '<option value="', $row['ISBN'], '">', $row['ISBN'], '</option>';
						}
					echo '</select>
					<input id="BusISCatNoRegBot" type="submit" value="Buscar"></br>
				</form>';
				// BUSQUEDA POR AUTOR, TITULO //
				echo '<form id="BusCatNoReg" action="Busqueda.php" method="GET">';
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
					echo '<input id="BusCatNoRegBot" type="submit" value="Buscar">				 
				</form>';
				// CIERRE CONEXION //
				CerrarServidor ($con);
			echo '</div>';
		}
	?>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Dirección : Calle 30 N 416  - La Plata - Argentina | Teléfono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resolución Minima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright © 2014 CookBook – Todos los derechos reservados.</samp>
		</div>
	</body>
</html>