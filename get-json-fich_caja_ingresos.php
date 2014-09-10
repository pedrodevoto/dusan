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
	if (isset($_GET['sucursal_id']) && isset($_GET['date'])) {
		$sucursal_id = $_GET['sucursal_id'];
		$date = $_GET['date'];
	}
	$query_Recordset1 = sprintf("SELECT caja_ingreso_id as id, time(caja_ingreso_fecha) as hora, usuario_usuario, caja_ingreso_recibo, caja_ingreso_cliente, caja_ingreso_valor from caja_ingresos join usuario using (usuario_id) where sucursal_id = %s and date(caja_ingreso_fecha) = %s",
							GetSQLValueString($sucursal_id, "int"),
							GetSQLValueString($date, "date"));
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