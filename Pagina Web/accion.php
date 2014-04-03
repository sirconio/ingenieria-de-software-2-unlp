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
		$cons = 'SELECT usuario.Nombre, usuario.Password, usuario.Categoria
								FROM usuario
								ORDER BY usuario.Id_Usuario';
		$resUsuarioClave = mysql_query($cons);
		$num = mysql_num_rows($resUsuarioClave);
		$entro = false;
		if($num != 0){
			while($row = mysql_fetch_assoc($resUsuarioClave)){
				if ($usuario == $row['Nombre'] && $clave == $row['Password']){
					$entro = true;
					$_SESSION['estado'] = 'logeado';
					$_SESSION['usuario'] = $usuario;
					$_SESSION['categoria'] = $row['Categoria'];
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
	function ConsultaPorDefecto (&$res){
	$res = mysql_query('SELECT libro.ISBN, libro.Titulo, autor.NombreApellido, libro.CantidadPaginas, libro.Precio, idioma.Descripcion, libro.Fecha, disponibilidad.Descripcion, libro.Visible
						FROM libro, autor, idioma, disponibilidad
						WHERE autor.Id_Autor = libro.Id_Autor
						AND idioma.Id_Idioma = libro.Id_Idioma
						AND disponibilidad.Id_Disponibilidad = libro.Id_Disponibilidad');
	}
?>