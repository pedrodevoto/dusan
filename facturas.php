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
	
	
	$sql = "SELECT cuota_id, cuota_fe_pago FROM cuota WHERE cuota_estado_id = 2 AND cuota_nro_factura IS NULL ORDER BY cuota_fe_pago ASC";
	$rows = mysql_query($sql);
	
	while ($row = mysql_fetch_array($rows)) {
		$cuota_id = $row[0];

		$sql = sprintf('SELECT poliza.sucursal_id, sucursal_num_factura  FROM cuota JOIN (poliza, sucursal) ON cuota.poliza_id = poliza.poliza_id AND poliza.sucursal_id = sucursal.sucursal_id WHERE cuota_id = %s', $cuota_id);
		$res = mysql_query($sql, $connection) or die(mysql_die());
		list($sucursal_id, $sucursal_num_factura) = mysql_fetch_array($res);

		$sql = sprintf('SELECT COALESCE(MAX(cuota_nro_factura)+1,%s) FROM cuota JOIN poliza ON cuota.poliza_id = poliza.poliza_id WHERE sucursal_id = %s', $sucursal_num_factura, $sucursal_id);
		$res = mysql_query($sql, $connection) or die(mysql_die());
		list($cuota_nro_factura) = mysql_fetch_array($res);
		
		$sql = sprintf('UPDATE cuota SET cuota_nro_factura = %s WHERE cuota_id=%s', 
						$cuota_nro_factura,
						$cuota_id);
		mysql_query($sql, $connection) or die(mysql_error());
		echo $cuota_id . ", " . $cuota_nro_factura . "<br />";
	}
	
	echo 'El pago fue procesado';