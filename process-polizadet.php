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
	// Obtain URL parameter
	$poliza_id = intval($_POST['box-poliza_id']);
	
	// Recordset: Poliza
	$query_Recordset1 = sprintf("SELECT subtipo_poliza_tabla FROM subtipo_poliza JOIN (poliza) ON (subtipo_poliza.subtipo_poliza_id=poliza.subtipo_poliza_id) WHERE poliza.poliza_id=%s", $poliza_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	if ($totalRows_Recordset1 !== 1) {
		die("Error: Poliza no encontrada.");
	}
	mysql_free_result($Recordset1);	

	// Determine subtype
	switch($row_Recordset1['subtipo_poliza_tabla']) {
			
		case 'automotor':		
			// ---------------------------------- AUTOMOTOR ---------------------------------- //
			
			// Recordset: Automotor
			$query_Recordset2 = sprintf("SELECT automotor.automotor_id FROM automotor WHERE automotor.poliza_id=%s", $poliza_id);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);		
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);
			
			// If record exists
			if ($totalRows_Recordset2 === 0) {				
				// Insert
				$insertSQL = sprintf("INSERT INTO automotor (poliza_id, marca, modelo, castigado, patente, tipo, uso, ano, carroceria, combustible, 0km, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, accesorios, zona_riesgo, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, micro_grabado, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, cobertura_tipo, franquicia, limite_rc, servicio_grua, valor_vehiculo, valor_gnc, valor_accesorios, valor_total) 
							          VALUES (%s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s)",
										$poliza_id,												
										GetSQLValueString($_POST['box-marca'], 'text'),
										GetSQLValueString($_POST['box-modelo'], 'text'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente'], 'text'),
										GetSQLValueString($_POST['box-tipo'], 'text'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-carroceria'], 'text'),
										GetSQLValueString($_POST['box-combustible'], 'text'),
										GetSQLValueString(isset($_POST['box-0km']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-importado']) ? 'true' : '', "defined",'1','0'),
										GetSQLValueString($_POST['box-nro_motor'], 'text'),
										GetSQLValueString($_POST['box-nro_chasis'], 'text'),
										GetSQLValueString($_POST['box-chapa'], 'text'),
										GetSQLValueString($_POST['box-pintura'], 'text'),
										GetSQLValueString($_POST['box-tipo_pintura'], 'text'),
										GetSQLValueString($_POST['box-tapizado'], 'text'),
										GetSQLValueString($_POST['box-color'], 'text'),
										GetSQLValueString(isset($_POST['box-accesorios']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-zona_riesgo'], 'text'),
										GetSQLValueString(isset($_POST['box-prendado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-acreedor_rs'], 'text'),
										GetSQLValueString($_POST['box-acreedor_cuit'], 'text'),
										GetSQLValueString(isset($_POST['box-infoauto']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-observaciones'], 'text'),
										GetSQLValueString(isset($_POST['box-alarma']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-corta_corriente']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-corta_nafta']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-traba_volante']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-matafuego']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-tuercas']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-equipo_rastreo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-antena']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-estereo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-parlantes']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-aire']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cristales_electricos']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-faros_adicionales']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cierre_sincro']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-techo_corredizo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-direccion_hidraulica']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-frenos_abs']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-airbag']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cristales_tonalizados']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-gps']) ? 'true' : '', 'defined','1','0'),										
										GetSQLValueString($_POST['box-cubiertas_medidas'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_marca'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_di'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_dd'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_ti'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_td'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_1ei'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_1ed'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_auxilio'], 'text'),
										GetSQLValueString($_POST['box-nro_oblea'], 'text'),
										GetSQLValueString($_POST['box-nro_regulador'], 'text'),
										GetSQLValueString($_POST['box-marca_regulador'], 'text'),
										GetSQLValueString($_POST['box-marca_cilindro'], 'text'),
										GetSQLValueString($_POST['box-venc_oblea'], 'date'),
										GetSQLValueString($_POST['box-nro_tubo'], 'text'),
										GetSQLValueString($_POST['box-cobertura_tipo'], 'text'),
										GetSQLValueString($_POST['box-franquicia'], 'int'),
										GetSQLValueString($_POST['box-limite_rc'], 'text'),
										GetSQLValueString($_POST['box-servicio_grua'], 'int'),
										GetSQLValueString($_POST['box-valor_vehiculo'], 'int'),
										GetSQLValueString($_POST['box-valor_gnc'], 'int'),
										GetSQLValueString($_POST['box-valor_accesorios'], 'int'),
										GetSQLValueString($_POST['box-valor_total'], 'int'));																		
				$Result1 = mysql_query($insertSQL, $connection);														
			} else {
				// Update
				$updateSQL = sprintf("UPDATE automotor 
										SET marca=UPPER(TRIM(%s)), modelo=UPPER(TRIM(%s)), castigado=%s, patente=UPPER(TRIM(%s)), tipo=%s, uso=%s, ano=%s, carroceria=%s, combustible=%s, 0km=%s, importado=%s, nro_motor=UPPER(TRIM(%s)), nro_chasis=UPPER(TRIM(%s)), chapa=%s, pintura=%s, tipo_pintura=%s, tapizado=%s, color=UPPER(TRIM(%s)), accesorios=%s, zona_riesgo=%s, prendado=%s, acreedor_rs=UPPER(TRIM(%s)), acreedor_cuit=UPPER(TRIM(%s)), infoauto=%s, observaciones=%s, alarma=%s, corta_corriente=%s, corta_nafta=%s, traba_volante=%s, matafuego=%s, tuercas=%s, equipo_rastreo=%s, micro_grabado=%s, antena=%s, estereo=%s, parlantes=%s, aire=%s, cristales_electricos=%s, faros_adicionales=%s, cierre_sincro=%s, techo_corredizo=%s, direccion_hidraulica=%s, frenos_abs=%s, airbag=%s, cristales_tonalizados=%s, gps=%s, 
											cubiertas_medidas=UPPER(TRIM(%s)), cubiertas_marca=UPPER(TRIM(%s)), cubiertas_desgaste_di=%s, cubiertas_desgaste_dd=%s, cubiertas_desgaste_ti=%s, cubiertas_desgaste_td=%s, cubiertas_desgaste_1ei=%s, cubiertas_desgaste_1ed=%s, cubiertas_desgaste_auxilio=%s, nro_oblea=UPPER(TRIM(%s)), nro_regulador=UPPER(TRIM(%s)), marca_regulador=UPPER(TRIM(%s)), marca_cilindro=UPPER(TRIM(%s)), venc_oblea=%s, nro_tubo=UPPER(TRIM(%s)), cobertura_tipo=%s, franquicia=%s, limite_rc=%s, servicio_grua=%s, valor_vehiculo=%s, valor_gnc=%s, valor_accesorios=%s, valor_total=%s 
										WHERE automotor.automotor_id=%s",
										GetSQLValueString($_POST['box-marca'], 'text'),
										GetSQLValueString($_POST['box-modelo'], 'text'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente'], 'text'),
										GetSQLValueString($_POST['box-tipo'], 'text'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-carroceria'], 'text'),
										GetSQLValueString($_POST['box-combustible'], 'text'),
										GetSQLValueString(isset($_POST['box-0km']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-importado']) ? 'true' : '', "defined",'1','0'),
										GetSQLValueString($_POST['box-nro_motor'], 'text'),
										GetSQLValueString($_POST['box-nro_chasis'], 'text'),
										GetSQLValueString($_POST['box-chapa'], 'text'),
										GetSQLValueString($_POST['box-pintura'], 'text'),
										GetSQLValueString($_POST['box-tipo_pintura'], 'text'),
										GetSQLValueString($_POST['box-tapizado'], 'text'),
										GetSQLValueString($_POST['box-color'], 'text'),
										GetSQLValueString(isset($_POST['box-accesorios']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-zona_riesgo'], 'text'),
										GetSQLValueString(isset($_POST['box-prendado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-acreedor_rs'], 'text'),
										GetSQLValueString($_POST['box-acreedor_cuit'], 'text'),
										GetSQLValueString(isset($_POST['box-infoauto']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-observaciones'], 'text'),										
										GetSQLValueString(isset($_POST['box-alarma']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-corta_corriente']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-corta_nafta']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-traba_volante']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-matafuego']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-tuercas']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-equipo_rastreo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-antena']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-estereo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-parlantes']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-aire']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cristales_electricos']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-faros_adicionales']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cierre_sincro']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-techo_corredizo']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-direccion_hidraulica']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-frenos_abs']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-airbag']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cristales_tonalizados']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-gps']) ? 'true' : '', 'defined','1','0'),										
										GetSQLValueString($_POST['box-cubiertas_medidas'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_marca'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_di'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_dd'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_ti'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_td'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_1ei'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_1ed'], 'text'),
										GetSQLValueString($_POST['box-cubiertas_desgaste_auxilio'], 'text'),
										GetSQLValueString($_POST['box-nro_oblea'], 'text'),
										GetSQLValueString($_POST['box-nro_regulador'], 'text'),
										GetSQLValueString($_POST['box-marca_regulador'], 'text'),
										GetSQLValueString($_POST['box-marca_cilindro'], 'text'),
										GetSQLValueString($_POST['box-venc_oblea'], 'date'),
										GetSQLValueString($_POST['box-nro_tubo'], 'text'),										
										GetSQLValueString($_POST['box-cobertura_tipo'], 'text'),
										GetSQLValueString($_POST['box-franquicia'], 'int'),
										GetSQLValueString($_POST['box-limite_rc'], 'text'),
										GetSQLValueString($_POST['box-servicio_grua'], 'int'),
										GetSQLValueString($_POST['box-valor_vehiculo'], 'int'),
										GetSQLValueString($_POST['box-valor_gnc'], 'int'),
										GetSQLValueString($_POST['box-valor_accesorios'], 'int'),
										GetSQLValueString($_POST['box-valor_total'], 'int'),										
										$row_Recordset2['automotor_id']);								
				$Result1 = mysql_query($updateSQL, $connection);				
			}		
			
			// Close Recordset: Automotor
			mysql_free_result($Recordset2);									
		
			// Break
			break;
			
		default:
			// ---------------------------------- UNDEFINED ---------------------------------- //		
			die("Error: Subtipo no habilitado.");
			break;
	}

	// Evaluate MySQL result
	switch (mysql_errno()) {
		case 0:
			echo "El registro ha sido insertado/actualizado con éxito.";
			break;
		case 1062:
			echo "Error: Registro duplicado.";
			break;
		default:
			mysql_die();
			break;
	}			
?>