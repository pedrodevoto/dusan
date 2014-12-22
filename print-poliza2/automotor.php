<?php
$sql = sprintf("SELECT * FROM automotor LEFT JOIN (automotor_tipo, seguro_cobertura_tipo, automotor_marca, seguro_cobertura_tipo_limite_rc, seguro_zona_riesgo) ON automotor.automotor_tipo_id = automotor_tipo.automotor_tipo_id AND automotor.seguro_cobertura_tipo_id = seguro_cobertura_tipo.seguro_cobertura_tipo_id and automotor.automotor_marca_id = automotor_marca.automotor_marca_id AND seguro_cobertura_tipo_limite_rc.seguro_cobertura_tipo_limite_rc_id = automotor.seguro_cobertura_tipo_limite_rc_id AND automotor.zona_riesgo_id = seguro_zona_riesgo.seguro_zona_riesgo_id LEFT JOIN producto ON producto.producto_id = automotor.producto_id LEFT JOIN automotor_modelo ON automotor.automotor_modelo_id = automotor_modelo.automotor_modelo_id LEFT JOIN automotor_version ON automotor.automotor_version_id = automotor_version.automotor_version_id WHERE automotor.poliza_id=%s", $row['poliza_id']);

$res2 = mysql_query($sql) or die(mysql_error());
$row2 = mysql_fetch_assoc($res2);
if (!$row2) {
	die("Error: detalle de Poliza no encontrado.");
}

$marca = $row2['automotor_marca_nombre'];
if (empty($row2['automotor_version_nombre'])) {
	if (empty($row2['automotor_modelo_nombre'])) {
		$modelo = $row2['modelo'];
	}
	else {
		$modelo = $row2['automotor_modelo_nombre'];
	}
}
else {
	$modelo = $row2['automotor_version_nombre'];
}
$auto_modelo = "$marca $modelo";

