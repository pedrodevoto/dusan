<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
	// Obtain URL parameter
	$cuota_id = intval($_POST['box-cuota_id']);	
	
	// Determinar factura y recibo
	$sql = sprintf('SELECT poliza.sucursal_id, sucursal_num_factura, cuota_pfc  FROM cuota JOIN (poliza, sucursal) ON cuota.poliza_id = poliza.poliza_id AND poliza.sucursal_id = sucursal.sucursal_id WHERE cuota_id = %s', $cuota_id);
	$res = mysql_query($sql, $connection) or die(mysql_die());
	list($sucursal_id, $sucursal_num_factura, $cuota_pfc) = mysql_fetch_array($res);
	
	$sql = sprintf("SELECT COALESCE(MAX(cuota_recibo)+1,1) AS val_max FROM cuota JOIN poliza ON cuota.poliza_id = poliza.poliza_id WHERE sucursal_id = %s", $sucursal_id);
	$res = mysql_query($sql, $connection) or die(mysql_die());
	list($recibo) = mysql_fetch_array($res);	
	
	if ($cuota_pfc==1) {
		$cuota_nro_factura = 'NULL';
	}
	else {
		$sql = sprintf('SELECT COALESCE(MAX(cuota_nro_factura)+1,%s) FROM cuota JOIN poliza ON cuota.poliza_id = poliza.poliza_id WHERE sucursal_id = %s', $sucursal_num_factura, $sucursal_id);
		$res = mysql_query($sql, $connection) or die(mysql_die());
		list($cuota_nro_factura) = mysql_fetch_array($res);
	}
	
	$sql = sprintf('UPDATE cuota SET cuota_estado_id=2, cuota_fe_pago=%s, cuota_fe_anulada=NULL, cuota_monto=%s, cuota_recibo=%s, cuota_nro_factura = %s WHERE cuota_id=%s', 
					GetSQLValueString($_POST['box-cuota_fe_pago'], "date"),
					GetSQLValueString($_POST['box-cuota_monto'], "double"),
					$recibo,
					$cuota_nro_factura,
					$cuota_id);
	mysql_query($sql, $connection) or die(mysql_error());
	
	// Log
	$sql = sprintf('SELECT poliza_id FROM cuota WHERE cuota_id = %s', $cuota_id);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$poliza_id = $row[0];
	$sql = sprintf('INSERT INTO cuota_log (cuota_id, poliza_id, cuota_log_tipo, cuota_log_fecha, cuota_recibo, usuario_id, timestamp) VALUES (%s, %s, 1, %s, %s, %s, NOW())', $cuota_id, $poliza_id, GetSQLValueString($_POST['box-cuota_fe_pago'], "date"), $recibo, $_SESSION['ADM_UserId']);
	
	mysql_query($sql, $connection) or die(mysql_error());
	
	if (isset($_POST['box-cuota_vencimiento']) and $_POST['box-cuota_vencimiento'] != '') {
		$sql = sprintf('SELECT poliza_id, cuota_nro FROM cuota WHERE cuota_id = %s', $cuota_id);
		$res = mysql_query($sql, $connection);
		if ($row = mysql_fetch_array($res)) {
			$sql = sprintf('UPDATE cuota SET cuota_vencimiento=%s WHERE poliza_id = %s AND cuota_nro = %s + 1',
							GetSQLValueString($_POST['box-cuota_vencimiento'], "date"),
							$row[0], $row[1]);
			mysql_query($sql, $connection);
		}
	}
	echo 'El pago fue procesado';