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
	$query_Recordset1 = "SELECT tipo_poliza.tipo_poliza_id, tipo_poliza_nombre FROM tipo_poliza";	
	if (isset($_GET['include'])) {
		$query_Recordset1 .= " WHERE tipo_poliza_id IN ('".implode('\',\'',explode(',',$_GET['include']))."')";
	}		
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());

	$output = array();
	while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>