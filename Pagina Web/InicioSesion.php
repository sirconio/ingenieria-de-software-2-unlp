<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
		if(!empty($_SESSION['estado'])){
?>			
			<script languaje="javascript">						
				self.close();
			</script>
<?php
		}
?>
<html>
	<head>
		<title>Iniciar Sesion</title>
		<link type="text/css" rel="stylesheet" href="styleIS.css">
		<script>
			function Entrar(){	
				window.open("index.php");
				self.close();
			}
			function validar(){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fvalidar.usuario.value.length==0){
					msg = msg + "Usuario; "
					entro = true;
				}
				if (document.fvalidar.clave.value.length==0){
					msg = msg + "Clave; "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.fvalidar.usuario.focus()
				   return 0;
				}			
				document.fvalidar.submit(); 		
			}	
		</script>
	</head>
	<body id='Sesion' > 
		<?php 
			include 'accion.php';
			ConexionServidor ($con);					
			ConexionBaseDatos ($bd);
			$msg = ' ';
			$entro = false;
			if	(!empty($_POST['usuario']) && !empty($_POST['clave'])){
				IniciarSesion($_POST['usuario'], $_POST['clave'], $msg, $entro);
			}
			if ($msg == ' '){
		?>
			<form method="post" name="fvalidar" action="">
				<samp>Por favor, ingrese:<samp/></br></br>
				<label for="Usuario">Usuario:</label><input type="text" name="usuario" placeholder="Usuario" maxlength="10" required /></br></br>
				<label for="Clave">Clave:</label><input type="password" name="clave" placeholder="Contraseña" maxlength="30" required /></br></br>				
				<input class="botones" type="button" value="Enviar" onclick="validar()">
			</form>
		<?php
			}
			else{ 
				if ($entro){
					echo $msg;
		?>			
					<script languaje="javascript">						
							self.Entrar();
					</script>
		<?php
				}
				else{
					echo $msg;
				}	
			}
			///CIERRE///
			CerrarServidor ($con);
		?>	
	</body>
</html>