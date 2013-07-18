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
	$query_Recordset1 = sprintf("SELECT poliza.poliza_id, poliza_numero, subtipo_poliza_nombre, IF(poliza_medio_pago = 'Directo', IF(COUNT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', 1, NULL))=0, 'Sí', 'No'), IF(poliza_medio_pago='Cuponera', 'Cup', IF(poliza_medio_pago='Débito Bancario', 'DC', 'TC'))) AS poliza_al_dia, IF(poliza_medio_pago = 'Directo', GROUP_CONCAT(IF(cuota_vencimiento <= DATE(NOW()) AND cuota_estado = '1 - No Pagado', CONCAT('Cuota número ', cuota_nro, ' (Período: ', DATE_FORMAT(cuota_periodo, '%%m/%%y'), ', venc: ', DATE_FORMAT(cuota_vencimiento, '%%d/%%m/%%y'), ')'), NULL) SEPARATOR '\n'), '') AS poliza_al_dia_detalle FROM poliza JOIN (subtipo_poliza) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id) LEFT JOIN cuota ON cuota.poliza_id = poliza.poliza_id WHERE poliza.cliente_id=%s GROUP BY poliza.poliza_id", GetSQLValueString($colname_Recordset1, "int"));
			
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