<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	require_once('Connections/connection.php');
	
	$sql = "SELECT COUNT(poliza_id) AS total FROM poliza WHERE poliza_estado_id IN(3,4) AND subtipo_poliza_id=6";
	$res = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($res);
	$total = $row['total'];
	echo "TOTAL VIGENTES: ".number_format($total, 0, ',', '.');
?>