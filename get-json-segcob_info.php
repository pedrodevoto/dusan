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
	// Main query
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}	
	$query_Recordset1 = sprintf("SELECT seguro_cobertura_tipo_limite_rc_id, seguro_cobertura_tipo_gruas as servicio_grua, YEAR(NOW()) - seguro_cobertura_tipo_antiguedad as seguro_cobertura_tipo_antiguedad, seguro_cobertura_tipo_todo_riesgo, seguro_cobertura_tipo_franquicia as franquicia, seguro_cobertura_tipo_ajuste as ajuste FROM seguro_cobertura_tipo WHERE seguro_cobertura_tipo_id=%s", GetSQLValueString($colname_Recordset1, "int"));
			
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	$output = array();
	foreach ($row_Recordset1 as $key=>$value) {
		$output[$key] = strip_tags($value);
	}		
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>