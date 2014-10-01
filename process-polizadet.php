<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	require_once('inc/process-foto.php');
?>
<?php
	// Obtain URL parameter
	$poliza_id = intval($_POST['box-poliza_id']);
	$flota = $_POST['flota'];
	
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
			if ($totalRows_Recordset2 === 0 || $flota == 'new') {				
				// Insert
				$insertSQL = sprintf("INSERT INTO automotor (poliza_id, automotor_marca_id, automotor_modelo_id, automotor_version_id, castigado, patente_0, patente_1, automotor_tipo_id, uso, ano, automotor_carroceria_id, combustible, 0km, cert_rodamiento, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, zona_riesgo_id, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, equipo_rastreo_pedido_id, equipo_rastreo_id, micro_grabado, cupon_vintrak, cupon_vintrak_fecha, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, producto_id, seguro_cobertura_tipo_id, franquicia, seguro_cobertura_tipo_limite_rc_id, servicio_grua, valor_vehiculo, gnc_flag, valor_gnc, valor_accesorios, valor_total, pedido_instalacion, pedido_instalacion_direccion, pedido_instalacion_horario, pedido_instalacion_telefono, pedido_instalacion_observaciones, ajuste) 
							          VALUES (%s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s)",
										$poliza_id,												
										GetSQLValueString($_POST['box-automotor_marca_id'], 'int'),
										GetSQLValueString($_POST['box-automotor_modelo_id'], 'int'),
										GetSQLValueString($_POST['box-automotor_version_id'], 'int'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente_0'], 'text'),
										GetSQLValueString($_POST['box-patente_1'], 'text'),
										GetSQLValueString($_POST['box-automotor_tipo_id'], 'int'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-automotor_carroceria_id'], 'int'),
										GetSQLValueString($_POST['box-combustible'], 'text'),
										GetSQLValueString(isset($_POST['box-0km']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cert_rodamiento']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-importado']) ? 'true' : '', "defined",'1','0'),
										GetSQLValueString($_POST['box-nro_motor'], 'text'),
										GetSQLValueString($_POST['box-nro_chasis'], 'text'),
										GetSQLValueString($_POST['box-chapa'], 'text'),
										GetSQLValueString($_POST['box-pintura'], 'text'),
										GetSQLValueString($_POST['box-tipo_pintura'], 'text'),
										GetSQLValueString($_POST['box-tapizado'], 'text'),
										GetSQLValueString($_POST['box-color'], 'text'),
										GetSQLValueString($_POST['box-zona_riesgo_id'], 'int'),
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
										$_POST['box-equipo_rastreo_pedido_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_pedido_id'], 'int'),
										$_POST['box-equipo_rastreo_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_id'], 'int'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-cupon_vintrak'], 'text'),
										GetSQLValueString($_POST['box-cupon_vintrak_fecha'], 'date'),
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
										GetSQLValueString($_POST['box-producto_id'], 'int'),
										GetSQLValueString($_POST['box-seguro_cobertura_tipo_id'], 'int'),
										GetSQLValueString($_POST['box-franquicia'], 'int'),
										GetSQLValueString($_POST['box-seguro_cobertura_tipo_limite_rc_id'], 'text'),
										GetSQLValueString($_POST['box-servicio_grua'], 'int'),
										GetSQLValueString($_POST['box-valor_vehiculo'], 'int'),
										GetSQLValueString(isset($_POST['box-gnc_flag']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-valor_gnc'], 'int'),
										GetSQLValueString($_POST['box-valor_accesorios'], 'int'),
										GetSQLValueString($_POST['box-valor_total'], 'int'),
										GetSQLValueString(isset($_POST['box-pedido_instalacion']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-pedido_instalacion_direccion'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_horario'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_telefono'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_observaciones'], 'text'),
										GetSQLValueString($_POST['box-ajuste'], 'int'));
				$Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());														
				$automotor_id = mysql_insert_id();
			} else {
				// Update
				$updateSQL = sprintf("UPDATE automotor 
										SET automotor_marca_id=%s, automotor_modelo_id=%s, automotor_version_id=%s, castigado=%s, patente_0=UPPER(TRIM(%s)), patente_1=UPPER(TRIM(%s)), automotor_tipo_id=%s, uso=%s, ano=%s, automotor_carroceria_id=%s, combustible=%s, 0km=%s, cert_rodamiento=%s, importado=%s, nro_motor=UPPER(TRIM(%s)), nro_chasis=UPPER(TRIM(%s)), chapa=%s, pintura=%s, tipo_pintura=%s, tapizado=%s, color=UPPER(TRIM(%s)), zona_riesgo_id=%s, prendado=%s, acreedor_rs=UPPER(TRIM(%s)), acreedor_cuit=UPPER(TRIM(%s)), infoauto=%s, observaciones=%s, alarma=%s, corta_corriente=%s, corta_nafta=%s, traba_volante=%s, matafuego=%s, tuercas=%s, equipo_rastreo=%s, equipo_rastreo_pedido_id=%s, equipo_rastreo_id=%s, micro_grabado=%s, cupon_vintrak=%s, cupon_vintrak_fecha=%s, antena=%s, estereo=%s, parlantes=%s, aire=%s, cristales_electricos=%s, faros_adicionales=%s, cierre_sincro=%s, techo_corredizo=%s, direccion_hidraulica=%s, frenos_abs=%s, airbag=%s, cristales_tonalizados=%s, gps=%s, 
											cubiertas_medidas=UPPER(TRIM(%s)), cubiertas_marca=UPPER(TRIM(%s)), cubiertas_desgaste_di=%s, cubiertas_desgaste_dd=%s, cubiertas_desgaste_ti=%s, cubiertas_desgaste_td=%s, cubiertas_desgaste_1ei=%s, cubiertas_desgaste_1ed=%s, cubiertas_desgaste_auxilio=%s, nro_oblea=UPPER(TRIM(%s)), nro_regulador=UPPER(TRIM(%s)), marca_regulador=UPPER(TRIM(%s)), marca_cilindro=UPPER(TRIM(%s)), venc_oblea=%s, nro_tubo=UPPER(TRIM(%s)), producto_id=%s, seguro_cobertura_tipo_id=%s, franquicia=%s, seguro_cobertura_tipo_limite_rc_id=%s, servicio_grua=%s, valor_vehiculo=%s, gnc_flag=%s, valor_gnc=%s, valor_accesorios=%s, valor_total=%s, pedido_instalacion=%s, pedido_instalacion_direccion=UPPER(TRIM(%s)), pedido_instalacion_horario=UPPER(TRIM(%s)), pedido_instalacion_telefono=UPPER(TRIM(%s)), pedido_instalacion_observaciones=UPPER(TRIM(%s)), ajuste=%s
										WHERE automotor.automotor_id=%s",
										GetSQLValueString($_POST['box-automotor_marca_id'], 'int'),
										GetSQLValueString($_POST['box-automotor_modelo_id'], 'int'),
										GetSQLValueString($_POST['box-automotor_version_id'], 'int'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente_0'], 'text'),
										GetSQLValueString($_POST['box-patente_1'], 'text'),
										GetSQLValueString($_POST['box-automotor_tipo_id'], 'text'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-automotor_carroceria_id'], 'int'),
										GetSQLValueString($_POST['box-combustible'], 'text'),
										GetSQLValueString(isset($_POST['box-0km']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-cert_rodamiento']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-importado']) ? 'true' : '', "defined",'1','0'),
										GetSQLValueString($_POST['box-nro_motor'], 'text'),
										GetSQLValueString($_POST['box-nro_chasis'], 'text'),
										GetSQLValueString($_POST['box-chapa'], 'text'),
										GetSQLValueString($_POST['box-pintura'], 'text'),
										GetSQLValueString($_POST['box-tipo_pintura'], 'text'),
										GetSQLValueString($_POST['box-tapizado'], 'text'),
										GetSQLValueString($_POST['box-color'], 'text'),
										GetSQLValueString($_POST['box-zona_riesgo_id'], 'text'),
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
										$_POST['box-equipo_rastreo_pedido_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_pedido_id'], 'int'),
										$_POST['box-equipo_rastreo_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_id'], 'int'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-cupon_vintrak'], 'text'),
										GetSQLValueString($_POST['box-cupon_vintrak_fecha'], 'date'),
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
										GetSQLValueString($_POST['box-producto_id'], 'int'),
										GetSQLValueString($_POST['box-seguro_cobertura_tipo_id'], 'int'),
										GetSQLValueString($_POST['box-franquicia'], 'int'),
										GetSQLValueString($_POST['box-seguro_cobertura_tipo_limite_rc_id'], 'text'),
										GetSQLValueString($_POST['box-servicio_grua'], 'int'),
										GetSQLValueString($_POST['box-valor_vehiculo'], 'int'),
										GetSQLValueString(isset($_POST['box-gnc_flag']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-valor_gnc'], 'int'),
										GetSQLValueString($_POST['box-valor_accesorios'], 'int'),
										GetSQLValueString($_POST['box-valor_total'], 'int'),
										GetSQLValueString(isset($_POST['box-pedido_instalacion']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-pedido_instalacion_direccion'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_horario'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_telefono'], 'text'),
										GetSQLValueString($_POST['box-pedido_instalacion_observaciones'], 'text'),
										GetSQLValueString($_POST['box-ajuste'], 'int'),
										(intval($flota)>0?GetSQLValueString($flota, 'int'):$row_Recordset2['automotor_id']));								
				$Result1 = mysql_query($updateSQL, $connection) or die(mysql_error());	
				$automotor_id = $row_Recordset2['automotor_id'];	
			}		
			
			// Close Recordset: Automotor
			mysql_free_result($Recordset2);									
		
			// Fotos
			$types = array('micrograbado', 'gnc', 'cedula_verde', 'cert_rodamiento');
			foreach ($types as $type) {
			    if(isset($_FILES['box-'.$type.'_foto']['tmp_name'])){
					for ($i=0; $i < count($_FILES['box-'.$type.'_foto']['tmp_name']);$i++) {
						if ($_FILES['box-'.$type.'_foto']['error'][$i] == 0) {
							if ($photo = processFoto($_FILES['box-'.$type.'_foto'], $i)){
								$sql = sprintf('INSERT INTO automotor_%1$s_foto (poliza_id, automotor_%1$s_foto_url, automotor_%1$s_foto_thumb_url, automotor_%1$s_foto_width, automotor_%1$s_foto_height) VALUES (%2$s, \'%3$s\', \'%4$s\', %5$s, %6$s)', $type, $poliza_id, $photo['filename'], $photo['thumb_filename'], $photo['width'], $photo['height']);
								mysql_query($sql, $connection) or die(mysql_error());
							}
						}
					}
				}
			}
			
			$objects = array('accesorio');

			foreach ($objects as $object) {
				$deleteSQL = "DELETE FROM automotor_".$object." WHERE automotor_id = ".$automotor_id;
				mysql_query($deleteSQL);
				if (isset($_POST['box-automotor_'.$object])) {
					foreach ($_POST['box-automotor_'.$object] as $item) {
						if (isset($item['cantidad']) and isset($item['detalle']) and isset($item['valor'])){
							$insertSQL = sprintf('INSERT INTO automotor_%5$s (automotor_id, automotor_%5$s_cantidad, automotor_%5$s_detalle, automotor_%5$s_valor) VALUES (%1$s, %2$s, UPPER(TRIM(%3$s)), %4$s)',
												$automotor_id,												
												GetSQLValueString($item['cantidad'], 'int'),
												GetSQLValueString($item['detalle'], 'text'),
												GetSQLValueString($item['valor'], 'double'),
												$object);
							mysql_query($insertSQL) or die(mysql_error());					
						}
					}
				}
			}
			
			// Break
			break;
		case 'accidentes':
		// ---------------------------------- ACCIDENTES PERSONALES ---------------------------------- //
		
		// Recordset: Automotor
		$query_Recordset2 = sprintf("SELECT accidentes.accidentes_id FROM accidentes WHERE accidentes.poliza_id=%s", $poliza_id);
		$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
		$row_Recordset2 = mysql_fetch_assoc($Recordset2);		
		$totalRows_Recordset2 = mysql_num_rows($Recordset2);
		
		// If record exists
		if ($totalRows_Recordset2 === 0) {				
			// Insert
			$insertSQL = sprintf("INSERT INTO accidentes (poliza_id) VALUES (%s)", $poliza_id);
			$Result1 = mysql_query($insertSQL, $connection);
		}
		else {
			// Update
			
		}
		
			break;
		case 'combinado_familiar':
			// ---------------------------------- COMBINADO FAMILIAR ---------------------------------- //

			$upsertSQL = sprintf('INSERT INTO combinado_familiar (poliza_id, combinado_familiar_domicilio_calle, combinado_familiar_domicilio_nro, combinado_familiar_domicilio_piso, combinado_familiar_domicilio_dpto, combinado_familiar_domicilio_localidad, combinado_familiar_domicilio_cp, combinado_familiar_country, combinado_familiar_lote, combinado_familiar_valor_tasado, combinado_familiar_inc_edif, combinado_familiar_rc_inc,  combinado_familiar_cristales, combinado_familiar_responsabilidad_civil, combinado_familiar_danios_agua, combinado_familiar_jugadores_golf, combinado_familiar_inc_edif_flag, combinado_familiar_rc_inc_flag, combinado_familiar_tv_aud_vid_flag, combinado_familiar_obj_esp_prorrata_flag, combinado_familiar_equipos_computacion_flag, combinado_familiar_cristales_flag, combinado_familiar_responsabilidad_civil_flag, combinado_familiar_danios_agua_flag, combinado_familiar_jugadores_golf_flag, combinado_familiar_film_foto_flag, combinado_familiar_inc_mob, combinado_familiar_inc_mob_flag, combinado_familiar_ef_personales, combinado_familiar_ef_personales_flag, combinado_familiar_inc_edif_rep) 
						          VALUES (%1$s, UPPER(TRIM(%2$s)), UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), UPPER(TRIM(%6$s)), UPPER(TRIM(%7$s)), UPPER(TRIM(%8$s)), UPPER(TRIM(%9$s)), %10$s, %11$s, %12$s, %13$s, %14$s, %15$s, %16$s, %17$s, %18$s, %19$s, %20$s, %21$s, %22$s, %23$s, %24$s, %25$s, %26$s, %27$s, %28$s, %29$s, %30$s, %31$s) 
							  ON DUPLICATE KEY UPDATE combinado_familiar_domicilio_calle=UPPER(TRIM(%2$s)), combinado_familiar_domicilio_nro=UPPER(TRIM(%3$s)), combinado_familiar_domicilio_piso=UPPER(TRIM(%4$s)), combinado_familiar_domicilio_dpto=UPPER(TRIM(%5$s)), combinado_familiar_domicilio_localidad=UPPER(TRIM(%6$s)), combinado_familiar_domicilio_cp=UPPER(TRIM(%7$s)), combinado_familiar_country=UPPER(TRIM(%8$s)), combinado_familiar_lote=UPPER(TRIM(%9$s)), combinado_familiar_valor_tasado=%10$s, combinado_familiar_inc_edif=%11$s, combinado_familiar_rc_inc=%12$s, combinado_familiar_cristales=%13$s, combinado_familiar_responsabilidad_civil=%14$s, combinado_familiar_danios_agua=%15$s, combinado_familiar_jugadores_golf=%16$s, combinado_familiar_inc_edif_flag=%17$s, combinado_familiar_rc_inc_flag=%18$s, combinado_familiar_tv_aud_vid_flag=%19$s, combinado_familiar_obj_esp_prorrata_flag=%20$s, combinado_familiar_equipos_computacion_flag=%21$s, combinado_familiar_cristales_flag=%22$s, combinado_familiar_responsabilidad_civil_flag=%23$s, combinado_familiar_danios_agua_flag=%24$s, combinado_familiar_jugadores_golf_flag=%25$s, combinado_familiar_film_foto_flag=%26$s, combinado_familiar_inc_mob=%27$s, combinado_familiar_inc_mob_flag=%28$s, combinado_familiar_ef_personales=%29$s, combinado_familiar_ef_personales_flag=%30$s, combinado_familiar_inc_edif_rep=%31$s, combinado_familiar_id=LAST_INSERT_ID(combinado_familiar_id)',
									$poliza_id,												
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_calle'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_nro'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_piso'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_dpto'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_localidad'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_domicilio_cp'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_country'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_lote'], 'text'),
									GetSQLValueString($_POST['box-combinado_familiar_valor_tasado'], 'double'),
									isset ($_POST['box-combinado_familiar_inc_edif'])?
										GetSQLValueString($_POST['box-combinado_familiar_inc_edif'], 'double'):'NULL',
									isset ($_POST['box-combinado_familiar_rc_inc'])?
										GetSQLValueString($_POST['box-combinado_familiar_rc_inc'], 'double'):'NULL',
									isset ($_POST['box-combinado_familiar_cristales'])?
										GetSQLValueString($_POST['box-combinado_familiar_cristales'], 'double'):'NULL',
									isset ($_POST['box-combinado_familiar_responsabilidad_civil'])?
										GetSQLValueString($_POST['box-combinado_familiar_responsabilidad_civil'], 'double'):'NULL',
									isset ($_POST['box-combinado_familiar_danios_agua'])?
										GetSQLValueString($_POST['box-combinado_familiar_danios_agua'], 'double'):'NULL',
									isset ($_POST['box-combinado_familiar_jugadores_golf'])?
										GetSQLValueString($_POST['box-combinado_familiar_jugadores_golf'], 'double'):'NULL',
									GetSQLValueString(isset($_POST['box-combinado_familiar_inc_edif_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_rc_inc_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_tv_aud_vid_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_obj_esp_prorrata_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_equipos_computacion_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_cristales_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_responsabilidad_civil_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_danios_agua_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_jugadores_golf_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_film_foto_flag']) ? 'true' : '', 'defined','1','0'),
									isset ($_POST['box-combinado_familiar_inc_mob'])?
										GetSQLValueString($_POST['box-combinado_familiar_inc_mob'], 'double'):'NULL',
									GetSQLValueString(isset($_POST['box-combinado_familiar_inc_mob_flag']) ? 'true' : '', 'defined','1','0'),
									isset ($_POST['box-combinado_familiar_ef_personales'])?
										GetSQLValueString($_POST['box-combinado_familiar_ef_personales'], 'double'):'NULL',
									GetSQLValueString(isset($_POST['box-combinado_familiar_ef_personales_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_inc_edif_rep']) ? 'true' : '', 'defined','1','0'));

			$Result1 = mysql_query($upsertSQL, $connection) or die(mysql_error());				
			$combinado_familiar_id = mysql_insert_id();
			
			
			$objects = array('tv_aud_vid', 'obj_esp_prorrata', 'equipos_computacion', 'film_foto');
			
			foreach ($objects as $object) {
				$deleteSQL = "DELETE FROM combinado_familiar_".$object." WHERE combinado_familiar_id = ".$combinado_familiar_id;
				mysql_query($deleteSQL);
				if (isset($_POST['box-combinado_familiar_'.$object])) {
					foreach ($_POST['box-combinado_familiar_'.$object] as $item) {
						if (isset($item['cantidad']) and isset($item['producto']) and isset($item['marca']) and isset($item['valor'])){
							$insertSQL = sprintf('INSERT INTO combinado_familiar_%7$s (combinado_familiar_id, combinado_familiar_%7$s_cantidad, combinado_familiar_%7$s_producto, combinado_familiar_%7$s_marca, combinado_familiar_%7$s_serial, combinado_familiar_%7$s_valor) VALUES (%1$s, %2$s, UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), %6$s)',
												$combinado_familiar_id,												
												GetSQLValueString($item['cantidad'], 'int'),
												GetSQLValueString($item['producto'], 'text'),
												GetSQLValueString($item['marca'], 'text'),
												GetSQLValueString($item['serial'], 'text'),
												GetSQLValueString($item['valor'], 'double'),
												$object);
							mysql_query($insertSQL);					
						}
					}
				}
			}

									
		break;
		case 'incendio_edificio':
			// ---------------------------------- INCENDIO EDIFICIO ---------------------------------- //
			
			$upsertSQL = sprintf('INSERT INTO incendio_edificio (poliza_id, incendio_edificio_domicilio_calle, incendio_edificio_domicilio_nro, incendio_edificio_domicilio_piso, incendio_edificio_domicilio_dpto, incendio_edificio_domicilio_localidad, incendio_edificio_domicilio_cp, incendio_edificio_country, incendio_edificio_lote, incendio_edificio_valor_tasado, incendio_edificio_inc_edif, incendio_edificio_inc_edif_rep, incendio_edificio_inc_mob, incendio_edificio_rc_inc) 
				VALUES (%1$s, UPPER(TRIM(%2$s)), UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), UPPER(TRIM(%6$s)), UPPER(TRIM(%7$s)), UPPER(TRIM(%8$s)), UPPER(TRIM(%9$s)), %10$s, %11$s, %12$s, %13$s, %14$s)
				ON DUPLICATE KEY UPDATE incendio_edificio_domicilio_calle=UPPER(TRIM(%2$s)), incendio_edificio_domicilio_nro=UPPER(TRIM(%3$s)), incendio_edificio_domicilio_piso=UPPER(TRIM(%4$s)), incendio_edificio_domicilio_dpto=UPPER(TRIM(%5$s)), incendio_edificio_domicilio_localidad=UPPER(TRIM(%6$s)), incendio_edificio_domicilio_cp=UPPER(TRIM(%7$s)), incendio_edificio_country=UPPER(TRIM(%8$s)), incendio_edificio_lote=UPPER(TRIM(%9$s)), incendio_edificio_valor_tasado=%10$s, incendio_edificio_inc_edif=%11$s, incendio_edificio_inc_edif_rep=%12$s, incendio_edificio_inc_mob=%13$s, incendio_edificio_rc_inc=%14$s, incendio_edificio_id=LAST_INSERT_ID(incendio_edificio_id)',
				$poliza_id,												
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_calle'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_nro'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_piso'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_dpto'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_localidad'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_domicilio_cp'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_country'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_lote'], 'text'),
				GetSQLValueString($_POST['box-incendio_edificio_valor_tasado'], 'double'),
				GetSQLValueString($_POST['box-incendio_edificio_inc_edif'], 'double'),
				GetSQLValueString(isset($_POST['box-incendio_edificio_inc_edif_rep']) ? 'true' : '', 'defined','1','0'),
				GetSQLValueString($_POST['box-incendio_edificio_inc_mob'], 'double'),
				GetSQLValueString($_POST['box-incendio_edificio_rc_inc'], 'double'));
				$Result1 = mysql_query($upsertSQL, $connection);				
				$incendio_edificio_id = mysql_insert_id();
				
			break;	
			case 'integral_comercio':
				// ---------------------------------- INTEGRAL DE COMERCIO ---------------------------------- //

				$upsertSQL = sprintf('INSERT INTO integral_comercio (poliza_id, integral_comercio_domicilio_calle, integral_comercio_domicilio_nro, integral_comercio_domicilio_piso, integral_comercio_domicilio_dpto, integral_comercio_domicilio_localidad, integral_comercio_domicilio_cp, integral_comercio_actividad, integral_comercio_valor_tasado, integral_comercio_inc_edif, integral_comercio_inc_edif_rep, integral_comercio_bienes_de_uso_flag, integral_comercio_inc_contenido, integral_comercio_robo_pra, integral_comercio_cristales_pra, integral_comercio_rc_comprensiva, integral_comercio_rc_ascensor, integral_comercio_robo_matafuegos, integral_comercio_robo_lcm, integral_comercio_danios_agua, integral_comercio_rc_garage, integral_comercio_rc_lind) 
							          VALUES (%1$s, UPPER(TRIM(%2$s)), UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), UPPER(TRIM(%6$s)), UPPER(TRIM(%7$s)), UPPER(TRIM(%8$s)), %9$s, %10$s, %11$s, %12$s, %13$s, %14$s, %15$s, %16$s, %17$s, %18$s, %19$s, %20$s, %21$s, %22$s) 
								  ON DUPLICATE KEY UPDATE integral_comercio_domicilio_calle=UPPER(TRIM(%2$s)), integral_comercio_domicilio_nro=UPPER(TRIM(%3$s)), integral_comercio_domicilio_piso=UPPER(TRIM(%4$s)), integral_comercio_domicilio_dpto=UPPER(TRIM(%5$s)), integral_comercio_domicilio_localidad=UPPER(TRIM(%6$s)), integral_comercio_domicilio_cp=UPPER(TRIM(%7$s)), integral_comercio_actividad=UPPER(TRIM(%8$s)), integral_comercio_valor_tasado=%9$s, integral_comercio_inc_edif=%10$s, integral_comercio_inc_edif_rep=%11$s, integral_comercio_bienes_de_uso_flag=%12$s, integral_comercio_inc_contenido=%13$s, integral_comercio_robo_pra=%14$s, integral_comercio_cristales_pra=%15$s, integral_comercio_rc_comprensiva=%16$s, integral_comercio_rc_ascensor=%17$s, integral_comercio_robo_matafuegos=%18$s, integral_comercio_robo_lcm=%19$s, integral_comercio_danios_agua=%20$s, integral_comercio_rc_garage=%21$s, integral_comercio_rc_lind=%22$s, integral_comercio_id=LAST_INSERT_ID(integral_comercio_id)',
										$poliza_id,
										GetSQLValueString($_POST['box-integral_comercio_domicilio_calle'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_domicilio_nro'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_domicilio_piso'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_domicilio_dpto'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_domicilio_localidad'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_domicilio_cp'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_actividad'], 'text'),
										GetSQLValueString($_POST['box-integral_comercio_valor_tasado'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_inc_edif'], 'double'),
										GetSQLValueString(isset($_POST['box-integral_comercio_inc_edif_rep']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString(isset($_POST['box-integral_comercio_bienes_de_uso_flag']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-integral_comercio_inc_contenido'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_robo_pra'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_cristales_pra'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_rc_comprensiva'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_rc_ascensor'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_robo_matafuegos'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_robo_lcm'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_danios_agua'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_rc_garage'], 'double'),
										GetSQLValueString($_POST['box-integral_comercio_rc_lind'], 'double'));

				$Result1 = mysql_query($upsertSQL, $connection) or die(mysql_error());				
				$integral_comercio_id = mysql_insert_id();
			
			
				$objects = array('bienes_de_uso');
			
				foreach ($objects as $object) {
					$deleteSQL = "DELETE FROM integral_comercio_".$object." WHERE integral_comercio_id = ".$integral_comercio_id;
					mysql_query($deleteSQL);
					if (isset($_POST['box-integral_comercio_'.$object])) {
						foreach ($_POST['box-integral_comercio_'.$object] as $item) {
							if (isset($item['cantidad']) and isset($item['producto']) and isset($item['marca']) and isset($item['valor'])){
								$insertSQL = sprintf('INSERT INTO integral_comercio_%7$s (integral_comercio_id, integral_comercio_%7$s_cantidad, integral_comercio_%7$s_producto, integral_comercio_%7$s_marca, integral_comercio_%7$s_serial, integral_comercio_%7$s_valor) VALUES (%1$s, %2$s, UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), %6$s)',
													$integral_comercio_id,												
													GetSQLValueString($item['cantidad'], 'int'),
													GetSQLValueString($item['producto'], 'text'),
													GetSQLValueString($item['marca'], 'text'),
													GetSQLValueString($item['serial'], 'text'),
													GetSQLValueString($item['valor'], 'double'),
													$object);
								mysql_query($insertSQL);					
							}
						}
					}
				}

									
			break;
			case 'integral_consorcio':
				// ---------------------------------- INTEGRAL DE CONSORCIO ---------------------------------- //

				$upsertSQL = sprintf('INSERT INTO integral_consorcio (poliza_id, integral_consorcio_domicilio_calle, integral_consorcio_domicilio_nro, integral_consorcio_domicilio_piso, integral_consorcio_domicilio_dpto, integral_consorcio_domicilio_localidad, integral_consorcio_domicilio_cp, integral_consorcio_valor_tasado, integral_consorcio_inc_edif, integral_consorcio_inc_edif_rep, integral_consorcio_inc_contenido, integral_consorcio_robo_gral, integral_consorcio_robo_matafuegos, integral_consorcio_robo_lcm, integral_consorcio_rc_comprensiva, integral_consorcio_cristales, integral_consorcio_danios_agua, integral_consorcio_rc_garage, integral_consorcio_acc_personales, integral_consorcio_robo_exp)
							          VALUES (%1$s, UPPER(TRIM(%2$s)), UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)), UPPER(TRIM(%5$s)), UPPER(TRIM(%6$s)), UPPER(TRIM(%7$s)), UPPER(TRIM(%8$s)), %9$s, %10$s, %11$s, %12$s, %13$s, %14$s, %15$s, %16$s, %17$s, %18$s, %19$s, %20$s) 
								  ON DUPLICATE KEY UPDATE integral_consorcio_domicilio_calle=UPPER(TRIM(%2$s)), integral_consorcio_domicilio_nro=UPPER(TRIM(%3$s)), integral_consorcio_domicilio_piso=UPPER(TRIM(%4$s)), integral_consorcio_domicilio_dpto=UPPER(TRIM(%5$s)), integral_consorcio_domicilio_localidad=UPPER(TRIM(%6$s)), integral_consorcio_domicilio_cp=UPPER(TRIM(%7$s)), integral_consorcio_valor_tasado=%8$s, integral_consorcio_inc_edif=%9$s, integral_consorcio_inc_edif_rep=%10$s, integral_consorcio_inc_contenido=%11$s, integral_consorcio_robo_gral=%12$s, integral_consorcio_robo_matafuegos=%13$s, integral_consorcio_robo_lcm=%14$s, integral_consorcio_rc_comprensiva=%15$s, integral_consorcio_cristales=%16$s, integral_consorcio_danios_agua=%17$s, integral_consorcio_rc_garage=%18$s, integral_consorcio_acc_personales=%19$s, integral_consorcio_robo_exp=%20$s, integral_consorcio_id=LAST_INSERT_ID(integral_consorcio_id)',
										$poliza_id,
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_calle'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_nro'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_piso'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_dpto'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_localidad'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_domicilio_cp'], 'text'),
										GetSQLValueString($_POST['box-integral_consorcio_valor_tasado'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_inc_edif'], 'double'),
										GetSQLValueString(isset($_POST['box-integral_consorcio_inc_edif_rep']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-integral_consorcio_inc_contenido'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_robo_gral'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_robo_matafuegos'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_robo_lcm'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_rc_comprensiva'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_cristales'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_danios_agua'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_rc_garage'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_acc_personales'], 'double'),
										GetSQLValueString($_POST['box-integral_consorcio_robo_exp'], 'double'));

				$Result1 = mysql_query($upsertSQL, $connection) or die(mysql_error());				
				$integral_consorcio_id = mysql_insert_id();
				
			break;
		case 'otros_riesgos':
			// ---------------------------------- OTROS RIESGOS ---------------------------------- //
			$upsertSQL = sprintf('INSERT INTO otros_riesgos (poliza_id, otros_riesgos_riesgo, otros_riesgos_datos_riesgo, otros_riesgos_detalle_riesgo)
				VALUES (%1$s, UPPER(TRIM(%2$s)), UPPER(TRIM(%3$s)), UPPER(TRIM(%4$s)))
				ON DUPLICATE KEY UPDATE otros_riesgos_riesgo=UPPER(TRIM(%2$s)), otros_riesgos_datos_riesgo=UPPER(TRIM(%3$s)), otros_riesgos_detalle_riesgo=UPPER(TRIM(%4$s)), otros_riesgos_id=LAST_INSERT_ID(otros_riesgos_id)',
				$poliza_id,
				GetSQLValueString($_POST['box-otros_riesgos_riesgo'], 'text'),
				GetSQLValueString($_POST['box-otros_riesgos_datos_riesgo'], 'text'),
				GetSQLValueString($_POST['box-otros_riesgos_detalle_riesgo'], 'text'));
			$Result1 = mysql_query($upsertSQL, $connection) or die(mysql_error());
			$otros_riesgos_id = mysql_insert_id();
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