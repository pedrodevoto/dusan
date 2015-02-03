<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/general_functions.php'); ?>
<?php
function dateRange( $first, $last) {
	$step = '+1 month';
	$format1 = 'Y-m-1';
	$format2 = 'Y-m-15';
	$format3 = 'Y-m-t';
	$dates = array();
	$current = strtotime( $first );
	$last = strtotime( $last );

	while( $current <= $last ) {		
		if (strtotime(date($format1, $current)) < $last and strtotime(date($format2, $current)) < $last) 
			$dates[] = array('label'=>sprintf('De %s a %s', date('1/m/Y', $current), date('15/m/Y', $current)), 'from'=>date($format1, $current), 'to'=>date($format2, $current));
		if (strtotime(date($format2, $current)) < $last and strtotime(date($format3, $current)) < $last) 
			$dates[] = array('label'=>sprintf('De %s a %s', date('15/m/Y', $current), date('t/m/Y', $current)), 'from'=>date($format2, $current), 'to'=>date($format3, $current));
		$current = strtotime( $step, $current );
	}

	return array_reverse($dates);
}
$periodos = dateRange('2014-11-01', date('Y-m-d'));
?>
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
				if ($('#periodo').val()=='') {
					alert('Seleccione un período');
				}
				else {
					var periodo = $('#periodo').val().split('|');
					var de = periodo[0];
					var a = periodo[1];
					window.open('export-libros_rubricados.php?de='+de+'&a='+a+'&productor='+$('#productor').val());
				}
			});
			populateListProductor('productor', 'main');
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
					<select name="periodo" id="periodo">
						<?php
						foreach ($periodos as $periodo) {
							?>
							<option value="<?=$periodo['from']?>|<?=$periodo['to']?>"><?=$periodo['label']?></option>
							<?php
						}
						?>
					</select>
				</p>
				<p>
					Productor
					<select name="productor" id="productor">
						
					</select>
				</p>
				<p>
					<button id="exportar-libros-rubricados">Exportar</button>
				</p>
			</div>
    	</div>
	</body>
</html>