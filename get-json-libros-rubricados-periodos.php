<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
?>
<?php
	if (!empty($_GET['type'])) {
		$type = mysql_real_escape_string($_GET['type']);
	}
	// Main Query
	$query_Recordset1 = sprintf("SELECT DISTINCT DATE(timestamp) FROM libros_rubricados_log ORDER BY timestamp DESC");
	
	// Recordset: Acceso	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	// Output
	$output = array();
	while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
		 $output[] = $row_Recordset1[0];
	}
	echo json_encode($output);			
	
	// Close Recordset: Acceso
	mysql_free_result($Recordset1);
?>