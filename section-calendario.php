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
					className: 'vencimientos'
				}
			],
			eventClick: function(event, jsEvent, view) {
				$('#eventdialog').html('Cargando...');
				if ($(this).hasClass('vencimientos')) {
					$.ajax({
						url: "get-json-vencimientos.php?date="+event.id,
						dataType: 'json',
						success: function (j) {
							var output = '';
							output += '<table class="tblBox">';
							$.each(j, function (key, object) {
								output += '<tr>';
								output += '<td>PZA '+object.poliza_numero+'</td>';
								output += '<td>Cuota '+object.cuota_nro+'/'+object.poliza_cant_cuotas+'</td>';
								output += '<td>$'+object.cuota_monto+'</td>';
								output += '</tr>';
							});
							output += '</table>';
							$('#eventdialog').html(output);
						}
					});
					date = moment(event.id, 'YYYY-MM-DD');
					$("#eventdialog").dialog({
						position: { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: 'Venimientos el '+date.format('DD/MM/YY')
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