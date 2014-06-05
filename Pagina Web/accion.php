<?php
	session_start();
// GESTION DE CONEXIONES //
	// CONEXION CON EL SERVIDOR //
	function ConexionServidor (&$con){
		$con = mysql_connect('127.0.0.1', 'Fernando', 'Gimnasia13.');
		if (!$con){
			die('NO PUDO CONECTARSE: ' .mysql_error());
		}
	}
	// CERRAR CONEXION CON EL SERVIDOR //
	function CerrarServidor (&$con){
		mysql_close($con);
	}
	// CONEXION CON LA BASE DE DATOS //
	function ConexionBaseDatos (&$bd){
		$bd = mysql_select_db('cookbook') or die('La Base de Datos no se pudo seleccionar');
	}
// GESTION DE SESIONES //
	// INICIO DE SESION //
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
	// CERADO DE SESION //
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
// GESTION DE USUARIOS //
	// CONSULTA TODOS LOS USUARIOS //
	function ConsultarUsuarios (&$res){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, "-----" as Password, usuario.Categoria, cliente.*,usuario.Visible as Estado 
						FROM usuario, cliente
						WHERE usuario.DNI = cliente.DNI
						ORDER BY ID'); 
		$res = mysql_query ($cons);
	}
	// CONSULTA TODOS LOS USUARIOS PAGINADO//
	function ConsultarUsuariosPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, "-----" as Password, usuario.Categoria, cliente.*,usuario.Visible as Estado 
						FROM usuario, cliente
						WHERE usuario.DNI = cliente.DNI
						ORDER BY ID
						LIMIT ' .$pag .',10';						
		$res = mysql_query ($cons);
	}
	// CONSULTA USUARIO CON ID //
	function DatosUsuario(&$res, $ID){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre, usuario.Password, usuario.Visible As Estado, cliente.* 
						FROM usuario, cliente
						WHERE usuario.Id_Usuario = ' .$ID .'
						AND usuario.DNI = cliente.DNI'); 
		$res = mysql_query ($cons);
	}
	// BAJA LOGICA DE USUARIO //
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
	// ACTIVACION DE USUARIO //
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
	// MODIFICAR USUARIO CON ID //
	function ModUsuario (&$Con, $ID, $NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, &$AltMsg){
		ComprobarNomUs ($ID, $NomUs,$Flag);
		if ($Flag){
			$AltMsg = "Ya se encuentra registrado un cliente con dicho Nombre de Usuario";
		}
		else{
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
				$Con = true;
			}
		}
	}
	// CAMBIAR CLAVE //
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
	// ALTA DE USUARIOS //
	function AltaUsuario($Cons, $NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, $Pass1, $Pass2, &$AltMsg){
		if ($Pass1 == $Pass2){
			ComprobarDNI ($DNI,$Flag);
			if ($Flag){
				$AltMsg = "Ya se encuentra registrado un cliente con dicho DNI";
			}
			else{
				ComprobarNomUs ($ID, $NomUs,$Flag2);
				if ($Flag2){
					$AltMsg = "Ya se encuentra registrado un cliente con dicho Nombre de Usuario";
				}
				else{
					$today = getdate();
					$Fecha = $today[year]. '-' .$today[mon]. '-'. $today[mday];
					if	(!empty($Tel)){$Telf = $Tel;}else{$Telf = Null;}
					if	(!empty($Dir)){$Dirc = $Dir;}else{$Dirc = Null;}
					$cons = 'INSERT INTO cliente (`DNI` ,`NombreApellido` ,`FechaAlta` ,`Telefono` ,`Direccion` ,`Contacto`)
							VALUES ("' .$DNI .'", "' .$NomApe .'", "' .$Fecha .'", "' .$Telf .'", "' .$Dirc .'", "' .$Mail .'")';
					$res = mysql_query($cons);
					$cons2 = 'INSERT INTO usuario (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Visible`, `CantCarrito`) 
							VALUES (NULL, "' .$NomUs .'", "' .$Pass1 .'", "Normal","' .$DNI .'", 1, 0)';
					$res1 = mysql_query($cons2);
					if(!$res || !$res1){
						$AltMsg = "Usuario no pudo agregarse";
						if (!$res){
							$cons =	'DELETE FROM usuario WHERE usuario.DNI = ' .$DNI;
							$res = mysql_query( $cons);
						}
						else{
							$cons =	'DELETE FROM cliente WHERE cliente.DNI = ' .$DNI;
							$res = mysql_query( $cons);
						}
					}	
					else{
						$AltMsg = "Usuario agregado satisfactoriamente";
						$Cons = true;
					}
				}	
			}	
		}
		else{
			$AltMsg = "Las Contraseña no coinciden";
		}
	}
	// COMPRUEBA DNI UNICO //
	function ComprobarDNI ($Dniphp, &$correcto){
		$res = mysql_query ('SELECT DNI FROM cliente');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($Dniphp == $row['DNI']){
				$correcto = true;
				break;
			}
		}
	}
	// COMPRUEBA NOMBRE USUARIO UNICO //
	function ComprobarNomUs ($ID, $NomUs,&$correcto){
		$res = mysql_query ('SELECT Nombre FROM usuario WHERE Id_Usuario <> ' .$ID);
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($NomUs == $row['Nombre']){
				$correcto = true;
				break;
			}
		}
	}
	// USUARIOS REGISTRADOS EN UN PERIODO //
	function UsuarioPeriodo (&$res, $Fini, $Ffin){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, cliente.DNI, cliente.NombreApellido, cliente.FechaAlta,usuario.Visible as Estado 
					FROM usuario, cliente
					WHERE usuario.DNI = cliente.DNI
					AND	cliente.FechaAlta BETWEEN "' .$Fini. '" AND "' .$Ffin. '"
					ORDER BY ID'); 
		$res = mysql_query ($cons);
	}
