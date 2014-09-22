<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/db_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JARVIS - Calendario</title>
	
	<?php require_once('inc/library.php'); ?>
	
	<script>
	$(document).ready(function() {
	    $( "#eventdialog" ).dialog({
	      autoOpen: false,
	    });
		
		$('#calendar').fullCalendar({
			header: {
					left: 'prev,next today',
					center: 'title',
					right: ''
			},
			defaultView: 'month',
			editable: false,
			eventLimit: true,
			eventSources: [
				{
					url: 'get-json-vencimientos.php',
					className: 'vencimiento',
					color: '#cd0a0a'
				},
				{
					url: 'get-json-renovaciones.php',
					className: 'renovacion'
				},
				{
					url: 'https://www.google.com/calendar/feeds/info%40dusanasegurador.com.ar/public/basic',
					className: 'gcal-event'
				}
			],
			eventClick: function(event, jsEvent, view) {
				$('#eventdialog').html('Cargando...');
				if ($(this).hasAnyClass('vencimiento renovacion')) {
					date = event.start;
					type = event.id;
					populateDialog_Calendar(type, date.format("YYYY-MM-DD"));
					$("#eventdialog").dialog({
						position: { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: type.capitalize()+' el '+date.format('DD/MM/YY'),
						width: 500
					}).dialog("open");
				}
			}
		})
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
			<div id='calendar' style="margin-top:30px"></div>
			<div id="eventdialog">
			  
			</div>
		</div>
	</div>
</body>
</html>