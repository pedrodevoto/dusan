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
	$query_Recordset1 = "SELECT localidad_id, CONCAT(localidad_nombre, ' / ', localidad_cp) as localidad_nombre FROM localidad ORDER BY localidad_nombre, localidad_cp";
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[] = array($row_Recordset1[0], strip_tags($row_Recordset1[1]));
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>
