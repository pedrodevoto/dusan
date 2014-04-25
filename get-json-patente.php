<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');		
?>
<?php	
	// Main Query
	$query_Recordset1 = sprintf("SELECT automotor_id FROM automotor JOIN poliza ON automotor.poliza_id = poliza.poliza_id WHERE poliza_estado_id=3 AND patente_0 = UPPER(TRIM('%s')) AND patente_1 = UPPER(TRIM('%s'))", mysql_real_escape_string($_GET['patente_0']), mysql_real_escape_string($_GET['patente_1']));
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = (mysql_num_rows($Recordset1)?TRUE:FALSE);
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>