// GESTION DE PEDIDOS //
	// CONSULTAR TODOS LOS PEDIDOS //
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
	// CONSULTAR TODOS LOS PEDIDOS PAGINADOS //
	function ConsultarPedidosPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR PEDIDO CON ID //
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
	// CONSULTAR PEDIDOS PENDIENTES //
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
	// CONSULTAR PEDIDOS PENDIENTES PAGINADO //
	function ConsultarPedidosPendPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 1
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}	
	// CONSULTAR PEDIDOS ENVIDADOS //
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
	// CONSULTAR PEDIDOS ENVIDADOS PAGINADO //
	function ConsultarPedidosEnvPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 2
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR PEDIDOS ENTREGADOS //
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
	// CONSULTAR PEDIDOS ENTREGADOS PAGINADO //
	function ConsultarPedidosEntPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 3
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CAMBIAR PEDIDO A ENTREGADO //
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
	// CAMBIAR PEDIDO A ENVIADO //
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
// GESTION DEL CARRITO DE COMPRAS //
	// CONSULTAR CARRITO CON ID //
	function ConsultaCarrito (&$res, $ID){
		$cons = 'SELECT usuario.DNI, usuario.Nombre, libro.ISBN, libro.Titulo, autor.NombreApellido, libro.Precio
				FROM usuario, carrito, libro, autor
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.Id_Usuario = carrito.Id_Usuario
				AND carrito.ISBN = libro.ISBN
				AND libro.Id_Autor = autor.Id_Autor';
		$res = mysql_query( $cons);
	}
	// AGREGAR LIBRO-ISBN A CARRITO CON ID //
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
	// RETIRAR LIBRO-ISBN DEL CARRITO CON ID //
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
	// VACIAR CARRITO CON ID //
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
	// EFECTIVIZAR COMPRA DEL CARRITO //
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
// GESTION DE LIBROS //
	// CONSULTA LIBRO CON ISBN //
	function ConsultaLibro (&$res, $ISBN){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible as Estado
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND libro.ISBN=' .$ISBN);
	}
	// BAJA LOGICA DE LIBRO CON ISBN //
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
	// ACTIVAR LIBRO //
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
	// CONSULTA ETIQUETAS DE UN ISBN //
	function BuscarEtiq ($IS, &$LibEtiq){
		$LibEtiq = mysql_query('SELECT Descripcion
			FROM etiqueta_Libro, etiqueta	
			WHERE ISBN = ' .$IS .'
			AND etiqueta_Libro.Id_Etiqueta = etiqueta.Id_Etiqueta');
	}	
	// ALTA DE LIBRO //
	function AltaLibro(&$Con, $IS, $Tit, $Aut, $CPag, $Pre, $Idio, $Fec, $Etiq, &$AltMsg){
		ComprobarISBN ($IS,$Flag);
		if ($Flag){
			$AltMsg = "Ya se encuentra registrado un libro con dicho ISBN";
		}
		else{
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
				$Con = true;
			}
		}
	}
	// COMPROBAR ISBN UNICO //
	function ComprobarISBN ($ISBN, &$correcto){
		$res = mysql_query ('SELECT ISBN FROM libro');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($ISBN == $row['ISBN']){
				$correcto = true;
				break;
			}
		}
	}
	// MODIFICACION DE LIBRO //
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
	// TOP 10 LIBROS MAS VENDIDOS EN UN PERIODO //	
	function LibroPeriodo (&$res, $Fini, $Ffin){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, autor.NombreApellido AS Autor, pedidos.DNI, cliente.NombreApellido AS Cliente, pedidos.FechaPedido
				FROM cliente, pedidos, libro, autor
				WHERE cliente.DNI = pedidos.DNI
				AND libro.Id_Autor = autor.Id_Autor
				AND pedidos.ISBN = libro.ISBN
				AND	pedidos.FechaPedido BETWEEN "' .$Fini. '" AND "' .$Ffin. '"';
		$res = mysql_query( $cons);
	}
