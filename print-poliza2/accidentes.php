<?php
$sql = sprintf("SELECT *  FROM accidentes WHERE poliza_id=%s", $row['poliza_id']);
$res2 = mysql_query($sql) or die(mysql_error());
$row2 = mysql_fetch_assoc($res);

$sql = sprintf("SELECT accidentes_asegurado_nombre, accidentes_asegurado_documento, DATE_FORMAT(accidentes_asegurado_nacimiento, '%%d/%%m/%%y') as accidentes_asegurado_nacimiento, asegurado_actividad_nombre, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, IF(accidentes_asegurado_beneficiario<3, 'Si', 'No') AS accidentes_asegurado_legal, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento, IF(accidentes_asegurado_beneficiario=2, 'Tomador', '') AS accidentes_asegurado_beneficiario_tomador FROM accidentes_asegurado JOIN asegurado_actividad ON asegurado_actividad.asegurado_actividad_id = accidentes_asegurado_actividad WHERE poliza_id=%s", $row['poliza_id']);
$res3 = mysql_query($sql, $connection) or die(mysql_die());
$asegurados = array();
while($row3 = mysql_fetch_assoc($res3)) {
	// for($i=0;$i<80;$i++) {
		$asegurados[] = $row3;
		$asegurados[] = array('beneficiario'=>true);
	// }
	// break;
}

