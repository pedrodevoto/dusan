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
	$query_Recordset1 = sprintf("SELECT endoso_id, endoso.poliza_id as poliza_id, poliza_numero, endoso_fecha_pedido, endoso_tipo_id, endoso_cuerpo, endoso_numero, endoso_fecha_compania, endoso_completo FROM endoso JOIN poliza ON poliza.poliza_id = endoso.poliza_id WHERE endoso_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Main)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>