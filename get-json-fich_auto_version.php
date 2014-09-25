<?php
	$MM_authorizedUsers = "master";
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
	$query_Recordset1 = sprintf("SELECT automotor_version_id, automotor_marca_nombre, automotor_modelo_nombre, automotor_version_nombre, GROUP_CONCAT(automotor_ano) as automotor_anos FROM automotor_version JOIN automotor_modelo USING (automotor_modelo_id) JOIN automotor_marca USING (automotor_marca_id) JOIN automotor_version_ano USING (automotor_version_id) WHERE automotor_version_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Seguro
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Seguro)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Seguro
	mysql_free_result($Recordset1);
	
?>