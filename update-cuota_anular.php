<?php
	$MM_authorizedUsers = "master";
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
	$cuota_id = intval($_POST['id']);	
	
	$sql = sprintf('SELECT poliza_id, cuota_recibo FROM cuota WHERE cuota_id = %s', $cuota_id);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$poliza_id = $row[0];
	$recibo = $row[1];
	
	$cuota_estado = 1;
	
	$sql = sprintf('UPDATE cuota SET cuota_estado_id="%s", cuota_fe_pago=NULL, cuota_fe_anulada=NOW(), cuota_recibo=NULL, cuota_nro_factura=NULL WHERE cuota_id=%s', $cuota_estado, $cuota_id);
	mysql_query($sql, $connection) or die(mysql_error());
	
	// Log
	$sql = sprintf('INSERT INTO cuota_log (cuota_id, poliza_id, cuota_log_tipo, cuota_log_fecha, cuota_recibo, usuario_id, timestamp) VALUES (%s, %s, 2, NOW(), %s, %s, NOW())', $cuota_id, $poliza_id, $recibo, $_SESSION['ADM_UserId']);
	error_log($sql);
	mysql_query($sql, $connection) or die(mysql_error());
	
	echo 'Cuota anulada';