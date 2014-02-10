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
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo_id, seguro_nombre, seguro_cobertura_tipo_nombre, seguro_cobertura_tipo_limite_rc_id, seguro_cobertura_tipo_gruas, seguro_cobertura_tipo_gruas_km, seguro_cobertura_tipo_gruas_desde, seguro_cobertura_tipo_anios_de, seguro_cobertura_tipo_anios_a FROM seguro_cobertura_tipo JOIN seguro ON seguro_cobertura_tipo.seguro_id = seguro.seguro_id WHERE seguro_cobertura_tipo_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
	
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