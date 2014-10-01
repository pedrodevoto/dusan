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
	if (!empty($_GET['modelo_id']) and !empty($_GET['ano'])) {
		// Recordset: Main	
	
		$query_Recordset1 = sprintf("SELECT automotor_version_id, automotor_version_nombre FROM automotor_version JOIN automotor_version_ano USING (automotor_version_id) WHERE automotor_modelo_id = %s and automotor_ano = %s", 
			GetSQLValueString($_GET['modelo_id'], 'int'),
			GetSQLValueString($_GET['ano'], 'int'));
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		error_log($query_Recordset1);
		$output = array();
		while ($row_Recordset1 = mysql_fetch_array($Recordset1)) {
			$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
		}
		echo json_encode($output);

		// Close Recordset: Main	
		mysql_free_result($Recordset1);
	}
?>