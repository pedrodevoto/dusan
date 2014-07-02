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
	$query_Recordset1 = sprintf("SELECT siniestros_lesiones_terceros.id as id, nombre.value as nombre, nro_doc.value as nro_doc, tel.value as tel
		FROM siniestros_lesiones_terceros
		LEFT JOIN siniestros_lesiones_terceros_data nombre ON nombre.siniestros_lesiones_terceros_id = siniestros_lesiones_terceros.id AND nombre.key = 'nombre' 
		LEFT JOIN siniestros_lesiones_terceros_data nro_doc ON nro_doc.siniestros_lesiones_terceros_id = siniestros_lesiones_terceros.id AND nro_doc.key = 'nro_doc' 
		LEFT JOIN siniestros_lesiones_terceros_data tel ON  tel.siniestros_lesiones_terceros_id = siniestros_lesiones_terceros.id AND tel.key = 'tel' 
		WHERE siniestro_id = %s
		GROUP BY siniestros_lesiones_terceros.id", GetSQLValueString($colname_Recordset1, "int"));
			
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