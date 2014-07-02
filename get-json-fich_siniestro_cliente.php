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
	$sql = sprintf('SELECT cliente_id, automotor_id FROM siniestros WHERE siniestros.id = %s', GetSQLValueString($colname_Recordset1, "int"));
	$res = mysql_query($sql, $connection) or die(mysql_error());
	$row = mysql_fetch_array($res);
	list($cliente_id, $automotor_id) = $row;
	
	// Info de cliente
	$query_Recordset1 = sprintf("SELECT IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as asegurado_nombre, IF(cliente_sexo='F', 2, IF(cliente_sexo='M', 1, NULL)) as asegurado_sexo, contacto_domicilio as asegurado_calle, contacto_nro as asegurado_altura, localidad_nombre as asegurado_localidad, localidad_cp as asegurado_cp, contacto_telefono1 as asegurado_tel, contacto_telefono2 as asegurado_cel, cliente_nacimiento as asegurado_fec_nac, cliente_registro as asegurado_registro, cliente_reg_vencimiento as asegurado_registro_venc FROM cliente JOIN contacto ON contacto.cliente_id = cliente.cliente_id AND contacto.contacto_default = 1 JOIN localidad USING(localidad_id) WHERE cliente.cliente_id = %s", $cliente_id);
	
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		

	$output = array();
	
	// If Recordset not empty (Cliente)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	}
	
	// Info de automotor
	$query_Recordset1 = sprintf("SELECT automotor_marca_nombre as asegurado_marca, modelo as asegurado_modelo, ano as asegurado_ano, patente_0 as asegurado_patente_0, patente_1 as asegurado_patente_1, automotor_tipo_nombre as asegurado_tipo, uso as asegurado_uso, nro_motor as asegurado_nro_motor, nro_chasis as asegurado_nro_chasis FROM automotor LEFT JOIN automotor_marca USING (automotor_marca_id) LEFT JOIN automotor_tipo USING(automotor_tipo_id) WHERE automotor_id = %s", $automotor_id);
	
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);		
	
	// If Recordset not empty (Cliente)
	if ($totalRows_Recordset1 > 0) {
		
		// Set Basic Info
		foreach ($row_Recordset1 as $key=>$value) {
			$output[$key] = strip_tags($value);
		}				
				
	}
	
	echo json_encode($output);
		
	// Close Recordset: Main
	mysql_free_result($Recordset1);	
?>