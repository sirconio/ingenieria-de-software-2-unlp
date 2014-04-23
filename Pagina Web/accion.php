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
		$cons = 'SELECT usuario.Nombre, usuario.Password, usuario.Categoria, usuario.Id_Usuario, usuario.Visible
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
	}
	function DatosUsuario(&$res, $ID){
		$cons = ('SELECT usuario.Nombre, usuario.Password, cliente.* 
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
					SET Nombre = "'.$NomUs .'",
						Contacto = "' .$Mail.'" 
					WHERE Id_Usuario = ' .$ID;
		$res1 = mysql_query ($cons1);
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
			$cons = 'INSERT INTO `cookbook`.`cliente` (`DNI` ,`NombreApellido` ,`FechaAlta` ,`Telefono` ,`Direccion` ,`Contacto`)
					VALUES ("' .$DNI .'", "' .$NomApe .'", "' .$Fecha .'", "' .$Tel .'", "' .$Dir .'", "' .$Mail .'")';
			$res = mysql_query($cons);
			$cons2 = 'INSERT INTO `cookbook`.`usuario` (`Id_Usuario`, `Nombre`, `Password`, `Categoria`, `DNI`, `Contacto`, `Visible`) 
					VALUES (NULL, "' .$NomUs .'", "' .$Pass1 .'", "Normal","' .$DNI .'", "' .$Mail .'", 1)';
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
	function ConsultaPedidos (&$res, $ID){
		$cons = 'SELECT pedidos.ISBN, libro.Titulo, pedidos.DNI, cliente.NombreApellido, pedidos.FechaPedido, estado.Descripcion as Estado
				FROM usuario, cliente, pedidos, estado, libro
				WHERE usuario.Id_Usuario =' .$ID .'
				AND usuario.DNI = cliente.DNI
				AND cliente.DNI = pedidos.DNI
				AND pedidos.Id_Estado = estado.Id_Estado
				AND pedidos.ISBN = libro.ISBN';
		$res = mysql_query( $cons);
	}
	function PedidoEntregado ($ISBN, $DNI){
		$cons = 'UPDATE pedidos 
					SET Id_Estado = 3 
					WHERE ISBN = ' .$ISBN. '
					AND DNI = ' .$DNI;
		$res = mysql_query( $cons);			
	}
	function ConsultaLibro (&$res, $ISBN){
		$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
						AND libro.ISBN=' .$ISBN);
	}
	function ConsultaPorDefecto (&$res){
	$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
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
	function ConsultaFiltros (&$res, &$Pinf, &$Psup){
		if	(!empty($Pinf)){$PreInf = '"'.$Pinf.'"';}else{$PreInf = 'libro.Precio';}
		if	(!empty($Psup)){$PreSup = '"'.$Psup.'"';}else{$PreSup = 'libro.Precio';}
		$cons = 'SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion as Idioma, libro.Fecha, disponibilidad.Descripcion as Disponibilidad, libro.Visible
				FROM libro, autor, idioma, disponibilidad
				WHERE autor.Id_Autor = libro.Id_Autor
				AND idioma.Id_Idioma = libro.Id_Idioma
				AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad
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
		if	(!empty($Finf)){$FecInf = '"'.$Finf.'"';}else{$FecInf = 'libro.Fecha';}
		if	(!empty($Fsup)){$FecSup = '"'.$Fsup.'"';}else{$FecSup = 'libro.Fecha';}
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