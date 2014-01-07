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
	$query_Recordset1 = sprintf("SELECT productor_seguro.productor_seguro_id as productor_seguro_id, productor_seguro.seguro_id as seguro_id, sucursal_nombre, productor_seguro_codigo, seguro_nombre, productor_nombre, zona_riesgo_id, GROUP_CONCAT(seguro_cobertura_tipo_id) as seguro_cobertura_tipo_id, productor_seguro_organizacion_flag, productor_seguro_organizacion_nombre, productor_seguro_organizacion_tipo_persona, productor_seguro_organizacion_matricula, productor_seguro_organizacion_cuit FROM productor_seguro JOIN (seguro, productor, sucursal) ON productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.sucursal_id = sucursal.sucursal_id AND productor_seguro.productor_id = productor.productor_id LEFT JOIN productor_seguro_cobertura_tipo ON productor_seguro_cobertura_tipo.productor_seguro_id = productor_seguro.productor_seguro_id WHERE productor_seguro.productor_seguro_id=%s GROUP BY productor_seguro.productor_seguro_id", GetSQLValueString($colname_Recordset1, "int"));
			
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