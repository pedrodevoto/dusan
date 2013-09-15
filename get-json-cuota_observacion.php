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
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}
	$query_Recordset1 = "SELECT observacion FROM cuota WHERE cuota_id = ".mysql_real_escape_string($colname_Recordset1);
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$row_Recordset1=mysql_fetch_array($Recordset1);
	$output = strip_tags($row_Recordset1[0]);
	
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>
