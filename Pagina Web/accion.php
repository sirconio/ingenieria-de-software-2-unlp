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
	function IniciarSesion (&$usuario, &$clave, &$msg, &$flag){
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
					$msg = "Acceso Permitido, se logeo correctamente";
					$flag = true;
					break;
				}
			}
			if (!$entro){
				$msg = "<samp>>>>>>>Acceso Denegado, intentar Nuevamente<<<<<<</samp>";
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
	// CONSULTA TODOS LOS USUARIOS BUSQUEDA //
	function ConsultarUsuariosBus (&$res, $bus){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, "-----" as Password, usuario.Categoria, cliente.*,usuario.Visible as Estado 
						FROM usuario, cliente
						WHERE usuario.DNI = cliente.DNI
						AND ( usuario.Nombre LIKE "%' .$bus .'%" 
						OR    cliente.NombreApellido LIKE "%' .$bus .'%" 
						OR	  cliente.DNI LIKE "%'.$bus .'%")
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
	// CONSULTA TODOS LOS USUARIOS PAGINADO BUSQUEDA//
	function ConsultarUsuariosPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, "-----" as Password, usuario.Categoria, cliente.*,usuario.Visible as Estado 
						FROM usuario, cliente
						WHERE usuario.DNI = cliente.DNI
						AND ( usuario.Nombre LIKE "%' .$bus .'%" 
						OR    cliente.NombreApellido LIKE "%' .$bus .'%" 
						OR	  cliente.DNI LIKE "%'.$bus .'%")
						ORDER BY ID
						LIMIT ' .$pag .',10';						
		$res = mysql_query ($cons);
	}
	// CONSULTA USUARIO CON NOMUS //
	function DatosUsuario(&$res, $NomUs){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre, usuario.Password, usuario.Visible As Estado, cliente.* 
						FROM usuario, cliente
						WHERE usuario.Nombre = "' .$NomUs .'"
						AND usuario.DNI = cliente.DNI'); 
		$res = mysql_query ($cons);
	}
	// CONSULTA USUARIO CON ID //
	function DatosUsuarioID (&$res, $ID){
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre, usuario.Password, usuario.Visible As Estado, cliente.* 
						FROM usuario, cliente
						WHERE usuario.Id_Usuario = ' .$ID .'
						AND usuario.DNI = cliente.DNI'); 
		$res = mysql_query ($cons);
	}
	// BAJA LOGICA DE USUARIO //
	function BajaUsuario($ID, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE usuario 
					SET Visible = 0 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Usuario no se pudo borrar";
		}	
		else{
			$AltMsg = "Borrado satisfactorio";
			$Comp = true;
		}
	}
	// ACTIVACION DE USUARIO //
	function ActivarUsuario($ID, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE usuario 
					SET Visible = 1 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Usuario no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactoria";
			$Comp = true;
		}	
	}
	// MODIFICAR USUARIO CON ID //
	function ModUsuario (&$Con, $ID, $NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, &$AltMsg){
		ComprobarNomUs ($ID, $NomUs,$Flag);
		if ($Flag){
			$AltMsg = "La modificacion no pudo realizarse, ya se encuentra registrado un cliente con dicho Nombre de Usuario";
		}
		else{
			$cons = 'UPDATE cliente 
						SET NombreApellido = "'.$NomApe .'",
							Telefono = "' .$Tel .'",
							Direccion = "' .$Dir .'",
							Contacto = "' .$Mail.'" 
						WHERE cliente.DNI = ' .$DNI;
			$res = mysql_query ($cons);
			$cons1 = 'UPDATE usuario 
						SET Nombre = "'.$NomUs .'"
						WHERE Id_Usuario = ' .$ID;
			$res1 = mysql_query ($cons1);
			if(!$res && !$res1) {
					$AltMsg = "La modificacion no pudo realizarse";
			}	
			elseif (!$res1){
					$AltMsg = "Nombre de Usuario no modificado";
			}
			elseif (!$res){
					$AltMsg = "Datos personales no modificados";
			}
			else {
				$AltMsg = "Modificacion satisfactoria";
				$Con = true;
			}
		}
	}
	// CAMBIAR CLAVE //
	function ModClaves (&$Comp, $ID, $PassActual, $Pass1, $Pass2, &$AltMsg){
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
				$Comp = true;
			}
			else{
				$AltMsg = "Las Contraseña no coinciden";
			}
		}
		else{
			$AltMsg = "Contraseña actual incorrecta";
		}
	}
	// ALTA DE USUARIOS //
	function AltaUsuario(&$Con, $NomApe, $NomUs, $DNI, $Tel, $Dir, $Mail, $Pass1, $Pass2, &$AltMsg){
		if ($Pass1 == $Pass2){
			ComprobarDNI ($DNI,$Flag);
			if ($Flag){
				$AltMsg = "Usuario no pudo agregarse, ya se encuentra registrado un cliente con dicho DNI";
			}
			else{
				ComprobarNomUsAlta ($NomUs,$Flag2);
				if ($Flag2){
					$AltMsg = "Usuario no pudo agregarse, ya se encuentra registrado un cliente con dicho Nombre de Usuario";
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
						$Con = true;
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
	// COMPRUEBA NOMBRE USUARIO UNICO EN ALTA //
	function ComprobarNomUsAlta ($NomUs,&$correcto){
		$res = mysql_query ('SELECT Nombre FROM usuario');
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($NomUs == $row['Nombre']){
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
		$dateini = date('Y-m-d', strtotime($Fini));
		$datefin = date('Y-m-d', strtotime($Ffin));
		$cons = ('SELECT usuario.Id_Usuario as ID, usuario.Nombre as Usuario, cliente.DNI, cliente.NombreApellido, cliente.FechaAlta,usuario.Visible as Estado 
					FROM usuario, cliente
					WHERE usuario.DNI = cliente.DNI
					AND	cliente.FechaAlta BETWEEN "' .$dateini. '" AND "' .$datefin. '"
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
	// CONSULTAR TODOS LOS PEDIDOS BUSQUEDA //
	function ConsultarPedidosBus (&$res, $bus){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				AND ( pedidos.ISBN LIKE "%' .$bus .'%" 
				OR    pedidos.DNI LIKE "%' .$bus .'%" 
				OR	  estado.Descripcion LIKE "%'.$bus .'%")
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
	// CONSULTAR TODOS LOS PEDIDOS PAGINADOS BUSQUEDA //
	function ConsultarPedidosPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				AND ( pedidos.ISBN LIKE "%' .$bus .'%" 
				OR    pedidos.DNI LIKE "%' .$bus .'%" 
				OR	  estado.Descripcion LIKE "%'.$bus .'%")
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
	// CONSULTAR PEDIDO PAGINADOS CON ID //
	function ConsultaPedidosPag (&$res, $NroPag, $ID){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
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
	// CONSULTAR PEDIDOS PENDIENTES CON ID //
	function ConsultarPedidosPendId (&$res, $ID){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .'
				AND usuario.DNI = cliente.DNI
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
	// CONSULTAR PEDIDOS PENDIENTES PAGINADO CON ID//
	function ConsultarPedidosPendPagId (&$res, $NroPag, $ID){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .'
				AND usuario.DNI = cliente.DNI
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
	// CONSULTAR PEDIDOS ENVIDADOS CON ID //
	function ConsultarPedidosEnvId (&$res, $ID){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .' 
				AND usuario.DNI = cliente.DNI
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
	// CONSULTAR PEDIDOS ENVIDADOS PAGINADO CON ID //
	function ConsultarPedidosEnvPagId (&$res, $NroPag, $ID){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .'
				AND usuario.DNI = cliente.DNI
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
	// CONSULTAR PEDIDOS ENTREGADOS CON ID //
	function ConsultarPedidosEntId (&$res, $ID){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .'
				AND usuario.DNI = cliente.DNI
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
	// CONSULTAR PEDIDOS ENTREGADOS PAGINADO CON ID//
	function ConsultarPedidosEntPagId (&$res, $NroPag, $ID){
		$pag = (10*$NroPag);
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario = ' .$ID .'
				AND usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.Id_Estado = 3
				AND pedidos.ISBN = libro.ISBN
				ORDER BY pedidos.FechaPedido DESC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CAMBIAR PEDIDO A ENTREGADO //
 	function PedidoEntregado ($ISBN, $DNI, &$Msj, &$Comp){
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
			$Comp = true;
		}
	}
	// CAMBIAR PEDIDO A ENVIADO //
	function PedidoEnviado($ISBN, $DNI, &$Msj, &$Comp){
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
			$Comp = true;
		}
	}
// GESTION DEL CARRITO DE COMPRAS //
	// CONSULTAR CARRITO CON ID //
	function ConsultaCarrito (&$res, $ID){
		$cons = 'SELECT carrito.Id_Carrito AS ID, usuario.DNI, usuario.Nombre, libro.ISBN, libro.Titulo, autor.NombreApellido, libro.Precio
				FROM usuario, carrito, libro, autor
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.Id_Usuario = carrito.Id_Usuario
				AND carrito.ISBN = libro.ISBN
				AND libro.Id_Autor = autor.Id_Autor';
		$res = mysql_query( $cons);
	}
	// CONSULTAR CARRITO PAGINADO CON ID //
	function ConsultaCarritoPag (&$res, $ID, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT carrito.Id_Carrito AS ID, usuario.DNI, usuario.Nombre, libro.ISBN, libro.Titulo, autor.NombreApellido, libro.Precio
				FROM usuario, carrito, libro, autor
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.Id_Usuario = carrito.Id_Usuario
				AND carrito.ISBN = libro.ISBN
				AND libro.Id_Autor = autor.Id_Autor
				LIMIT ' .$pag .',10';
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
	function RetirarCarrito($ID, $IDUs, &$Msj, &$Comp){
		$cons =	'DELETE FROM `carrito` WHERE `carrito`.`Id_Carrito` = ' .$ID;
		$res = mysql_query( $cons);
		if (!$res){
			$Msj = "No se pudo borrar del carrito";
		}
		else{
			$cons1 = 'SELECT usuario.CantCarrito 
					FROM usuario 
					WHERE Id_Usuario = ' .$IDUs;
			$res1 = mysql_query ($cons1);
			while($row = mysql_fetch_assoc($res1)){
				$cant = $row['CantCarrito'] - 1;
				$cons2 = 'UPDATE usuario 
							SET CantCarrito = ' .$cant. ' 
							WHERE Id_Usuario = ' .$IDUs;
				$res2 = mysql_query ($cons2);
				$_SESSION['CarritoCant'] = $cant;
			}
			$Msj = "Borrado con exito del carrito";
			$Comp = true;
		}
	}
	// VACIAR CARRITO CON ID //
	function VaciarCarrito($ID, &$Msj, &$Comp){
		$cons3 = 'SELECT carrito.ISBN, carrito.Id_Carrito AS ID
		FROM carrito
		WHERE carrito.ID_Usuario = ' .$ID;
		$res3 = mysql_query( $cons3);
		$num = mysql_num_rows($res3);
		if ($num != 0){
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
				$Comp = true;
			}
		}
		else{
			$Msj = 'No hay nada en el carrito por vaciar';
		}		
	}
	// VERIFICAR CARRITO VACIO //
	function verificarCarrito(&$Comp, $ID, &$AltMsg){
		$cons3 = 'SELECT carrito.ISBN, carrito.Id_Carrito AS ID
		FROM carrito
		WHERE carrito.ID_Usuario = ' .$ID;
		$res3 = mysql_query( $cons3);
		$num = mysql_num_rows($res3);
		if ($num != 0){
			$Comp = true;
		}
		else{
			$AltMsg = 'No hay nada en el carrito por comprar';
		}
	}
	// EFECTIVIZAR COMPRA DEL CARRITO //
	function ComprarCarrito($ID, &$AltMsg, &$Comp){
		$cons = 'SELECT carrito.ISBN, carrito.Id_Carrito AS ID
				FROM carrito
				WHERE carrito.ID_Usuario = ' .$ID;
		$res = mysql_query( $cons);
		$num = mysql_num_rows($res);
		if ($num != 0){
			$cons1 = 'SELECT usuario.DNI
					FROM usuario
					WHERE usuario.ID_Usuario = ' .$ID;
			$res1 = mysql_query( $cons1);
			$today = getdate();
			$Fecha = $today[year]. '-' .$today[mon]. '-'. $today[mday];
			$Msg = 'Libros comprados con exito: ';
			$Msg2 = 'Operaciones Fallidas: ';
			$entro = false;
			while($row1 = mysql_fetch_assoc($res1)){
				while($row = mysql_fetch_assoc($res)){
					$cons2 = 'INSERT INTO `cookbook`.`pedidos` (`ISBN` ,`DNI` ,`FechaPedido` ,`Id_Estado`)VALUES (' .$row['ISBN']. ', ' .$row1['DNI'].', "' .$Fecha. '", 1)';
					$res2 = mysql_query( $cons2);
					if ($res2) {
						$Msg = $Msg .'' .$row['ISBN']. '; ';
						RetirarCarrito($row['ID'], $ID, $Msj, $Comp);
					}
					else{
						$Msg2 = $Msg2 .'' .$row['ISBN']. '; ';
						$entro = true;
					}
				}
			}
			if ($entro){
				$AltMsg = $Msg. ' /// ' .$Msg2;
			}
			else{
				$AltMsg = 'Libros comprados con exito!';
				$Comp = true;
			}
		}
		else{
			$AltMsg = 'No hay nada en el carrito por comprar';
		}
	}
// GESTION DE LIBROS //
	// CONSULTA LIBRO CON ISBN //
	function ConsultaLibro (&$res, $ISBN){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible as Estado, libro.Hojear AS Indice
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND libro.ISBN=' .$ISBN);
	}
	// BAJA LOGICA DE LIBRO CON ISBN //
	function BajaLibro($ISBN, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE libro 
					SET Visible = 0 
					WHERE ISBN = ' .$ISBN;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Libro no se pudo borrar";
		}	
		else{
			$AltMsg = "Borrado satisfactorio";
			$Comp = true;
		}	
	}
	// ACTIVAR LIBRO //
	function ActivarLibro($ISBN, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE libro 
					SET Visible = 1 
					WHERE ISBN = ' .$ISBN;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Libro no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
			$Comp = true;
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
	function AltaLibro(&$Con, $IS, $Tit, $Aut, $CPag, $Pre, $Idio, $Fec, $Etiq, $Ind, &$AltMsg){
		ComprobarISBN ($IS,$Flag);
		$datefin = date('Y-m-d', strtotime($Fec));
		if ($Flag){
			$AltMsg = "El Libro no se agrego correctamente, ya se encuentra registrado un libro con dicho ISBN";
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
				VALUES (' .$IS .', "' .$Tit .'", ' .$Arow['Id_Autor'] .',' .$CPag .' , ' .$Pre .', ' .$Irow['Id_Idioma'] .', "' .$datefin .'", 1, 1, "' .$Ind .'")';
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
	function ModLibro (&$Comp, $IS, $Tit, $Aut, $CPag, $Pre, $Idio, $Fec, $Etiq, $Disp, $Ind, &$AltMsg){
		$datefin = date('Y-m-d', strtotime($Fec));
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
				Fecha = "' .$datefin .'",
				Hojear = "' .$Ind .'",
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
			$Comp = true;
		}
	}
	// TOP 10 LIBROS MAS VENDIDOS EN UN PERIODO //	
	function LibroPeriodo (&$res, $Fini, $Ffin){
		$dateini = date('Y-m-d', strtotime($Fini));
		$datefin = date('Y-m-d', strtotime($Ffin));
		$cons = 'SELECT  COUNT(pedidos.ISBN) As Ventas, pedidos.ISBN, libro.Titulo, autor.NombreApellido AS Autor
				FROM cliente, pedidos, libro, autor
				WHERE cliente.DNI = pedidos.DNI
				AND libro.Id_Autor = autor.Id_Autor
				AND pedidos.ISBN = libro.ISBN
				AND	pedidos.FechaPedido BETWEEN "' .$dateini. '" AND "' .$datefin. '"
				GROUP BY pedidos.ISBN, libro.Titulo, autor.NombreApellido
				ORDER BY Ventas DESC
				LIMIT 0,10';
		$res = mysql_query( $cons);
	}
// GESTION DE AUTOR //	
	// CONSULTAR AUTORES //
	function ConsultaAutores (&$res){
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor, Visible AS Estado
		FROM autor
		ORDER BY Id_Autor ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTORES BUSQUEDA //
	function ConsultaAutoresBus (&$res, $bus){
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor, Visible AS Estado
		FROM autor
		WHERE autor.NombreApellido LIKE "%' .$bus .'%" 
		ORDER BY Id_Autor ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTORES PAGINADO //
	function ConsultaAutoresPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor, Visible AS Estado
				FROM autor
				ORDER BY Id_Autor ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTORES PAGINADO BUSQUEDA //
	function ConsultaAutoresPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor, Visible AS Estado
				FROM autor
				WHERE autor.NombreApellido LIKE "%' .$bus .'%"
				ORDER BY Id_Autor ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR AUTOR POR NOMBRE //
	function ConsultaAutor (&$res, $AutorNom){
		$cons = 'SELECT Id_Autor AS ID, NombreApellido AS Autor, Visible AS Estado
				FROM autor
				WHERE NombreApellido = "'.$AutorNom. '"';
		$res = mysql_query( $cons);
	}
	// AGERGAR UN AUTOR //
	function AgregarAutor ($NomApe, &$AltMsg, &$Comp){
		ComprobarAutor ($NomApe,$Flag);
		if ($Flag){
			$AltMsg = "El alta del autor no se pudo realizar, ya se encuentra registrado un Autor con dicho Nombre";
		}
		else{
			$cons = 'INSERT INTO autor (`Id_Autor` ,`NombreApellido`, `Visible`)
								VALUES (NULL , "' .$NomApe .'", 1)';
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "El alta del autor no se pudo realizar";
			}	
			else{
				$AltMsg = "Autor agregado Satisfactoriamente";
				$Comp = true;
			}
		}
	}	
	// BAJA LOGICA DE AUTOR //
	function BajaAutor ($ID, &$AltMsg, &$Comp){
		$cons = 'UPDATE autor
				 SET Visible = 0
				 WHERE Id_Autor = ' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La baja del autor no se pudo realizar";
		}	
		else{
			$AltMsg = "Autor eliminado Satisfactoriamente";
			$Comp = true;
		}	
	}
	// ACTIVAR AUTOR //
	function ActivarAutor ($ID, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE autor 
					SET Visible = 1 
					WHERE Id_Autor = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Autor no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
			$Comp =true;
		}	
	}
	// MODIFICAR AUTOR //
	function ModAutor ($ID, $AutorNom, &$AltMsg, &$Comp){
		ComprobarAutorID ($ID, $AutorNom,$Flag);
		if ($Flag){
			$AltMsg = "La modificacion del autor no se pudo realizar, ya se encuentra registrado un Autor con dicho Nombre";
		}
		else{
			$cons = 'UPDATE autor 
			SET NombreApellido = "'.$AutorNom .'"
				WHERE Id_Autor =' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La modificacion del autor no se pudo realizar";
			}	
			else{
				$AltMsg = "Autor modificado Satisfactoriamente";
				$Comp = true;
			}
		}
	}
	// COMPROBAR AUTOR UNICO CON ID //
	function ComprobarAutorID ($ID, $NomApe, &$correcto){	
		$res = mysql_query ('SELECT NombreApellido FROM autor WHERE Id_Autor <> ' .$ID);
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($NomApe == $row['NombreApellido']){
				$correcto = true;
				break;
			}
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
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma, Visible AS Estado
		FROM idioma
		ORDER BY Id_Idioma ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA BUSQUEDA //
	function ConsultaIdiomaBus (&$res, $bus){
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma, Visible AS Estado
		FROM idioma
		WHERE idioma.Descripcion LIKE "%' .$bus .'%"
		ORDER BY Id_Idioma ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA PAGINADO //
	function ConsultaIdiomaPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma, Visible AS Estado
				FROM idioma
				ORDER BY Id_Idioma ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA PAGINADO BUSQUEDA //
	function ConsultaIdiomaPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma, Visible AS Estado
				FROM idioma
				WHERE idioma.Descripcion LIKE "%' .$bus .'%"
				ORDER BY Id_Idioma ASC
				LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR IDIOMA POR DESCRIPCION //
	function ConsultaIdio (&$res, $IdiomaNom){
		$cons = 'SELECT Id_Idioma AS ID, Descripcion AS Idioma, Visible AS Estado
				FROM idioma
				WHERE Descripcion = "'.$IdiomaNom. '"';
		$res = mysql_query( $cons);
	}
	// BAJA LOGICA DE IDIOMA //
	function BajaIdioma ($ID, &$AltMsg, &$Comp){
		$cons = 'UPDATE idioma
				 SET Visible = 0
				 WHERE Id_Idioma = ' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La baja del idioma no se pudo realizar";
		}	
		else{
			$AltMsg = "Idioma eliminado Satisfactoriamente";	
			$Comp = true;
		}	
	}
	// ACTIVAR IDIOMA //
	function ActivarIdioma ($ID, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE idioma 
					SET Visible = 1 
					WHERE Id_Idioma = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Idioma no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
			$Comp = true;
		}	
	}
	// MODIFICAR IDIOMA //
	function ModIdioma ($ID, $IdiomaNom, &$AltMsg, &$Comp){
		ComprobarIdiomaID ($ID, $IdiomaNom,$Flag);
		if ($Flag){
			$AltMsg = "La modificacion del idioma no se pudo realizar, ya se encuentra registrado un Idioma con dicha Descripcion";
		}
		else{
			$cons = 'UPDATE idioma 
			SET Descripcion = "'.$IdiomaNom .'"
				WHERE Id_Idioma =' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La modificacion del idioma no se pudo realizar";
			}	
			else{
				$AltMsg = "Idioma modificado Satisfactoriamente";
				$Comp = true;
			}
		}
	}
	// AGERGAR UN IDIOMA //
	function AgregarIdioma ($NomIdo, &$AltMsg, &$Comp){
		ComprobarIdioma ($NomIdo,$Flag);
		if ($Flag){
			$AltMsg = "El alta del idioma no se pudo realizar, ya se encuentra registrado un Idioma con dicha Descripcion";
		}
		else{
			$cons = 'INSERT INTO idioma (`Id_Idioma` ,`Descripcion`, `Visible`)
					VALUES (NULL , "' .$NomIdo .'", 1)';
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "El alta del idioma no se pudo realizar";
			}	
			else{
				$AltMsg = "Idioma agregado Satisfactoriamente";
				$Comp = true;
			}
		}	
	}
	// COMPROBAR IDIOMA UNICO CON ID //
	function ComprobarIdiomaID ($ID, $Desc, &$correcto){	
		$res = mysql_query ('SELECT Descripcion FROM idioma WHERE Id_Idioma <> ' .$ID);
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($Desc == $row['Descripcion']){
				$correcto = true;
				break;
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
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta, Visible AS Estado
		FROM etiqueta
		ORDER BY Id_Etiqueta ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR ETIQUETA BUSQUEDA //
	function ConsultaEtiquetaBus (&$res, $bus){
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta, Visible AS Estado
		FROM etiqueta
		WHERE etiqueta.Descripcion LIKE "%' .$bus .'%"
		ORDER BY Id_Etiqueta ASC';
		$res = mysql_query( $cons);
	}
	// CONSULTAR ETIQUETA PAGINADO //
	function ConsultaEtiquetaPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta, Visible AS Estado
			FROM etiqueta
			ORDER BY Id_Etiqueta ASC
			LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR ETIQUETA PAGINADO BUSQUEDA//
	function ConsultaEtiquetaPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta, Visible AS Estado
			FROM etiqueta
			WHERE etiqueta.Descripcion LIKE "%' .$bus .'%"
			ORDER BY Id_Etiqueta ASC
			LIMIT ' .$pag .',10';
		$res = mysql_query( $cons);
	}
	// CONSULTAR ETIQUETA POR DESCRIPCION //
	function ConsultaEtiq (&$res, $EtiqNom){
		$cons = 'SELECT Id_Etiqueta AS ID, Descripcion AS Etiqueta, Visible AS Estado
				FROM etiqueta
				WHERE Descripcion = "'.$EtiqNom. '"';
		$res = mysql_query( $cons);
	}
	// AGERGAR UN ETIQUETA //
	function AgregarEtiqueta ($NomEtq, &$AltMsg, &$Comp){
		ComprobarEtiqueta ($NomEtq,$Flag);
		if ($Flag){
			$AltMsg = "El alta de la etiqueta no se pudo realizar, ya se encuentra registrado una Etiqueta con dicha Descripcion";
		}
		else{
			$cons = 'INSERT INTO etiqueta (`Id_Etiqueta` ,`Descripcion`, `Visible`)
					VALUES (NULL , "' .$NomEtq .'", 1)';
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "El alta de la etiqueta no se pudo realizar";
			}	
			else{
				$AltMsg = "Etiqueta agregada Satisfactoriamente";
				$Comp = true;
			}
		}	
	}
	// BAJA LOGICA DE ETIQUETA //
	function BajaEtiqueta ($ID, &$AltMsg, &$Comp){
		$cons = 'UPDATE etiqueta
				 SET Visible = 0
				 WHERE Id_Etiqueta = ' .$ID;
		$res = mysql_query ($cons);
		if(!$res) {
			$AltMsg = "La baja de la etiqueta no se pudo realizar";
		}	
		else{
			$AltMsg = "Etiqueta eliminada Satisfactoriamente";
			$Comp = true;
		}	
	}
	// ACTIVAR ETIQUQTA //
	function ActivarEtiqueta ($ID, &$AltMsg, &$Comp){
		$cons1 = 'UPDATE etiqueta 
					SET Visible = 1 
					WHERE Id_Etiqueta = ' .$ID;
		$res1 = mysql_query ($cons1);
		if(!$res1) {
			$AltMsg = "Etiqueta no se pudo activar";
		}	
		else{
			$AltMsg = "Activacion satisfactorio";
			$Comp = true;
		}	
	}
	// MODIFICAR ETIQUETA //
	function ModEtiqueta ($ID, $EtiqNom, &$AltMsg, &$Comp){
		ComprobarEtiquetaID ($ID, $EtiqNom,$Flag);
		if ($Flag){
			$AltMsg = "La modificacion de la etiqueta no se pudo realizar, ya se encuentra registrado una Etiqueta con dicha Descripcion";
		}
		else{
			$cons = 'UPDATE etiqueta 
			SET Descripcion = "'.$EtiqNom .'"
				WHERE Id_Etiqueta =' .$ID;
			$res = mysql_query ($cons);
			if(!$res) {
				$AltMsg = "La modificacion de la etiqueta no se pudo realizar";
			}	
			else{
				$AltMsg = "Etiqueta modificada Satisfactoriamente";
				$Comp = true;
			}
		}	
	}
	// COMPROBAR ETIQUETA UNICO CON ID//
	function ComprobarEtiquetaID ($ID, $Desc, &$correcto){	
		$res = mysql_query ('SELECT Descripcion FROM etiqueta WHERE Id_Etiqueta <> ' .$ID);
		$correcto = false;
		while($row = mysql_fetch_assoc($res)){
			if ($Desc == $row['Descripcion']){
				$correcto = true;
				break;
			}
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
	// BUSCAR ETIQUETAS PARA ISBN //
	function BuscarEtiquetas($ISBN, &$Etiq){
		$cons = 'SELECT etiqueta.Descripcion AS Etiqueta
		FROM etiqueta, etiqueta_Libro
		WHERE etiqueta.Id_Etiqueta =  etiqueta_Libro.Id_Etiqueta
		AND etiqueta_Libro.ISBN = ' .$ISBN .'
		ORDER BY etiqueta.Id_Etiqueta ASC';
		$res = mysql_query( $cons);
		$Etiq = "";
		while($row = mysql_fetch_assoc($res)){
			$Etiq = $Etiq. $row['Etiqueta']. "; ";
		}
	}
// GESTION DEL CATALOGO //
	// CONSULTA POR DEFECTO ADMI//
	function ConsultaPorDefectoAdm (&$res){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad');
	}
	// CONSULTA POR DEFECTO BUSQUEDA ADMI//
	function ConsultaPorDefectoBusAdm (&$res, $bus){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND ( libro.Titulo LIKE "%' .$bus .'%" 
						OR    autor.NombreApellido LIKE "%' .$bus .'%" 
						OR	  libro.ISBN LIKE "%'.$bus .'%")');
	}
	// CONSULTA POR DEFECTO PAGINADA ADMI //
	function ConsultaPorDefectoPagAdm (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// CONSULTA POR DEFECTO PAGINADA BUSQUEDA ADMI //
	function ConsultaPorDefectoPagBusAdm (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND ( libro.Titulo LIKE "%' .$bus .'%" 
				OR    autor.NombreApellido LIKE "%' .$bus .'%" 
				OR	  libro.ISBN LIKE "%'.$bus .'%")
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// CONSULTA POR DEFECTO //
	function ConsultaPorDefecto (&$res){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND libro.Visible = 1
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad');
	}
	// CONSULTA POR DEFECTO BUSQUEDA//
	function ConsultaPorDefectoBus (&$res, $bus){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND libro.Visible = 1
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND ( libro.Titulo LIKE "%' .$bus .'%" 
						OR    autor.NombreApellido LIKE "%' .$bus .'%" 
						OR	  libro.ISBN LIKE "%'.$bus .'%")');
	}
	// CONSULTA POR DEFECTO PAGINADA//
	function ConsultaPorDefectoPag (&$res, $NroPag){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// CONSULTA POR DEFECTO PAGINADA BUSQUEDA//
	function ConsultaPorDefectoPagBus (&$res, $NroPag, $bus){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado, libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND libro.Visible = 1
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND ( libro.Titulo LIKE "%' .$bus .'%" 
				OR    autor.NombreApellido LIKE "%' .$bus .'%" 
				OR	  libro.ISBN LIKE "%'.$bus .'%")
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// BUSQUEDA PARA USURIO NO REGISTRADO //
	function ConsultaBusqueda (&$res, &$Aut, &$Tit, &$IS){
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN;		
		$res = mysql_query($cons);
	}
	// BUSQUEDA PARA USURIO NO REGISTRADO PAGINADO //
	function ConsultaBusquedaPag (&$res, $NroPag, &$Aut, &$Tit, &$IS){
		$pag = (10*$NroPag);
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN . '
				LIMIT ' .$pag .',10';		
		$res = mysql_query($cons);
	}
	// BUSQUEDA PARA USUARIO REGISTRADO //
	function ConsultaBusqueda2 (&$res, &$Aut, &$Tit, &$IS, &$Et){
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		if (!empty($Et)){
			$eti = 'AND libro.ISBN = etiqueta_libro.ISBN 
					AND etiqueta.Descripcion IN (';
			$temp = '';
			foreach ($Et as &$valor){
					$temp = $temp .'"' .$valor .'", ';
			}
			$eti = $eti . $temp . '" ")';
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, 
			disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
			FROM libro, autor, idioma, disponibilidad, etiqueta, etiqueta_libro
			WHERE autor.Id_Autor = libro.Id_Autor
			AND idioma.Id_Idioma = libro.Id_Idioma
			AND libro.Visible = 1
			AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad				
			AND etiqueta.Id_Etiqueta = etiqueta_libro.Id_Etiqueta
			AND autor.NombreApellido = ' .$Autor .'
			AND libro.Titulo = ' .$Titulo .'
			AND libro.ISBN = ' .$ISBN .' '
			.$eti;		
		}
		else{
			$eti = '';
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, 
				disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad						
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN .' '
				.$eti;		
		}
		$res = mysql_query($cons);
	}
	// BUSQUEDA PARA USUARIO REGISTRADO PAGINADO //
	function ConsultaBusqueda2Pag (&$res, $NroPag, &$Aut, &$Tit, &$IS, &$Et){
		$pag = (10*$NroPag);
		if	(!empty($Aut)){$Autor = '"'.$Aut.'"';}else{$Autor = 'autor.NombreApellido';}
		if	(!empty($Tit)){$Titulo = '"'.$Tit.'"';}else{$Titulo = 'libro.Titulo';}
		if	(!empty($IS)){$ISBN = '"'.$IS.'"';}else{$ISBN = 'libro.ISBN';}
		if (!empty($Et)){
			$eti = 'AND libro.ISBN = etiqueta_libro.ISBN
					AND etiqueta.Descripcion IN (';
			$temp = '';
			foreach ($Et as &$valor){
					$temp = $temp .'"' .$valor .'", ';
			}
			$eti = $eti . $temp . '" ")';
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, 
				disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad, etiqueta, etiqueta_libro
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad				
				AND etiqueta.Id_Etiqueta = etiqueta_libro.Id_Etiqueta
				AND autor.NombreApellido = ' .$Autor .'
				AND libro.Titulo = ' .$Titulo .'
				AND libro.ISBN = ' .$ISBN .' '
				.$eti . '
				LIMIT ' .$pag .',10';	
		}
		else{
			$eti = '';
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, 
					disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
					FROM libro, autor, idioma, disponibilidad
					WHERE autor.Id_Autor = libro.Id_Autor
					AND idioma.Id_Idioma = libro.Id_Idioma
					AND libro.Visible = 1
					AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad									
					AND autor.NombreApellido = ' .$Autor .'
					AND libro.Titulo = ' .$Titulo .'
					AND libro.ISBN = ' .$ISBN .' '
					.$eti . '
					LIMIT ' .$pag .',10';	
		}			
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
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'
				AND idioma.Descripcion = ' .$Idioma .$IS;
		$res = mysql_query($cons);
	}
	// FILTRADO CON UNA TABLA USUARIO NO REGISTRADO PAGINADO //
	function ConsultaFiltrosPag (&$res, $NroPag, &$Pinf, &$Psup, &$Idio, &$Tab){
		$pag = (10*$NroPag);
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
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'
				AND idioma.Descripcion = ' .$Idioma .$IS .'
				LIMIT ' .$pag .',10';
		$res = mysql_query($cons);
	}
	// FILTRADO CON UNA TABLA USUARIO REGISTRADO //
	function ConsultaFiltros2 (&$res, &$Preinf, &$Presup, &$Idio, &$Disp, &$Paginf, &$Pagsup, &$Finf, &$Fsup, &$Tab){
		if	(!empty($Preinf)){$PrecInf = '"'.$Preinf.'"';}else{$PrecInf = 'libro.Precio';}
		if	(!empty($Presup)){$PrecSup = '"'.$Presup.'"';}else{$PrecSup = 'libro.Precio';}
		if	(!empty($Idio)){$Idioma = '"'.$Idio.'"';}else{$Idioma = 'idioma.Descripcion';}
		if	(!empty($Disp)){$Dispo = '"'.$Disp.'"';}else{$Dispo = 'disponibilidad.Descripcion';}
		if	(!empty($Paginf)){$PagiInf = '"'.$Paginf.'"';}else{$PagiInf = 'libro.CantidadPaginas';}
		if	(!empty($Pagsup)){$PagiSup = '"'.$Pagsup.'"';}else{$PagiSup = 'libro.CantidadPaginas';}
		if	(!empty($Finf)){$dinf = date('Y-m-d', strtotime($Finf));$dateinf = '"'.$dinf .'"';}else{$dateinf = 'libro.Fecha';}
		if	(!empty($Fsup)){$dsup = date('Y-m-d', strtotime($Fsup));$datesup = '"'.$dsup .'"';}else{$datesup = 'libro.Fecha';}
		if  (!empty($Tab)){
			$IS = ' AND libro.ISBN IN ( ';
			$temp = '';
			foreach ($Tab as &$valor){
				$temp = $temp .$valor .', ';
			}
			$IS = $IS . $temp . '"")';
		}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND libro.Visible = 1
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'	
				AND idioma.Descripcion = ' .$Idioma .'
				AND disponibilidad.Descripcion = ' .$Dispo .'
				AND libro.CantidadPaginas >= ' .$PagiInf .'
				AND libro.CantidadPaginas <= ' .$PagiSup .'
				AND libro.Fecha >= ' .$dateinf .'
				AND libro.Fecha <= ' .$datesup .$IS;		
		$res = mysql_query($cons);
	}
	// FILTRADO CON UNA TABLA USUARIO REGISTRADO PAGINADO //
	function ConsultaFiltros2Pag (&$res, $NroPag, &$Preinf, &$Presup, &$Idio, &$Disp, &$Paginf, &$Pagsup, &$Finf, &$Fsup, &$Tab){
		$pag = (10*$NroPag);
		if	(!empty($Preinf)){$PrecInf = '"'.$Preinf.'"';}else{$PrecInf = 'libro.Precio';}
		if	(!empty($Presup)){$PrecSup = '"'.$Presup.'"';}else{$PrecSup = 'libro.Precio';}
		if	(!empty($Idio)){$Idioma = '"'.$Idio.'"';}else{$Idioma = 'idioma.Descripcion';}
		if	(!empty($Disp)){$Dispo = '"'.$Disp.'"';}else{$Dispo = 'disponibilidad.Descripcion';}
		if	(!empty($Paginf)){$PagiInf = '"'.$Paginf.'"';}else{$PagiInf = 'libro.CantidadPaginas';}
		if	(!empty($Pagsup)){$PagiSup = '"'.$Pagsup.'"';}else{$PagiSup = 'libro.CantidadPaginas';}
		if	(!empty($Finf)){$dinf = date('Y-m-d', strtotime($Finf));$dateinf = '"'.$dinf .'"';}else{$dateinf = 'libro.Fecha';}
		if	(!empty($Fsup)){$dsup = date('Y-m-d', strtotime($Fsup));$datesup = '"'.$dsup .'"';}else{$datesup = 'libro.Fecha';}
		if  (!empty($Tab)){
			$IS = ' AND libro.ISBN IN ( ';
			$temp = '';
			foreach ($Tab as &$valor){
				$temp = $temp .$valor .', ';
			}
			$IS = $IS . $temp . '"")';
		}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND libro.Precio >= ' .$PrecInf .'
				AND libro.Precio <= ' .$PrecSup .'	
				AND idioma.Descripcion = ' .$Idioma .'
				AND disponibilidad.Descripcion = ' .$Dispo .'
				AND libro.CantidadPaginas >= ' .$PagiInf .'
				AND libro.CantidadPaginas <= ' .$PagiSup .'
				AND libro.Fecha >= ' .$dateinf .'
				AND libro.Fecha <= ' .$datesup  .$IS .'
				LIMIT ' .$pag .',10';		
		$res = mysql_query($cons);
	}
	// BUSQUEDA RAPIDA //
	function ConsultaBusquedaRapida (&$res, &$BusRap){
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND ( libro.Titulo LIKE "%' .$BusRap .'%" 
				OR    autor.NombreApellido LIKE "%' .$BusRap .'%" 
				OR	  libro.ISBN LIKE "%'.$BusRap .'%")';			
		$res = mysql_query($cons);
	}
	// BUSQUEDA RAPIDA PAGINADO //
	function ConsultaBusquedaRapidaPag (&$res, $NroPag, &$BusRap){
		$pag = (10*$NroPag);
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible As Estado,  libro.Hojear AS Indice
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND libro.Visible = 1
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
				AND ( libro.Titulo LIKE "%' .$BusRap .'%" 
				OR    autor.NombreApellido LIKE "%' .$BusRap .'%" 
				OR	  libro.ISBN LIKE "%'.$BusRap .'%")
				LIMIT ' .$pag .',10';			
		$res = mysql_query($cons);
	}
	// GENERADOR DE SELECT //
	function ConsultasSelect (&$residiomas, &$resdisp, &$resetiquetas, &$resautor, &$restitulo, &$resisbn){
		$residiomas = mysql_query('SELECT idioma.Descripcion as Idioma, idioma.Visible AS Estado
								FROM idioma
								ORDER BY idioma.Descripcion');						
		$resdisp = mysql_query('SELECT disponibilidad.Descripcion as Disponibilidad
								FROM disponibilidad
								ORDER BY disponibilidad.Descripcion');
		$resetiquetas = mysql_query('SELECT etiqueta.Descripcion as Etiqueta, etiqueta.Visible AS Estado
								FROM etiqueta
								ORDER BY etiqueta.Descripcion');
		$resautor = mysql_query('SELECT autor.NombreApellido as Autor, autor.Visible AS Estado
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
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio ASC';
			$res = mysql_query($cons);					
		}
		elseif ($orden == 'PrecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio DESC';
			$res = mysql_query($cons);						
		}
		elseif  ($orden == 'TitAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'TitDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'AutAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'AutDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido DESC';
			$res = mysql_query($cons);	
		}			
		elseif  ($orden == 'ISBNAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'ISBNDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'CPAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'CPDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas DESC';
			$res = mysql_query($cons);	
		}	
		elseif  ($orden == 'FecAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'FecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha DESC';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'IdioAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion ASC';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'IdioDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion DESC';
			$res = mysql_query($cons);	
		}			
	}
	// GENERADOR DE ORDENACION PAGINADO //
	function ConsultaOrdenamientoPag (&$res, $NroPag, &$orden, &$Tab){
		$pag = (10*$NroPag);
		if  (!empty($Tab)){
			$IS = ' AND libro.ISBN IN ( ';
			$temp = '';
			foreach ($Tab as &$valor){
				$temp = $temp .$valor .', ';
			}
			$IS = $IS . $temp . '"")';
		}
		if ($orden == 'PrecAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);					
		}
		elseif ($orden == 'PrecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Precio DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);						
		}
		elseif  ($orden == 'TitAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'TitDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Titulo DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'AutAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'AutDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY autor.NombreApellido DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}			
		elseif  ($orden == 'ISBNAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'ISBNDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.ISBN DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'CPAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'CPDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.CantidadPaginas DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}	
		elseif  ($orden == 'FecAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'FecDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado,  libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY libro.Fecha DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}
		elseif  ($orden == 'IdioAsc') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion ASC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}						
		elseif  ($orden == 'IdioDes') {
			$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible AS Estado, libro.Hojear AS Indice
								FROM libro, autor, idioma, disponibilidad
								WHERE autor.Id_Autor = libro.Id_Autor
								AND idioma.Id_Idioma = libro.Id_Idioma
								AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad' .$IS .'
								ORDER BY idioma.Descripcion DESC
								LIMIT ' .$pag .',10';
			$res = mysql_query($cons);	
		}			
	}
?>