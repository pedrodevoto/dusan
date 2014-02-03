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
	$query_Recordset1 = sprintf("SELECT IF(cliente_tipo_persona=1, CONCAT(IFNULL(cliente_nombre, ''), '', IFNULL(cliente_apellido, '')), cliente_razon_social) as cliente_nombre, GROUP_CONCAT(CONCAT(a.contacto_telefono1, ', ', a.contacto_telefono2) SEPARATOR ', ') as cliente_telefonos, CONCAT(b.contacto_domicilio, ' ', b.contacto_nro, ' ', IFNULL(b.contacto_piso, ''), ' ', IFNULL(b.contacto_dpto, ''), ' ', b.contacto_localidad, ' ', b.contacto_cp) as cliente_domicilio, seguro_nombre, productor_nombre, poliza_numero, CONCAT('Patente: ', CONCAT(IF(automotor_carroceria_id=17, '101', ''), patente_0, patente_1), ', Marca: ', automotor_marca_nombre, ', Modelo: ', modelo) as detalle_poliza FROM poliza JOIN (cliente, productor_seguro, seguro, productor) ON (poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.seguro_id=seguro.seguro_id AND productor_seguro.productor_id=productor.productor_id) LEFT JOIN (automotor, automotor_marca, contacto a, contacto b) ON automotor.poliza_id = poliza.poliza_id AND automotor.automotor_marca_id = automotor_marca.automotor_marca_id AND cliente.cliente_id = a.cliente_id AND cliente.cliente_id = b.cliente_id AND b.contacto_default = 1 WHERE poliza.poliza_id=%s", GetSQLValueString($colname_Recordset1, "int"));	
		
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