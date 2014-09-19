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
	
	$sql = sprintf('SELECT cuota_vencimiento, COUNT(cuota_id) from cuota join poliza using (poliza_id) left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1) WHERE endoso_id is null and cuota_vencimiento BETWEEN %s AND DATE(%s)-interval 1 day AND cuota_estado_id = 1 GROUP BY cuota_vencimiento', GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['end'], 'date'));
	$res = mysql_query($sql, $connection) or die(mysql_die());
	error_log($sql);
	$output = array();
	while ($row = mysql_fetch_array($res)) {
		$output[] = array('title'=>sprintf('Vencen %s cuotas', $row[1]), 'start'=>$row[0]);
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($res);
?>