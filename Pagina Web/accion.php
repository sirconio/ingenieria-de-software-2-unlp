<?php
	session_start();
	function ConexionServidor (&$con){
		$con = mysql_connect('127.0.0.1', 'Fernando', 'Gimnasia13.');
		if (!$con){
			die('NO PUDO CONECTARSE: ' .mysql_error());
		}
	}
	function CerrarServidor (&$con){
		mysql_close($con);
	}
	function ConexionBaseDatos (&$bd){
		$bd = mysql_select_db('cookbook') or die('La Base de Datos no se pudo seleccionar');
	}
	function IniciarSesion (&$usuario, &$clave, &$msg){
		session_start();
		$cons = 'SELECT usuario.Nombre, usuario.Password, usuario.Categoria, usuario.Id_Usuario, usuario.Visible, usuario.CantCarrito
								FROM usuario
								ORDER BY usuario.Id_Usuario';
		$resUsuarioClave = mysql_query($cons);
		$num = mysql_num_rows($resUsuarioClave);
		$entro = false;
		if($num != 0){
			while($row = mysql_fetch_assoc($resUsuarioClave)){
				if ($usuario == $row['Nombre'] && $clave == $row['Password'] && $row['Visible'] == 1){
					$entro = true;
					$_SESSION['estado'] = 'logeado';
					$_SESSION['usuario'] = $usuario;
					$_SESSION['categoria'] = $row['Categoria'];
					$_SESSION['ID'] = $row['Id_Usuario'];
					$_SESSION['CarritoCant'] = $row['CantCarrito'];
					$msg = "Acceso Permitido, Haga Click en el Boton para Ingresar</br><button onclick='Entrar()'>Entrar</button>";
					break;
				}
			}
			if (!$entro){
				$msg = "<a href='InicioSesion.php'> Acceso Denegado, Haga Click Aqui Para Intentar Nuevamente</a>";
			}
		}
	}
	function CerrarSesion (){
		session_start();
		session_unset();
		session_destroy();
	?>
		<script languaje="javascript"> 	
			location.href="index.php";
		</script>
	<?php
	}
	function ConsultarUsuarios (&$res){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, "-----" as Password, usuario.Categoria, cliente.*,usuario.Visible as Estado 
						FROM usuario, cliente
						WHERE usuario.DNI = cliente.DNI
						ORDER BY ID'); 
		$res = mysql_query ($cons);
	}
	function DatosUsuario(&$res, $ID){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre, usuario.Password, usuario.Visible As Estado, cliente.* 
						FROM usuario, cliente
						WHERE usuario.Id_Usuario = ' .$ID .'
						AND usuario.DNI = cliente.DNI'); 
		$res = mysql_query ($cons);
	}
	function ModUsuario ($ID, $NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, &$AltMsg){
		$cons = 'UPDATE cliente 
					SET NombreApellido = "'.$NomApe .'",
						Telefono = "' .$Tel .'",
						Direccion = "' .$Dir .'",
						Contacto = "' .$Mail.'" 
					WHERE cliente.DNI =' .$DNI;
		$res = mysql_query ($cons);
		$cons1 = 'UPDATE usuario 
					SET Nombre = "'.$NomUs .'"
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res && !$res1) {
				$AltMsg = "La modificacion no pudo realizarse";
		}	
		elseif (!$res){
				$AltMsg = "Nombre de Usuario no modificado";
		}
		elseif (!$res1){
				$AltMsg = "Datos personales no modificados";
		}
		else {
			$AltMsg = "Modificacion satisfactoria";
		}
	}
	function ModClaves ($ID, $PassActual, $Pass1, $Pass2, &$AltMsg){
		$cons = ('SELECT usuario.Password 
					FROM usuario
					WHERE usuario.Id_Usuario = ' .$ID); 
		$res = mysql_query ($cons);
		$row = mysql_fetch_assoc($res);
		if ($PassActual == $row['Password']){
			if ($Pass1 == $Pass2){
				$cons = 'UPDATE usuario 
					SET Password = "'.$Pass1 .'" 
					WHERE usuario.Id_Usuario =' .$ID;
				$res = mysql_query ($cons);
				$AltMsg = "La contraseña fue modificada";
			}
			else{
				$AltMsg = "Las Contraseña no coinciden";
			}
		}
		else{
			$AltMsg = "Contraseña incorrecta";
		}
	}
	function AltaUsuario($NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, $Pass1, $Pass2, &$AltMsg){
		if ($Pass1 == $Pass2){
			$today = getdate();
			$Fecha = $today[year]. '-' .$today[mon]. '-'. $today[mday];
			if	(!empty($Tel)){$Telf = $Tel;}else{$Telf = Null;}
			if	(!empty($Dir)){$Dirc = $Dir;}else{$Dirc = Null;}
			$cons = 'INSERT INTO `cookbook`.`cliente` (`DNI` ,`NombreApellido` ,`FechaAlta` ,`Telefono` ,`Direccion` ,`Contacto`)
					VALUES ("' .$DNI .'", "' .$NomApe .'", "' .$Fecha .'", "' .$Telf .'", "' .$Dirc .'", "' .$Mail .'")';
			$res = mysql_query($cons);
			$cons2 = 'INSERT INTO `cookbook`.`usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Contacto`, `Visible`, `CantCarrito`) 
					VALUES (NULL, "' .$NomUs .'", "' .$Pass1 .'", "Normal","' .$DNI .'", "' .$Mail .'", 1, 0)';
			$res1 = mysql_query($cons2);
			if(!$res || !$res1) {
				$AltMsg = "Usuario no pudo agregarse";
			}	
			else{
				$AltMsg = "Usuario agregado satisfactoriamente";
			}
		}
		else{
			$AltMsg = "Las Contraseña no coinciden";
		}
	}
	function BajaUsuario($ID, &$AltMsg){
		$cons1 = 'UPDATE usuario 
					SET Visible = 0 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Usuario no se pudo borrar";
		}	
		else{
			$AltMsg = "Borrado satisfactorio";
		}
	}
	function ActivarUsuario($ID, &$AltMsg){
		$cons1 = 'UPDATE usuario 
					SET Visible = 1 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Usuario no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
		}	
	}
	function UsuarioPeriodo (&$res, $Fini, $Ffin){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, cliente.DNI, cliente.NombreApellido, cliente.FechaAlta,usuario.Visible as Estado 
					FROM usuario, cliente
					WHERE usuario.DNI = cliente.DNI
					AND	cliente.FechaAlta BETWEEN "' .$Fini. '" AND "' .$Ffin. '"
					ORDER BY ID'); 
		$res = mysql_query ($cons);
	}
	function ConsultaPedidos (&$res, $ID){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC';
		$res = mysql_query( $cons);
	}
	function ConsultarPedidos (&$res){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC';
		$res = mysql_query( $cons);
	}
	function ConsultarPedidosPend (&$res){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 1
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC';
		$res = mysql_query( $cons);
	}	
	function ConsultarPedidosEnv (&$res){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 2
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC';
		$res = mysql_query( $cons);
	}
	function ConsultarPedidosEnt (&$res){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 3
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC';
		$res = mysql_query( $cons);
	}
	function PedidoEntregado ($ISBN, $DNI, &$Msj){
		$cons = 'UPDATE pedidos 
					SET Id_Estado = 3 
					WHERE ISBN = ' .$ISBN. '
					AND DNI = ' .$DNI;
		$res = mysql_query( $cons);		
		if (!$res){
			$Msj = "Estado no modificado";
		}
		else{
			$Msj = "Estado modificado con exito";
		}
	}
	function PedidoEnviado($ISBN, $DNI, &$Msj){
		$cons = 'UPDATE pedidos 
					SET Id_Estado = 2 
					WHERE ISBN = ' .$ISBN. '
					AND DNI = ' .$DNI;
		$res = mysql_query( $cons);		
		if (!$res){
			$Msj = "Estado no modificado";
		}
		else{
			$Msj = "Estado modificado con exito";
		}
	}
	function ConsultaCarrito (&$res, $ID){
		$cons = 'SELECT usuario.DNI, usuario.Nombre, libro.ISBN, libro.Titulo, autor.NombreApellido, libro.Precio
				FROM usuario, carrito, libro, autor
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.Id_Usuario = carrito.Id_Usuario
				AND carrito.ISBN = libro.ISBN
				AND libro.Id_Autor = autor.Id_Autor';
		$res = mysql_query( $cons);
	}
	function AgregarCarrito($ISBN, $ID, &$Msj){
		$cons = 'INSERT INTO `cookbook`.`carrito` (`Id_Usuario` ,`ISBN`)VALUES (' .$ID. ', ' .$ISBN. ')';
		$res = mysql_query( $cons);
		if (!$res){
			$Msj = "No se pudo agregar al carrito";
		}
		else{
			$cons1 = 'SELECT usuario.CantCarrito 
						FROM usuario 
						WHERE Id_Usuario = ' .$ID;
			$res1 = mysql_query ($cons1);
			while($row = mysql_fetch_assoc($res1)){
				$cant = $row['CantCarrito'] + 1;
				$cons2 = 'UPDATE usuario 
							SET CantCarrito = ' .$cant. ' 
							WHERE Id_Usuario = ' .$ID;
				$res2 = mysql_query ($cons2);
			$_SESSION['CarritoCant'] = $cant;
			$Msj = "Agregado con exito al carrito";
			}
		}
	}
	function RetirarCarrito($ISBN, $ID, &$Msj){
		$cons =	'DELETE FROM `carrito` WHERE `carrito`.`Id_Usuario` = ' .$ID. ' AND `carrito`.`ISBN` = ' .$ISBN;
		$res = mysql_query( $cons);
		$cons1 = 'SELECT usuario.CantCarrito 
					FROM usuario 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		while($row = mysql_fetch_assoc($res1)){
			$cant = $row['CantCarrito'] - 1;
			$cons2 = 'UPDATE usuario 
						SET CantCarrito = ' .$cant. ' 
						WHERE Id_Usuario = ' .$ID;
			$res2 = mysql_query ($cons2);
			$_SESSION['CarritoCant'] = $cant;
		}
		if (!$res){
			$Msj = "No se pudo borrar del carrito";
		}
		else{
			$Msj = "Borrado con exito del carrito";
		}
	}
	function VaciarCarrito($ID, &$Msj){
		$cons =	'DELETE FROM carrito WHERE carrito.Id_Usuario = ' .$ID;
		$res = mysql_query( $cons);
		$cons2 = 'UPDATE usuario 
					SET CantCarrito = 0 
					WHERE Id_Usuario = ' .$ID;
		$res2 = mysql_query ($cons2);
		$_SESSION['CarritoCant'] = 0;
		if (!$res){
			$Msj = "No se pudo vaciar el carrito";
		}
		else{
			$Msj = "Carrito vaciado con exito";
		}
	}
	function ComprarCarrito($ID, &$AltMsg){
		$cons = 'SELECT carrito.ISBN
				FROM carrito
				WHERE carrito.ID_Usuario = ' .$ID;
		$res = mysql_query( $cons);
		$cons1 = 'SELECT usuario.DNI
				FROM usuario
				WHERE usuario.ID_Usuario = ' .$ID;
		$res1 = mysql_query( $cons1);
		$today = getdate();
		$Fecha = $today[year]. '-' .$today[mon]. '-'. $today[mday];
		$Msg = 'Libros comprados con exito: ';
		$Msg2 = 'Operaciones Fallidas: ';
		while($row1 = mysql_fetch_assoc($res1)){
			while($row = mysql_fetch_assoc($res)){
				$cons2 = 'INSERT INTO `cookbook`.`pedidos` (`ISBN` ,`DNI` ,`FechaPedido` ,`Id_Estado`)VALUES (' .$row['ISBN']. ', ' .$row1['DNI'].', "' .$Fecha. '", 1)';
				$res2 = mysql_query( $cons2);
				if ($res2) {
					$Msg = $Msg .'' .$row['ISBN']. '; ';
					RetirarCarrito($row['ISBN'], $ID, $Msj);
				}
				else{
					$Msg2 = $Msg2 .'' .$row['ISBN']. '; ';
				}
			}
		}
		$AltMsg = $Msg. ' /// ' .$Msg2;
	}
	function LibroPeriodo (&$res, $Fini, $Ffin){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, autor.NombreApellido AS Autor, pedidos.DNI, cliente.NombreApellido AS Cliente, pedidos.FechaPedido
				FROM cliente, pedidos, libro, autor
				WHERE cliente.DNI = pedidos.DNI
				AND libro.Id_Autor = autor.Id_Autor
				AND pedidos.ISBN = libro.ISBN
				AND	pedidos.FechaPedido BETWEEN "' .$Fini. '" AND "' .$Ffin. '"';
		$res = mysql_query( $cons);
	}
	function BuscarEtiq ($IS, &$LibEtiq){
		$LibEtiq = mysql_query('SELECT Descripcion
			FROM etiqueta_Libro, etiqueta	
			WHERE ISBN = ' .$IS .'
			AND etiqueta_Libro.Id_Etiqueta = etiqueta.Id_Etiqueta');
	}
	function ConsultaLibro (&$res, $ISBN){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible as Estado
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND libro.ISBN=' .$ISBN);
	}
	function BajaLibro($ISBN, &$AltMsg){
		$cons1 = 'UPDATE libro 
					SET Visible = 0 
					WHERE ISBN = ' .$ISBN;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Libro no se pudo borrar";
		}	
		else{
			$AltMsg = "Borrado satisfactorio";
		}	
	}
	function ActivarLibro($ISBN, &$AltMsg){
		$cons1 = 'UPDATE libro 
					SET Visible = 1 
					WHERE ISBN = ' .$ISBN;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Libro no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
		}	
	}	
	function AltaLibro($IS, $Tit, $Aut, $CPag, $Pre, $Idio, $Fec, $Etiq, &$AltMsg){
		$RAut = mysql_query('SELECT Id_Autor
							FROM autor
							WHERE NombreApellido = "'. $Aut .'"');
		$Arow = mysql_fetch_assoc($RAut);
		$RIdio = mysql_query('SELECT Id_Idioma
							FROM idioma
							WHERE Descripcion = "'. $Idio .'"');
		$Irow = mysql_fetch_assoc($RIdio);
		$cons1 ='INSERT INTO `cookbook`.`libro` (`ISBN` ,`Titulo` ,`Id_Autor` ,`CantidadPaginas` ,`Precio` ,`Id_Idioma` ,`Fecha` ,`Id_Disponibilidad` ,`Visible` ,`Hojear`)
			VALUES (' .$IS .', "' .$Tit .'", ' .$Arow['Id_Autor'] .',' .$CPag .' , ' .$Pre .', ' .$Irow['Id_Idioma'] .', "' .$Fec .'", 1, 1, NULL)';
		$res1 = mysql_query ($cons1);
		if (!empty($Etiq)){		
			foreach ($Etiq as &$valor){
				$REtiq = mysql_query('SELECT Id_Etiqueta
							FROM etiqueta
							WHERE Descripcion = "'. $valor .'"');
				$Erow = mysql_fetch_assoc($REtiq);
				$consetiq = 'INSERT INTO Etiqueta_Libro (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (NULL, "'.$Erow['Id_Etiqueta'] .'", "'.$IS .'")';
				mysql_query($consetiq);
			}			
		}
		if(!$res1) {
			$AltMsg = "El Libro no se agrego correctamente";
		}	
		else{
			$AltMsg = "El Libro se agrego correctamente";
		}
	}
	function ModLibro ($IS, $Tit, $Aut, $CPag, $Pre, $Idio, $Fec, $Etiq, $Disp, &$AltMsg){
		$RAut = mysql_query('SELECT Id_Autor
							FROM autor
							WHERE NombreApellido = "'. $Aut .'"');
		$Arow = mysql_fetch_assoc($RAut);
		$RIdio = mysql_query('SELECT Id_Idioma
							FROM idioma
							WHERE Descripcion = "'. $Idio .'"');
		$Irow = mysql_fetch_assoc($RIdio);
		$RDis = mysql_query('SELECT Id_Disponibilidad
							FROM disponibilidad
							WHERE Descripcion = "'. $Disp .'"');
		$Drow = mysql_fetch_assoc($RDis);
		$cons = 'UPDATE libro 
			SET Titulo = "'.$Tit .'",
				Id_Autor = "' .$Arow['Id_Autor'] .'",
				CantidadPaginas = "' .$CPag .'",
				Precio = "' .$Pre .'",
				Id_Idioma = "' .$Irow['Id_Idioma'] .'",
				Fecha = "' .$Fec .'",
				Id_Disponibilidad = "' .$Drow['Id_Disponibilidad'] .'"
			WHERE ISBN =' .$IS;
		$res = mysql_query ($cons);
		$Eres = mysql_query ('SELECT Id_EtiquetaLibro
							FROM etiqueta_Libro
							WHERE ISBN =' .$IS);				
		while ($row = mysql_fetch_assoc($Eres)){					
			$conscar = 'DELETE FROM etiqueta_Libro WHERE etiqueta_Libro.Id_EtiquetaLibro =' .$row["Id_EtiquetaLibro"];
			mysql_query ($conscar);
		}	
		if (!empty($Etiq)){
			foreach ($Etiq as &$valor){
				$REtiq = mysql_query('SELECT Id_Etiqueta
							FROM etiqueta
							WHERE Descripcion = "'. $valor .'"');
				$Erow = mysql_fetch_assoc($REtiq);
				$consetiq = 'INSERT INTO Etiqueta_Libro (`Id_EtiquetaLibro`, `Id_Etiqueta`, `ISBN`) VALUES (NULL, "'.$Erow['Id_Etiqueta'] .'", "'.$IS .'")';
				mysql_query($consetiq);		
			}
		}	
		if(!$res) {
			$AltMsg = "Lo modificacion no se pudo realizar";
		}	
		else{
			$AltMsg = "Modificacion Satisfactoria";
		}
	}
	function AgregarAutor ($NomApe, &$AltMsg){
		$cons = 'INSERT INTO autor (`Id_Autor` ,`NombreApellido`)
							VALUES (NULL , "' .$NomApe .'")';
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "El alta del autor no se pudo realizar";
		}	
		else{
			$AltMsg = "Autor agregado Satisfactoriamente";
		}
	}
	function AgregarIdioma ($NomIdo, &$AltMsg){
		$cons = 'INSERT INTO idioma (`Id_Idioma` ,`Descripcion`)
										VALUES (NULL , "' .$NomIdo .'")';
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "El alta del idioma no se pudo realizar";
		}	
		else{
			$AltMsg = "Idioma agregado Satisfactoriamente";
		}
	}
	function AgregarEtiqueta ($NomEtq, &$AltMsg){
		$cons = 'INSERT INTO etiqueta (`Id_Etiqueta` ,`Descripcion`)
											VALUES (NULL , "' .$NomEtq .'")';
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "El alta de la etiqueta no se pudo realizar";
		}	
		else{
			$AltMsg = "Etiqueta agregado Satisfactoriamente";
		}
	}
	function ConsultaPorDefecto (&$res){
	$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad');
	}
	function ConsultasSelect (&$residiomas, &$resdisp, &$resetiquetas, &$resautor, &$restitulo, &$resisbn){
		$residiomas = mysql_query('SELECT idioma.Descripcion as Idioma
								FROM idioma
								ORDER BY idioma.Descripcion');						
		$resdisp = mysql_query('SELECT disponibilidad.Descripcion as Disponibilidad
								FROM disponibilidad
								ORDER BY disponibilidad.Descripcion');
		$resetiquetas = mysql_query('SELECT etiqueta.Descripcion as Etiqueta
								FROM etiqueta
								ORDER BY etiqueta.Descripcion');
		$resautor = mysql_query('SELECT autor.NombreApellido as Autor
								FROM autor
								ORDER BY autor.NombreApellido');
		$restitulo = mysql_query('SELECT libro.Titulo as Titulo
								FROM libro
								ORDER BY libro.Titulo');
		$resisbn = mysql_query('SELECT libro.ISBN as ISBN
								FROM libro
								ORDER BY libro.ISBN');								
	}	
	function consultatitulos(&$restitulo, $autornom){
		$restitulo = mysql_query('SELECT libro.Titulo as Titulo
								FROM libro, autor
								WHERE autor.Id_Autor = libro.Id_Autor
								AND autor.NombreApellido = "'.$autornom .'"
								ORDER BY libro.Titulo');
	}
	function ConsultaOrdenamiento (&$res, &$orden){
		if ($orden == 'PrecAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Precio ASC');
		}
		elseif ($orden == 'PrecDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Precio DESC');
		}
		elseif  ($orden == 'TitAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Titulo ASC');
		}						
		elseif  ($orden == 'TitDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Titulo DESC');
		}
		elseif  ($orden == 'AutAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY autor.NombreApellido ASC');
		}						
		elseif  ($orden == 'AutDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY autor.NombreApellido DESC');
		}			
		elseif  ($orden == 'ISBNAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.ISBN ASC');
		}						
		elseif  ($orden == 'ISBNDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.ISBN DESC');
		}
		elseif  ($orden == 'CPAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.CantidadPaginas ASC');
		}						
		elseif  ($orden == 'CPDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.CantidadPaginas DESC');
		}	
		elseif  ($orden == 'FecAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Fecha ASC');
		}						
		elseif  ($orden == 'FecDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY libro.Fecha DESC');
		}
		elseif  ($orden == 'IdioAsc') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY idioma.Descripcion ASC');
		}						
		elseif  ($orden == 'IdioDes') {
			$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
								ORDER BY idioma.Descripcion DESC');
		}			
	}
	function ConsultaBusqueda (&$res, &$Aut, &$Tit, &$IS){
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN;		
		$res = mysql_query($cons);
	}
	function ConsultaBusca (&$res, &$C){	
		$carts = ' AND Caracteristicas.Caracteristica IN (';
		$temp = '';
		foreach ($C as &$valor){
				$temp = $temp .'"' .$valor .'",';
		}
		$carts = $carts . $temp . '"")';
		$cons = 'SELECT Marcas.Marca, Modelos.Modelo, Tipos.Tipo, Vehiculos.Dominio, Vehiculos.Anio, Vehiculos.Precio
				FROM Marcas, Modelos, Tipos, Vehiculos, Caracteristicas, Vehiculos_Caracteristicas
				WHERE Marcas.idMarca = Modelos.idMarca
				AND Modelos.idModelo = Vehiculos.idModelo
				AND Tipos.idTipo = Vehiculos.idTipo
				AND Vehiculos.idVehiculo = Vehiculos_Caracteristicas.idVehiculo
				AND Caracteristicas.idCaracteristica = Vehiculos_Caracteristicas.idCaracteristica'
				.$carts;
		$res = mysql_query($cons);
	}
	function ConsultaBusqueda2 (&$res, &$Aut, &$Tit, &$IS, &$Et){
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		if (!empty($Et)){
			$eti = ' AND etiqueta.Descripcion IN (';
			$temp = '';
			foreach ($Et as &$valor){
					$temp = $temp .'"' .$valor .'",';
			}
			$eti = $eti . $temp . '"")';
		}
		else{
			$eti = '';
		}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, 
				disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad, etiqueta, etiqueta_libro
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.ISBN = etiqueta_libro.ISBN
				AND etiqueta.Id_Etiqueta = etiqueta_libro.Id_Etiqueta
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN .''
				.$eti;		
		$res = mysql_query($cons);
	}
	// and libro.ISBN IN (res) lo mismo para ordenar
	function ConsultaFiltros (&$res, $Pinf, $Psup, $Idio){
		if	(!empty($Pinf)){$PreInf = '"'.$Pinf.'"';}else{$PreInf = 'libro.Precio';}
		if	(!empty($Psup)){$PreSup = '"'.$Psup.'"';}else{$PreSup = 'libro.Precio';}
		if	(!empty($Idio)){$Idioma = '"'.$Idio.'"';}else{$Idioma = 'idioma.Descripcion';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND idioma.Descripcion = "' .$idioma .'"
				AND libro.Precio >= ' .$PreInf .'
				AND libro.Precio <= ' .$PreSup;
		$res = mysql_query($cons);
	}
	function ConsultaFiltros2 (&$res, &$Preinf, &$Presup, &$Idio, &$Disp, &$Paginf, &$Pagsup, &$Finf, &$Fsup){
		if	(!empty($Preinf)){$PrecInf = '"'.$Preinf.'"';}else{$PrecInf = 'libro.Precio';}
		if	(!empty($Presup)){$PrecSup = '"'.$Presup.'"';}else{$PrecSup = 'libro.Precio';}
		if	(!empty($Idio)){$Idioma = '"'.$Idio.'"';}else{$Idioma = 'idioma.Descripcion';}
		if	(!empty($Disp)){$Dispo = '"'.$Disp.'"';}else{$Dispo = 'disponibilidad.Descripcion';}
		if	(!empty($Paginf)){$PagiInf = '"'.$Paginf.'"';}else{$PagiInf = 'libro.CantidadPaginas';}
		if	(!empty($Pagsup)){$PagiSup = '"'.$Pagsup.'"';}else{$PagiSup = 'libro.CantidadPaginas';}
		if	(!empty($Finf)){$FecInf = $Finf;}else{$FecInf = 'libro.Fecha';}
		if	(!empty($Fsup)){$FecSup = $Fsup;}else{$FecSup = 'libro.Fecha';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'	
				AND idioma.Descripcion = ' .$Idioma .'
				AND disponibilidad.Descripcion = ' .$Dispo .'
				AND libro.CantidadPaginas >= ' .$PagiInf .'
				AND libro.CantidadPaginas <= ' .$PagiSup .'
				AND libro.Fecha >= ' .$FecInf .'
				AND libro.Fecha <= ' .$FecSup;		
		$res = mysql_query($cons);
	}
	function ConsultaBusquedaRapida (&$res, &$BusRap){
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND ( libro.Titulo LIKE "%' .$BusRap .'%" 
				OR    autor.NombreApellido LIKE "%' .$BusRap .'%" 
				OR	  libro.ISBN LIKE "%'.$BusRap .'%")';			
		$res = mysql_query($cons);
	}
?>