// GESTION DE AUTOR //	
	// CONSULTAR AUTORES //
	function ConsultaAutores (&$res){
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor
		FROM autor
		ORDER BY Id_Autor ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTORES PAGINADO //
	function ConsultaAutoresPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor
				FROM autor
				ORDER BY Id_Autor ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTOR POR NOMBRE //
	function ConsultaAutor (&$res, $AutorNom){
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor
				FROM autor
				WHERE NombreApellido = "'.$AutorNom. '"';
		$res = mysql_query( $cons);
	}
	// AGERGAR UN AUTOR //
	function AgregarAutor ($NomApe, &$AltMsg){
		ComprobarAutor ($NomApe,$Flag);
		if ($Flag){
			$AltMsg = "Ya se encuentra registrado un Autor con dicho Nombre";
		}
		else{
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
	}	
	// BAJA AUTOR //
	function BajaAutor ($ID, &$AltMsg){
		$Comp = 'SELECT * FROM libro WHERE Id_Autor = ' .$ID;
		$RComp = mysql_query ($Comp);
		$num1 = mysql_num_rows($RComp);
		if($num1 != 0){
			$AltMsg = "La baja del autor no se pudo realizar, existen libros con dicho autor registrado";
		}
		else{
			$cons = 'DELETE FROM autor WHERE Id_Autor = ' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La baja del autor no se pudo realizar";
			}	
			else{
				$AltMsg = "Autor eliminado Satisfactoriamente";
			}
		}	
	}
	// MODIFICAR AUTOR //
	function ModAutor ($ID, $AutorNom, &$AltMsg){
		$cons = 'UPDATE autor 
		SET NombreApellido = "'.$AutorNom .'"
			WHERE Id_Autor =' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La modificacion del autor no se pudo realizar";
		}	
		else{
			$AltMsg = "Autor modificado Satisfactoriamente";
		}
	
	}
	// COMPROBAR AUTOR UNICO //
	function ComprobarAutor ($NomApe, &$correcto){	
		$res = mysql_query ('SELECT NombreApellido FROM autor');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($NomApe == $row['NombreApellido']){
				$correcto = true;
				break;
			}
		}
	}	
