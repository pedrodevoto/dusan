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
	$query_Recordset1 = sprintf("SELECT IF(cliente_tipo_persona=1, CONCAT(IFNULL(cliente_nombre, ''), ' ', IFNULL(cliente_apellido, '')), cliente_razon_social) as cliente_nombre, GROUP_CONCAT(CONCAT_WS(', ', contacto_telefono1, contacto_telefono2) SEPARATOR ', ') as cliente_telefonos, GROUP_CONCAT(IF(contacto_default=1, CONCAT_WS(' ', contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp), '')) as cliente_domicilio, seguro_nombre, productor_nombre, poliza_numero, CONCAT_WS('', 'Patente: ', CONCAT(IF(automotor_carroceria_id=17, '101', ''), patente_0, patente_1), ', Marca: ', automotor_marca_nombre, ', Modelo: ', modelo, ', Cobertura: ', seguro_cobertura_tipo_nombre, ', Valor asegurado: ', valor_total) as detalle_poliza FROM poliza JOIN (cliente, productor_seguro, seguro, productor) ON (poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.productor_id=productor.productor_id) LEFT JOIN contacto ON cliente.cliente_id = contacto.cliente_id LEFT JOIN localidad ON localidad.localidad_id = contacto.localidad_id LEFT JOIN (automotor, automotor_marca, seguro_cobertura_tipo) ON automotor.poliza_id = poliza.poliza_id AND automotor.automotor_marca_id = automotor_marca.automotor_marca_id AND seguro_cobertura_tipo.seguro_cobertura_tipo_id = automotor.seguro_cobertura_tipo_id");	
	switch ($_GET['tipo']) {
		case 'poliza':
		default:
		$query_Recordset1 .= sprintf(" WHERE poliza.poliza_id=%s GROUP BY poliza.poliza_id", GetSQLValueString($colname_Recordset1, "int"));
		break;
		case 'automotor':
		$query_Recordset1 .= sprintf(" WHERE automotor.automotor_id=%s GROUP BY automotor.automotor_id", GetSQLValueString($colname_Recordset1, "int"));
		break;
	}
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Main)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}			
				
	} else {
		$output["empty"] = true;
	}
	
	echo json_encode($output);			
	
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>