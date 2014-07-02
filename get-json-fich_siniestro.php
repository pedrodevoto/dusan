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
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}
	$query_Recordset1 = sprintf("SELECT `key`, `value` FROM siniestros_data WHERE siniestro_id = %s", GetSQLValueString($colname_Recordset1, "int"));	
		
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Main)
	if ($totalRows_Recordset1 > 0) {		
		$sql = sprintf('SELECT automotor_id, siniestros.cliente_id, productor_seguro_codigo FROM siniestros JOIN automotor USING(automotor_id) JOIN poliza USING (poliza_id) JOIN productor_seguro USING (productor_seguro_id) WHERE siniestros.id = %s', GetSQLValueString($colname_Recordset1, "int"));
		$res = mysql_query($sql, $connection) or die(mysql_error());
		$row = mysql_fetch_array($res);
		$output['automotor_id'] = $row[0];
		$output['cliente_id'] = $row[1];
		$output['productor_seguro_codigo'] = $row[2];
		
		while ($row = mysql_fetch_array($Recordset1)) {
			// Set Basic Info
			$output[$row[0]] = strip_tags($row[1]);
		}
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>