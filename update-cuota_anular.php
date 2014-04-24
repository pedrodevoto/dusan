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
	
	$cuota_estado = 1;
	
	$sql = sprintf('UPDATE cuota SET cuota_estado_id="%s", cuota_fe_pago=NULL, cuota_recibo=NULL, cuota_nro_factura=NULL WHERE cuota_id=%s', $cuota_estado, $cuota_id);
	mysql_query($sql, $connection) or die(mysql_error());
	
	echo 'Cuota anulada';