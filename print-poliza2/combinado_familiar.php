<?php
$sql = sprintf("SELECT *  FROM combinado_familiar WHERE poliza_id=%s", $row['poliza_id']);
$res = mysql_query($sql) or die(mysql_error());
$row2 = mysql_fetch_assoc($res);

$objects = array(
	array('db'=>'tv_aud_vid', 'desc'=>'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto'),
	array('db'=>'obj_esp_prorrata', 'desc'=>'Robo y/o Hurto de Objetos Especificos y/o Aparatos Electrodomesticos a Prorrata'),
	array('db'=>'equipos_computacion', 'desc'=>'Todo Riesgo Equipos de Computacion en Domicilio a Primer Riesgo Absoluto'),
	array('db'=>'film_foto', 'desc'=>'Robo de Filmadoras y/o Cam. Fotogrficas a Prorrata')
);

for ($i = 0; $i < count($objects); $i++) {
	$objects[$i]['items'] = array();
	$sql = sprintf('SELECT combinado_familiar_%1$s_cantidad as cantidad, combinado_familiar_%1$s_producto as producto, combinado_familiar_%1$s_marca as marca, combinado_familiar_%1$s_serial as serial_no, combinado_familiar_%1$s_valor as valor FROM combinado_familiar_%1$s WHERE combinado_familiar_id=%2$s', 
		$objects[$i]['db'], 
		$row2['combinado_familiar_id']);
	
	$res3 = mysql_query($sql, $connection) or die(mysql_error());
	while($row3 = mysql_fetch_assoc($res3)) {
		// for($j=0;$j<15;$j++) {
			// $row['producto'] .= ' '.$j;
			$objects[$i]['items'][] = $row3;
		// }
	}
}

$detalle_plan = array();
if ($row['poliza_plan_flag']) {
	$sql = 'SELECT poliza_pack_detalle_cobertura, poliza_pack_detalle_valor FROM poliza_pack_detalle WHERE poliza_pack_id = '.$row['poliza_pack_id'];
	$res3 = mysql_query($sql, $connection) or die(mysql_error());
	while($row3 = mysql_fetch_array($res3)) {
		$detalle_plan[] = array('cobertura'=>$row3[0], 'valor'=>$row3[1]);
	}
}

