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
		$('#calendar').fullCalendar({
			header: {
					left: 'prev,next today',
					center: 'title',
					right: ''
			},
			defaultView: 'basicWeek',
			// height: 120,
			contentHeight: 110,
			editable: false,
			eventLimit: true,
			events: [
				{
					id: 999,
					title: 'Vence XXXXX',
					start: '2014-09-16T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2014-09-16T20:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2014-09-19T16:00:00'
				},
			]
		})

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
	
	</style>
</head>
<body>
	<div id="divContainer">
		<!-- Include Header -->
		<?php include('inc/header.php'); ?>    
		<div id="divMain">
			<p class="txtMain">Bienvenido al panel de administración, <strong><?php echo($_SESSION['ADM_Username']); ?></strong>.</p>          
			<div style="margin-top:80px">
				<a href='#' id="btncrearcliente"><img width="180px" height="180px" src="media/images/crearcliente.jpg" /></a>
				<a href='#' id="btncrearautomotor"><img width="180px" height="180px" src="media/images/crearautomotor.jpg" /></a>
				<a href='#' id="btncrearpolizapersonas"><img width="180px" height="180px" src="media/images/crearpolizapersonas.jpg" /></a>
				<a href='#' id="bntncrearpolizariesgos"><img width="180px" height="180px" src="media/images/crearpolizariesgos.jpg" /></a>
				<a href='#' id="btnemitirrecibo"><img width="180px" height="180px" src="media/images/emitirrecibo.jpg" /></a>
			</div>
			<div id='calendar' style="margin-top:30px"></div>
		</div>
	</div>
</body>
</html>