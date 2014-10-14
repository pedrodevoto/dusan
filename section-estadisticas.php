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
		var barData = {
		    labels: ["January", "February", "March", "April", "May", "June", "July"],
		    datasets: [
		        {
		            label: "My First dataset",
		            fillColor: "rgba(220,220,220,0.5)",
		            strokeColor: "rgba(220,220,220,0.8)",
		            highlightFill: "rgba(220,220,220,0.75)",
		            highlightStroke: "rgba(220,220,220,1)",
		            data: [65, 59, 80, 81, 56, 55, 40]
		        },
		        {
		            label: "My Second dataset",
		            fillColor: "rgba(151,187,205,0.5)",
		            strokeColor: "rgba(151,187,205,0.8)",
		            highlightFill: "rgba(151,187,205,0.75)",
		            highlightStroke: "rgba(151,187,205,1)",
		            data: [28, 48, 40, 19, 86, 27, 90]
		        }
		    ]
		};
		var barCtx = $("#barChart").get(0).getContext("2d");
		var myBarChart = new Chart(barCtx).Bar(barData, {});
		
		var pieData = [
		    {
		        value: 300,
		        color:"#F7464A",
		        highlight: "#FF5A5E",
		        label: "Red"
		    },
		    {
		        value: 50,
		        color: "#46BFBD",
		        highlight: "#5AD3D1",
		        label: "Green"
		    },
		    {
		        value: 100,
		        color: "#FDB45C",
		        highlight: "#FFC870",
		        label: "Yellow"
		    }
		];
		
		var pieCtx = $("#pieChart").get(0).getContext("2d");
		var myPieChart = new Chart(pieCtx).Pie(pieData, {});
		
		var barCtx2 = $("#barChart2").get(0).getContext("2d");
		var myBarChart2 = new Chart(barCtx2).Bar(barData, {});
		var pieCtx2 = $("#pieChart2").get(0).getContext("2d");
		var myPieChart2 = new Chart(pieCtx2).Pie(pieData, {});
		
		var barCtx3 = $("#barChart3").get(0).getContext("2d");
		var myBarChart3 = new Chart(barCtx3).Bar(barData, {});
		var pieCtx3 = $("#pieChart3").get(0).getContext("2d");
		var myPieChart3 = new Chart(pieCtx3).Pie(pieData, {});
		
		var barCtx4 = $("#barChart4").get(0).getContext("2d");
		var myBarChart4 = new Chart(barCtx4).Bar(barData, {});
		var pieCtx4 = $("#pieChart4").get(0).getContext("2d");
		var myPieChart4 = new Chart(pieCtx4).Pie(pieData, {});
		
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
		<div id="divMain">
			<div class="frame ui-corner-all" style="float:left;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					Filtros
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart" width="250" height="250"></canvas>
					<canvas id="pieChart" width="250" height="250"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="frame ui-corner-all" style="float:right;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					Filtros
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart2" width="250" height="250"></canvas>
					<canvas id="pieChart2" width="250" height="250"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both;margin-top:20px"></div>
			<div class="frame ui-corner-all" style="float:left;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					Filtros
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart3" width="250" height="250"></canvas>
					<canvas id="pieChart3" width="250" height="250"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="frame ui-corner-all" style="float:right;width:48%">
				<div style="float:left;width:40%;margin-top:10px">
					Filtros
				</div>
				<div style="float:left;width:50%;margin:10px">
					<canvas id="barChart4" width="250" height="250"></canvas>
					<canvas id="pieChart4" width="250" height="250"></canvas>
				</div>
				<div style="clear:both"></div>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
</body>
</html>