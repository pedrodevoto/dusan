<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/db_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JARVIS - Main</title>
	
	<?php require_once('inc/library.php'); ?>
	
	<script>
	$(document).ready(function() {		
		initCalendar('basicWeek', 110);

		$('#btncrearcliente').click(function(){openBoxAltaCliente()});
		$('#btncrearautomotor').click(function(){openBoxAltaPoliza('2', '6')});
		$('#btncrearpolizapersonas').click(function(){openBoxAltaPoliza('3')});
		$('#bntncrearpolizariesgos').click(function(){openBoxAltaPoliza('2')});
		$('#btnemitirrecibo').click(function() {openBoxEmitirRecibo()});
	});
	</script>                        
	<style>
	#calendar {
			max-width: 900px;
			margin: 0 auto;
		}
	.fc-event {
		cursor:pointer;
	}
	</style>
</head>
<body>
	<div id="divContainer">
		<!-- Include Header -->
		<?php include('inc/header.php'); ?>    
		<div id="divMain">
			<p class="txtMain">Bienvenido al panel de administración, <strong><?php echo($_SESSION['ADM_Username']); ?></strong>.</p>          
			<div style="margin-top:20px">
				<a href='#' id="btncrearcliente"><img width="180px" height="180px" src="media/images/crearcliente.jpg" /></a>
				<a href='#' id="btncrearautomotor"><img width="180px" height="180px" src="media/images/crearautomotor.jpg" /></a>
				<a href='#' id="btncrearpolizapersonas"><img width="180px" height="180px" src="media/images/crearpolizapersonas.jpg" /></a>
				<a href='#' id="bntncrearpolizariesgos"><img width="180px" height="180px" src="media/images/crearpolizariesgos.jpg" /></a>
				<a href='#' id="btnemitirrecibo"><img width="180px" height="180px" src="media/images/emitirrecibo.jpg" /></a>
			</div>
			<div id='calendar' style="margin-top:30px"></div>
			<div id="eventdialog">
			  
			</div>
			<div id="neweventdialog">
				<form id="frmEvent">
					<input type="hidden" name="box-evento_fecha" id="box-evento_fecha" />
					<input type="hidden" name="box-evento_id" id="box-evento_id" />
					<p>
						<input type="text" name="box-evento_titulo" id="box-evento_titulo" placeholder="Título" class="ui-widget-content" style="width:100%" maxlength="200" />
					</p>
					<p>
						<textarea name="box-evento_descripcion" id="box-evento_descripcion" placeholder="Descripción (opcional)" class="ui-widget-content" style="width:100%"></textarea>
					</p>
					<input type="submit" id="btnEvent" value="Guardar">
				</form>
			</div>
		</div>
	</div>
</body>
</html>