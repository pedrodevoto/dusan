<?php
	$MM_authorizedUsers = "master";
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
	if (isset($_GET['sucursal_id']) && isset($_GET['fecha'])) {
		$sucursal_id = GetSQLValueString($_GET['sucursal_id'], "int");
		$date = mysql_real_escape_string($_GET['fecha']);
	}
	$query_Recordset1 = sprintf("SELECT caja_diaria_apertura, caja_diaria_cierre, caja_diaria_observaciones FROM caja_diaria WHERE sucursal_id = %s AND caja_diaria_fecha = '%s'",
							$sucursal_id,
							$date);
							// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
	$output = array();
	if ($totalRows_Recordset1) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
			$output['master'] = $_SESSION['ADM_UserGroup'] == 'master';
		}
	}
	
	$sql = sprintf('SELECT SUM(cuota_monto) FROM cuota JOIN poliza USING (poliza_id) WHERE sucursal_id = %s AND DATE(cuota_fe_pago) >= "%s" AND DATE(cuota_fe_pago) < "%s"', $sucursal_id, date('Y-m-01', strtotime($date)), date('Y-m-d', strtotime($date)));
	$res = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$total_cuotas = $row[0];
	
	$sql = sprintf('SELECT SUM(caja_ingreso_valor) FROM caja_ingresos WHERE sucursal_id = %s AND DATE(caja_ingreso_fecha) >= "%s" AND DATE(caja_ingreso_fecha) < "%s"', $sucursal_id, date('Y-m-01', strtotime($date)), date('Y-m-d', strtotime($date)));
	$res = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$total_ingresos = $row[0];
	
	$sql = sprintf('SELECT SUM(caja_egreso_valor) FROM caja_egresos WHERE sucursal_id = %s AND DATE(caja_egreso_fecha) >= "%s" AND DATE(caja_egreso_fecha) < "%s"', $sucursal_id, date('Y-m-01', strtotime($date)), date('Y-m-d', strtotime($date)));
	$res = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$total_egresos = $row[0];
	
	$arrastre = round((float)$total_cuotas + (float)$total_ingresos - (float)$total_egresos, 2);
	
	$output['caja_arrastre_anterior'] = $arrastre;
	
	if (empty($output['caja_diaria_apertura'])) {
		$sql = sprintf('SELECT caja_diaria_cierre FROM caja_diaria WHERE sucursal_id = %s AND DATE(caja_diaria_fecha) = DATE("%s")-INTERVAL 1 DAY', $sucursal_id, $date);
		$res = mysql_query($sql) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$output['caja_diaria_apertura'] = $row[0];
	}
	
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>