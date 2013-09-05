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
				$insertSQL = sprintf("INSERT INTO automotor (poliza_id, automotor_marca_id, modelo, castigado, patente, automotor_tipo_id, uso, ano, automotor_carroceria_id, combustible, 0km, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, accesorios, zona_riesgo, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo_id, micro_grabado, cupon_vintrak, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, cobertura_tipo_id, franquicia, limite_rc, servicio_grua, valor_vehiculo, valor_gnc, valor_accesorios, valor_total) 
							          VALUES (%s, %s, UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), UPPER(TRIM(%s)), %s, UPPER(TRIM(%s)), %s, %s, %s, %s, %s, %s, %s, %s)",
										$poliza_id,												
										GetSQLValueString($_POST['box-automotor_marca_id'], 'int'),
										GetSQLValueString($_POST['box-modelo'], 'text'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente'], 'text'),
										GetSQLValueString($_POST['box-automotor_tipo_id'], 'int'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-automotor_carroceria_id'], 'int'),
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
										$_POST['box-equipo_rastreo_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_id'], 'int'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-cupon_vintrak'], 'text'),
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
										GetSQLValueString($_POST['box-cobertura_tipo_id'], 'int'),
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
										SET automotor_marca_id=%s, modelo=UPPER(TRIM(%s)), castigado=%s, patente=UPPER(TRIM(%s)), automotor_tipo_id=%s, uso=%s, ano=%s, automotor_carroceria_id=%s, combustible=%s, 0km=%s, importado=%s, nro_motor=UPPER(TRIM(%s)), nro_chasis=UPPER(TRIM(%s)), chapa=%s, pintura=%s, tipo_pintura=%s, tapizado=%s, color=UPPER(TRIM(%s)), accesorios=%s, zona_riesgo=%s, prendado=%s, acreedor_rs=UPPER(TRIM(%s)), acreedor_cuit=UPPER(TRIM(%s)), infoauto=%s, observaciones=%s, alarma=%s, corta_corriente=%s, corta_nafta=%s, traba_volante=%s, matafuego=%s, tuercas=%s, equipo_rastreo_id=%s, micro_grabado=%s, cupon_vintrak=%s, antena=%s, estereo=%s, parlantes=%s, aire=%s, cristales_electricos=%s, faros_adicionales=%s, cierre_sincro=%s, techo_corredizo=%s, direccion_hidraulica=%s, frenos_abs=%s, airbag=%s, cristales_tonalizados=%s, gps=%s, 
											cubiertas_medidas=UPPER(TRIM(%s)), cubiertas_marca=UPPER(TRIM(%s)), cubiertas_desgaste_di=%s, cubiertas_desgaste_dd=%s, cubiertas_desgaste_ti=%s, cubiertas_desgaste_td=%s, cubiertas_desgaste_1ei=%s, cubiertas_desgaste_1ed=%s, cubiertas_desgaste_auxilio=%s, nro_oblea=UPPER(TRIM(%s)), nro_regulador=UPPER(TRIM(%s)), marca_regulador=UPPER(TRIM(%s)), marca_cilindro=UPPER(TRIM(%s)), venc_oblea=%s, nro_tubo=UPPER(TRIM(%s)), cobertura_tipo_id=%s, franquicia=%s, limite_rc=%s, servicio_grua=%s, valor_vehiculo=%s, valor_gnc=%s, valor_accesorios=%s, valor_total=%s 
										WHERE automotor.automotor_id=%s",
										GetSQLValueString($_POST['box-automotor_marca_id'], 'int'),
										GetSQLValueString($_POST['box-modelo'], 'text'),
										GetSQLValueString(isset($_POST['box-castigado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-patente'], 'text'),
										GetSQLValueString($_POST['box-automotor_tipo_id'], 'text'),
										GetSQLValueString($_POST['box-uso'], 'text'),
										GetSQLValueString($_POST['box-ano'], 'int'),
										GetSQLValueString($_POST['box-automotor_carroceria_id'], 'int'),
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
										$_POST['box-equipo_rastreo_id'] == '' ? 'NULL' : 
											GetSQLValueString($_POST['box-equipo_rastreo_id'], 'int'),
										GetSQLValueString(isset($_POST['box-micro_grabado']) ? 'true' : '', 'defined','1','0'),
										GetSQLValueString($_POST['box-cupon_vintrak'], 'text'),
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
										GetSQLValueString($_POST['box-cobertura_tipo_id'], 'int'),
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
		
			// Foto micrograbado
			if ($_FILES['box-micrograbado_foto']['error'] == 0) {
				if ($micrograbado_foto = processFoto($_FILES['box-micrograbado_foto'], $poliza_id)){
					$sql = sprintf("INSERT INTO automotor_micrograbado_foto (poliza_id, automotor_micrograbado_foto_url, automotor_micrograbado_foto_thumb_url, automotor_micrograbado_foto_width, automotor_micrograbado_foto_height) VALUES (%s, '%s', '%s', %s, %s)", $poliza_id, $micrograbado_foto['filename'], $micrograbado_foto['thumb_filename'], $micrograbado_foto['width'], $micrograbado_foto['height']);
					mysql_query($sql, $connection) or die(mysql_error());
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
									GetSQLValueString($_POST['box-combinado_familiar_inc_edif'], 'double'),
									GetSQLValueString($_POST['box-combinado_familiar_rc_inc'], 'double'),
									GetSQLValueString($_POST['box-combinado_familiar_cristales'], 'double'),
									GetSQLValueString($_POST['box-combinado_familiar_responsabilidad_civil'], 'double'),
									GetSQLValueString($_POST['box-combinado_familiar_danios_agua'], 'double'),
									GetSQLValueString($_POST['box-combinado_familiar_jugadores_golf'], 'double'),
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
									GetSQLValueString($_POST['box-combinado_familiar_inc_mob'], 'double'),
									GetSQLValueString(isset($_POST['box-combinado_familiar_inc_mob_flag']) ? 'true' : '', 'defined','1','0'),
									GetSQLValueString($_POST['box-combinado_familiar_ef_personales'], 'double'),
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