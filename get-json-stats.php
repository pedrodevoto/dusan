<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	require_once('Connections/connection.php');
	require_once('inc/db_functions.php');
	
	$sql = 'SELECT seguro_id, seguro_nombre FROM seguro ORDER BY seguro_id';
	$res = mysql_query($sql, $connection) or die(mysql_error());
	
	$output = array();
	
	while ($row = mysql_fetch_assoc($res)) {
		$sql = 'SELECT COUNT(p.poliza_id) AS total, SUM(IF(poliza_medio_pago=\'Directo\', 1, 0)) AS directo, SUM(IF(poliza_medio_pago=\'Tarjeta de Crédito\' OR poliza_medio_pago=\'Débito Bancario\', 1, 0)) AS tc, SUM(IF(poliza_medio_pago=\'Cuponera\', 1, 0)) AS cup  FROM poliza p JOIN productor_seguro ps ON ps.productor_seguro_id = p.productor_seguro_id LEFT JOIN (endoso e, endoso_tipo et) ON (p.poliza_id = e.poliza_id AND e.endoso_tipo_id = et.endoso_tipo_id AND endoso_tipo_grupo_id = 1) WHERE sucursal_id = 2 AND poliza_estado_id IN(3,4,7) AND endoso_id IS NULL AND ps.seguro_id = '.$row['seguro_id'];
		$res2 = mysql_query($sql, $connection) or die(mysql_error());
		$row2 = mysql_fetch_assoc($res2);
		if ($row2['total']>0) 
			$output[] = array(
				'seguro_nombre' => $row['seguro_nombre'],
				'vigentes' => $row2['total'],
				'directo' => $row2['directo'],
				'tc' => $row2['tc'],
				'cup' => $row2['cup'],
			);
		$sql = 'SELECT COUNT(p.poliza_id) AS total FROM poliza p LEFT JOIN (endoso e, endoso_tipo et) ON (p.poliza_id = e.poliza_id AND e.endoso_tipo_id = et.endoso_tipo_id AND endoso_tipo_grupo_id = 1) WHERE sucursal_id = 2 AND poliza_estado_id IN(3,4,7) AND endoso_id IS NULL';
		$res2 = mysql_query($sql, $connection) or die(mysql_error());
		$row2 = mysql_fetch_assoc($res2);
		$output['total'] = $row2['total'];
	}
	echo json_encode($output);
	
?>