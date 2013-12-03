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
	if (isset($_GET['subtipo_poliza_id']) and isset($_GET['seguro_id'])) {
		$subtipo_poliza_id = $_GET['subtipo_poliza_id'];
		$seguro_id = $_GET['seguro_id'];
	}
	$query_Recordset1 = sprintf("SELECT poliza_plan_id, poliza_plan_nombre FROM poliza_plan WHERE subtipo_poliza_id=%s AND seguro_id=%s",
						GetSQLValueString($subtipo_poliza_id, "int"),
						GetSQLValueString($seguro_id, "int"));
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>