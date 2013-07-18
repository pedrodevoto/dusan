<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
	// If form was submitted
	if ((isset($_POST["box-poliza_id"])) && ($_POST["box-poliza_id"] !== "")) {
		
		/****************************************
		 * GENERAL
		 ****************************************/
		
		// Obtain Master ID
		$poliza_id = intval($_POST['box-poliza_id']);
		
		// Recordset: Poliza
		$query_Recordset1 = sprintf("SELECT poliza.poliza_id, subtipo_poliza_tabla FROM subtipo_poliza JOIN (poliza) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id) WHERE poliza.poliza_id=%s", $poliza_id);
		$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
		$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		$totalRows_Recordset1 = mysql_num_rows($Recordset1);
		if ($totalRows_Recordset1 !== 1) {
			die("Error: Poliza Maestra no encontrada.");
		}
		mysql_free_result($Recordset1);
		
		/****************************************
		 * DATOS DE POLIZA
		 ****************************************/		
		
		// Get reusable fields
		$poliza_cant_cuotas = intval($_POST['box-poliza_cant_cuotas']);
		$poliza_premio = doubleval($_POST['box-poliza_premio']);
		$poliza_validez_desde = $_POST['box-poliza_validez_desde'];
		$poliza_validez_hasta = $_POST['box-poliza_validez_hasta'];
		
		// Determine state
		$query_Recordset2 = sprintf("SELECT DATEDIFF(NOW(),%s) AS startdiff, DATEDIFF(NOW(),%s) AS enddiff FROM DUAL",
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"));	
		$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);		
		$estado = determineState($row_Recordset2['startdiff'], $row_Recordset2['enddiff']);
		if (is_null($estado)) {
			die("Error: No se pudo determinar el Estado de la póliza.");
		}
		mysql_free_result($Recordset2);						
		
		// Encrypt Payment info
		if (isset($_POST['box-poliza_pago_detalle']) && $_POST['box-poliza_pago_detalle'] !== '') {
			$poliza_pago_detalle = Encryption::encrypt($_POST['box-poliza_pago_detalle']);
		} else {
			$poliza_pago_detalle = '';
		}
		
		// Insert
		$insertSQL = sprintf("INSERT INTO poliza (cliente_id, subtipo_poliza_id, poliza_estado, poliza_anulada, poliza_numero, poliza_renueva_num, productor_seguro_id, poliza_vigencia, poliza_validez_desde, poliza_validez_hasta, poliza_cuotas, poliza_cant_cuotas, poliza_fecha_solicitud, poliza_fecha_emision, poliza_fecha_recepcion, poliza_fecha_entrega, poliza_correo, poliza_entregada, poliza_prima, poliza_premio, poliza_medio_pago, poliza_pago_detalle, poliza_recargo, poliza_ajuste)
		 					  (SELECT poliza.cliente_id, poliza.subtipo_poliza_id, %s, %s, TRIM(%s), poliza_numero, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s FROM poliza WHERE poliza.poliza_id=%s)",
								GetSQLValueString($estado, "text"),
								GetSQLValueString(isset($_POST['box-poliza_anulada']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-poliza_numero'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"),
								GetSQLValueString($_POST['box-poliza_vigencia'], "text"),
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"),
								GetSQLValueString($_POST['box-poliza_cuotas'], "text"),
								GetSQLValueString($poliza_cant_cuotas, "int"),
								GetSQLValueString($_POST['box-poliza_fecha_solicitud'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_emision'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_recepcion'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_entrega'], "date"),
								GetSQLValueString(isset($_POST['box-poliza_correo']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString(isset($_POST['box-poliza_entregada']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-poliza_prima'], "double"),
								GetSQLValueString($poliza_premio, "double"),
								GetSQLValueString($_POST['box-poliza_medio_pago'], "text"),
								GetSQLValueString($poliza_pago_detalle, "text"),
								GetSQLValueString($_POST['box-poliza_recargo'], "double"),
								GetSQLValueString($_POST['box-poliza_ajuste'], "int"),
								$row_Recordset1['poliza_id']);								
		$Result1 = mysql_query($insertSQL, $connection);
		
		// Evaluate insert
		switch (mysql_errno()) {
			case 0:

				// Get New ID
				$new_id = mysql_insert_id();				
			
				// Insert: Cuotas
				$monto = $poliza_premio / $poliza_cant_cuotas;
				for ($i=0; $i<$poliza_cant_cuotas; $i++) {
					$insertSQL = sprintf("INSERT INTO cuota (poliza_id, cuota_nro, cuota_periodo, cuota_monto, cuota_vencimiento, cuota_pfc) VALUES (%s, %s, DATE_FORMAT(DATE_ADD(%s, INTERVAL %s MONTH),'%%Y-%%m-01'), %s, DATE_ADD(%s, INTERVAL %s MONTH), IF(%s=1,1,0))",
									GetSQLValueString($new_id, "int"),
									GetSQLValueString($i+1, "int"),
									GetSQLValueString($poliza_validez_desde, "date"),									
									GetSQLValueString($i, "int"),									
									GetSQLValueString($monto, "double"),
									GetSQLValueString($poliza_validez_desde, "date"),
									GetSQLValueString($i, "int"),
									GetSQLValueString($i+1, "int"));
					$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				}

				break;
			case 1062:
				die('Error: Registro duplicado.');
				break;
			default:			
				die(mysql_die());
				break;
		}
		
		/****************************************
		 * DETALLE DE POLIZA
		 ****************************************/
				
		// Determine subtype
		switch($row_Recordset1['subtipo_poliza_tabla']) {
				
			case 'automotor':		
				// ---------------------------------- AUTOMOTOR ---------------------------------- //
				
				// Recordset: Automotor
				$query_Recordset3 = sprintf("SELECT automotor.automotor_id FROM automotor WHERE automotor.poliza_id=%s",
									$row_Recordset1['poliza_id']);
				$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
				$totalRows_Recordset3 = mysql_num_rows($Recordset3);
				mysql_free_result($Recordset3);

				// If Record exists, insert
				if ($totalRows_Recordset3 === 1) {
					$insertSQL = sprintf("INSERT INTO automotor (poliza_id, marca, modelo, patente, tipo, uso, ano, carroceria, combustible, 0km, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, accesorios, zona_riesgo, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, micro_grabado, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, cobertura_tipo, franquicia, limite_rc, servicio_grua, valor_vehiculo, valor_gnc, valor_accesorios, valor_total) 
										  (SELECT %s, marca, modelo, patente, tipo, uso, ano, carroceria, combustible, 0km, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, accesorios, zona_riesgo, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, micro_grabado, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, cobertura_tipo, franquicia, limite_rc, servicio_grua, valor_vehiculo, valor_gnc, valor_accesorios, valor_total FROM automotor WHERE automotor.poliza_id=%s)",
											$new_id,
											$row_Recordset1['poliza_id']);																		
					$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				}																						
			
				// Break
				break;
				
			default:
				// ---------------------------------- UNDEFINED ---------------------------------- //
				die("Error: Subtipo no habilitado.");
				break;
		}
		
		/****************************************
		 * UPDATE ORIGINAL
		 ****************************************/
		 
		// Update
		$updateSQL = sprintf("UPDATE poliza SET poliza_estado='RENOVADA' WHERE poliza.poliza_id=%s LIMIT 1",
						$poliza_id);			
		$Result1 = mysql_query($updateSQL, $connection) or die(mysql_die()); 

		/****************************************
		 * RETURN ID
		 ****************************************/

		echo $new_id;
	
	} else {
		die("Error: Acceso denegado.");
	} // End: If form was submitted		
?>