//GESTION DE IDIOMA //
	// CONSULTAR IDIOMA //
	function ConsultaIdioma (&$res){
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma
		FROM idioma
		ORDER BY Id_Idioma ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA PAGINADO //
	function ConsultaIdiomaPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma
				FROM idioma
				ORDER BY Id_Idioma ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA POR DESCRIPCION //
	function ConsultaIdio (&$res, $IdiomaNom){
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma
				FROM idioma
				WHERE Descripcion = "'.$IdiomaNom. '"';
		$res = mysql_query( $cons);
	}
	// BAJA IDIOMA //
	function BajaIdioma ($ID, &$AltMsg){
		$Comp = 'SELECT * FROM libro WHERE Id_Idioma = ' .$ID;
		$RComp = mysql_query ($Comp);
		$num1 = mysql_num_rows($RComp);
		if($num1 != 0){
			$AltMsg = "La baja del idioma no se pudo realizar, existen libros con dicho idioma registrado";
		}
		else{
			$cons = 'DELETE FROM idioma WHERE Id_Idioma =' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La baja del idioma no se pudo realizar";
			}	
			else{
				$AltMsg = "Idioma eliminado Satisfactoriamente";	
			}
		}	
	}
	// MODIFICAR IDIOMA //
	function ModIdioma ($ID, $IdiomaNom, &$AltMsg){
		$cons = 'UPDATE idioma 
		SET Descripcion = "'.$IdiomaNom .'"
			WHERE Id_Idioma =' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La modificacion del idioma no se pudo realizar";
		}	
		else{
			$AltMsg = "Idioma modificado Satisfactoriamente";
		}
	
	}
	// AGERGAR UN IDIOMA //
	function AgregarIdioma ($NomIdo, &$AltMsg){
		ComprobarIdioma ($NomIdo,$Flag);
		if ($Flag){
			$AltMsg = "Ya se encuentra registrado un Idioma con dicha Descripcion";
		}
		else{
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
	}
	// COMPROBAR IDIOMA UNICO //
	function ComprobarIdioma ($Desc, &$correcto){	
		$res = mysql_query ('SELECT Descripcion FROM idioma');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($Desc == $row['Descripcion']){
				$correcto = true;
				break;
			}
		}
	}
// GESTION DE ETIQUETA //	
	// CONSULTAR ETIQUETA //
	function ConsultaEtiqueta (&$res){
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta
		FROM etiqueta
		ORDER BY Id_Etiqueta ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR ETIQUETA PAGINADO //
	function ConsultaEtiquetaPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta
			FROM etiqueta
			ORDER BY Id_Etiqueta ASC
			LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA POR DESCRIPCION //
	function ConsultaEtiq (&$res, $EtiqNom){
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta
				FROM etiqueta
				WHERE Descripcion = "'.$EtiqNom. '"';
		$res = mysql_query( $cons);
	}
	// AGERGAR UN ETIQUETA //
	function AgregarEtiqueta ($NomEtq, &$AltMsg){
		ComprobarEtiqueta ($NomEtq,$Flag);
		if ($Flag){
			$AltMsg = "Ya se encuentra registrado una Etiqueta con dicha Descripcion";
		}
		else{
			$cons = 'INSERT INTO etiqueta (`Id_Etiqueta` ,`Descripcion`)
					VALUES (NULL , "' .$NomEtq .'")';
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "El alta de la etiqueta no se pudo realizar";
			}	
			else{
				$AltMsg = "Etiqueta agregada Satisfactoriamente";
			}
		}	
	}
	// BAJA ETIQUETA //
	function BajaEtiqueta ($ID, &$AltMsg){
		$Comp = 'SELECT * FROM etiqueta_Libro WHERE Id_Etiqueta = ' .$ID;
		$RComp = mysql_query ($Comp);
		$num1 = mysql_num_rows($RComp);
		if($num1 != 0){
			$AltMsg = "La baja de la etiqueta no se pudo realizar, existen libros con dicho etiqueta registrada";
		}
		else{
			$cons = 'DELETE FROM etiqueta WHERE Id_Etiqueta =' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La baja de la etiqueta no se pudo realizar";
			}	
			else{
				$AltMsg = "Etiqueta eliminada Satisfactoriamente";
			}
		}	
	}
	// MODIFICAR ETIQUETA //
	function ModEtiqueta ($ID, $EtiqNom, &$AltMsg){
		$cons = 'UPDATE etiqueta 
		SET Descripcion = "'.$EtiqNom .'"
			WHERE Id_Etiqueta =' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La modificacion de la etiqueta no se pudo realizar";
		}	
		else{
			$AltMsg = "Etiqueta modificado Satisfactoriamente";
		}
	
	}
	// COMPROBAR ETIQUETA UNICO //
	function ComprobarEtiqueta ($Desc, &$correcto){	
		$res = mysql_query ('SELECT Descripcion FROM etiqueta');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($Desc == $row['Descripcion']){
				$correcto = true;
				break;
			}
		}
	}
