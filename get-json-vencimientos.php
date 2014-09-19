<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	require_once('inc/db_functions.php');	
?>
<?php
	// Recordset: Main	
	$output = array();
	if (!empty($_GET['date'])) {
		$sql = sprintf('SELECT CONCAT_WS(" ", cliente_nombre, cliente_apellido, cliente_razon_social) cliente_nombre, IFNULL(poliza_numero, "-") poliza_numero, CONCAT_WS("", patente_0, patente_1) patente, cuota_nro, poliza_cant_cuotas, cuota_monto from cuota join poliza USING (poliza_id) join cliente using (cliente_id) left join automotor using (poliza_id) left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1) where endoso_id is null and cuota_vencimiento = %s and cuota_estado_id = 1', GetSQLValueString($_GET['date'], 'date'));
		$res = mysql_query($sql, $connection) or die(mysql_error());
		while ($row = mysql_fetch_assoc($res)) {
			$output[] = $row;
		}
	}
	else {
		$sql = sprintf('SELECT cuota_vencimiento, COUNT(cuota_id) from cuota join poliza using (poliza_id) left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1) WHERE endoso_id is null and cuota_vencimiento BETWEEN %s AND DATE(%s)-interval 1 day AND cuota_estado_id = 1 GROUP BY cuota_vencimiento', GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['end'], 'date'));
		$res = mysql_query($sql, $connection) or die(mysql_die());
		while ($row = mysql_fetch_array($res)) {
			$output[] = array('title'=>sprintf('Vencimientos (%s)', $row[1]), 'start'=>$row[0], 'id'=>$row[0]);
		}
	}
	echo json_encode($output);
	
	// Close Recordset: Main	
	mysql_free_result($res);
?>