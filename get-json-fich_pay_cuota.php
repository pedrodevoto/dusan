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
	$query_Recordset1 = sprintf("SELECT poliza_id, cuota.cuota_id, cuota_nro, cuota_monto, cuota_fe_pago FROM cuota WHERE cuota_id=%s",
							GetSQLValueString($colname_Recordset1, "int"));
							
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
	}		
	
	$sql = sprintf("SELECT cuota_vencimiento FROM cuota WHERE poliza_id=%s AND cuota_nro=%s + 1",
							$output['poliza_id'],
							$output['cuota_nro']);
	$res = mysql_query($sql);
	list($output['cuota_vencimiento']) = mysql_fetch_array($res);
	
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>