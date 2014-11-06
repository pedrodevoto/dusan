<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security.php'); ?>
<?php require_once('inc/db_functions.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JARVIS - Estadísticas</title>
	
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
	var barCtx5;
	var barChart5;
	var barCtx6;
	var barChart6;
	var pieCtx1;
	var pieChart1;
	var pieCtx2;
	var pieChart2;
	var pieCtx3;
	var pieChart3;
	var pieCtx4;
	var pieChart4;
	var pieCtx5;
	var pieChart5;
	var pieCtx6;
	var pieChart6;
	
	$(document).ready(function() {
		$('#tabs').tabs();
		populateListSeguro('seguro_id', 'main');
		
		$('#estado, #seguro_id').change(function() {
			$.get('get-json-estadisticas_automotor.php?estado='+$('#estado').val()+'&seguro_id='+$('#seguro_id').val(), {}, function(data) {
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
				
				var labels = '';
				$.each(data.coberturas.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart1Labels').html(labels);
				
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
				
				var labels = '';
				$.each(data.marcas.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart2Labels').html(labels);
				
				var barData = {
					labels: data.castigado.bar.labels,
					datasets: [
						{
							label: 'Castigados',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.castigado.bar.data
						}
					]
				}
				barChart3.destroy();
				barChart3 = new Chart(barCtx3).Bar(barData, {});

				pieChart3.destroy();
				pieChart3 = new Chart(pieCtx3).Pie(data.castigado.pie, {});
				
				var labels = '';
				$.each(data.castigado.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart3Labels').html(labels);
				
				var barData = {
					labels: data.gnc.bar.labels,
					datasets: [
						{
							label: 'GNC',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.gnc.bar.data
						}
					]
				}
				barChart4.destroy();
				barChart4 = new Chart(barCtx4).Bar(barData, {});

				pieChart4.destroy();
				pieChart4 = new Chart(pieCtx4).Pie(data.gnc.pie, {});
				
				var labels = '';
				$.each(data.gnc.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart4Labels').html(labels);
				
				var barData = {
					labels: data.renovadas.bar.labels,
					datasets: [
						{
							label: 'Renovadas',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.renovadas.bar.data
						}
					]
				}
				barChart6.destroy();
				barChart6 = new Chart(barCtx6).Bar(barData, {});

				pieChart6.destroy();
				pieChart6 = new Chart(pieCtx6).Pie(data.renovadas.pie, {});
				
				var labels = '';
				$.each(data.renovadas.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart6Labels').html(labels);
				
			}, 'json');
			$('#altas_bajas_periodo').change();
		});
		
		$('#altas_bajas_periodo').change(function() {
			$.get('get-json-estadisticas_automotor.php?type=altas_bajas&periodo='+$('#altas_bajas_periodo').val()+'&seguro_id='+$('#seguro_id').val(), {}, function(data) {
				var barData = {
					labels: data.altas_bajas.bar.labels,
					datasets: [
						{
							label: 'Altas/Bajas',
							fillColor: "rgba(151,187,205,0.5)",
							strokeColor: "rgba(151,187,205,0.8)",
							highlightFill: "rgba(151,187,205,0.75)",
							highlightStroke: "rgba(151,187,205,1)",
							data: data.altas_bajas.bar.data
						}
					]
				}
				barChart5.destroy();
				barChart5 = new Chart(barCtx5).Bar(barData, {});

				pieChart5.destroy();
				pieChart5 = new Chart(pieCtx5).Pie(data.altas_bajas.pie, {});
				
				var labels = '';
				$.each(data.altas_bajas.pie, function(i,e) {
					labels += '<p>'+e.label+': <b>'+e.value+'</b></p>';
				});
				$('#chart5Labels').html(labels);
				
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
		
		barCtx5 = $("#barChart5").get(0).getContext("2d");
		barChart5 = new Chart(barCtx5).Bar(barData, {});
		pieCtx5 = $("#pieChart5").get(0).getContext("2d");
		pieChart5 = new Chart(pieCtx5).Pie(pieData, {});
		
		barCtx6 = $("#barChart6").get(0).getContext("2d");
		barChart6 = new Chart(barCtx6).Bar(barData, {});
		pieCtx6 = $("#pieChart6").get(0).getContext("2d");
		pieChart6 = new Chart(pieCtx6).Pie(pieData, {});
		
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
  		<div id="divFilter" class="ui-corner-all">
              <form id="frmFiltro" name="frmFiltro">
                  <table cellpadding="5" cellspacing="0" border="0" width="100%">
  					<tr>
  						<td width="10%">
  							<select name="estado" id="estado">
  								<option value="vigente">Vigente</option>
  								<option value="historico">Histórico</option>
  							</select>
  						</td>
  						<td width="10%">
  							<select name="seguro_id" id="seguro_id">
  							</select>
  						</td>
  						<td width="80%">
						
  						</td>
  					</tr>
  				</table>
  			</form>
  		</div>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Automotor</a></li>
				<li><a href="#tabs-2">Clientes</a></li>
				<li><a href="#tabs-3">General</a></li>
			</ul>
			<div id="tabs-1">
		  		<div id="divMain" style="padding-top:5px">
		  			<div class="frame ui-corner-all" style="float:left;width:48%">
		  				<div style="float:left;width:40%;margin-top:10px">
		  					<p><b>Coberturas</b></p>
							<div id="chart1Labels" style="text-align:left;padding-left:40px"></div>
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
							<div id="chart2Labels" style="text-align:left;padding-left:40px"></div>
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
							<div id="chart3Labels" style="text-align:left;padding-left:40px"></div>
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
							<div id="chart4Labels" style="text-align:left;padding-left:40px"></div>
		  				</div>
		  				<div style="float:left;width:50%;margin:10px">
		  					<canvas id="barChart4" width="230" height="230"></canvas>
		  					<canvas id="pieChart4" width="200" height="200"></canvas>
		  				</div>
		  				<div style="clear:both"></div>
		  			</div>
		  			<div style="clear:both;margin-top:20px"></div>
		  			<div class="frame ui-corner-all" style="float:left;width:48%">
		  				<div style="float:left;width:40%;margin-top:10px">
		  					<b>Altas/Bajas</b>
							<p>
								<select name="altas_bajas_periodo" id="altas_bajas_periodo">
									<option value="6">Últimos 6 meses</option>
									<option value="1">Último año</option>
									<option value="2">Últimos 2 años</option>
									<option value="3">Últimos 3 años</option>
								</select>
							</p>
							<div id="chart5Labels" style="text-align:left;padding-left:40px"></div>
		  				</div>
		  				<div style="float:left;width:50%;margin:10px">
		  					<canvas id="barChart5" width="230" height="230"></canvas>
		  					<canvas id="pieChart5" width="200" height="200"></canvas>
		  				</div>
		  				<div style="clear:both"></div>
		  			</div>
		  			<div class="frame ui-corner-all" style="float:right;width:48%">
		  				<div style="float:left;width:40%;margin-top:10px">
		  					<b>Efectividad de renovación</b>
							<div id="chart6Labels" style="text-align:left;padding-left:40px"></div>
		  				</div>
		  				<div style="float:left;width:50%;margin:10px">
		  					<canvas id="barChart6" width="230" height="230"></canvas>
		  					<canvas id="pieChart6" width="200" height="200"></canvas>
		  				</div>
		  				<div style="clear:both"></div>
		  			</div>
		  			<div style="clear:both"></div>
		  		</div>
			</div>
			<div id="tabs-2">

			</div>
			<div id="tabs-3">
			  
			</div>
		</div>
	</div>
	<?php include('inc/footer.php');?>
</body>
</html>