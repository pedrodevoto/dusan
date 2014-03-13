<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require Connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
	// Main Query
	$query_Recordset1 = "SELECT poliza.poliza_id, TRIM(CONCAT(IFNULL(cliente_nombre, ''), ' ', IFNULL(cliente_apellido, ''))) as cliente_nombre, poliza_numero, CONCAT(DATE_FORMAT(poliza_validez_desde, '%d/%m/%y'), ' - ', DATE_FORMAT(poliza_validez_hasta, '%d/%m/%y')) AS validez, seguro_nombre FROM poliza JOIN (subtipo_poliza, productor_seguro, seguro, cliente) ON poliza.subtipo_poliza_id = subtipo_poliza.subtipo_poliza_id AND poliza.productor_seguro_id = productor_seguro.productor_seguro_id AND productor_seguro.seguro_id = seguro.seguro_id AND cliente.cliente_id = poliza.cliente_id LEFT JOIN automotor ON poliza.poliza_id = automotor.poliza_id WHERE 1";
	
	// Query Where	
	if (isset($_GET['box0-poliza_numero']) or isset($_GET['box0-cliente_nombre'])) {
		if (isset($_GET['box0-poliza_numero']) && $_GET['box0-poliza_numero'] !== '') {
			$query_Recordset1 .= sprintf(" AND poliza_numero=%s",
									GetSQLValueString($_GET['box0-poliza_numero'], "text"));			
		}
		if (isset($_GET['box0-cliente_nombre']) && $_GET['box0-cliente_nombre'] !== '') {
			$query_Recordset1 .= sprintf(' AND (TRIM(CONCAT(IFNULL(cliente_nombre, \'\'), \' \', IFNULL(cliente_apellido, \'\'))) LIKE %1$s OR TRIM(CONCAT(IFNULL(cliente_apellido, \'\'), \' \', IFNULL(cliente_nombre, \'\'))) LIKE %1$s)',
									GetSQLValueString('%'.$_GET['box0-cliente_nombre'].'%', "text"));	
									error_log($query_Recordset1);		
		}	
		if (!empty($_GET['box0-patente'])) {
			$query_Recordset1 .= sprintf(" AND CONCAT(IF(automotor_carroceria_id=17, '101', ''), patente_0, patente_1) LIKE %s",GetSQLValueString('%' . $_GET['box0-patente'] . '%', "text"));
		}
	} else 	{
		$query_Recordset1 .= " AND 1=2";
	}
	
	// Recordset	
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	// Output
	$output = array();
	if ($totalRows_Recordset1 > 0) {
		for ($i=0; $i<$totalRows_Recordset1; $i++) {
			foreach ($row_Recordset1 as $key=>$value) {
				$output[$i][$key] = strip_tags($value);
			}		
			$row_Recordset1 = mysql_fetch_assoc($Recordset1);		
		}
	} else {
		$output["empty"] = true;
	}
	echo json_encode($output);			
	
	// Close Recordset
	mysql_free_result($Recordset1);	
?>