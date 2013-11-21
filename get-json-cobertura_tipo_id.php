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
	$query_Recordset1 = "SELECT seguro_cobertura_tipo_id, seguro_cobertura_tipo_nombre FROM seguro_cobertura_tipo";
	if (isset($_GET['id'])) {
		$query_Recordset1 .= ' WHERE seguro_id = "'.mysql_real_escape_string($_GET['id']).'"';
	}
	$query_Recordset1 .= " group by seguro_cobertura_tipo_nombre";
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>
