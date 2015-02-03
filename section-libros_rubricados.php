<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Libros Rubricados</title>

		<?php require_once('inc/library.php'); ?>               
		
		<script>
		$(document).ready(function() {
			$.ajax({
				url: 'get-json-libros-rubricados-periodos.php?type=1',
				dataType: 'json',
				success: function(j) {
					$('#ultimo-libros-rubricados').text(j[0]);
					var options = '';
					$.each(j, function (key, value) {
						options += '<option value="' + value + '">' + value + '</option>';
					});
					var field = 'exportar-libros-rubricados-periodo';
					$('#' + field).html(options);
					appendListItem(field, '', 'Elegir');
				}
			});
			$('#exportar-libros-rubricados').click(function() {
				if ($('#from').val()=='' || $('#to').val()=='') {
					alert('Seleccione un período');
				}
				else {
					window.open('export-libros_rubricados.php?de='+$('#from').val()+'&a='+$('#to').val());
				}
			});
			
			$( "#from" ).datepicker({
				dateFormat: 'yy-mm-dd',
				defaultDate: "-15d",
				changeMonth: true,
				numberOfMonths: 3,
				onClose: function( selectedDate ) {
					$( "#to" ).datepicker( "option", "minDate", selectedDate );
				}
			});
			$( "#to" ).datepicker({
				dateFormat: 'yy-mm-dd',
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 3,
				onClose: function( selectedDate ) {
					$( "#from" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
		});
		</script>
	</head>
	<body>     
		<div id="divContainer">
        
            <!-- Include Header -->
            <?php include('inc/header.php'); ?>
			<div class="center">
				<p>
					Último proceso: <span id="ultimo-libros-rubricados">cargando...</span>
				</p>
				<p>
					Período: 
					<label for="from">de</label>
					<input type="text" id="from" name="from">
					<label for="to">a</label>
					<input type="text" id="to" name="to">
				</p>
				<p>
					<button id="exportar-libros-rubricados">Exportar</button>
				</p>
			</div>
    	</div>
	</body>
</html>