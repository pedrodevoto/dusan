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
	if (isset($_GET['id']) and isset($_GET['id2'])) {
		$colname_Recordset1 = $_GET['id'];
		$sucursal_id = $_GET['id2'];
	}
	$query_Recordset1 = sprintf("SELECT productor_seguro.productor_seguro_id, CONCAT(productor_nombre,' [',productor_seguro_codigo,']') AS productor_nombre FROM productor_seguro JOIN (productor, productor_seguro_sucursal) ON (productor_seguro.productor_id=productor.productor_id AND productor_seguro.productor_seguro_id = productor_seguro_sucursal.productor_seguro_id) WHERE productor_seguro.seguro_id=%s AND sucursal_id = %s",
						GetSQLValueString($colname_Recordset1, "int"),
						GetSQLValueString($sucursal_id, "int"));
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	$output = array();
	while ($row_Recordset1=mysql_fetch_array($Recordset1)) {
		$output[$row_Recordset1[0]] = strip_tags($row_Recordset1[1]);
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>