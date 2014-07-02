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
	$colname_Recordset1 = "-1";
	if (isset($_GET['id'])) {
		$colname_Recordset1 = $_GET['id'];
	}	
	$query_Recordset1 = sprintf("SELECT siniestros_datos_terceros.id as id, nombre.value as nombre, CONCAT(patente_0.value, patente_1.value) as patente 
		FROM siniestros_datos_terceros
		LEFT JOIN siniestros_datos_terceros_data nombre ON nombre.siniestros_datos_terceros_id = siniestros_datos_terceros.id AND nombre.key = 'nombre' 
		LEFT JOIN siniestros_datos_terceros_data patente_0 ON patente_0.siniestros_datos_terceros_id = siniestros_datos_terceros.id AND patente_0.key = 'patente_0' 
		LEFT JOIN siniestros_datos_terceros_data patente_1 ON  patente_0.siniestros_datos_terceros_id = siniestros_datos_terceros.id AND patente_1.key = 'patente_1' 
		WHERE siniestro_id = %s
		GROUP BY siniestros_datos_terceros.id", GetSQLValueString($colname_Recordset1, "int"));
			
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	// Output
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