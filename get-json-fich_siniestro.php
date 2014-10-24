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
	
	$query_Recordset1 .= sprintf(' AND `key` %s IN ("asegurado_registro", "asegurado_registro_venc", "siniestro_numero", "fecha_compania", "fecha_denuncia", "fecha_ocurrencia", "tipo_siniestro", "pagado", "cerrado", "enviado_estudio_juridico", "fecha_enviado_estudio_juridico", "compania_tercero", "forma_pago", "cobrado", "siniestro_id")', (empty($_GET['limited'])?'NOT':''));
	
	// Recordset: Main
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);	
	
	$output = array();
	
	// If Recordset not empty (Main)
	if ($totalRows_Recordset1 > 0) {		
		$sql = sprintf('SELECT automotor_id, siniestros.cliente_id, CONCAT(productor_nombre, " (", productor_seguro_codigo, ")"), CONCAT_WS(" ", cliente_apellido, cliente_nombre, cliente_razon_social), CONCAT_WS(" ", contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp), CONCAT_WS(", ", contacto_telefono1, contacto_telefono2, contacto_telefono2_compania, contacto_telefono_laboral, contacto_telefono_alt), seguro_nombre, poliza_numero, CONCAT(DATE_FORMAT(poliza_validez_desde, "%%d/%%m/%%Y"), " al ", DATE_FORMAT(poliza_validez_hasta, "%%d/%%m/%%Y")), CONCAT_WS("", "Patente: ", CONCAT(IF(automotor_carroceria_id=17, "101", ""), patente_0, patente_1), ", Marca: ", automotor_marca_nombre, ", Modelo: ", IFNULL(automotor_version_nombre, IFNULL(automotor_modelo_nombre, modelo)), ", Año: ", ano, ", Cobertura: ", seguro_cobertura_tipo_nombre, ", Valor asegurado: ", valor_total) FROM siniestros JOIN cliente USING (cliente_id) JOIN contacto USING (cliente_id) LEFT JOIN localidad USING (localidad_id) JOIN automotor USING(automotor_id) LEFT JOIN seguro_cobertura_tipo USING (seguro_cobertura_tipo_id) LEFT JOIN automotor_marca USING (automotor_marca_id) LEFT JOIN automotor_modelo USING (automotor_modelo_id) LEFT JOIN automotor_version USING (automotor_version_id) JOIN poliza USING (poliza_id) JOIN productor_seguro USING (productor_seguro_id) JOIN seguro ON seguro.seguro_id = productor_seguro.seguro_id JOIN productor USING (productor_id) WHERE contacto_default = 1 and siniestros.id = %s', GetSQLValueString($colname_Recordset1, "int"));
		$res = mysql_query($sql, $connection) or die(mysql_error());
		$row = mysql_fetch_array($res);
		list(
			$output['automotor_id'],
			$output['cliente_id'],
			$output['productor_seguro_codigo'],
			$output['cliente_nombre'],
			$output['cliente_domicilio'],
			$output['cliente_telefonos'],
			$output['seguro_nombre'],
			$output['poliza_numero'],
			$output['poliza_vigencia'],
			$output['poliza_detalle'],
		) = $row;
		
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