switch(substr($_GET['type'], 0, 2)) {
	case 'cc':
	// -------------------------- //
	// Constancia de cobertura    //
	// -------------------------- //
	
	$pdf = new FPDIW('P');
	function newPage($pdf, $first) {
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();
		if ($first) {
			$pdf->setSourceFile(sprintf('pdf/nuevos/cc_%s_otros.pdf', isset($_GET['print'])?'print':'email'));
		}
		else {
			$pdf->setSourceFile(sprintf('pdf/nuevos/cc_blank.pdf'));
		}
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);
	}
	newPage($pdf, true);
	
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
	
	$pdf->wwrite(146, 60, sprintf('Tipo de seguro: COMBINADO FAMILIAR'), 8);
	$pdf->wwrite(146, 64, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 68, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 72, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 76, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));
	
	$pdf->wwrite(26, 85.6, $row['cliente_email'], 12, 'B');
	
	$pdf->wwrite(13, 97, sprintf('Dirección: %s %s', $row2['combinado_familiar_domicilio_calle'], $row2['combinado_familiar_domicilio_nro']));

	$pdf->wwrite(100, 97, sprintf('Piso/Dpto: %s %s', $row2['combinado_familiar_domicilio_piso'], $row2['combinado_familiar_domicilio_dpto']));
	
	$pdf->wwrite(130, 97, sprintf('Localidad: %s', $row2['combinado_familiar_domicilio_localidad']));
	$pdf->wwrite(180, 97, sprintf('CP: %s', $row2['combinado_familiar_domicilio_cp']));
	
	
	$pdf->wwrite(13, 102, sprintf('Barrio cerrado/country: %s', $row2['combinado_familiar_country']));
	$pdf->wwrite(100, 102, sprintf('Lote: %s', $row2['combinado_familiar_lote']));
		

	$pdf->wwrite(13, 107, sprintf('Incendio Edificio: $%s', formatNumber($row2['combinado_familiar_inc_edif'], 2)));
	$pdf->wwrite(79, 107, sprintf('Incendio Mobiliario: $%s', formatNumber($row2['combinado_familiar_inc_mob'], 2)));
	$pdf->wwrite(149, 107, sprintf('Efectos Personales: $%s', formatNumber($row2['combinado_familiar_ef_personales'], 2)));
	
	$pdf->wwrite(13, 112, sprintf('Valor tasado de la propiedad: $%s', formatNumber($row2['combinado_familiar_valor_tasado'], 2)));
	
	$pdf->wwrite(11, 202, sprintf('Forma de pago: %s', $row['poliza_medio_pago']));
	$pdf->wwrite(11, 207, sprintf('Detalle de pago: %s', preg_replace('/\n/', ' ', $row['poliza_pago_detalle'])));

	$pdf->wwrite(80, 202, sprintf('Plan de pago: %s cuotas', ($row['poliza_cant_cuotas']+$row['cuota_pfc'])));

	// $pdf->wwrite(148, 252, 'Prima');
	// $pdf->wwrite(148, 256.5, 'Premio');
	//
	// $txt_imp_c2 = array(
	// 	array('maxwidth' => 95, 'text' => "$ ".formatNumber($row['poliza_prima'])." "),
	// 	array('maxwidth' => 95, 'text' => "$ ".formatNumber($row['poliza_premio'])." ")
	// );

	// $pdf->SetXY(149, 260);
	// foreach ($txt_imp_c2 as $array) {
	// 	printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
	// }

	$pdf->wwrite(11, 213, sprintf('RECARGO: %s%%', formatNumber($row['poliza_recargo'])));
	$pdf->wwrite(11, 217, sprintf('DESCUENTO: %s%%', formatNumber($row['poliza_descuento'])));

	$pdf->wwrite(100, 213, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
	$pdf->wwrite(100, 217, sprintf('CÓDIGO: %s', $row['productor_seguro_codigo']));
	
	$y = 117;
	$pdf->wwrite(13, $y, 'Cantidad', 10, 'B');
	$pdf->wwrite(30, $y, 'Producto', 10, 'B');
	$pdf->wwrite(102, $y, 'Marca', 10, 'B');
	$pdf->wwrite(180, $y, 'Valor', 10, 'B');
	$y += 5;
	$max_y = 190;
	foreach ($objects as $object) {
		$items = $object['items'];
		$description = $object['desc'];
		 
		if (count($items)==0) {
			continue;
		}

		if ($y>$max_y) {
			newPage($pdf, false);
			$pdf->wwrite(13, 40, 'CONTINUACIÓN');
			$y = 50;
			$max_y = 265;
		}
		$pdf->wwrite(13, $y, $description, 9, 'B');
		$y += 5;

		foreach ($items as $item) {
			if ($y>$max_y) {
				$max_y = 265;
				newPage($pdf, false);
				$pdf->wwrite(13, 40, 'CONTINUACIÓN');
				$y = 50;
			}
			$pdf->wwrite(13, $y, $item['cantidad']);
			$pdf->wwrite(30, $y, $item['producto']);
			$pdf->wwrite(102, $y, $item['marca']);
			$pdf->wwrite(180, $y, sprintf('$%s', $item['valor']));
			$y += 5;
		}
	}
	
	break;
	case 'pe':
	// -------------------------- //
	// Pedido de emisión / Endoso //
	// -------------------------- //
	
	$pdf = new FPDIW('P');
	function newPage($pdf, $first) {
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();
		$pdf->setSourceFile(sprintf('pdf/nuevos/pe_%s.pdf', $first?'otros':'blank'));
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);
	}
	newPage($pdf, true);
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

	$pdf->wwrite(146, 50, sprintf('Tipo de seguro: %s', strtoupper($row['subtipo_poliza_nombre'])), 8);
	$pdf->wwrite(146, 54, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 58, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 62, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 66, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));

	$pdf->wwrite(26, 75.6, $row['cliente_email'], 12, 'B');
	
	$pdf->wwrite(13, 92, sprintf('Dirección: %s %s', $row2['combinado_familiar_domicilio_calle'], $row2['combinado_familiar_domicilio_nro']));

	$pdf->wwrite(100, 92, sprintf('Piso/Dpto: %s %s', $row2['combinado_familiar_domicilio_piso'], $row2['combinado_familiar_domicilio_dpto']));
	
	$pdf->wwrite(130, 92, sprintf('Localidad: %s', $row2['combinado_familiar_domicilio_localidad']));
	$pdf->wwrite(180, 92, sprintf('CP: %s', $row2['combinado_familiar_domicilio_cp']));
	
	
	$pdf->wwrite(13, 97, sprintf('Barrio cerrado/country: %s', $row2['combinado_familiar_country']));
	$pdf->wwrite(100, 97, sprintf('Lote: %s', $row2['combinado_familiar_lote']));
		

	$pdf->wwrite(13, 102, sprintf('Incendio Edificio: $%s', formatNumber($row2['combinado_familiar_inc_edif'], 2)));
	$pdf->wwrite(79, 102, sprintf('Incendio Mobiliario: $%s', formatNumber($row2['combinado_familiar_inc_mob'], 2)));
	$pdf->wwrite(149, 102, sprintf('Efectos Personales: $%s', formatNumber($row2['combinado_familiar_ef_personales'], 2)));
	
	$pdf->wwrite(13, 107, sprintf('Valor tasado de la propiedad: $%s', formatNumber($row2['combinado_familiar_valor_tasado'], 2)));
	
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
	$pdf->wwrite(100, 262, sprintf('CÓDIGO: %s', $row['productor_seguro_codigo']));
	
	$y = 117;
	$pdf->wwrite(13, $y, 'Cantidad', 10, 'B');
	$pdf->wwrite(30, $y, 'Producto', 10, 'B');
	$pdf->wwrite(102, $y, 'Marca', 10, 'B');
	$pdf->wwrite(180, $y, 'Valor', 10, 'B');
	$y += 5;
	$max_y = 232;
	foreach ($objects as $object) {
		$items = $object['items'];
		$description = $object['desc'];
		 
		if (count($items)==0) {
			continue;
		}

		if ($y>$max_y) {
			newPage($pdf, false);
			$pdf->wwrite(13, 40, 'CONTINUACIÓN');
			$y = 50;
			$max_y = 265;
		}
		$pdf->wwrite(13, $y, $description, 9, 'B');
		$y += 5;

		foreach ($items as $item) {
			if ($y>$max_y) {
				$max_y = 265;
				newPage($pdf, false);
				$pdf->wwrite(13, 40, 'CONTINUACIÓN');
				$y = 50;
			}
			$pdf->wwrite(13, $y, $item['cantidad']);
			$pdf->wwrite(30, $y, $item['producto']);
			$pdf->wwrite(102, $y, $item['marca']);
			$pdf->wwrite(180, $y, sprintf('$%s', $item['valor']));
			$y += 5;
		}
	}
	
	break;
}
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
			$to = $row['seguro_email_patrimoniales_otras'];
			switch(substr($_GET['type'], 2)) {
				case '':
					$file_name = $row['subtipo_poliza_nombre'].' - '.$row['cliente_nombre'].'.pdf';
					$body = FALSE;
					$type = 2;
				break;
				case 'mc':
					$file_name = $row['subtipo_poliza_nombre'].' - '.$row['cliente_nombre'].'.pdf';
					$body = FALSE;
					$type = 3;
				break;
				case 're':
					$file_name = 'Renovacion '.$row['subtipo_poliza_nombre'].' - '.$row['cliente_nombre'].'.pdf';
					$body = FALSE;
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
					$body = FALSE;
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