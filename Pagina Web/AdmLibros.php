<?php header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
      header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
      header( "Cache-Control: no-cache, must-revalidate" );
      header( "Pragma: no-cache" );
	  session_start();
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
		
		<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<link rel="stylesheet" href="/resources/demos/style.css">
		
		<script>
			<!-- DATAPICKER ALTA -->
			$(function() {	
				$("#datepickerAltaLibro").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
			<!-- DATAPICKER MODIFICAR -->
			$(function() {	
				$("#datepickerModLibro").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
			<!-- DATAPICKER LIMITE INF -->
			$(function() {	
				$("#datepickerLimInf").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
			<!-- DATAPICKER LIMITE SUP -->
			$(function() {	
				$("#datepickerLimSup").datepicker({
					changeMonth: true,
					changeYear: true,
					showOtherMonths: true,
					selectOtherMonths: true
				});
			});
			<!-- VENTANA EMERGENTE DE INICIO DE SESION -->
			function acceso(){
				window.open("InicioSesion.php","myWindow","status = 1, height = 150, width = 350, resizable = no" );
			}
			<!-- RECARGA LA PAGINA CON EL FLAG EN TRUE -->
			function salir(){
				location.href="index.php?flag=true";
			}
			<!-- TRASLADA LA PAGINA VER PERFIL -->
			function irperfil(){
				location.href="VerPerfil.php";
			}
			<!-- ACTIVACION DEL FLAG DE LISTA DE LIBROS -->
			function listarlibros(){
				location.href="AdmLibros.php?flag=lista";
			}
			<!-- ACTIVACION DEL FLAG DE ALTA DE LIBROS -->
			function altalibro(){
				location.href="AdmLibros.php?flag=alta";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBROS -->
			function bajalibro(){
				location.href="AdmLibros.php?flag=baja";
			}
			<!-- ACTIVACION DEL FLAG DE MODIFICAR LIBRO -->
			function modlibro(){
				location.href="AdmLibros.php?flag=mod";
			}
			<!-- ACTIVACION DEL FLAG DE TOP 10 DE LIBROS MAS VENDIDOS -->
			function conlibro(){
				location.href="AdmLibros.php?flag=con";
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBRO CON ISBN -->
			function bajaLibro2(ISBN){		
				if (confirm("Desea dar de baja este libro?")){
					location.href="AdmLibros.php?accion=borrar&ISBN=" + ISBN;
				}
			}
			<!-- ACTIVACION DEL FLAG DE ACTIVACION DE LIBRO CON ID -->
			function activarLibro2(ISBN){				
				if (confirm("Desea reactivar este libro?")){
					location.href="AdmLibros.php?accion=activar&ISBN=" + ISBN;
				}
			}
			<!-- ACTIVACION DEL FLAG DE BAJA DE LIBRO CON ISBN -->
			function modLibro2(ISBN){
				location.href="AdmLibros.php?flag=mod&ISBN=" + ISBN;
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR AUTOR -->
			function agregarAutor(flag, IS){
				ISBN = document.getElementsByName("ISBN")[0].value;
				Titulo = document.getElementsByName("Titulo")[0].value;
				Autor = document.getElementsByName("Autor")[0].value;
				CantPag = document.getElementsByName("CantPag")[0].value;
				Precio = document.getElementsByName("Precio")[0].value;
				Idioma = document.getElementsByName("Idioma")[0].value;
				Fecha = document.getElementsByName("Fecha")[0].value;
				Disp = document.getElementsByName("Disp")[0].value;
				Ind = document.getElementsByName("Ind")[0].value;
				divCont = document.getElementById('AdminEtiq');
				checks  = divCont.getElementsByTagName('input');
				Et = "&Etiquetas[]=";
				for(i=0;i<checks.length; i++){
					if(checks[i].checked == true){
						Et = Et + checks[i].value;
						Et = Et + "&Etiquetas[]=";
					}
				}
				Et = Et + "none";				
				if ( flag == 'alta'){
					location.href="AdmLibros.php?flag=AgAu&F="+flag+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
				else{
					location.href="AdmLibros.php?flag=AgAu&F="+flag+"&ISBN="+IS+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- ACTIVACION DE LA ACCION ALTA AUTOR -->
			function AltaAutor(flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				entro = false;
				msg = "Tiene que completar el campo de: ";
				if (document.faut.AutorNom.value.length==0){
					msg = msg + "Nombre y Apellido del autor "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.faut.AutorNom.focus()
				   return 0;
				}	
				else{ 
					accion = document.getElementsByName("accion")[0].value;
					AutorNom = document.getElementsByName("AutorNom")[0].value;
					if ( Etiquetas == 0){
						Et = "";
					}
					else{
						Et = "&Etiquetas[]=";
						for (var i = 0; i < Etiquetas.length; i++) {
							Et = Et + Etiquetas[i];
							Et = Et + "&Etiquetas[]=";
						}
						Et = Et + "none";
					}
					location.href="AdmLibros.php?flag="+flag+"&accion="+accion+"&AutorNom="+AutorNom+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR IDIOMA -->
			function agregarIdioma(flag, IS){
				ISBN = document.getElementsByName("ISBN")[0].value;
				Titulo = document.getElementsByName("Titulo")[0].value;
				Autor = document.getElementsByName("Autor")[0].value;
				CantPag = document.getElementsByName("CantPag")[0].value;
				Precio = document.getElementsByName("Precio")[0].value;
				Idioma = document.getElementsByName("Idioma")[0].value;
				Fecha = document.getElementsByName("Fecha")[0].value;
				Disp = document.getElementsByName("Disp")[0].value;
				Ind = document.getElementsByName("Ind")[0].value;
				divCont = document.getElementById('AdminEtiq');
				checks  = divCont.getElementsByTagName('input');
				Et = "&Etiquetas[]=";
				for(i=0;i<checks.length; i++){
					if(checks[i].checked == true){
						Et = Et + checks[i].value;
						Et = Et + "&Etiquetas[]=";
					}
				}
				Et = Et + "none";
				if ( flag == 'alta'){
					location.href="AdmLibros.php?flag=AgId&F="+flag+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
				else{
					location.href="AdmLibros.php?flag=AgId&F="+flag+"&ISBN="+IS+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- ACTIVACION DE LA ACCION ALTA IDIOMA -->
			function AltaIdioma(flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				entro = false;
				msg = "Tiene que completar el campo de: ";
				if (document.fido.IdiomaNom.value.length==0){
					msg = msg + "Descripcion del idioma "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.fido.IdiomaNom.focus()
				   return 0;
				}	
				else{ 
					accion = document.getElementsByName("accion")[0].value;
					IdiomaNom = document.getElementsByName("IdiomaNom")[0].value;
					if ( Etiquetas == 0){
						Et = "";
					}
					else{
						Et = "&Etiquetas[]=";
						for (var i = 0; i < Etiquetas.length; i++) {
							Et = Et + Etiquetas[i];
							Et = Et + "&Etiquetas[]=";
						}
						Et = Et + "none";
					}
					location.href="AdmLibros.php?flag="+flag+"&accion="+accion+"&IdiomaNom="+IdiomaNom+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- ACTIVACION DEL FLAG DE AGREGAR ETIQUETA -->
			function agregarEtiqueta(flag, IS){
				ISBN = document.getElementsByName("ISBN")[0].value;
				Titulo = document.getElementsByName("Titulo")[0].value;
				Autor = document.getElementsByName("Autor")[0].value;
				CantPag = document.getElementsByName("CantPag")[0].value;
				Precio = document.getElementsByName("Precio")[0].value;
				Idioma = document.getElementsByName("Idioma")[0].value;
				Fecha = document.getElementsByName("Fecha")[0].value;
				Disp = document.getElementsByName("Disp")[0].value;
				Ind = document.getElementsByName("Ind")[0].value;
				divCont = document.getElementById('AdminEtiq');
				checks  = divCont.getElementsByTagName('input');
				Et = "&Etiquetas[]=";
				for(i=0;i<checks.length; i++){
					if(checks[i].checked == true){
						Et = Et + checks[i].value;
						Et = Et + "&Etiquetas[]=";
					}
				}
				Et = Et + "none";
				if ( flag == 'alta'){
					location.href="AdmLibros.php?flag=AgEt&F="+flag+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
				else{
					location.href="AdmLibros.php?flag=AgEt&F="+flag+"&ISBN="+IS+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- ACTIVACION DE LA ACCION ALTA ETIQUETA -->
			function AltaEtiqueta(flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				entro = false;
				msg = "Tiene que completar el campo de: ";
				if (document.feti.EtiquetaNom.value.length==0){
					msg = msg + "Descripcion de la etiqueta "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.feti.EtiquetaNom.focus()
				   return 0;
				}	
				else{ 
					accion = document.getElementsByName("accion")[0].value;
					EtiquetaNom = document.getElementsByName("EtiquetaNom")[0].value;
					if ( Etiquetas == 0){
						Et = "";
					}
					else{
						Et = "&Etiquetas[]=";
						for (var i = 0; i < Etiquetas.length; i++) {
							Et = Et + Etiquetas[i];
							Et = Et + "&Etiquetas[]=";
						}
						Et = Et + "none";
					}
					location.href="AdmLibros.php?flag="+flag+"&accion="+accion+"&EtiquetaNom="+EtiquetaNom+"&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
				}
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE LIBROS SATISFACTORIAS -->
			function MensajeResp(Msj){
				location.href="AdmLibros.php?flag=lista&respmsg="+Msj;
			}
			<!-- MENSAJE DE RESPUESTA A CONSULTAS SOBRE LIBROS ERRONEAS -->
			function Error(Msj, ISBN){
				alert(Msj);
				location.href="AdmLibros.php?flag=baja&ISBN="+ISBN;
			}
			<!-- RETORNA A PAGINA DE ALTA LIBRO -->
			function Atras(flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				if ( Etiquetas == 0){
					Et = "";
				}
				else{
					Et = "&Etiquetas[]=";
					for (var i = 0; i < Etiquetas.length; i++) {
						Et = Et + Etiquetas[i];
						Et = Et + "&Etiquetas[]=";
					}
					Et = Et + "none";
				}
				location.href="AdmLibros.php?flag="+flag+"&tip=err&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
			}
			<!-- MENSAJE DE ALTA DE AUTRO,IDIOMA Y ETIQUETA SATISFACTORIA -->
			function MensajeResp2(Msj, flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				if ( Etiquetas == 0){
					Et = "";
				}
				else{
					Et = "&Etiquetas[]=";
					for (var i = 0; i < Etiquetas.length; i++) {
						Et = Et + Etiquetas[i];
						Et = Et + "&Etiquetas[]=";
					}
					Et = Et + "none";
				}
				location.href="AdmLibros.php?flag="+flag+"&tip=err&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
			}
			<!-- MENSAJE DE ALTA DE AUTRO,IDIOMA Y ETIQUETA ERRONEA -->
			function MensajeErr2(Msj,cond, flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				alert(Msj);
				if ( Etiquetas == 0){
					Et = "";
				}
				else{
					Et = "&Etiquetas[]=";
					for (var i = 0; i < Etiquetas.length; i++) {
						Et = Et + Etiquetas[i];
						Et = Et + "&Etiquetas[]=";
					}
					Et = Et + "none";
				}
				location.href="AdmLibros.php?flag="+cond+"&F="+flag+"&tip=err&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
			}			
			<!-- MENSAJE DE MODIFICACION ERRONEA -->
			function ErrorMod(Msj, flag, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Disp, Ind){
				alert(Msj);
				if ( Etiquetas == 0){
					Et = "";
				} 
				else{
					Et = "&Etiquetas[]=";
					for (var i = 0; i < Etiquetas.length; i++) {
						Et = Et + Etiquetas[i];
						Et = Et + "&Etiquetas[]=";
					}
					Et = Et + "none";
				}
				location.href="AdmLibros.php?flag="+flag+"&tip=err&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Disp="+Disp+"&Indice="+Ind;
			}
			<!-- MENSAJE DE ERROR -->
			function MensajeErr(Msj, ISBN, Titulo , Autor, CantPag, Precio, Idioma, Fecha, Etiquetas, Ind){
				alert(Msj);
				if ( Etiquetas == 0){
					Et = "";
				}
				else{
					Et = "&Etiquetas[]=";
					for (var i = 0; i < Etiquetas.length; i++) {
						Et = Et + Etiquetas[i];
						Et = Et + "&Etiquetas[]=";
					}
					Et = Et + "none";
					
				}
				location.href="AdmLibros.php?flag=alta&tip=err&ISBN="+ISBN+"&Titulo="+Titulo+"&Autor="+Autor+"&CantPag="+CantPag+"&Precio="+Precio+"&Idioma="+Idioma+"&Fecha="+Fecha+Et+"&Indice="+Ind;
			}
			<!-- VENTANA DE DETALLES -->
			function Hojear(ISBN, Titulo, NombreApellido, Precio, CantPag, Idioma, Fecha, Disp, Etiq, Nro){
				Ventana = window.open('','Detalles','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600,height=400');
				Ventana.moveTo(100,100);
				Ventana.document.innerHTML = "";
				var Indice = "Ind"+Nro;
				Ind = document.getElementsByName(Indice)[0].value;			
				Ventana.document.write("<html><head></head><body background='Fondo8.jpg' background-size='cover' style='color:white' onblur='self.close()' ><p>ISBN: " + ISBN + " - Autor: " + NombreApellido + " - Titulo: " + Titulo + "</p><p>Idioma: " + Idioma + " - Cantidad de Paginas: " + CantPag + " - Fecha Publicacion: " + Fecha + "</p><p>Precio: $" + Precio + "</p><p>Etiquetas: " + Etiq + "</p><p>Indice: <p>&nbsp;&nbsp;&nbsp;&nbsp;</p>" + Ind + "</p><p>------------------------------</p></body></html>");
				myWindow.focus();
			}
			<!-- VALIDACIONES DE CAMPOS -->
			function Numeros(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function NumerosPunto(e){
				var keynum = window.event ? window.event.keyCode : e.which;
				if ((keynum == 8) || (keynum == 46))
				return true;
				 
				return /\d/.test(String.fromCharCode(keynum));
			}
			function LetrasEspacio(e) {
				tecla = (document.all) ? e.keyCode : e.which;
				if ((tecla==8) || (tecla == 32)) return true;
				patron =/[A-Za-z]/;
				te = String.fromCharCode(tecla);
				return patron.test(te);
			}
			function validarbus (){
				if (document.fbus.BusRap.value.length==0){
				   alert("Tiene que completar el campo de busqueda")
				   document.fbus.BusRap.focus()
				   return 0;
				}			
				document.fbus.submit(); 		
			}
			function validarbusmod (){
				if (document.fbusmod.ISBN.value.length==0){
				   alert("Tiene que completar el campo de ISBN del libro ha modificar")
				   document.fbusmod.ISBN.focus()
				   return 0;
				}			
				document.fbusmod.submit(); 		
			}					
			function validarbusbaja (){
				if (document.fbusbaja.ISBN.value.length==0){
				   alert("Tiene que completar el campo de ISBN del libro ha borrar/activar")
				   document.fbusbaja.ISBN.focus()
				   return 0;
				}			
				document.fbusbaja.submit(); 		
			}			
			function validarperiodo(){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fperiodo.Fini.value.length==0){
					msg = msg + "Fecha Inicial; "
					entro = true;
				}
				if (document.fperiodo.Ffin.value.length==0){
					msg = msg + "Fecha Final; "
					entro = true;
				}
				if(entro){
				   alert(msg)
				   document.fperiodo.NomUs.focus()
				   return 0;
				}			
				document.fperiodo.submit(); 		
			}
			function validarmod (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.fmod.Titulo.value.length==0){
				   	msg = msg + "Titulo; "
					entro = true;
				}	
				if (document.fmod.Autor.value.length==0){
				   	msg = msg + "Autor; "
					entro = true;
				}	
				if (document.fmod.CantPag.value.length==0){
				   	msg = msg + "Cantida de Paginas; "
					entro = true;
				}		
				if (document.fmod.Idioma.value.length==0){
				   	msg = msg + "Idioma; "
					entro = true;
				}	
				if (document.fmod.Precio.value.length==0){
				   	msg = msg + "Precio; "
					entro = true;
				}	
				if (document.fmod.Fecha.value.length==0){
				   	msg = msg + "Fecha; "
					entro = true;
				}				
				if (document.fmod.Disp.value.length==0){
				   	msg = msg + "Disponibilidad; "
					entro = true;
				}		
				if (document.fmod.Ind.value.length==0){
				   	msg = msg + "Indice; "
					entro = true;
				}						
				if(entro){
				   alert(msg)
				   document.fmod.Titulo.focus()
				   return 0;
				}			
				document.fmod.submit(); 		
			}
			function validaralta (){
				entro = false;
				msg = "Tiene que completar el/los campo/s de: ";
				if (document.falta.ISBN.value.length==0){
				   	msg = msg + "ISBN; "
					entro = true;
				}
				if (document.falta.Titulo.value.length==0){
				   	msg = msg + "Titulo; "
					entro = true;
				}	
				if (document.falta.Autor.value.length==0){
				   	msg = msg + "Autor; "
					entro = true;
				}	
				if (document.falta.CantPag.value.length==0){
				   	msg = msg + "Cantida de Paginas; "
					entro = true;
				}	
				if (document.falta.Precio.value.length==0){
				   	msg = msg + "Precio; "
					entro = true;
				}					
				if (document.falta.Idioma.value.length==0){
				   	msg = msg + "Idioma; "
					entro = true;
				}	
				if (document.falta.Fecha.value.length==0){
				   	msg = msg + "Fecha; "
					entro = true;
				}				
				if (document.falta.Ind.value.length==0){
				   	msg = msg + "Indice; "
					entro = true;
				}						
				if(entro){
				   alert(msg)
				   document.falta.Titulo.focus()
				   return 0;
				}			
				document.falta.submit(); 		
			}			
			<!-- FIN VALIDACIONES DE CAMPOS -->
		</script>
	</head>
	<body>
		<!-- CABECERA -->
		<div id='cabecera'>
			<!-- LOGO COOKBOOK -->
			<div id='imglogo'><a href="index.php"><img src="Logo1.gif" width="85%" height="475%"></a></div> 
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
				// ACCION = AGREGAR, INDICA QUE SE DARA DE ALTA UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'agregar'){
					$Cons = false;
					AltaLibro($Cons, $_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $_GET['Ind'], $AltMsg);
					if ($Cons){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
						if (!empty($_GET['Etiquetas'])){
	?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
	<?php
						}
						else{
	?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
	<?php
						}
	?>
						<script languaje="javascript"> 	
							MensajeErr("<?=$AltMsg?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Ind']?>");	
						</script>
	<?php				
					}
				}
				// ACCION = BORRAR, INDICA QUE SE DARA DE BAJA UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'borrar'){
					$Comp = false;
					BajaLibro($_GET['ISBN'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							Error("<?=$AltMsg?>", "<?=$_GET['ISBN']?>");	
						</script>
	<?php
					}
				}
				// ACCION = ACTIVAR, INDICA QUE SE DARA LA ACTIVACION DE UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'activar'){
					$Comp = false;
					ActivarLibro($_GET['ISBN'], $AltMsg, $Comp);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php
					}
					else{
	?>
						<script languaje="javascript"> 	
							Error("<?=$AltMsg?>", "<?=$_GET['ISBN']?>");	
						</script>
	<?php					
					}
				}
				// ACCION = MODIFICAR, INDICA QUE SE DARA LA MODIFICACION DE UN LIBRO
				if (!empty($_GET['accion']) && $_GET['accion'] == 'modificar'){
					$Comp = false;
					ModLibro ($Comp, $_GET['ISBN'], $_GET['Titulo'], $_GET['Autor'], $_GET['CantPag'], $_GET['Precio'], $_GET['Idioma'], $_GET['Fecha'], $_GET['Etiquetas'], $_GET['Disp'], $_GET['Ind'], $AltMsg);
					if ($Comp){
	?>
						<script languaje="javascript"> 	
							MensajeResp("<?=$AltMsg?>");	
						</script>
	<?php		
					}
					else{
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
		?>
							<script languaje="javascript"> 	
								ErrorMod("<?=$AltMsg?>", "mod", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");
							</script>
		<?php					
					}	
				}					
				// ACCION = AGREGARAU, INDICA QUE SE DARA DE ALTA UN AUTOR
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarAu'){
					$Comp = false;
					AgregarAutor ($_GET['AutorNom'], $AltMsg, $Comp);
					if ($Comp){
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
						if (!empty($_GET['flag2'])){
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag2']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php							
						}
						else{
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php			
						}
					}
					else{
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
		?>
						<script languaje="javascript"> 	
							MensajeErr2("<?=$AltMsg?>", "AgAu", "<?=$_GET['flag']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
						</script>
		<?php								
					}
				}		
				// ACCION = AGREGARIDIO, INDICA QUE SE DARA DE ALTA UN IDIOMA				
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarIdio'){
					$Comp = false;
					AgregarIdioma ($_GET['IdiomaNom'], $AltMsg, $Comp);
					if ($Comp){
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
						if (!empty($_GET['flag2'])){
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag2']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php							
						}
						else{
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php			
						}			
					}
					else{
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
		?>
						<script languaje="javascript"> 	
							MensajeErr2("<?=$AltMsg?>", "AgId", "<?=$_GET['flag']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
						</script>
		<?php										
					}
				}	
				// ACCION = AGREGARETIQ, INDICA QUE SE DARA DE ALTA UNA ETIQUETA	
				if (!empty($_GET['accion']) && $_GET['accion'] == 'AgregarEtiq'){
					$Comp = false;
					AgregarEtiqueta ($_GET['EtiquetaNom'], $AltMsg, $Comp);
					if ($Comp) {
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
						if (!empty($_GET['flag2'])){
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag2']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php							
						}
						else{
		?>
							<script languaje="javascript"> 	
								MensajeResp2("<?=$AltMsg?>", "<?=$_GET['flag']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
							</script>
		<?php			
						}				
					}
					else{
						if (!empty($_GET['Etiquetas'])){
		?>	
							<script languaje="javascript"> 	
								var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
							</script>				
		<?php
						}
						else{
		?>
							<script languaje="javascript"> 	
								var Etiquetasjs = 0;
							</script>
		<?php
						}
		?>
						<script languaje="javascript"> 	
							MensajeErr2("<?=$AltMsg?>", "AgEt", "<?=$_GET['flag']?>",  "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>");	
						</script>
		<?php										
					}
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
							echo ' logeado - Categor&iacutea:';
							echo $_SESSION['categoria'];
							echo '</li>';
							echo '<li><a onclick="irperfil()">Ir a Perfil</a> - <a onclick="salir()">Cerrar Sesion</a></li>';//BOTON DE CIERRE DE SESION, LLAMA A LA fuction salir()
						echo '</ul>';
					}
				}
					
	?>
			</div>
		</div>
		<!-- CUERPO -->
		<div id='cuerpo'>
			<!-- BOTONES DE DESPLAZAMIENTO -->
			<div id='encabezado'>
				<ul id='botones'>
					<li><a href="AdmLibros.php?flag=lista">Listar todos los libros</a></li>
					<li><a href="AdmLibros.php?flag=alta">Dar de alta un libro</a></li>
					<li><a href="AdmLibros.php?flag=baja">Dar de baja/activar un libro</a></li>
					<li><a href="AdmLibros.php?flag=mod">Modificar un libro</a></li>
					<li><a href="AdmLibros.php?flag=con">Libros m&aacutes vendidos en un per&iacuteodo </a></li>
					<li><a href="Administrador.php">Volver a administrar</a></li>
				</ul>
			</div>
			<!-- CONTENIDO ABM LIBRO -->
			<div id='contenidoadm'>
				<!-- RECTANGULO DE TRABAJO -->
				<div id='libros'>
	<?php	
	 				// OPCION LISTAR LIBROS //
					if (!empty($_GET['flag']) && $_GET['flag'] == 'lista'){
						if(!empty($_GET['respmsg'])){
							echo '<div id="textoadmped"><samp>>>>>>>' .$_GET['respmsg'] .'<<<<<<</samp></br><samp>Listado de todos los libros:</samp></div>';
						}
						else{
							echo '<div id="textoadmped"><samp>Listado de todos los libros:</samp></div>';
						}
						echo '<div id="barrabusquedaABM" action="Busqueda.php" method="GET">
						<form name="fbus">
							<input size="40" type="text" name="BusRap" placeholder="Autor, Titulo, ISBN" required>
							<input type="hidden" name="flag" value="lista" required readonly>							
							<input id="BusRapBotABM" type="button" value="Buscar" onclick="validarbus()">
						</form>
						</div>';
						echo '<div id="TablaLibros">';
						if (!empty($_GET['BusRap'])){
							ConsultaPorDefectoBus ($restam, $_GET['BusRap']);
						}
						else{
							ConsultaPorDefecto ($restam);
						}	
						if(!$restam) {
							$message= 'Consulta invalida: ' .mysql_error() ."\n";
							die($message);
						}
						$num1 = mysql_num_rows($restam);
						if($num1 == 0){
							echo 'No se encontro ning&uacuten libro';
						}
						else{
							if (empty($_GET['numpag'])){
								$NroPag = 1;
							}
							else{
								$NroPag = $_GET['numpag'];
							}
							if (!empty($_GET['BusRap'])){
								ConsultaPorDefectoPagBus ($res, ($NroPag-1), $_GET['BusRap']);
							}
							else{
								ConsultaPorDefectoPag ($res, ($NroPag-1));
							}	
							$num2 = mysql_num_rows($res);
							if($num2 == 0){
								echo 'No se localizo ning&uacuten libro';
							}
							else{	
								// GENERAR TABLA //
								echo 'Pagina Numero: ' .$NroPag;
								echo "<table border='1'>
									<tr>
										<th>ISBN</th>
										<th>T&iacutetulo</th>
										<th>Autor</th>
										<th>Precio</th>										
										<th>Detalle</th>
										<th>Estado</th>
									</tr>";
								$ant = ' ';
								$Nro = 0;
								while($row = mysql_fetch_assoc($res)) {
									if ($row['ISBN'] != $ant){
										echo "<tr>";
											echo "<td>", $row['ISBN'], "</td>";
											echo "<td>", $row['Titulo'], "</td>";
											echo "<td>", $row['NombreApellido'], "</td>";									
											echo "<td>", $row['Precio'], "</td>";											
											BuscarEtiquetas($row['ISBN'], $Etiq);
											echo '<textarea hidden name="Ind' .$Nro .'" rows="10" cols="10">' .$row['Indice'] .'</textarea>';											
		?>																	
											<td><input class="botones" type='button' value='Detalle' onclick='Hojear("<?=$row['ISBN']?>", "<?=$row['Titulo']?>", "<?=$row['NombreApellido']?>", "<?=$row['Precio']?>", "<?=$row['CantidadPaginas']?>", "<?=$row['Idioma']?>", "<?=$row['Fecha']?>", "<?=$row['Disponibilidad']?>", "<?=$Etiq?>", "<?=$Nro?>")' /></td>
		<?php
											echo '<td>'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '</td>';
											if ($row['Estado'] == 1){
		?>
												<td><input class="botones" type='button' value='Modificar' onclick='modLibro2("<?=$row['ISBN']?>")' /></td>
												<td><input class="botones" type='button' value='Eliminar' onclick='bajaLibro2("<?=$row['ISBN']?>")' /></td>
		<?php
											}
											else{
		?>											
											<td><input class="botones" type='button' value='ReActivar' onclick='activarLibro2("<?=$row['ISBN']?>")' /></td>
		<?php
											}								
										echo "</tr>";
										$Nro = $Nro + 1;
										$ant = $row['ISNB'];
									}
								}
								echo "</table>";
							}	
						}	
						echo '</div>';
						echo '<div id="PaginasPed">';
							$pag = 1;
							echo 'P&aacuteginas: ';
							while ( $num1 > 0 ) {
								echo '<li><a href="AdmLibros.php?flag=lista&numpag=' .$pag .'">' .$pag .'</a></li>
								<li> - </li>';
								$pag ++;
								$num1 = $num1-10;
							}
						echo '</div>';		
					}
					// OPCION ALTA LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'alta'){ 
						echo '<div id="textoadmped"><samp>Alta de libros:</samp></div>';
						// CONSULTAS SELECT //
						ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
	?>			
						<div id='ABMDiv'>
	<?php
							if (!empty($_GET['tip']) && $_GET['tip'] == 'err'){
	?>
									<!-- FROMULARIO DE ALTA -->
	<?php										
								echo '<form class="FAbm" name="falta" action="" method="GET">
									<label for="ISNB">ISBN:</label>
									<input type="text" name="ISBN" value="', $_GET['ISBN'], '" placeholder="Ej: 1234567890" maxlength="9" onkeypress="return Numeros(event);" required><br>
									<label for="Titulo">T&iacutetulo:</label>
									<input type="text" name="Titulo" value="', $_GET['Titulo'], '" size="40" placeholder="Ej: Titulo" maxlength="45" required><br>
									<label for="Autor">Autor:</label>			
									<select class="botones" name="Autor" required>';
									if (!empty($_GET['Autor'])){
										echo '<option value="', $_GET['Autor'], '">', $_GET['Autor'], '</option>';
									}
									else{
										echo '<option value="">Seleccione un Autor...</option>';
									}
										while($row = mysql_fetch_assoc($resautor)){	
											if ( $row['Estado'] == 1){
												echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
											}
										}
									echo '</select>';
		?>			
									<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor('alta', '0')"></br>
		<?php		
									echo '<label for="CantPag">Cantidad P&aacuteginas:</label>
									<input type="text" name="CantPag" value="', $_GET['CantPag'], '" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required><br>
									<label for="Precio">Precio:</label>
									<input type="text" name="Precio" value="', $_GET['Precio'], '" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required><br>
									<label for="Idioma">Idioma:</label>
									<select class="botones" name="Idioma" required>';
									if (!empty($_GET['Idioma'])){
										echo '<option value="', $_GET['Idioma'], '">', $_GET['Idioma'], '</option>';
									}  
									else{
										echo '<option value="">Seleccione un idioma...</option>';
									}
									  while($row = mysql_fetch_assoc($residiomas)){	
											if ( $row['Estado'] == 1){
												echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
											}
										}
									echo '</select>';
	?>											
									<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma('alta', '0')"></br>
	<?php
									echo '<label for="Fecha">Fecha:</label>
									<input type="text" name="Fecha" value="', $_GET['Fecha'], '" id="datepickerAltaLibro" required><br>
									<select hidden class="botones" name="Disp" required>
										<option  value="0"></option>';
									echo '</select></br>
									<label for="Etiquetas">Etiquetas:</label>
									<div id="AdminEtiq">';	
									if (!empty($_GET['Etiquetas'])){
										while($row = mysql_fetch_assoc($resetiquetas)){	
											if ( $row['Estado'] == 1){
												$entro = false;						
												foreach ($_GET['Etiquetas'] as &$valor){											
													if ($row['Etiqueta'] == $valor){
														echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '" checked="checked" >', $row['Etiqueta'];
														$entro = true;
													}
												}
												if (!$entro){
													echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
												}
											}	
										}
									}
									else{
										while($row = mysql_fetch_assoc($resetiquetas)){	
											echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
										}	
									}
									echo '</div>';
	?>								
									<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta('alta','0')"></br>
	<?php
									echo '<div id="AdminIndice">
										<label for="Fecha">Indice:</label></br>
										<textarea name="Ind" rows="10" cols="37" maxlength="255" placeholder="Indice del libro..."  required>' .$_GET['Indice']. '</textarea> 
									</div>';
									echo '<input type="hidden" name="accion" value="agregar" required readonly>									
									<input class="botones" type="button" value="Cargar" onclick="validaralta()">	
								</form>';
							}
							else{
	?>
								<!-- FROMULARIO DE ALTA -->
								<form class='FAbm' name="falta" action="" method="GET">
									<label for="ISNB">ISBN:</label>
									<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="9" onkeypress="return Numeros(event);" required><br>
									<label for="Titulo">T&iacutetulo:</label>
									<input type="text" name="Titulo" size="40" placeholder="Ej: Titulo" maxlength="45" required><br>
									<label for="Autor">Autor:</label>
		<?php						
									echo '<select class="botones" name="Autor" required>
										<option value="">Seleccione un Autor...</option>';
										while($row = mysql_fetch_assoc($resautor)){	
											if ( $row['Estado'] == 1){
												echo '<option value="', $row['Autor'], '">', $row['Autor'], '</option>';
											}
										}
									echo '</select>';
		?>			
									<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor('alta', '0')"></br>
		<?php		
									echo '<label for="CantPag">Cantidad P&aacuteginas:</label>
									<input type="text" name="CantPag" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required><br>
									<label for="Precio">Precio:</label>
									<input type="text" name="Precio" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required><br>
									<label for="Idioma">Idioma:</label>
									<select class="botones" name="Idioma" required>
										<option value="">Seleccione un idioma...</option>';
										while($row = mysql_fetch_assoc($residiomas)){	
											if ( $row['Estado'] == 1){
												echo '<option value="', $row['Idioma'], '">', $row['Idioma'], '</option>';
											}
										}
									echo '</select>';
	?>									
									<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma('alta', '0')"></br>
	<?php
									echo '<label for="Fecha">Fecha:</label>
									<input type="text" name="Fecha" id="datepickerAltaLibro" required><br>
									<select hidden class="botones" name="Disp" required>
										<option  value="0"></option>';
									echo '</select></br>
									<label for="Etiquetas">Etiquetas:</label>
									<div id="AdminEtiq">';								
									while($row = mysql_fetch_assoc($resetiquetas)){	
										if ( $row['Estado'] == 1){	
											echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row['Etiqueta'], '">', $row['Etiqueta'];
										}
									}
									echo '</div>';
	?>								
									<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta('alta', '0')"></br>
	<?php
									echo '<div id="AdminIndice">
										<label for="Fecha">Indice:</label></br>
										<textarea name="Ind" rows="10" cols="37" maxlength="255" placeholder="Indice del libro..."  required></textarea> 
									</div>';
									echo '<input type="hidden" name="accion" value="agregar" required readonly>
									<input class="botones" type="button" value="Cargar" onclick="validaralta()">	
								</form>';
							}
						echo '</div>';
					}
					// OPCION BAJA LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'baja'){
						echo '<div id="textoadmped"><samp>Baja/Activaci&oacuten de libros:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ISBN -->
							<form class='FAbm' name="fbusbaja" action="" method="GET">
								<label for="ISNB">ISBN del Libro a Borrar/Activar:</label>
								<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="9" onkeypress="return Numeros(event);" required>
								<input type="hidden" name="flag" value="baja" required readonly>
								<input class="botones" type="button" value="Buscar" onclick="validarbusbaja()">	
							</form>
	<?php	
							if (!empty($_GET['ISBN'])){
								ConsultaLibro ($res, $_GET['ISBN']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ning&uacuten libro con ese ISBN';
								}
								else{								
									while($row = mysql_fetch_assoc($res)){
										// FORMULARIO DE BAJA //
										echo '<form class="FAbm" action="" method="GET">											
											<label for="Visble">ISBN:</label><input type="text" name="ISBN" value="', $row['ISBN'], '" required readonly>	
											<label for="Visble">Estado:</label><input type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>
											<label for="Titulo">T&iacutetulo:</label>
											<input type="text" name="Titulo" value="', $row['Titulo'], '" size="40"  required readonly><br>
											<label for="Autor">Autor:</label>
											<input type="text" name="Autor" value="', $row['NombreApellido'], '" required readonly><br>
											<label for="CantPag">Cantidad Paginas:</label>
											<input type="text" name="CantPag" value="', $row['CantidadPaginas'], '" required readonly><br>
											<label for="Precio">Precio:</label>
											<input type="text" name="Precio" value="', $row['Precio'], '" required readonly><br>
											<label for="Idioma">Idioma:</label>
											<input type="text" name="Idioma" value="', $row['Idioma'], '" required readonly><br>
											<label for="Fecha">Fecha:</label>
											<input type="text" name="Fecha" value="', $row['Fecha'], '" required readonly><br>';							
											if ($row['Estado'] == 1){ 
												echo '<input type="hidden" name="accion" value="borrar" required readonly>';	
	?>
													<input class="botones" type="button" value="Borrar" onclick='bajaLibro2("<?=$row['ISBN']?>")' />
	<?php												
											}
											else{ 
												echo '<input type="hidden" name="accion" value="activar" required readonly>';	
	?>
													<input class="botones" type="button" value="Activar" onclick='activarLibro2("<?=$row['ISBN']?>")' />
	<?php																							
											}
										echo '</form>';
									}	
								}
							}
						echo '</div>';
					}
					// OPCION MODIFICAR LIBROS //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'mod'){	
						echo '<div id="textoadmped"><samp>Modificaci&oacuten de libros:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE BUSQUEDA POR ISBN -->
							<form class='FAbm' name="fbusmod" action="" method="GET">
								<label for="ISNB">ISBN del Libro modificar:</label>
								<input type="text" name="ISBN" placeholder="Ej: 1234567890" maxlength="9" onkeypress="return Numeros(event);" required>
								<input type="hidden" name="flag" value="mod" required readonly>
								<input class="botones" type="button" value="Buscar" onclick="validarbusmod()">	
							</form>
	<?php		
							if (!empty($_GET['ISBN'])){
								ConsultaLibro ($res, $_GET['ISBN']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se encontro ning&uacuten libro con ese ISBN';
								}
								else{		
									// CONSULTAS SELECT //
									ConsultasSelect ($residiomas, $resdisp, $resetiquetas, $resautor, $restitulo, $resisbn);
									while($row = mysql_fetch_assoc($res)){
										if ($row['Estado'] == 1){
											// FORMULARIO DE MODIFICACION //
											if (!empty($_GET['tip']) && $_GET['tip'] == 'err'){
												echo '<form class="FAbm" name="fmod" action="" method="GET">			
													<label for="Visble">ISBN:</label><input type="text" name="ISBN" value="', $_GET['ISBN'], '" required readonly>	
													<input hidden type="text" name="Estad" value="', $_GET['Estad'], '" required readonly><br>';
													echo'<label for="Titulo">Titulo:</label>
													<input type="text" name="Titulo" value="', $_GET['Titulo'], '" size="40" placeholder="Ej: Titulo" maxlength="45" required ><br>
													<label for="Autor">Autor:</label>
													<select class="botones" name="Autor" required>
														<option value="' .$_GET['Autor']. '">' .$_GET['Autor']. '</option>';
														while($row2 = mysql_fetch_assoc($resautor)){
															if ( $row2['Estado'] == 1){
																echo '<option value="', $row2['Autor'], '">', $row2['Autor'], '</option>';
															}
														}
													echo '</select>';
		?>													
													<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor('mod', '<?=$_GET['ISBN']?>')"></br>
		<?php	
													echo '<label for="CantPag">Cantidad P&aacuteginas:</label>
													<input type="text" name="CantPag" value="', $_GET['CantPag'], '" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required ><br>
													<label for="Precio">Precio:</label>
													<input type="text" name="Precio" value="', $_GET['Precio'], '" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required ><br>
													<label for="Idioma">Idioma:</label>
													<select class="botones" name="Idioma" required>
														<option value="' .$_GET['Idioma']. '">' .$_GET['Idioma']. '</option>';
														while($row3 = mysql_fetch_assoc($residiomas)){	
															if ( $row3['Estado'] == 1){
																echo '<option value="', $row3['Idioma'], '">', $row3['Idioma'], '</option>';
															}
														}
													echo '</select>';
		?>											
													<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma('mod', '<?=$_GET['ISBN']?>')"></br>			
		<?php
													echo '<label for="Fecha">Fecha:</label>';
													$date = date('m-d-Y', strtotime($_GET['Fecha']));
													$datefin = str_replace('-', '/', $date);
													echo '<input type="text" name="Fecha" value="', $datefin, '" id="datepickerModLibro" required ><br>
													<label for="Dis">Disponibilidad:</label>
													<select class="botones" name="Disp" required>
														<option value="' .$_GET['Disp']. '">' .$_GET['Disp']. '</option>';
														while($row6 = mysql_fetch_assoc($resdisp)){	
																echo '<option value="', $row6['Disponibilidad'], '">', $row6['Disponibilidad'], '</option>';
														}
													echo '</select></br>';
													echo '<label for="Etiquetas">Etiquetas:</label>
													<div id="AdminEtiq">';
													// CONSULTA DE BUSQUEDA DE ETIQUETAS PARA UN ISBN //
													if (!empty($_GET['Etiquetas'])){
														while($row10 = mysql_fetch_assoc($resetiquetas)){	
															if ( $row10['Estado'] == 1){
																$entro = false;						
																foreach ($_GET['Etiquetas'] as &$valor){											
																	if ($row10['Etiqueta'] == $valor){
																		echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row10['Etiqueta'], '" checked="checked" >', $row10['Etiqueta'];
																		$entro = true;
																	}
																}
																if (!$entro){
																	echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row10['Etiqueta'], '">', $row10['Etiqueta'];
																}
															}	
														}
													}
													else{
														while($row = mysql_fetch_assoc($resetiquetas)){	
															echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row10['Etiqueta'], '">', $row10['Etiqueta'];
														}	
													}
													echo '</div>';
		?>											
													<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta('mod', '<?=$_GET['ISBN']?>')"></br>
		<?php
													echo '<div id="AdminIndice">
														<label for="Fecha">Indice:</label></br>
														<textarea name="Ind" rows="10" cols="37" maxlength="255" placeholder="Indice del libro..."  required>' .$_GET['Indice'].'</textarea> 
													</div>';
													if ($row['Estado'] == 1){ 
														echo '<input type="hidden" name="accion" value="modificar" required readonly>	
														<input class="botones" type="button" value="Modificar" onclick="validarmod()">';
													}
													else{ 
														echo '<input class="botones" type="submit" value="Modificar" disabled>';
													}
												echo '</form>';
											}
											else{
												echo '<form class="FAbm" name="fmod" action="" method="GET">			
													<label for="Visble">ISBN:</label><input type="text" name="ISBN" value="', $row['ISBN'], '" required readonly>	
													<input hidden type="text" name="Estad" value="'; if ($row['Estado'] == 1){ echo 'Activo';}else{ echo 'Borrado';} echo '" required readonly><br>';
													echo'<label for="Titulo">Titulo:</label>
													<input type="text" name="Titulo" value="', $row['Titulo'], '" size="40" placeholder="Ej: Titulo" maxlength="45" required ><br>
													<label for="Autor">Autor:</label>
													<select class="botones" name="Autor" required>
														<option value="' .$row['NombreApellido']. '">' .$row['NombreApellido']. '</option>';
														while($row2 = mysql_fetch_assoc($resautor)){	
															if ( $row2['Estado'] == 1){
																echo '<option value="', $row2['Autor'], '">', $row2['Autor'], '</option>';
															}
														}
													echo '</select>';
		?>													
													<input class="botones" type="button" value="Agregar Autor" onclick="agregarAutor('mod', '<?=$_GET['ISBN']?>')"></br>
		<?php	
													echo '<label for="CantPag">Cantidad P&aacuteginas:</label>
													<input type="text" name="CantPag" value="', $row['CantidadPaginas'], '" placeholder="Ej: 1" maxlength="10" onkeypress="return Numeros(event);" required ><br>
													<label for="Precio">Precio:</label>
													<input type="text" name="Precio" value="', $row['Precio'], '" placeholder="Ej: 37.14" maxlength="10" onkeypress="return NumerosPunto(event);" required ><br>
													<label for="Idioma">Idioma:</label>
													<select class="botones" name="Idioma" required>
														<option value="' .$row['Idioma']. '">' .$row['Idioma']. '</option>';
														while($row3 = mysql_fetch_assoc($residiomas)){	
															if ( $row3['Estado'] == 1){
																echo '<option value="', $row3['Idioma'], '">', $row3['Idioma'], '</option>';
															}
														}
													echo '</select>';
		?>											
													<input class="botones" type="button" value="Agregar Idioma" onclick="agregarIdioma('mod', '<?=$_GET['ISBN']?>')"></br>			
		<?php
													echo '<label for="Fecha">Fecha:</label>';
													$date = date('m-d-Y', strtotime($row['Fecha']));
													$datefin = str_replace('-', '/', $date);
													echo '<input type="text" name="Fecha" value="', $datefin, '" id="datepickerModLibro" required ><br>
													<label for="Dis">Disponibilidad:</label>
													<select class="botones" name="Disp" required>
														<option value="' .$row['Disponibilidad']. '">' .$row['Disponibilidad']. '</option>';
														while($row6 = mysql_fetch_assoc($resdisp)){	
																echo '<option value="', $row6['Disponibilidad'], '">', $row6['Disponibilidad'], '</option>';
														}
													echo '</select></br>';
													echo '<label for="Etiquetas">Etiquetas:</label>
													<div id="AdminEtiq">';
													// CONSULTA DE BUSQUEDA DE ETIQUETAS PARA UN ISBN //
													BuscarEtiq ($row['ISBN'], $LibEtiq);
													$num = mysql_num_rows($LibEtiq);
													while($row4 = mysql_fetch_assoc($resetiquetas)){	
														if ( $row4['Estado'] == 1){
															$entro = false;
															if($num != 0){
																mysql_data_seek ($LibEtiq, 0);
															}												
															while($row5 = mysql_fetch_assoc($LibEtiq)){	
																if ($row4['Etiqueta'] == $row5['Descripcion']){
																	echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '" checked="checked" >', $row4['Etiqueta'];
																	$entro = true;
																}
															}
															if (!$entro){
																echo '<input class="botones" type="checkbox" name="Etiquetas[]" value="', $row4['Etiqueta'], '">', $row4['Etiqueta'];
															}
														}
													}
													echo '</div>';
		?>											
													<input class="botones" type="button" value="Agregar Etiqueta" onclick="agregarEtiqueta('mod', '<?=$_GET['ISBN']?>')"></br>
		<?php
													echo '<div id="AdminIndice">
														<label for="Fecha">Indice:</label></br>
														<textarea name="Ind" rows="10" cols="37" maxlength="255" placeholder="Indice del libro..."  required> ' .$row['Indice'] .'</textarea> 
													</div>';
													if ($row['Estado'] == 1){ 
														echo '<input type="hidden" name="accion" value="modificar" required readonly>	
														<input class="botones" type="button" value="Modificar" onclick="validarmod()">';
													}
													else{ 
														echo '<input class="botones" type="submit" value="Modificar" disabled>';
													}
												echo '</form>';
											}	
										}
										else{
											echo 'El libro: ISBN = ' .$_GET['ISBN'] .', Titulo = ' . $row['Titulo'] .'; no es un libro activo y sus datos no son modificables';
										}
									}
								}
							}
						echo '</div>';							
					}
					// OPCION TOP 10 LIBROS MAS VENDIDOS EN UN PERIODO //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'con'){
						echo '<div id="textoadmped"><samp>Top 10 de libros m&aacutes vendidos en un per&iacuteodo:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO DE INGRESO PERIODO -->
							<form class='FAbm' name="fperiodo" action="" method="GET">
								<label for="periodo">Ingrese per&iacuteodo de tiempo:</label></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Inicial:</label>
								<input type="date" name="Fini" id="datepickerLimInf" required></br>
								&nbsp;&nbsp;&nbsp;&nbsp;<label class="AVinput" for="periodo">Fecha Final:</label>
								<input type="date" name="Ffin" id="datepickerLimSup" required></br>
								<input type="hidden" name="flag" value="con" required readonly>
								<input class="botones" type="button" value="Buscar" onclick="validarperiodo()">	
							</form>
	<?php	
							if (!empty($_GET['Fini']) && !empty($_GET['Ffin'])){
								// CONSULTA DE BUSQUEDA DEL TOP 10 //
								LibroPeriodo ($res, $_GET['Fini'], $_GET['Ffin']);
								if(!$res) {
									$message= 'Consulta invalida: ' .mysql_error() ."\n";
									die($message);
								}
								$num1 = mysql_num_rows($res);
								if($num1 == 0){
									echo 'No se vendio ningun libro en dicho periodo temporal';
								}
								else{
									// GENERADOR DE TABLA RESULTANTE //
									echo '<div id="TablaTopLibros">';
									echo "<table border='1'>
										<tr>
											<th>ISBN</th>
											<th>T&iacutetulo</th>
											<th>Autor</th>
											<th>DNI</th>
											<th>Cliente</th>
											<th>FechaPedido</th>
										</tr>";
									$ant = ' ';
									while($row = mysql_fetch_assoc($res)) {
										if ($row['ISBN'] != $ant){
											echo "<tr>";
												echo "<td>", $row['ISBN'], "</td>";
												echo "<td>", $row['Titulo'], "</td>";
												echo "<td>", $row['Autor'], "</td>";
												echo "<td>", $row['DNI'], "</td>";
												echo "<td>", $row['Cliente'], "</td>";
												echo "<td>", $row['FechaPedido'], "</td>";		
											echo "</tr>";
											$ant = $row['ISNB'];
										}
									}
									echo "</table>
									</div>";
								}
							}		
						echo '</div>';
					}
					// OPCION ALTA AUTOR //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgAu'){
						echo '<div id="textoadmped"><samp>Agregar autor:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA AUTOR -->
							<form class='FAbm' name='faut' action="" method="GET">
								<label for="Nombre">Nombre y Apellido del Autor:</label></br>
								<input type="text" name="AutorNom" placeholder="Nombre Apellido" maxlength="45" onkeypress="return LetrasEspacio(event)"  required></br>
								<input type="hidden" name="accion" value="AgregarAu" required readonly>
	<?php							
								if (!empty($_GET['Etiquetas'])){
	?>	
									<script languaje="javascript"> 	
										var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
									</script>				
	<?php
								}
								else{
	?>
									<script languaje="javascript"> 	
										var Etiquetasjs = 0;
									</script>
	<?php
								}
	?>								
								<input class="botones" type="button" value="Agregar" onclick='AltaAutor("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>
								<input class="botones" type="button" value="Atras" onclick='Atras("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>
							</form>
						</div>
	<?php	
					}
					// OPCION ALTA IDIOMA //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgId'){
						echo '<div id="textoadmped"><samp>Agregar idioma:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA IDIOMA -->
							<form class='FAbm' name='fido' action="" method="GET">
								<label for="Nombre">Descripci&oacuten del Idioma:</label></br>
								<input type="text" name="IdiomaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input type="hidden" name="accion" value="AgregarIdio" required readonly>
	<?php							
								if (!empty($_GET['Etiquetas'])){
	?>	
									<script languaje="javascript"> 	
										var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
									</script>				
	<?php
								}
								else{
	?>
									<script languaje="javascript"> 	
										var Etiquetasjs = 0;
									</script>
	<?php
								}
	?>								
								<input class="botones" type="button" value="Agregar" onclick='AltaIdioma("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>
								<input class="botones" type="button" value="Atras" onclick='Atras("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>
							</form>
						</div>	
	<?php		
					}
					// OPCION ALTA ETIQUETA //
					elseif (!empty($_GET['flag']) && $_GET['flag'] == 'AgEt'){
						echo '<div id="textoadmped"><samp>Agregar etiqueta:</samp></div>';
	?>
						<div id='ABMDiv'>
							<!-- FORMULARIO ALTA ETIQUETA -->
							<form class='FAbm' name='feti' action="" method="GET">							
								<label for="Nombre">Descripci&oacuten de la Etiquta:</label></br>
								<input type="text" name="EtiquetaNom" placeholder="Descripcion" maxlength="45" onkeypress="return LetrasEspacio(event)" required></br>
								<input type="hidden" name="accion" value="AgregarEtiq" required readonly>
	<?php							
								if (!empty($_GET['Etiquetas'])){
	?>	
									<script languaje="javascript"> 	
										var Etiquetasjs=<?php echo json_encode($_GET['Etiquetas']);?>; 
									</script>				
	<?php
								}
								else{
	?>
									<script languaje="javascript"> 	
										var Etiquetasjs = 0;
									</script>
	<?php
								}
	?>								
								<input class="botones" type="button" value="Agregar" onclick='AltaEtiqueta("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>
								<input class="botones" type="button" value="Atras" onclick='Atras("<?=$_GET['F']?>", "<?=$_GET['ISBN']?>", "<?=$_GET['Titulo']?>" , "<?=$_GET['Autor']?>", "<?=$_GET['CantPag']?>", "<?=$_GET['Precio']?>", "<?=$_GET['Idioma']?>", "<?=$_GET['Fecha']?>", Etiquetasjs, "<?=$_GET['Disp']?>", "<?=$_GET['Indice']?>")'/>						
							</form>
						</div>
	<?php										
					}
					// CIERRE SERVIDOR //
					CerrarServidor ($con);
	?>	
				</div>
			</div>
		</div>
		<!-- PIE DE PAGINA -->
		<div id='pie'>
			<samp> Direcci&oacuten : Calle 30 N&deg 416  - La Plata - Argentina | Tel&eacutefono : (0221) 411-3257 | E-mail : info@cookbook.com.ar |</br>Resoluci&oacuten M&iacutenima 1024 x 768 | Mozilla Firefox | </samp> 
			<samp>Copyright &copy 2014 CookBook – &reg Todos los derechos reservados.</samp>
		</div>
	</body>
</html>