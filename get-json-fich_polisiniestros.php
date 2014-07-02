<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php
	// Main query
	$id = "-1";
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	}	
	$query_Recordset1 = sprintf("SELECT siniestros.id as id, fecha.value as fecha, lugar.value as lugar, siniestro_numero.value as siniestro_numero 
		FROM siniestros
		LEFT JOIN siniestros_data fecha ON fecha.siniestro_id = siniestros.id AND fecha.key = 'fecha_denuncia' 
		LEFT JOIN siniestros_data lugar ON lugar.siniestro_id = siniestros.id AND lugar.key = 'lugar_denuncia' 
		LEFT JOIN siniestros_data siniestro_numero ON siniestro_numero.siniestro_id = siniestros.id AND siniestro_numero.key = 'siniestro_numero' 
		WHERE automotor_id = %s", GetSQLValueString($id, "int"));
	
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	$output = array();
	for ($i=0; $i<$totalRows_Recordset1; $i++) {
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$i][$key] = strip_tags($value);
		}		
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
	}
	echo json_encode($output);

	// Close Recordset: Main	
	mysql_free_result($Recordset1);
?>