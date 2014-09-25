<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	require_once('inc/db_functions.php');	
?>
<?php
	if (!empty($_GET['marca_id'])) {
		// Recordset: Main	
	
		$query_Recordset1 = sprintf("SELECT automotor_modelo_id, automotor_modelo_nombre FROM automotor_modelo WHERE automotor_marca_id = %s", GetSQLValueString($_GET['marca_id'], 'int'));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());

		$output = array();
		while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
			$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
		}
		echo json_encode($output);

		// Close Recordset: Main	
		mysql_free_result($Recordset1);
	}
?>