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
	// Main Query
	$query_Recordset1 = sprintf("SELECT automotor_id, nro_motor, nro_chasis, ano, automotor_marca_id, automotor_modelo_id, automotor_version_id FROM automotor JOIN poliza ON automotor.poliza_id = poliza.poliza_id WHERE patente_0 = UPPER(TRIM('%s')) AND patente_1 = UPPER(TRIM('%s')) ORDER BY automotor_id DESC", mysql_real_escape_string($_GET['patente_0']), mysql_real_escape_string($_GET['patente_1']));
	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	
	if (mysql_num_rows($Recordset1)) {
		$row = mysql_fetch_array($Recordset1);
		$output = array('nro_motor'=>$row[1], 'nro_chasis'=>$row[2], 'ano'=>$row[3], 'automotor_marca_id'=>$row[4], 
		'automotor_modelo_id'=>$row[5], 'automotor_version_id'=>$row[6]);
	}
	else {
		$output = FALSE;
	}
	echo json_encode($output);
	
	mysql_free_result($Recordset1);
?>