// GESTION DEL CATALOGO //
	// CONSULTA POR DEFECTO //
	function ConsultaPorDefecto (&$res){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad');
	}
	// CONSULTA POR DEFECTO PAGINADA//
	function ConsultaPorDefectoPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// BUSQUEDA PARA USURIO NO REGISTRADO //
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
	// BUSQUEDA PARA USUARIO REGISTRADO //
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
	// FILTRADO CON UNA TABLA USUARIO NO REGISTRADO //
	function ConsultaFiltros (&$res, &$Pinf, &$Psup, &$Idio, &$Tab){
		if	(!empty($Pinf)){$PrecInf = '"'.$Pinf.'"';}else{$PrecInf = 'libro.Precio';}
		if	(!empty($Psup)){$PrecSup = '"'.$Psup.'"';}else{$PrecSup = 'libro.Precio';}
		if	(!empty($Idio)){$Idioma = '"'.$Idio.'"';}else{$Idioma = 'idioma.Descripcion';}
		if  (!empty($Tab)){
			$IS = ' AND libro.ISBN IN ( ';
			$temp = '';
			foreach ($Tab as &$valor){
				$temp = $temp .$valor .', ';
			}
			$IS = $IS . $temp . '"")';
		}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'
				AND idioma.Descripcion = ' .$Idioma .$IS;
		$res = mysql_query($cons);
	}
	// FILTRADO CON UNA TABLA USUARIO REGISTRADO //
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
	// BUSQUEDA RAPIDA //
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
	// GENERADOR DE SELECT //
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
	// CONSULTA DE TITULOS CON AUTOR //
	function consultatitulos(&$restitulo, $autornom){
		$restitulo = mysql_query('SELECT libro.Titulo as Titulo
								FROM libro, autor
								WHERE autor.Id_Autor = libro.Id_Autor
								AND autor.NombreApellido = "'.$autornom .'"
								ORDER BY libro.Titulo');
	}
	// GENERADOR DE ORDENACION //
	function ConsultaOrdenamiento (&$res, &$orden, &$Tab){
		if  (!empty($Tab)){
			$IS = ' AND libro.ISBN IN ( ';
			$temp = '';
			foreach ($Tab as &$valor){
				$temp = $temp .$valor .', ';
			}
			$IS = $IS . $temp . '"")';
		}
		if ($orden == 'PrecAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio ASC';
			$res = mysql_query($cons);					
		}
		elseif ($orden == 'PrecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio DESC';
			$res = mysql_query($cons);						
		}
		elseif  ($orden == 'TitAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'TitDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'AutAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'AutDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido DESC';
			$res = mysql_query($cons);	
		}			
		elseif  ($orden == 'ISBNAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'ISBNDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'CPAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'CPDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas DESC';
			$res = mysql_query($cons);	
		}	
		elseif  ($orden == 'FecAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'FecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'IdioAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'IdioDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion DESC';
			$res = mysql_query($cons);	
		}			
	}
?>