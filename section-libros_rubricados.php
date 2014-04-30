<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>JARVIS - Pólizas - Listado</title>

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
				if ($('#exportar-libros-rubricados-periodo').val()=='') {
					alert('Seleccione un período');
				}
				else {
					window.open('export-libros_rubricados.php?fecha='+$('#exportar-libros-rubricados-periodo').val());
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
					<label for="exportar-libros-rubricados-periodo">Período</label>
					<select name="exportar-libros-rubricados-periodo" id="exportar-libros-rubricados-periodo">
						<option>Cargando</option>
					</select>
				</p>
				<p>
					<button id="exportar-libros-rubricados">Exportar</button>
				</p>
			</div>
    	</div>
	</body>
</html>