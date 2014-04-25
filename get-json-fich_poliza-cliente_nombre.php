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
	$query_Recordset1 = sprintf("SELECT cliente.cliente_id, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre FROM cliente WHERE cliente.cliente_id=%s", GetSQLValueString($colname_Recordset1, "int"));
	
	// Recordset	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	// Output
	$output = array();
	if ($totalRows_Recordset1 > 0) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}	
	} else {
		$output["empty"] = true;
	}
	echo json_encode($output);			
	
	// Free Recordset
	mysql_free_result($Recordset1);	
?>