<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
?>
<?php
	// Recordset: Main
	$query_Recordset1 = "SELECT cuota_estado_id, cuota_estado_nombre FROM cuota_estado";	
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());

	$output = array();
	while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>