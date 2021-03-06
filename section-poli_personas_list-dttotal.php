<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	require_once('Connections/connection.php');
	require_once('inc/db_functions.php');		
	
	$sql = "SELECT COUNT(poliza.poliza_id) AS total FROM poliza JOIN subtipo_poliza ON poliza.subtipo_poliza_id = subtipo_poliza.subtipo_poliza_id LEFT JOIN (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1) WHERE poliza_estado_id IN(3,4,7) AND tipo_poliza_id = 3 AND endoso_id IS NULL";
	if (!empty($_GET['sucursal_id'])) {
		$sql .= sprintf(' AND sucursal_id = %s', GetSQLValueString($_GET['sucursal_id'], 'int'));
	}
	if (!empty($_GET['poliza_medio_pago'])) {
		$sql .= sprintf(' AND poliza_medio_pago = %s', GetSQLValueString($_GET['poliza_medio_pago'], 'text'));
	}
	$res = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($res);
	$total = $row['total'];
	echo "TOTAL VIGENTES: ".number_format($total, 0, ',', '.');
?>