switch(substr($_GET['type'], 0, 2)) {
	case 'cc':
	
	// -------------------------- //
	// Constancia de cobertura    //
	// -------------------------- //
	
	$pdf = new FPDIW('P');
	$pdf->AddPage();
	$pdf->setSourceFile(sprintf('pdf/nuevos/cc_%s_automotor.pdf', isset($_GET['print'])?'print':'email'));
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	
	$pdf->wwrite(12, 45, sprintf('Compañía aseguradora: %s - Sucursal: %s', $row['seguro_nombre'], $row['sucursal_nombre']), 12, 'B');
	
	$pdf->wwrite(11, 60, sprintf('Nombre/Razón social: %s', $row['cliente_nombre']));

	$pdf->wwrite(11, 64, sprintf('Domicilio: %s %s %s %s', $row['contacto_domicilio'], $row['contacto_nro'], $row['contacto_piso'], $row['contacto_dpto']));
	$pdf->wwrite(100, 64, sprintf('CP: %s', $row['localidad_cp']));
	$pdf->wwrite(11, 68, sprintf('Localidad: %s', $row['localidad_nombre']));
	$pdf->wwrite(11, 72, sprintf('Teléfonos: %s / %s', $row['contacto_telefono1'], $row['contacto_telefono2']));
	$pdf->wwrite(11, 76, sprintf('Categoría de IVA: %s', $row['cliente_cf']));
	$pdf->wwrite(100, 76, sprintf('CUIT: %s%s%s', $row['cliente_cuit_0'], $row['cliente_cuit_1'], $row['cliente_cuit_2']));
	$pdf->wwrite(11, 80, sprintf('Fecha de nacimiento: %s', (!empty($row['cliente_nacimiento'])?strftime("%d/%m/%Y", strtotime($row['cliente_nacimiento'])):'')));
	$pdf->wwrite(100, 80, sprintf('DNI: %s', $row['cliente_nro_doc']));
	
	$pdf->wwrite(146, 60, sprintf('Tipo de seguro: AUTOMOTOR'));
	$pdf->wwrite(146, 64, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 68, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 72, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 76, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));
	
	$pdf->wwrite(26, 85.6, $row['cliente_email'], 12, 'B');
	
	$pdf->SetTextColor(255,255,255);
	$pdf->wwrite(30, 94, $auto_modelo, 8);
	$pdf->wwrite(166, 92, ($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'], 12, 'B');
	$pdf->SetTextColor(0,0,0);

	$pdf->wwrite(11, 101, sprintf('Tipo de vehículo: %s', $row2['automotor_tipo_nombre']));
	$pdf->wwrite(11, 106, sprintf('0KM: %s', formatCB($row2['0km'],'W')));
	$pdf->wwrite(11, 111, sprintf('Año: %s', $row2['ano']));
	$pdf->wwrite(11, 116, sprintf('Motor: %s', $row2['nro_motor']));
	$pdf->wwrite(11, 121, sprintf('Chásis: %s', $row2['nro_chasis']));
	
	$pdf->wwrite(100, 101, sprintf('Uso: %s', $row2['uso']));
	$pdf->wwrite(100, 106, sprintf('Importado: %s', formatCB($row2['importado'],'W')));
	$pdf->wwrite(100, 111, sprintf('Accesorios: %s', formatCB($row2['accesorios'], 'W')));
	$pdf->wwrite(100, 116, sprintf('Zona riesgo: %s', $row2['seguro_zona_riesgo_nombre']));
	$pdf->wwrite(100, 121, sprintf('Acreedor: %s', ($row2['prendado'] == 1 ? "Prendario (".$row2['acreedor_rs']." / CUIT: ".$row2['acreedor_cuit'].")" : "No")));
	
	// GNC
	
	$pdf->wwrite(11, 135, sprintf('Nro. oblea: %s', $row2['nro_oblea']));
	$pdf->wwrite(11, 139, sprintf('Nro. regulador: %s', $row2['nro_regulador']));
	
	$pdf->wwrite(80, 135, sprintf('Marca regulador: %s', $row2['marca_regulador']));
	$pdf->wwrite(80, 139, sprintf('Nro. cilindro: %s', $row2['marca_cilindro']));
	
	$pdf->wwrite(146, 135, sprintf('Venc. oblea: %s', (is_null($row2['venc_oblea']) ? "" : strftime("%d/%m/%Y", strtotime($row2['venc_oblea'])))));
	$pdf->wwrite(146, 139, sprintf('Nro. tubo: %s', $row2['nro_tubo']));
	
	// Sumas aseguradas
	
	$pdf->wwrite(11, 153, sprintf('Suma asegurada del vehículo:'));
	$pdf->wwrite(11, 157, 'Equipo GNC');
	$pdf->wwrite(11, 161, 'Accesorios');
	$pdf->wwrite(11, 165, sprintf('Ajuste %s%%', intval($row2['ajuste'])));
	$pdf->wwrite(11, 169, 'TOTAL');
	
	$txt_sumas_c2 = array(
		array('maxwidth' => 95, 'text' => formatNumber($row2['valor_vehiculo'])." "),
		array('maxwidth' => 95, 'text' => formatNumber($row2['valor_gnc'])." "),
		array('maxwidth' => 95, 'text' => formatNumber($row2['valor_accesorios'])." "),
		array('maxwidth' => 95, 'text' => ''),
		array('maxwidth' => 95, 'text' => formatNumber($row2['valor_total'])." ")
	);
	$pdf->SetXY(11, 156);
	foreach ($txt_sumas_c2 as $array) {
		printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
	}
	
	$txt_cobertura = ($row2['producto_id']>0?"Producto: ".$row2['producto_nombre']." | ":'')."Cobertura: ".$row2['seguro_cobertura_tipo_nombre']." | Límite RC: ".$row2['seguro_cobertura_tipo_limite_rc_valor']." | Franquicia: ".(!is_null($row2['franquicia']) ? "$ ".formatNumber($row2['franquicia'],0) : "-");
	
	$pdf->wwrite(35, 175, $txt_cobertura);
	$pdf->wwrite(11, 187, $row2['observaciones']);
	
	$pdf->wwrite(11, 202, sprintf('RECARGO: %s%%', formatNumber($row['poliza_recargo'])));
	$pdf->wwrite(11, 207, sprintf('DESCUENTO: %s%%', formatNumber($row['poliza_descuento'])));
	
	$pdf->wwrite(100, 202, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
	$pdf->wwrite(100, 207, sprintf('DESCUENTO: %s', $row['productor_seguro_codigo']));

	break;
	case 'pe':
	

	// -------------------------- //
	// Pedido de emisión / Endoso //
	// -------------------------- //
	
	$pdf = new FPDIW('P');
	$pdf->AddPage();
	$pdf->setSourceFile(sprintf('pdf/nuevos/%s_automotor.pdf', (!empty($_GET['en'])?'en':'pe')));
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	
	$size_emitir = 38;
	if ((isset($_GET['mc']) && $_GET['mc'] === "1") or $_GET['type']=='pemc') {
		$txt_emitir = "MC".($row['poliza_flota']==1?' FLOTA':'');
	} 
	elseif ((isset($_GET['re']) && $_GET['re'] === "1") or $_GET['type']=='pere') {
		$txt_emitir = "RENOVACIÓN".($row['poliza_flota']==1?' FLOTA':'');
	}
	elseif (isset($_GET['en']) && $_GET['en']==1) {
		$txt_emitir = "ENDOSO".($row['poliza_flota']==1?' FLOTA':'');
		if($endoso['anulacion']) {
			$size_emitir = 30;
			$txt_emitir = "ENDOSO - ANULACION".($row['poliza_flota']==1?' FLOTA':'');
		}
	} 
	else {
		$txt_emitir = "EMITIR".($row['poliza_flota']==1?' FLOTA':'');						
	}

	$pdf->wwrite(45, 1, $txt_emitir, $size_emitir);
	
	$pdf->wwrite(44.5, 15, $row['seguro_nombre'], 30);
	
	$pdf->wwrite(45, 30, sprintf('Código %s', $row['productor_seguro_codigo']), 12);
	
	$pdf->wwrite(11, 50, sprintf('Nombre/Razón social: %s', $row['cliente_nombre']));

	$pdf->wwrite(11, 54, sprintf('Domicilio: %s %s %s %s', $row['contacto_domicilio'], $row['contacto_nro'], $row['contacto_piso'], $row['contacto_dpto']));
	$pdf->wwrite(100, 54, sprintf('CP: %s', $row['localidad_cp']));
	$pdf->wwrite(11, 58, sprintf('Localidad: %s', $row['localidad_nombre']));
	$pdf->wwrite(11, 62, sprintf('Teléfonos: %s / %s', $row['contacto_telefono1'], $row['contacto_telefono2']));
	$pdf->wwrite(11, 66, sprintf('Categoría de IVA: %s', $row['cliente_cf']));
	$pdf->wwrite(100, 66, sprintf('CUIT: %s%s%s', $row['cliente_cuit_0'], $row['cliente_cuit_1'], $row['cliente_cuit_2']));
	$pdf->wwrite(11, 70, sprintf('Fecha de nacimiento: %s', (!empty($row['cliente_nacimiento'])?strftime("%d/%m/%Y", strtotime($row['cliente_nacimiento'])):'')));
	$pdf->wwrite(100, 70, sprintf('DNI: %s', $row['cliente_nro_doc']));

	$pdf->wwrite(146, 50, sprintf('Tipo de seguro: AUTOMOTOR'));
	$pdf->wwrite(146, 54, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 58, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 62, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 66, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));

	$pdf->wwrite(26, 75.6, $row['cliente_email'], 12, 'B');

	$pdf->SetTextColor(255,255,255);
	$pdf->wwrite(30, 84, $auto_modelo, 8);
	$pdf->wwrite(166, 82, ($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'], 12, 'B');
	$pdf->SetTextColor(0,0,0);

	$pdf->wwrite(11, 91, sprintf('Tipo de vehículo: %s', $row2['automotor_tipo_nombre']));
	$pdf->wwrite(11, 96, sprintf('0KM: %s', formatCB($row2['0km'],'W')));
	$pdf->wwrite(11, 101, sprintf('Año: %s', $row2['ano']));
	$pdf->wwrite(11, 106, sprintf('Motor: %s', $row2['nro_motor']));
	$pdf->wwrite(11, 111, sprintf('Chásis: %s', $row2['nro_chasis']));

	$pdf->wwrite(100, 91, sprintf('Uso: %s', $row2['uso']));
	$pdf->wwrite(100, 96, sprintf('Importado: %s', formatCB($row2['importado'],'W')));
	$pdf->wwrite(100, 101, sprintf('Accesorios: %s', formatCB($row2['accesorios'], 'W')));
	$pdf->wwrite(100, 106, sprintf('Zona riesgo: %s', $row2['seguro_zona_riesgo_nombre']));
	$pdf->wwrite(100, 111, sprintf('Acreedor: %s', ($row2['prendado'] == 1 ? "Prendario (".$row2['acreedor_rs']." / CUIT: ".$row2['acreedor_cuit'].")" : "No")));
	
	if (!empty($_GET['en'])) {
		$pdf->wwrite(11, 125, sprintf('Motivo del endoso: %s', $endoso['endoso_tipo_nombre']));
		$pdf->wwrite(11, 129, sprintf('Vigencia del endoso: %s a %s', date('d/m/Y', strtotime($endoso['endoso_fecha_pedido'])), date('d/m/Y', strtotime($row['poliza_validez_hasta']))));
		$pdf->SetXY(11, 136);
		$text = iconv('UTF-8', 'windows-1252', $endoso['endoso_cuerpo']);
		$pdf->MultiCell(190, 5, sprintf('Detalle: %s', $text), 0, 'L', 0, 20);
		
	}
	else {

		// Inspecciones del seguro

		$pdf->wwrite(11, 125, sprintf('Chapa: %s', $row2['chapa']));
		$pdf->wwrite(11, 129, sprintf('Pintura: %s', $row2['pintura']));
		$pdf->wwrite(11, 133, sprintf('Tipo pintura: %s', $row2['tipo_pintura']));
		$pdf->wwrite(11, 137, sprintf('Tapizado: %s', $row2['tapizado']));
		$pdf->wwrite(11, 141, sprintf('Combustible: %s', $row2['combustible']));
		$pdf->wwrite(11, 145, sprintf('Color: %s', $row2['color']));

		$pdf->wwrite(60, 132, sprintf('Alarma: %s', FormatCB($row2['alarma'],'X')));
		$pdf->wwrite(60, 136, sprintf("Corta Corriente: %s", FormatCB($row2['corta_corriente'],'X')));
		$pdf->wwrite(60, 140, sprintf("Corta Nafta: %s", FormatCB($row2['corta_nafta'],'X')));
		$pdf->wwrite(60, 144, sprintf("Traba Volante: %s", FormatCB($row2['traba_volante'],'X')));
		$pdf->wwrite(60, 148, sprintf("Matafuego: %s", FormatCB($row2['corta_corriente'],'X')));
		$pdf->wwrite(60, 152, sprintf("Tuercas: %s", FormatCB($row2['tuercas'],'X')));
		$pdf->wwrite(60, 156, sprintf("Antena: %s", FormatCB($row2['antena'],'X')));
		$pdf->wwrite(60, 160, sprintf("Estéreo: %s", FormatCB($row2['estereo'],'X')));

		$pdf->wwrite(90, 132, sprintf('Parlantes: %s', FormatCB($row2['parlantes'],'X')));
		$pdf->wwrite(90, 136, sprintf("Aire: %s", FormatCB($row2['aire'],'X')));
		$pdf->wwrite(90, 140, sprintf("C. Eléctricos: %s", FormatCB($row2['cristales_electricos'],'X')));
		$pdf->wwrite(90, 144, sprintf("Faros Adic: %s", FormatCB($row2['faros_adicionales'],'X')));
		$pdf->wwrite(90, 148, sprintf("Cierre Sincro: %s", FormatCB($row2['cierre_sincro'],'X')));
		$pdf->wwrite(90, 152, sprintf("Techo Corredizo: %s", FormatCB($row2['techo_corredizo'],'X')));
		$pdf->wwrite(90, 156, sprintf("Dir. Hidráulica: %s", FormatCB($row2['direccion_hidraulica'],'X')));
		$pdf->wwrite(90, 160, sprintf("Frenos ABS: %s", FormatCB($row2['frenos_abs'],'X')));

		$pdf->wwrite(120, 132, sprintf('Airbag: %s', FormatCB($row2['airbag'],'X')));
		$pdf->wwrite(120, 136, sprintf("C. Tonalizados: %s", FormatCB($row2['cristales_tonalizados'],'X')));
		$pdf->wwrite(120, 140, sprintf("Equipo Rastreo: %s", FormatCB($row2['equipo_rastreo_id'],'X')));
		$pdf->wwrite(120, 144, sprintf("Micro Grabado: %s", FormatCB($row2['micro_grabado'],'X')));
		$pdf->wwrite(120, 148, sprintf("GPS: %s", FormatCB($row2['gps'],'X')));

		$pdf->wwrite(152, 125, 'CUBIERTAS');
		$pdf->wwrite(152, 128, sprintf('Medida: %s', $row2['cubiertas_medida']));
		$pdf->wwrite(152, 131, sprintf('Marca: %s', $row2['cubiertas_marca']));
		$pdf->wwrite(152, 134, sprintf('Desgaste:'));
		$pdf->wwrite(152, 137, sprintf('Del. Izq: %s%%', FormatNumber($row2['cubiertas_desgaste_di'],0)));
		$pdf->wwrite(152, 140, sprintf('Del. Der: %s%%', FormatNumber($row2['cubiertas_desgaste_dd'],0)));
		$pdf->wwrite(152, 143, sprintf('Tra. Izq: %s%%', FormatNumber($row2['cubiertas_desgaste_ti'],0)));
		$pdf->wwrite(152, 146, sprintf('Del. Der: %s%%', FormatNumber($row2['cubiertas_desgaste_td'],0)));
		$pdf->wwrite(152, 149, sprintf('1E Izq: %s', (!is_null($row2['cubiertas_desgaste_1ei']) ? FormatNumber($row2['cubiertas_desgaste_1ei'],0)." %" : "-")));
		$pdf->wwrite(152, 152, sprintf('1E Der: %s', (!is_null($row2['cubiertas_desgaste_1ed']) ? FormatNumber($row2['cubiertas_desgaste_1ed'],0)." %" : "-")));
		$pdf->wwrite(152, 155, sprintf('Auxilio: %s%%', FormatNumber($row2['cubiertas_desgaste_auxilio'],0)));
	
		// GNC
	
		$pdf->wwrite(11, 178, sprintf('Nro. oblea: %s', $row2['nro_oblea']));
		$pdf->wwrite(11, 182, sprintf('Nro. regulador: %s', $row2['nro_regulador']));
	
		$pdf->wwrite(80, 178, sprintf('Marca regulador: %s', $row2['marca_regulador']));
		$pdf->wwrite(80, 182, sprintf('Nro. cilindro: %s', $row2['marca_cilindro']));
	
		$pdf->wwrite(146, 178, sprintf('Venc. oblea: %s', (is_null($row2['venc_oblea']) ? "" : strftime("%d/%m/%Y", strtotime($row2['venc_oblea'])))));
		$pdf->wwrite(146, 182, sprintf('Nro. tubo: %s', $row2['nro_tubo']));
	
		// Sumas aseguradas
	
		$pdf->wwrite(11, 196, sprintf('Suma asegurada del vehículo:'));
		$pdf->wwrite(11, 200, 'Equipo GNC');
		$pdf->wwrite(11, 204, 'Accesorios');
		$pdf->wwrite(11, 208, sprintf('Ajuste %s%%', intval($row2['ajuste'])));
		$pdf->wwrite(11, 212, 'TOTAL');
	
		$txt_sumas_c2 = array(
			array('maxwidth' => 95, 'text' => formatNumber($row2['valor_vehiculo'])." "),
			array('maxwidth' => 95, 'text' => formatNumber($row2['valor_gnc'])." "),
			array('maxwidth' => 95, 'text' => formatNumber($row2['valor_accesorios'])." "),
			array('maxwidth' => 95, 'text' => ''),
			array('maxwidth' => 95, 'text' => formatNumber($row2['valor_total'])." ")
		);
		$pdf->SetXY(11, 199);
		foreach ($txt_sumas_c2 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
		}
	
		$txt_cobertura = ($row2['producto_id']>0?"Producto: ".$row2['producto_nombre']." | ":'')."Cobertura: ".$row2['seguro_cobertura_tipo_nombre']." | Límite RC: ".$row2['seguro_cobertura_tipo_limite_rc_valor']." | Franquicia: ".(!is_null($row2['franquicia']) ? "$ ".formatNumber($row2['franquicia'],0) : "-");
	
		$pdf->wwrite(35, 218, $txt_cobertura);
		$pdf->wwrite(11, 230, $row2['observaciones']);
	
		$pdf->wwrite(11, 245, sprintf('Forma de pago: %s', $row['poliza_medio_pago']));
		$pdf->wwrite(11, 250, sprintf('Detalle de pago: %s', preg_replace('/\n/', ' ', $row['poliza_pago_detalle'])));
	
		$pdf->wwrite(80, 245, sprintf('Plan de pago: %s cuotas', ($row['poliza_cant_cuotas']+$row['cuota_pfc'])));
	
		$pdf->wwrite(148, 247, 'Prima');
		$pdf->wwrite(148, 251.5, 'Premio');
	
		$txt_imp_c2 = array(
			array('maxwidth' => 95, 'text' => "$ ".formatNumber($row['poliza_prima'])." "),
			array('maxwidth' => 95, 'text' => "$ ".formatNumber($row['poliza_premio'])." ")
		);

		$pdf->SetXY(149, 250);
		foreach ($txt_imp_c2 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
		}
	
		$pdf->wwrite(11, 258, sprintf('RECARGO: %s%%', formatNumber($row['poliza_recargo'])));
		$pdf->wwrite(11, 262, sprintf('DESCUENTO: %s%%', formatNumber($row['poliza_descuento'])));
	
		$pdf->wwrite(100, 258, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
		$pdf->wwrite(100, 262, sprintf('DESCUENTO: %s', $row['productor_seguro_codigo']));
	}
	break;
}

// OUTPUT
if (isset($_GET['email'])) {
	$cc = explode(',', urldecode($_GET['email']));
	$subject = $_GET['mail-subject'];
	$type = 0;
	switch(substr($_GET['type'], 0, 2)) {
		case 'cc':
		$to = $row['cliente_email'];
		$file_name = 'Constancia de cobertura.pdf';
		$body = TRUE;
		$type = 1;
		break;
		case 'pe':
		$to = $row['seguro_email_emision'];
		switch(substr($_GET['type'], 2)) {
			case '':
				$file_name = ($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'].'.pdf';
				$type = 2;
			break;
			case 'mc':
				$file_name = ($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'].'.pdf';
				$type = 3;
			break;
			case 're':
				$file_name = 'Renovacion '.($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'].'.pdf';
				$type = 4;
			break;
			case 'en':
				switch($_GET['enviar_a']) {
					case 'comp':
					default:
					$to = $row['seguro_email_endosos'];
					break;
					case 'clie':
					$to = $row['cliente_email'];
					break;
				}
				$file_name = ($endoso['anulacion']?'Anulacion':'Endoso')." PZA.".$row['poliza_numero'].".pdf";
				$type = 5;
			break;
		}
		break;
		default:
		$to = '';
		break;
	}
	$filename = 'temp/'.md5(microtime()).'.pdf';
	$pdf->Output($filename, 'F');
	$attachments = array();
	$attachments[] = array('file'=>$filename, 'name'=>$file_name, 'type'=>'application/pdf');
	echo send_mail($type, $poliza_id, $to, $subject, $body, $attachments, $cc);
}
else {
	$pdf->Output();
}
?>