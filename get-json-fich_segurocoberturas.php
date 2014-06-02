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
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo_id, seguro_cobertura_tipo_nombre, seguro_cobertura_tipo_anios_de, seguro_cobertura_tipo_anios_a, seguro_cobertura_tipo_limite_rc_valor, seguro_cobertura_tipo_gruas FROM seguro_cobertura_tipo LEFT JOIN  seguro_cobertura_tipo_limite_rc ON seguro_cobertura_tipo_limite_rc.seguro_cobertura_tipo_limite_rc_id = seguro_cobertura_tipo.seguro_cobertura_tipo_limite_rc_id WHERE seguro_id=%s GROUP BY seguro_cobertura_tipo.seguro_cobertura_tipo_id", GetSQLValueString($colname_Recordset1, "int"));	
	
	// Recordset: Seguro
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);			
	
	// Close Recordset: Seguro
	mysql_free_result($Recordset1);
	
?>