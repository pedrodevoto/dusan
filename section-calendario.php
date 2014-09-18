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
			defaultView: 'month',
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
			<div id='calendar' style="margin-top:30px"></div>
		</div>
	</div>
</body>
</html>