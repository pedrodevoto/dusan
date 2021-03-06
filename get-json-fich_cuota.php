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
	$query_Recordset1 = sprintf("SELECT cuota.cuota_id, cuota_nro, DATE_FORMAT(cuota_periodo,'%%Y-%%m') AS cuota_periodo, cuota_monto, DATE_FORMAT(cuota_vencimiento, '%%d/%%m/%%y') as cuota_vencimiento, cuota_estado_nombre, DATE_FORMAT(cuota_fe_pago, '%%d/%%m/%%y %%H:%%i') as cuota_fe_pago, cuota_recibo, cuota_pfc FROM cuota JOIN cuota_estado ON cuota_estado.cuota_estado_id = cuota.cuota_estado_id WHERE cuota.poliza_id=%s ORDER BY cuota_nro ASC",
							GetSQLValueString($colname_Recordset1, "int"));
							
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
			$output[$i]['master'] = $_SESSION['ADM_UserGroup'] == 'master';
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>