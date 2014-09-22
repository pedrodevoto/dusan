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
	    $( "#eventdialog, #neweventdialog" ).dialog({
	      autoOpen: false,
	    });
		$('#btnEvent').button().click(function(event) {
			$.post('process-evento.php', $('#frmEvent').serializeArray(), function (data) {
				// Show message if error ocurred
				if (data.toLowerCase().indexOf("error") != -1) {
					alert($.trim(data));
				}
				else {
					if ($('#box-evento_id').val()) {
						// update
						var event = $('#calendar').fullCalendar('clientEvents', $('#box-evento_id').val());
						event.title = $('#box-evento_titulo').val().toUpperCase();
						event.description = $('#box-evento_descripcion').val().toUpperCase();
						$('#calendar').fullCalendar('updateEvent', event);
					}
					else if(data===parseInt(data)) {
						// add
						var event = {
							id: parseInt(data),
							start: $('#box-evento_fecha').val(),
							title: $('#box-evento_titulo').val().toUpperCase(),
							description: $('#box-evento_descripcion').val().toUpperCase(),
							className: 'evento'
						};
						$('#calendar').fullCalendar('addEventSource', [event]);
					}
					$('#frmEvent').each(function () {
						this.reset();
					});
					$('#neweventdialog').dialog('close');
				}
			});
			event.preventDefault();
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
					url: 'get-json-eventos.php',
					className: 'evento'
				},
				{
					url: 'https://www.google.com/calendar/feeds/info%40dusanasegurador.com.ar/public/basic',
					className: 'gcal-event'
				}
			],
			eventClick: function(event, jsEvent, view) {
				date = event.start;
				if ($(this).hasAnyClass('vencimiento renovacion')) {
					$('#eventdialog').html('Cargando...');
					type = event.id;
					populateDialog_Calendar(type, date.format("YYYY-MM-DD"));
					$("#eventdialog").dialog({
						position: { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: type.capitalize()+' el '+date.format('DD/MM/YY'),
						width: 500
					}).dialog("open");
				}
				if ($(this).hasClass('evento')) {
					$('#frmEvent').each(function () {
						this.reset();
					});
					$('#box-evento_id').val(event.id);
					$('#box-evento_fecha').val(date.format('YYYY-MM-DD'));
					$('#box-evento_titulo').val(event.title);
					$('#box-evento_descripcion').val(event.description);
					$("#neweventdialog").dialog({
						"position": { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
						title: "Evento el "+date.format('DD/MM/YY')
					}).dialog("open");
				}
			},
			dayClick: function(date, jsEvent, view) {
				$('#frmEvent').each(function () {
					this.reset();
				});
				$('#box-evento_fecha').val(date.format('YYYY-MM-DD'));
				$("#neweventdialog").dialog({
					"position": { my: "left top", at: "left top", of: $(jsEvent.srcElement)},
					title: "Evento el "+date.format('DD/MM/YY')
				}).dialog("open");
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