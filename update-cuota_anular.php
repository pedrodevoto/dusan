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
	
	$cuota_estado = '3 - Anulado';
	if (!empty($_POST['flag']) && $_POST['flag']==1) {
		$cuota_estado = '1 - No Pagado';
	}
	
	$sql = sprintf('UPDATE cuota SET cuota_estado="%s" WHERE cuota_id=%s', $cuota_estado, $cuota_id);
	mysql_query($sql, $connection) or die(mysql_error());
	
	echo 'Cuota anulada';