$sql = sprintf("SELECT * FROM accidentes_clausula WHERE poliza_id=%s", $row['poliza_id']);
$res3 = mysql_query($sql, $connection) or die(mysql_die());
$clausulas = array();
while($row3 = mysql_fetch_assoc($res3)) {
	// for($i=0;$i<45;$i++) {
		$clausulas[] = $row3;
	// }
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
	$pdf->wwrite(11, 76, sprintf('Categoría de IVA: %s', $row['cliente_cf_nombre']));
	$pdf->wwrite(100, 76, sprintf('CUIT: %s%s%s', $row['cliente_cuit_0'], $row['cliente_cuit_1'], $row['cliente_cuit_2']));
	$pdf->wwrite(11, 80, sprintf('Fecha de nacimiento: %s', (!empty($row['cliente_nacimiento'])?strftime("%d/%m/%Y", strtotime($row['cliente_nacimiento'])):'')));
	$pdf->wwrite(100, 80, sprintf('DNI: %s', $row['cliente_nro_doc']));
	
	$pdf->wwrite(146, 60, sprintf('Tipo de seguro: ACC. PERSONA.'));
	$pdf->wwrite(146, 64, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 68, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 72, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 76, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));
	
	$pdf->wwrite(12, 85.6, sprintf('EMAIL: %s', $row['cliente_email']), 12, 'B');
	
	$y = 96;
	$x = 13;
	if (count($asegurados)){
		$asegurados[] = array('total'=>true);
		// Imprimir asegurados
		$pdf->wwrite(90, $y, 'ASEGURADOS', 10, 'B');
		
		$y += 7.5;			
		$y += 2;
		
		$x = 13;
		$pdf->wwrite(13, $y, 'Nombre', 8, 'B');
		$pdf->wwrite(13+42, $y, 'DNI', 8, 'B');
		$pdf->wwrite(13+61, $y, 'Nac.', 8, 'B');
		$pdf->wwrite(13+75, $y, 'Actividad', 8, 'B');
		$pdf->wwrite(13+128, $y, 'Legal', 8, 'B');
		$pdf->wwrite(13+141, $y, 'Asegurado', 8, 'B');
		$pdf->wwrite(13+161, $y, 'Gastos Farm.', 8, 'B');
		
		$y += 5;
		
		$max_y = 186;

		$count_asegurados = 0;
		$count_asegurados_per_page = 0;
		$max_asegurados = 16;
		$total_suma_asegurada = 0;
		$total_gastos_medicos = 0;
		foreach ($asegurados as $asegurado){
			if ($count_asegurados_per_page % $max_asegurados == 0 and $count_asegurados_per_page > 0) {
				newPage($pdf, false);				
				$y = 48;

				$pdf->wwrite(90, $y, 'ASEGURADOS (Cont)', 10, 'B');
				
				$y += 7.5;			
				$y += 2;
				$count_asegurados_per_page = 0;
				$max_asegurados = 42;
			}
			if (isset($asegurado['beneficiario'])) {
				continue;
			}
			if (!isset($asegurado['total'])){
				$pdf->wwrite($x, $y, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48), 7);
				$pdf->wwrite($x + 42, $y, $asegurado['accidentes_asegurado_documento'], 7);
				$pdf->wwrite($x + 61, $y, $asegurado['accidentes_asegurado_nacimiento'], 7);
				$pdf->wwrite($x + 75, $y, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50), 7);
				$pdf->wwrite($x + 128, $y, ($asegurado['accidentes_asegurado_beneficiario_tomador']!='' ? $asegurado['accidentes_asegurado_beneficiario_tomador'] : $asegurado['accidentes_asegurado_legal']), 7);
				$pdf->wwrite($x + 141, $y, sprintf('$%s', formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2)), 7);
				$pdf->wwrite($x + 161, $y, sprintf('$%s', formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2)), 7);
				
				$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
				$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
				
				
				if ($asegurado['accidentes_asegurado_legal']=='No') {
					$y += 5;
					$pdf->wwrite($x, $y, trimText($asegurado['accidentes_asegurado_beneficiario_nombre'], $pdf, 48), 7, 'I');
					$pdf->wwrite($x+48, $y, $asegurado['accidentes_asegurado_beneficiario_documento'], 7, 'I');
					$pdf->wwrite($x+70, $y, '(Beneficiario)', 7, 'I');
					$count_asegurados++;
					$count_asegurados_per_page++;
				}
			}
			else {
				$pdf->wwrite($x, $y, sprintf('Total: %s', $count_asegurados), 8, 'B');
				$pdf->wwrite($x+141, $y, sprintf('$%s', formatNumber($total_suma_asegurada, 2)), 8, 'B');
				$pdf->wwrite($x+161, $y, sprintf('$%s', formatNumber($total_gastos_medicos, 2)), 8, 'B');
			}
			$count_asegurados++;
			$count_asegurados_per_page++;
			$y += 5;
			
		}
	}
	if (count($clausulas)) {
		$pdf->wwrite(76, $y, 'CLAUSULAS DE NO REPETICION', 10, 'B');
		
		// Imprimir clausulas
		$y += 9;
		$pdf->wwrite(13, $y, 'Nombre', 8, 'B');
		$pdf->wwrite(13+70, $y, 'CUIT', 8, 'B');
		$pdf->wwrite(13+105, $y, 'Domicilio', 8, 'B');
		
		$y += 5;
		
		$count_clausulas = 0;
		$count_clausulas_per_page = 0;
		$max_clausulas = count($asegurados)?($max_asegurados - $count_asegurados_per_page) -1 :8;

		foreach ($clausulas as $clausula){
			if ($count_clausulas_per_page % $max_clausulas == 0 and $count_clausulas_per_page > 0) {
				newPage($pdf, false);				
				$y = 48;
				$pdf->wwrite(78, $y, 'CLAUSULAS DE NO REPETICION', 10, 'B');
			
				$count_clausulas_per_page = 0;
				$max_clausulas = 45;
				
				$y += 7.5;
				$y += 2;
			}
			$pdf->wwrite(13, $y, trimText($clausula['accidentes_clausula_nombre'], $pdf, 70), 8);
			$pdf->wwrite(13+70, $y, $clausula['accidentes_clausula_cuit'], 8);
			$pdf->wwrite(13+105, $y, trimText($clausula['accidentes_clausula_domicilio'], $pdf, 85), 8);
			
			$y += 5;
			$count_clausulas++;
			$count_clausulas_per_page++;
		}
		
	}
	$pdf->wwrite(11, 202, sprintf('Forma de pago: %s', $row['poliza_medio_pago']));
	$pdf->wwrite(11, 207, sprintf('Detalle de pago: %s', preg_replace('/\n/', ' ', $row['poliza_pago_detalle'])));

	$pdf->wwrite(80, 202, sprintf('Plan de pago: %s cuotas', ($row['poliza_cant_cuotas']+$row['cuota_pfc'])));
	
	break;
	case 'pe':
	
	// -------------------------- //
	// Pedido de emisión / Endoso //
	// -------------------------- //
	
	function newPage($pdf, $first, $endoso_anulacion = NULL) {
		$pdf->SetAutoPageBreak(false);
		$pdf->AddPage();
		if ($first)
			$pdf->setSourceFile(sprintf('pdf/nuevos/%s_otros.pdf', (!empty($_GET['en'])?'en':'pe')));
		else
			$pdf->setSourceFile(sprintf('pdf/nuevos/pe_blank.pdf'));
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);
		// Emitir
		$size_emitir = 38;
		if ((isset($_GET['mc']) && $_GET['mc'] === "1") or $_GET['type']=='pemc') {
			$txt_emitir = "MC";
		} 
		elseif ((isset($_GET['re']) && $_GET['re'] === "1") or $_GET['type']=='pere') {
			$txt_emitir = "RENOVACIÓN";
		}
		elseif (isset($_GET['en']) && $_GET['en']==1) {
			$txt_emitir = "ENDOSO";
			if($endoso_anulacion) {
				$size_emitir = 30;
				$txt_emitir = "ENDOSO - ANULACION";
			}
		} 
		else {
			$txt_emitir = "EMITIR";						
		}
		$pdf->wwrite(45, 1, $txt_emitir, $size_emitir);
	}
	// NEW DOCUMENT
	$pdf = new FPDIW('P');
	
	newPage($pdf, true, $endoso['anulacion']);

	$pdf->wwrite(44.5, 15, $row['seguro_nombre'], 30);
	
	$pdf->wwrite(11, 50, sprintf('Nombre/Razón social: %s', $row['cliente_nombre']));

	$pdf->wwrite(11, 54, sprintf('Domicilio: %s %s %s %s', $row['contacto_domicilio'], $row['contacto_nro'], $row['contacto_piso'], $row['contacto_dpto']));
	$pdf->wwrite(100, 54, sprintf('CP: %s', $row['localidad_cp']));
	$pdf->wwrite(11, 58, sprintf('Localidad: %s', $row['localidad_nombre']));
	$pdf->wwrite(11, 62, sprintf('Teléfonos: %s / %s', $row['contacto_telefono1'], $row['contacto_telefono2']));
	$pdf->wwrite(11, 66, sprintf('Categoría de IVA: %s', $row['cliente_cf_nombre']));
	$pdf->wwrite(100, 66, sprintf('CUIT: %s%s%s', $row['cliente_cuit_0'], $row['cliente_cuit_1'], $row['cliente_cuit_2']));
	$pdf->wwrite(11, 70, sprintf('Fecha de nacimiento: %s', (!empty($row['cliente_nacimiento'])?strftime("%d/%m/%Y", strtotime($row['cliente_nacimiento'])):'')));
	$pdf->wwrite(100, 70, sprintf('DNI: %s', $row['cliente_nro_doc']));
	
	$pdf->wwrite(146, 50, sprintf('Tipo de seguro: ACC. PERSONA.'));
	$pdf->wwrite(146, 54, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 58, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 62, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 66, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));
	
	$pdf->wwrite(26, 75.6, $row['cliente_email'], 12, 'B');
	
	if (isset($_GET['en']) && $_GET['en']==1) {
		$pdf->wwrite(13, 95, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
		$pdf->wwrite(13+60, 95, sprintf('CODIGO: %s', $row['productor_seguro_codigo']));
		
		$pdf->wwrite(13, 100, sprintf('Motivo de endoso: %s', $endoso['endoso_tipo_nombre']));
		$pdf->wwrite(13, 105, sprintf('Vigencia del endoso: de %s a %s', date('d/m/Y'), date('d/m/Y', strtotime($row['poliza_validez_hasta']))));
		
		$endoso_cuerpo = iconv('UTF-8', 'windows-1252', $endoso['endoso_cuerpo']);
		$pdf->SetXY(13, 112);
		$pdf->MultiCell(90, 5, sprintf('Detalle: %s', $endoso_cuerpo), 0, 'L', 0, 10);
		
	}
	else {
		$y = 90;
		$x = 13;
		if (count($asegurados)){
			$asegurados[] = array('total'=>true);
			// Imprimir asegurados
			$pdf->wwrite(90, $y, 'ASEGURADOS', 10, 'B');
		
			$y += 7.5;			
			$y += 2;
		
			$x = 13;
			$pdf->wwrite(13, $y, 'Nombre', 8, 'B');
			$pdf->wwrite(13+42, $y, 'DNI', 8, 'B');
			$pdf->wwrite(13+61, $y, 'Nac.', 8, 'B');
			$pdf->wwrite(13+75, $y, 'Actividad', 8, 'B');
			$pdf->wwrite(13+128, $y, 'Legal', 8, 'B');
			$pdf->wwrite(13+141, $y, 'Asegurado', 8, 'B');
			$pdf->wwrite(13+161, $y, 'Gastos Farm.', 8, 'B');
		
			$y += 5;
		
			$max_y = 186;

			$count_asegurados = 0;
			$count_asegurados_per_page = 0;
			$max_asegurados = 16;
			$total_suma_asegurada = 0;
			$total_gastos_medicos = 0;
			foreach ($asegurados as $asegurado){
				if ($count_asegurados_per_page % $max_asegurados == 0 and $count_asegurados_per_page > 0) {
					newPage($pdf, false);				
					$y = 48;

					$pdf->wwrite(90, $y, 'ASEGURADOS (Cont)', 10, 'B');
				
					$y += 7.5;			
					$y += 2;
					$count_asegurados_per_page = 0;
					$max_asegurados = 42;
				}
				if (isset($asegurado['beneficiario'])) {
					continue;
				}
				if (!isset($asegurado['total'])){
					$pdf->wwrite($x, $y, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48), 7);
					$pdf->wwrite($x + 42, $y, $asegurado['accidentes_asegurado_documento'], 7);
					$pdf->wwrite($x + 61, $y, $asegurado['accidentes_asegurado_nacimiento'], 7);
					$pdf->wwrite($x + 75, $y, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50), 7);
					$pdf->wwrite($x + 128, $y, ($asegurado['accidentes_asegurado_beneficiario_tomador']!='' ? $asegurado['accidentes_asegurado_beneficiario_tomador'] : $asegurado['accidentes_asegurado_legal']), 7);
					$pdf->wwrite($x + 141, $y, sprintf('$%s', formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2)), 7);
					$pdf->wwrite($x + 161, $y, sprintf('$%s', formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2)), 7);
				
					$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
					$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
				
				
					if ($asegurado['accidentes_asegurado_legal']=='No') {
						$y += 5;
						$pdf->wwrite($x, $y, trimText($asegurado['accidentes_asegurado_beneficiario_nombre'], $pdf, 48), 7, 'I');
						$pdf->wwrite($x+48, $y, $asegurado['accidentes_asegurado_beneficiario_documento'], 7, 'I');
						$pdf->wwrite($x+70, $y, '(Beneficiario)', 7, 'I');
						$count_asegurados++;
						$count_asegurados_per_page++;
					}
				}
				else {
					$pdf->wwrite($x, $y, sprintf('Total: %s', $count_asegurados), 8, 'B');
					$pdf->wwrite($x+141, $y, sprintf('$%s', formatNumber($total_suma_asegurada, 2)), 8, 'B');
					$pdf->wwrite($x+161, $y, sprintf('$%s', formatNumber($total_gastos_medicos, 2)), 8, 'B');
				}
				$count_asegurados++;
				$count_asegurados_per_page++;
				$y += 5;
			
			}
		}
		if (count($clausulas)) {
			$pdf->wwrite(76, $y, 'CLAUSULAS DE NO REPETICION', 10, 'B');
		
			// Imprimir clausulas
			$y += 9;
			$pdf->wwrite(13, $y, 'Nombre', 8, 'B');
			$pdf->wwrite(13+70, $y, 'CUIT', 8, 'B');
			$pdf->wwrite(13+105, $y, 'Domicilio', 8, 'B');
		
			$y += 5;
		
			$count_clausulas = 0;
			$count_clausulas_per_page = 0;
			$max_clausulas = count($asegurados)?($max_asegurados - $count_asegurados_per_page) -1 :8;

			foreach ($clausulas as $clausula){
				if ($count_clausulas_per_page % $max_clausulas == 0 and $count_clausulas_per_page > 0) {
					newPage($pdf, false);				
					$y = 48;
					$pdf->wwrite(78, $y, 'CLAUSULAS DE NO REPETICION', 10, 'B');
			
					$count_clausulas_per_page = 0;
					$max_clausulas = 45;
				
					$y += 7.5;
					$y += 2;
				}
				$pdf->wwrite(13, $y, trimText($clausula['accidentes_clausula_nombre'], $pdf, 70), 8);
				$pdf->wwrite(13+70, $y, $clausula['accidentes_clausula_cuit'], 8);
				$pdf->wwrite(13+105, $y, trimText($clausula['accidentes_clausula_domicilio'], $pdf, 85), 8);
			
				$y += 5;
				$count_clausulas++;
				$count_clausulas_per_page++;
			}
		
		}
		
		
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
			$to = $row['seguro_email_emision_vida'];
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