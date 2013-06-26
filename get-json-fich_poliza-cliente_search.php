<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require Connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
	// Main Query
	$query_Recordset1 = "SELECT cliente.cliente_id, cliente_nombre, cliente_tipo_doc, cliente_nro_doc FROM cliente WHERE 1";
	
	// Query Where	
	if (isset($_GET['box0-cliente_nombre']) || isset($_GET['box0-cliente_nro_doc'])) {
		if (isset($_GET['box0-cliente_nombre']) && $_GET['box0-cliente_nombre'] !== '') {
			$query_Recordset1 .= sprintf(" AND cliente.cliente_nombre=%s",
									GetSQLValueString($_GET['box0-cliente_nombre'], "text"));			
		}
		if (isset($_GET['box0-cliente_nro_doc']) && $_GET['box0-cliente_nro_doc'] !== '') {
			$query_Recordset1 .= sprintf(" AND cliente.cliente_nro_doc=%s",
									GetSQLValueString($_GET['box0-cliente_nro_doc'], "text"));
		}	
	} else 	{
		$query_Recordset1 .= " AND 1=2";
	}
	
	// Recordset	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
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
	
	// Close Recordset
	mysql_free_result($Recordset1);	
?>