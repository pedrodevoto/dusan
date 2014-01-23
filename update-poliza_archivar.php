<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php	
	if (!empty($_POST["id"])) {
		if (!empty($_POST["flag"])) {
			$action = 'NULL';
		}
		else {
			$action = 1;
		}
		$sql = 'UPDATE poliza SET poliza_archivada = '.$action.' WHERE poliza_id = '.mysql_real_escape_string($_POST['id']);
		mysql_query($sql, $connection) or die(mysql_error());
	}
	echo 'Póliza '.($_POST["flag"]?'des':'').'archivada';
?>