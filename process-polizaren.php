<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
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
		$insertSQL = sprintf("INSERT INTO poliza (sucursal_id, cliente_id, subtipo_poliza_id, poliza_estado_id, poliza_numero, poliza_renueva_num, productor_seguro_id, poliza_vigencia, poliza_vigencia_dias, poliza_validez_desde, poliza_validez_hasta, poliza_cuotas, poliza_cant_cuotas, poliza_fecha_solicitud, poliza_fecha_emision, poliza_fecha_recepcion, poliza_fecha_entrega, poliza_correo, poliza_email, poliza_entregada, poliza_prima, poliza_premio, poliza_medio_pago, poliza_pago_detalle, poliza_recargo, poliza_descuento, poliza_plan_flag, poliza_plan_id, poliza_pack_id, poliza_flota, timestamp)
		 					  (SELECT poliza.sucursal_id, poliza.cliente_id, poliza.subtipo_poliza_id, %s, TRIM(%s), poliza_numero, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW() FROM poliza WHERE poliza.poliza_id=%s)",
								GetSQLValueString($estado, "int"),
								GetSQLValueString($_POST['box-poliza_numero'], "text"),
								GetSQLValueString($_POST['box-productor_seguro_id'], "int"),
								GetSQLValueString($_POST['box-poliza_vigencia'], "text"),
								$_POST['box-poliza_vigencia']=='Otra'?
									GetSQLValueString($_POST['box-poliza_vigencia_dias'], "int"):'NULL',
								GetSQLValueString($poliza_validez_desde, "date"),
								GetSQLValueString($poliza_validez_hasta, "date"),
								GetSQLValueString($_POST['box-poliza_cuotas'], "text"),
								GetSQLValueString($poliza_cant_cuotas, "int"),
								GetSQLValueString($_POST['box-poliza_fecha_solicitud'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_emision'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_recepcion'], "date"),
								GetSQLValueString($_POST['box-poliza_fecha_entrega'], "date"),
								GetSQLValueString(isset($_POST['box-poliza_correo']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString(isset($_POST['box-poliza_email']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString(isset($_POST['box-poliza_entregada']) ? 'true' : '', 'defined','1','0'),
								GetSQLValueString($_POST['box-poliza_prima'], "double"),
								GetSQLValueString($poliza_premio, "double"),
								GetSQLValueString($_POST['box-poliza_medio_pago'], "text"),
								GetSQLValueString($poliza_pago_detalle, "text"),
								GetSQLValueString($_POST['box-poliza_recargo'], "double"),
								GetSQLValueString($_POST['box-poliza_descuento'], "int"),
								GetSQLValueString($_POST['box-poliza_plan_flag'], "int"),
								$_POST['box-poliza_plan_flag']=='1'? 
									GetSQLValueString($_POST['box-poliza_plan_id'], "int"):'NULL',
								$_POST['box-poliza_plan_flag']=='1'? 
									GetSQLValueString($_POST['box-poliza_pack_id'], "int"):'NULL',
								(isset($_POST['box-poliza_flota']) && $_POST['box-poliza_flota']=='1')?
									GetSQLValueString($_POST['box-poliza_flota'], "int"):'NULL',
								$row_Recordset1['poliza_id']);								
		$Result1 = mysql_query($insertSQL, $connection);
		
		// Evaluate insert
		switch (mysql_errno()) {
			case 0:

				// Get New ID
				$new_id = mysql_insert_id();				
				
				// Insert: Cuotas
				if ($_POST['box-poliza_medio_pago']=='Directo' && isset($_POST['box-cuota_monto']) && intval($_POST['box-cuota_monto']>0)) {
					$monto = intval($_POST['box-cuota_monto']);
				}
				else {
					$monto = $poliza_premio / $poliza_cant_cuotas;
				}
				
				$pfc = (isset($_POST['box-sucursal_pfc'])?1:0);
				$poliza_cant_cuotas += $pfc;
				
				for ($i=0; $i<$poliza_cant_cuotas; $i++) {
					$insertSQL = sprintf("INSERT INTO cuota (poliza_id, cuota_nro, cuota_periodo, cuota_monto, cuota_vencimiento, cuota_estado_id, cuota_pfc) VALUES (%s, %s, DATE_FORMAT(DATE_ADD(%s, INTERVAL %s MONTH),'%%Y-%%m-01'), %s, DATE_ADD(%s, INTERVAL %s MONTH), 1, %s)",
									GetSQLValueString($new_id, "int"),
									GetSQLValueString($i+1, "int"),
									GetSQLValueString($poliza_validez_desde, "date"),									
									GetSQLValueString($i, "int"),									
									GetSQLValueString($monto, "double"),
									GetSQLValueString($poliza_validez_desde, "date"),
									GetSQLValueString($i, "int"),
									(($i==0 and $pfc==1)?'1':'0'));
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

				while ($row = mysql_fetch_array($Recordset3)) {
					$insertSQL = sprintf("INSERT INTO automotor (poliza_id, automotor_marca_id, automotor_modelo_id, automotor_version_id, modelo, patente_0, patente_1, automotor_tipo_id, uso, ano, automotor_carroceria_id, combustible, 0km, cert_rodamiento, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, zona_riesgo_id, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, equipo_rastreo_pedido_id, equipo_rastreo_id, micro_grabado, cupon_vintrak, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, producto_id, seguro_cobertura_tipo_id, franquicia, seguro_cobertura_tipo_limite_rc_id, servicio_grua, valor_vehiculo, gnc_flag, valor_gnc, valor_accesorios, valor_total, ajuste) 
										  (SELECT %s, automotor_marca_id, automotor_modelo_id, automotor_version_id, modelo, patente_0, patente_1, automotor_tipo_id, uso, ano, automotor_carroceria_id, combustible, 0km, cert_rodamiento, importado, nro_motor, nro_chasis, chapa, pintura, tipo_pintura, tapizado, color, zona_riesgo_id, prendado, acreedor_rs, acreedor_cuit, infoauto, observaciones, alarma, corta_corriente, corta_nafta, traba_volante, matafuego, tuercas, equipo_rastreo, equipo_rastreo_pedido_id, equipo_rastreo_id, micro_grabado, cupon_vintrak, antena, estereo, parlantes, aire, cristales_electricos, faros_adicionales, cierre_sincro, techo_corredizo, direccion_hidraulica, frenos_abs, airbag, cristales_tonalizados, gps, cubiertas_medidas, cubiertas_marca, cubiertas_desgaste_di, cubiertas_desgaste_dd, cubiertas_desgaste_ti, cubiertas_desgaste_td, cubiertas_desgaste_1ei, cubiertas_desgaste_1ed, cubiertas_desgaste_auxilio, nro_oblea, nro_regulador, marca_regulador, marca_cilindro, venc_oblea, nro_tubo, producto_id, seguro_cobertura_tipo_id, franquicia, seguro_cobertura_tipo_limite_rc_id, servicio_grua, null, gnc_flag, valor_gnc, valor_accesorios, valor_total, ajuste FROM automotor WHERE automotor_id=%s)",
											$new_id,
											$row[0]);																		
					$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
					
					$automotor_id = mysql_insert_id();
					
					if ($automotor_id) {
						
						$types = array('automotor', 'automotor_micrograbado', 'automotor_gnc', 'automotor_cedula_verde');
						foreach ($types as $type) {
							$sql = sprintf('INSERT INTO %1$s_foto (automotor_id, %1$s_foto_url, %1$s_foto_thumb_url, %1$s_foto_width, %1$s_foto_height) SELECT %2$s, %1$s_foto_url, %1$s_foto_thumb_url, %1$s_foto_width, %1$s_foto_height FROM %1$s_foto WHERE automotor_id=%3$s', $type, $automotor_id, $row[0]);
							mysql_query($sql, $connection) or die(mysql_error());
						}
					}
					
				}

				// Break
				break;
				
			case 'accidentes':
			
			
			// ---------------------------------- ACCIDENTES ---------------------------------- //
			
			// Recordset: Accidentes
			$query_Recordset3 = sprintf("SELECT accidentes.accidentes_id FROM accidentes WHERE accidentes.poliza_id=%s",
								$row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$totalRows_Recordset3 = mysql_num_rows($Recordset3);
			mysql_free_result($Recordset3);

			// If Record exists, insert
			if ($totalRows_Recordset3 === 1) {
				
				// Copiar Asegurados
				$insertSQL = sprintf("INSERT INTO accidentes_asegurado (poliza_id, accidentes_asegurado_nombre, accidentes_asegurado_documento, accidentes_asegurado_nacimiento, accidentes_asegurado_domicilio, accidentes_asegurado_actividad, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, accidentes_asegurado_beneficiario, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento)
					 (SELECT %s, accidentes_asegurado_nombre, accidentes_asegurado_documento, accidentes_asegurado_nacimiento, accidentes_asegurado_domicilio, accidentes_asegurado_actividad, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, accidentes_asegurado_beneficiario, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento FROM accidentes_asegurado WHERE poliza_id=%s)",
										$new_id,
										$row_Recordset1['poliza_id']);																		
				$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				
				// Copiar Clausulas de No Repeticion
				$insertSQL = sprintf("INSERT INTO accidentes_clausula (poliza_id, accidentes_clausula_nombre, accidentes_clausula_cuit, accidentes_clausula_domicilio)
					 (SELECT %s, accidentes_clausula_nombre, accidentes_clausula_cuit, accidentes_clausula_domicilio FROM accidentes_clausula WHERE poliza_id=%s)",
										$new_id,
										$row_Recordset1['poliza_id']);																		
				$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
				
				// Insertar nuevo accidente
				$insertSQL = sprintf("INSERT INTO accidentes (poliza_id) VALUES (%s)",
										$new_id);																		
				$Result1 = mysql_query($insertSQL, $connection) or die(mysql_die());
			}																						
			
			// Break
			break;
			// ---------------------------------- COMBINADO FAMILIAR ---------------------------------- //
			
			case 'combinado_familiar':
				$insertSQL = sprintf('INSERT INTO combinado_familiar (poliza_id, combinado_familiar_domicilio_calle, combinado_familiar_domicilio_nro, combinado_familiar_domicilio_piso, combinado_familiar_domicilio_dpto, combinado_familiar_domicilio_localidad, combinado_familiar_domicilio_cp, combinado_familiar_country, combinado_familiar_lote, combinado_familiar_valor_tasado, combinado_familiar_inc_edif, combinado_familiar_inc_edif_rep, combinado_familiar_rc_inc, combinado_familiar_cristales, combinado_familiar_responsabilidad_civil, combinado_familiar_danios_agua, combinado_familiar_jugadores_golf, combinado_familiar_inc_edif_flag, combinado_familiar_rc_inc_flag, combinado_familiar_tv_aud_vid_flag, combinado_familiar_obj_esp_prorrata_flag, combinado_familiar_equipos_computacion_flag, combinado_familiar_cristales_flag, combinado_familiar_responsabilidad_civil_flag, combinado_familiar_danios_agua_flag, combinado_familiar_jugadores_golf_flag, combinado_familiar_film_foto_flag, combinado_familiar_inc_mob, combinado_familiar_inc_mob_flag, combinado_familiar_ef_personales, combinado_familiar_ef_personales_flag) 
					(SELECT %s, combinado_familiar_domicilio_calle, combinado_familiar_domicilio_nro, combinado_familiar_domicilio_piso, combinado_familiar_domicilio_dpto, combinado_familiar_domicilio_localidad, combinado_familiar_domicilio_cp, combinado_familiar_country, combinado_familiar_lote, combinado_familiar_valor_tasado, combinado_familiar_inc_edif, combinado_familiar_inc_edif_rep, combinado_familiar_rc_inc, combinado_familiar_cristales, combinado_familiar_responsabilidad_civil, combinado_familiar_danios_agua, combinado_familiar_jugadores_golf, combinado_familiar_inc_edif_flag, combinado_familiar_rc_inc_flag, combinado_familiar_tv_aud_vid_flag, combinado_familiar_obj_esp_prorrata_flag, combinado_familiar_equipos_computacion_flag, combinado_familiar_cristales_flag, combinado_familiar_responsabilidad_civil_flag, combinado_familiar_danios_agua_flag, combinado_familiar_jugadores_golf_flag, combinado_familiar_film_foto_flag, combinado_familiar_inc_mob, combinado_familiar_inc_mob_flag, combinado_familiar_ef_personales, combinado_familiar_ef_personales_flag FROM combinado_familiar WHERE poliza_id=%s)',
					$new_id,
					$row_Recordset1['poliza_id']);
				
				
				mysql_query($insertSQL) or die(mysql_error());
				
				$combinado_familiar_id = mysql_insert_id();
				if ($combinado_familiar_id > 0) {
					$sql = "SELECT combinado_familiar_id FROM combinado_familiar WHERE poliza_id = ".$row_Recordset1['poliza_id'];
					$res = mysql_query($sql);
					list($combinado_familiar_id_old) = mysql_fetch_array($res);
					if ($combinado_familiar_id_old > 0) {
						
						$objects = array('tv_aud_vid', 'obj_esp_prorrata', 'equipos_computacion', 'film_foto');
						
						foreach ($objects as $object) {
							$insertSQL = sprintf('INSERT INTO combinado_familiar_%3$s (combinado_familiar_id, combinado_familiar_%3$s_cantidad, combinado_familiar_%3$s_producto, combinado_familiar_%3$s_marca, combinado_familiar_%3$s_valor)
								 (SELECT %1$s, combinado_familiar_%3$s_cantidad, combinado_familiar_%3$s_producto, combinado_familiar_%3$s_marca, combinado_familiar_%3$s_valor FROM combinado_familiar_%3$s WHERE combinado_familiar_id=%2$s)',
													$combinado_familiar_id,
													$combinado_familiar_id_old,
													$object);	
																						
							mysql_query($insertSQL, $connection) or die(mysql_die());
						}	
					}
				}
				
				break;
			
			case 'incendio_edificio':
				// ---------------------------------- INCENDIO EDIFICIO ---------------------------------- //
				
				$insertSQL = sprintf('INSERT INTO incendio_edificio (poliza_id, incendio_edificio_domicilio_calle, incendio_edificio_domicilio_nro, incendio_edificio_domicilio_piso, incendio_edificio_domicilio_dpto, incendio_edificio_domicilio_localidad, incendio_edificio_domicilio_cp, incendio_edificio_country, incendio_edificio_lote, incendio_edificio_valor_tasado, incendio_edificio_inc_edif, incendio_edificio_inc_edif_rep, incendio_edificio_inc_mob, incendio_edificio_rc_inc) 
					(SELECT %s, incendio_edificio_domicilio_calle, incendio_edificio_domicilio_nro, incendio_edificio_domicilio_piso, incendio_edificio_domicilio_dpto, incendio_edificio_domicilio_localidad, incendio_edificio_domicilio_cp, incendio_edificio_country, incendio_edificio_lote, incendio_edificio_valor_tasado, incendio_edificio_inc_edif, incendio_edificio_inc_edif_rep, incendio_edificio_inc_mob, incendio_edificio_rc_inc FROM incendio_edificio WHERE poliza_id=%s)',
					$new_id,
					$row_Recordset1['poliza_id']);
					mysql_query($insertSQL) or die(mysql_error());
				break;
				
				case 'integral_comercio':
					// ---------------------------------- INTEGRAL COMERCIO ---------------------------------- //
					
				$insertSQL = sprintf('INSERT INTO integral_comercio (poliza_id, integral_comercio_domicilio_calle, integral_comercio_domicilio_nro, integral_comercio_domicilio_piso, integral_comercio_domicilio_dpto, integral_comercio_domicilio_localidad, integral_comercio_domicilio_cp, integral_comercio_actividad, integral_comercio_valor_tasado, integral_comercio_inc_edif, integral_comercio_inc_edif_rep, integral_comercio_bienes_de_uso_flag, integral_comercio_inc_contenido, integral_comercio_robo_pra, integral_comercio_cristales_pra, integral_comercio_rc_comprensiva, integral_comercio_rc_ascensor, integral_comercio_robo_matafuegos, integral_comercio_robo_lcm, integral_comercio_danios_agua, integral_comercio_rc_garage, integral_comercio_rc_lind) 
					(SELECT %s, integral_comercio_domicilio_calle, integral_comercio_domicilio_nro, integral_comercio_domicilio_piso, integral_comercio_domicilio_dpto, integral_comercio_domicilio_localidad, integral_comercio_domicilio_cp, integral_comercio_actividad, integral_comercio_valor_tasado, integral_comercio_inc_edif, integral_comercio_inc_edif_rep, integral_comercio_bienes_de_uso_flag, integral_comercio_inc_contenido, integral_comercio_robo_pra, integral_comercio_cristales_pra, integral_comercio_rc_comprensiva, integral_comercio_rc_ascensor, integral_comercio_robo_matafuegos, integral_comercio_robo_lcm, integral_comercio_danios_agua, integral_comercio_rc_garage, integral_comercio_rc_lind FROM integral_comercio WHERE poliza_id=%s)',
					$new_id,
					$row_Recordset1['poliza_id']);
				
				
					mysql_query($insertSQL) or die(mysql_error());
				
					$integral_comercio_id = mysql_insert_id();
					if ($integral_comercio_id > 0) {
						$sql = "SELECT integral_comercio_id FROM integral_comercio WHERE poliza_id = ".$row_Recordset1['poliza_id'];
						$res = mysql_query($sql);
						list($integral_comercio_id_old) = mysql_fetch_array($res);
						if ($integral_comercio_id_old > 0) {
						
							$objects = array('bienes_de_uso');
						
							foreach ($objects as $object) {
								$insertSQL = sprintf('INSERT INTO integral_comercio_%3$s (integral_comercio_id, integral_comercio_%3$s_cantidad, integral_comercio_%3$s_producto, integral_comercio_%3$s_marca, integral_comercio_%3$s_valor)
									 (SELECT %1$s, integral_comercio_%3$s_cantidad, integral_comercio_%3$s_producto, integral_comercio_%3$s_marca, integral_comercio_%3$s_valor FROM integral_comercio_%3$s WHERE integral_comercio_id=%2$s)',
														$integral_comercio_id,
														$integral_comercio_id_old,
														$object);	
																						
								mysql_query($insertSQL, $connection) or die(mysql_die());
							}	
						}
					}
					break;
				case 'integral_consorcio':
					// ---------------------------------- INTEGRAL CONSORCIO ---------------------------------- //
				
				$insertSQL = sprintf('INSERT INTO integral_consorcio (poliza_id, integral_consorcio_domicilio_calle, integral_consorcio_domicilio_nro, integral_consorcio_domicilio_piso, integral_consorcio_domicilio_dpto, integral_consorcio_domicilio_localidad, integral_consorcio_domicilio_cp, integral_consorcio_valor_tasado, integral_consorcio_inc_edif, integral_consorcio_inc_edif_rep, integral_consorcio_inc_contenido, integral_consorcio_robo_gral, integral_consorcio_robo_matafuegos, integral_consorcio_robo_lcm, integral_consorcio_rc_comprensiva, integral_consorcio_cristales, integral_consorcio_danios_agua, integral_consorcio_rc_garage, integral_consorcio_acc_personales, integral_consorcio_robo_exp)
					(SELECT %s, integral_consorcio_domicilio_calle, integral_consorcio_domicilio_nro, integral_consorcio_domicilio_piso, integral_consorcio_domicilio_dpto, integral_consorcio_domicilio_localidad, integral_consorcio_domicilio_cp, integral_consorcio_valor_tasado, integral_consorcio_inc_edif, integral_consorcio_inc_edif_rep, integral_consorcio_inc_contenido, integral_consorcio_robo_gral, integral_consorcio_robo_matafuegos, integral_consorcio_robo_lcm, integral_consorcio_rc_comprensiva, integral_consorcio_cristales, integral_consorcio_danios_agua, integral_consorcio_rc_garage, integral_consorcio_acc_personales, integral_consorcio_robo_exp FROM integral_consorcio WHERE poliza_id=%s)',
					$new_id,
					$row_Recordset1['poliza_id']);
					
					mysql_query($insertSQL) or die(mysql_error());
					
					$integral_consorcio_id = mysql_insert_id();
					break;
				case 'otros_riesgos':
					$insertSQL = sprintf('INSERT INTO otros_riesgos (poliza_id, otros_riesgos_riesgo, otros_riesgos_datos_riesgo, otros_riesgos_detalle_riesgo)
					(SELECT %s, otros_riesgos_riesgo, otros_riesgos_datos_riesgo, otros_riesgos_detalle_riesgo FROM otros_riesgos WHERE poliza_id=%s)',
					$new_id,
					$row_Recordset1['poliza_id']);
					
					mysql_query($insertSQL) or die(mysql_error());
					
					$otros_riesgos_id = mysql_insert_id();
					break;
			default:
				// ---------------------------------- UNDEFINED ---------------------------------- //
				die("Error: Subtipo no habilitado.");
				break;
		}
		
		/****************************************
		 * UPDATE ORIGINAL
		 ****************************************/
		
		// determine state
		$sql = sprintf("SELECT DATEDIFF(NOW(),poliza_validez_desde) AS startdiff, DATEDIFF(NOW(),poliza_validez_hasta) AS enddiff FROM poliza WHERE poliza_id = %s", $poliza_id);
		$res = mysql_query($sql);
		list($startdiff, $enddiff) = mysql_fetch_array($res);
		$state = determineState($startdiff, $enddiff);
		$new_state = ($state==3 || $state==4)?7:5; // si la poliza original estaba vigente, pasa a estar VIGENTE/RENOVADA. si no, directamente pasa a RENOVADA
			
		// Update
		$updateSQL = sprintf("UPDATE poliza SET poliza_estado_id=%s WHERE poliza.poliza_id=%s LIMIT 1",
						$new_state,
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