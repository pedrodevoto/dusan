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
	if (isset($_GET['sucursal_id']) && isset($_GET['date'])) {
		$sucursal_id = $_GET['sucursal_id'];
		$date = $_GET['date'];
	}
	$query_Recordset1 = sprintf("SELECT time(cuota_fe_pago) as hora, c.cuota_recibo, usuario_usuario, concat_ws(' ', cliente_apellido, cliente_nombre) as nombre, cuota_nro, (select count(cuota_id) from cuota where poliza_id = c.poliza_id) as cuota_nros, cuota_monto from cuota c join poliza using (poliza_id) join cliente using (cliente_id) left join cuota_log using (cuota_id) left join usuario using (usuario_id) where sucursal_id = %s and date(cuota_fe_pago) = %s",
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