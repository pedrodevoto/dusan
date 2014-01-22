<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php	
	if ((isset($_POST["id"])) && ($_POST["id"] != "")) {
		$sql = 'UPDATE poliza SET poliza_archivada = 1 WHERE poliza_id = '.mysql_real_escape_string($_POST['id']);
		mysql_query($sql, $connection) or die(mysql_error());
	}
	echo 'Póliza archivada';
?>