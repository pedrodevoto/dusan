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
	Chart.defaults.global.responsive = true;
	var barCtx1;
	var barChart1;
	var barCtx2;
	var barChart2;
	var barCtx3;
	var barChart3;
	var barCtx4;
	var barChart4;
	var pieCtx1;
	var pieChart1;
	var pieCtx2;
	var pieChart2;
	var pieCtx3;
	var pieChart3;
	var pieCtx4;
	var pieChart4;
	
	$(document).ready(function() {
		populateListSeguro('seguro_id', 'main');
		
		$('#seguro_id').change(function() {
			$.get('get-json-estadisticas_automotor.php?seguro_id='+$(this).val(), {}, function(data) {
				var barData = {
					labels: data.coberturas.bar.labels,
					datasets: [
						{
							label: 'Automotores',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.coberturas.bar.data
						}
					]
				}
				
				barChart1.destroy();
				barChart1 = new Chart(barCtx1).Bar(barData, {});

				pieChart1.destroy();
				pieChart1 = new Chart(pieCtx1).Pie(data.coberturas.pie, {});
				
				var barData = {
					labels: data.marcas.bar.labels,
					datasets: [
						{
							label: 'Marcas',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.marcas.bar.data
						}
					]
				}
				barChart2.destroy();
				barChart2 = new Chart(barCtx2).Bar(barData, {});

				pieChart2.destroy();
				pieChart2 = new Chart(pieCtx2).Pie(data.marcas.pie, {});
				
			}, 'json');
		});
		
		var barData = {
		    labels: [],
		    datasets: []
		};
		var pieData = [];
		
		barCtx1 = $("#barChart").get(0).getContext("2d");
		barChart1 = new Chart(barCtx1).Bar(barData, {});
		pieCtx1 = $("#pieChart").get(0).getContext("2d");
		pieChart1 = new Chart(pieCtx1).Pie(pieData, {});
		
		barCtx2 = $("#barChart2").get(0).getContext("2d");
		barChart2 = new Chart(barCtx2).Bar(barData, {});
		pieCtx2 = $("#pieChart2").get(0).getContext("2d");
		pieChart2 = new Chart(pieCtx2).Pie(pieData, {});
		
		barCtx3 = $("#barChart3").get(0).getContext("2d");
		barChart3 = new Chart(barCtx3).Bar(barData, {});
		pieCtx3 = $("#pieChart3").get(0).getContext("2d");
		pieChart3 = new Chart(pieCtx3).Pie(pieData, {});
		
		barCtx4 = $("#barChart4").get(0).getContext("2d");
		barChart4 = new Chart(barCtx4).Bar(barData, {});
		pieCtx4 = $("#pieChart4").get(0).getContext("2d");
		pieChart4 = new Chart(pieCtx4).Pie(pieData, {});
		
	});
	</script>
	<style>
	.frame {
		background-color: #F5F5F5;
		border: 1px solid #AAAAAA;
	}
	</style>
</head>
<body>
	<div id="divContainer">
		<!-- Include Header -->
		<?php include('inc/header.php'); ?>    
		<p>
			<select name="seguro_id" id="seguro_id">
			</select>
		</p>
		<div id="divMain">
			<div class="frame ui-corner-all" style="float:left;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					<p><b>Coberturas</b></p>
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart" width="230" height="230"></canvas>
					<canvas id="pieChart" width="200" height="200"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="frame ui-corner-all" style="float:right;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					<b>Marcas</b>
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart2" width="230" height="230"></canvas>
					<canvas id="pieChart2" width="200" height="200"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both;margin-top:20px"></div>
			<div class="frame ui-corner-all" style="float:left;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					<b>Castigados</b>
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart3" width="230" height="230"></canvas>
					<canvas id="pieChart3" width="200" height="200"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="frame ui-corner-all" style="float:right;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					<b>Equipos GNC</b>
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart4" width="230" height="230"></canvas>
					<canvas id="pieChart4" width="200" height="200"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</body>
</html>