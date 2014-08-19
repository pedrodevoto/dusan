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
	$query_Recordset1 = "SELECT poliza.poliza_id, automotor_id, TRIM(CONCAT(IFNULL(cliente_nombre, ''), ' ', IFNULL(cliente_apellido, ''))) as cliente_nombre, poliza_numero, CONCAT(DATE_FORMAT(poliza_validez_desde, '%d/%m/%y'), ' - ', DATE_FORMAT(poliza_validez_hasta, '%d/%m/%y')) AS validez, seguro_nombre, productor_seguro_codigo, CONCAT(IF(automotor_carroceria_id=17, '101', ''), patente_0, patente_1) as patente FROM poliza JOIN (subtipo_poliza, productor_seguro, seguro, cliente) ON poliza.subtipo_poliza_id = subtipo_poliza.subtipo_poliza_id AND poliza.productor_seguro_id = productor_seguro.productor_seguro_id AND productor_seguro.seguro_id = seguro.seguro_id AND cliente.cliente_id = poliza.cliente_id LEFT JOIN automotor ON poliza.poliza_id = automotor.poliza_id";

	if (in_array($_SESSION['ADM_UserGroup'], array('administrativo'))) {
		$query_Recordset1 .= sprintf(' JOIN usuario_sucursal ON usuario_sucursal.sucursal_id = poliza.sucursal_id WHERE usuario_id = %s', GetSQLValueString($_SESSION['ADM_UserId'], "int"));
	}
	else {
		$query_Recordset1 .= ' WHERE 1';
	}
	
	$group = ' GROUP BY poliza.poliza_id, automotor_id';
	
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
		if (!empty($_GET['box0-cliente_id'])) {
			$query_Recordset1 .= sprintf(" AND poliza.cliente_id = %s", GetSQLValueString($_GET['box0-cliente_id'], "int"));
		}
	} else 	{
		$query_Recordset1 .= " AND 1=2";
	}
	
	$query_Recordset1 .= $group." ORDER BY poliza_validez_desde DESC ";
	
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