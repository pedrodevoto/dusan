<?php
$sql = sprintf("SELECT *  FROM integral_consorcio WHERE poliza_id=%s", $row['poliza_id']);
$res = mysql_query($sql) or die(mysql_error());
$row2 = mysql_fetch_assoc($res);

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
	
	$pdf->wwrite(146, 60, sprintf('Tipo de seguro: INCENDIO EDIFICIO'), 8);
	$pdf->wwrite(146, 64, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 68, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 72, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 76, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));
	
	$pdf->wwrite(26, 85.6, $row['cliente_email'], 12, 'B');
	
	$pdf->wwrite(13, 97, sprintf('Dirección: %s %s', $row2['integral_consorcio_domicilio_calle'], $row2['combinado_familiar_domicilio_nro']));

	$pdf->wwrite(100, 97, sprintf('Piso/Dpto: %s %s', $row2['integral_consorcio_domicilio_piso'], $row2['combinado_familiar_domicilio_dpto']));
	
	$pdf->wwrite(130, 97, sprintf('Localidad: %s', $row2['integral_consorcio_domicilio_localidad']));
	$pdf->wwrite(180, 97, sprintf('CP: %s', $row2['integral_consorcio_domicilio_cp']));
	
	
	$pdf->wwrite(13, 102, sprintf('Barrio cerrado/country: %s', $row2['integral_consorcio_country']));
	$pdf->wwrite(100, 102, sprintf('Lote: %s', $row2['integral_consorcio_lote']));
	
	$pdf->wwrite(13, 107, sprintf('Incendio edificio prorrata: $%s %s', $row2['integral_consorcio_inc_edif'], ($row2['integral_consorcio_inc_edif_rep']?' (con cláusula de reposición a nuevo)':'')));
	
	$pdf->wwrite(13, 112, sprintf('Incendio Contenido General - Partes Comunes: $%s', $row2['integral_consorcio_inc_contenido']));
	$pdf->wwrite(13, 117, sprintf('Robo Contenido General Mobiliario / Objetos Específicos – Partes Comunes: $%s', $row2['integral_consorcio_robo_gral']));
	$pdf->wwrite(13, 122, sprintf('Robo Matafuegos: $%s', $row2['integral_consorcio_robo_matafuegos']));
	$pdf->wwrite(13, 127, sprintf('Robo de Luces de Emergencia, Cámaras de Seguridad y Mangueras de Incendio: $%s', $row2['integral_consorcio_robo_lcm']));
	$pdf->wwrite(13, 132, sprintf('RC Comprensiva: $%s', $row2['integral_consorcio_rc_comprensiva']));
	$pdf->wwrite(13, 137, sprintf('Cristales y/o Vidrios y/o Espejos: $%s', $row2['integral_consorcio_cristales']));
	$pdf->wwrite(13, 142, sprintf('Daños por Agua al Contenido de propiedad común: $%s', $row2['integral_consorcio_danios_agua']));
	$pdf->wwrite(13, 147, sprintf('Responsabilidad Civil Garaje – Cubierto o Descubierto - por la guarda y/o depósito de vehículos: $%s', $row2['integral_consorcio_rc_garage']));
	$pdf->wwrite(13, 152, sprintf('Acc. Person. para el Personal que preste serv. al Consorcio sin rel. de depend. laboral en los térm. de la Ley de Contrato de Trabajo: $%s', $row2['integral_consorcio_acc_personales']), 8);
	$pdf->wwrite(13, 157, sprintf('Robo de Dinero de las Expensas en poder del Encargado: $%s', $row2['integral_consorcio_robo_exp']));

	
	$pdf->wwrite(11, 202, sprintf('Forma de pago: %s', $row['poliza_medio_pago']));
	$pdf->wwrite(11, 207, sprintf('Detalle de pago: %s', preg_replace('/\n/', ' ', $row['poliza_pago_detalle'])));

	$pdf->wwrite(80, 202, sprintf('Plan de pago: %s cuotas', ($row['poliza_cant_cuotas']+$row['cuota_pfc'])));
	
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
	$pdf->wwrite(11, 66, sprintf('Categoría de IVA: %s', $row['cliente_cf_nombre']));
	$pdf->wwrite(100, 66, sprintf('CUIT: %s%s%s', $row['cliente_cuit_0'], $row['cliente_cuit_1'], $row['cliente_cuit_2']));
	$pdf->wwrite(11, 70, sprintf('Fecha de nacimiento: %s', (!empty($row['cliente_nacimiento'])?strftime("%d/%m/%Y", strtotime($row['cliente_nacimiento'])):'')));
	$pdf->wwrite(100, 70, sprintf('DNI: %s', $row['cliente_nro_doc']));

	$pdf->wwrite(146, 50, sprintf('Tipo de seguro: %s', strtoupper($row['subtipo_poliza_nombre'])), 8);
	$pdf->wwrite(146, 54, sprintf('Póliza Nº: %s', $row['poliza_numero']));
	$pdf->wwrite(146, 58, sprintf('Fecha de solicitud: %s', strftime("%d/%m/%Y", strtotime($row['poliza_fecha_solicitud']))));
	$pdf->wwrite(146, 62, sprintf('VIGENCIA DESDE: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_desde']))));
	$pdf->wwrite(146, 66, sprintf('VIGENCIA HASTA: %s', strftime("%d/%m/%Y", strtotime($row['poliza_validez_hasta']))));

	$pdf->wwrite(26, 75.6, $row['cliente_email'], 12, 'B');
	
	$pdf->wwrite(13, 92, sprintf('Dirección: %s %s', $row2['integral_consorcio_domicilio_calle'], $row2['combinado_familiar_domicilio_nro']));

	$pdf->wwrite(100, 92, sprintf('Piso/Dpto: %s %s', $row2['integral_consorcio_domicilio_piso'], $row2['combinado_familiar_domicilio_dpto']));
	
	$pdf->wwrite(130, 92, sprintf('Localidad: %s', $row2['integral_consorcio_domicilio_localidad']));
	$pdf->wwrite(180, 92, sprintf('CP: %s', $row2['integral_consorcio_domicilio_cp']));
	
	
	$pdf->wwrite(13, 97, sprintf('Barrio cerrado/country: %s', $row2['integral_consorcio_country']));
	$pdf->wwrite(100, 97, sprintf('Lote: %s', $row2['integral_consorcio_lote']));
	
	if (isset($_GET['en']) && $_GET['en']==1) {
		$pdf->wwrite(13, 105, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
		$pdf->wwrite(13+60, 105, sprintf('CODIGO: %s', $row['productor_seguro_codigo']));
		
		$pdf->wwrite(13, 110, sprintf('Motivo de endoso: %s', $endoso['endoso_tipo_nombre']));
		$pdf->wwrite(13, 115, sprintf('Vigencia del endoso: de %s a %s', date('d/m/Y'), date('d/m/Y', strtotime($row['poliza_validez_hasta']))));
		
		$endoso_cuerpo = iconv('UTF-8', 'windows-1252', $endoso['endoso_cuerpo']);
		$pdf->SetXY(13, 122);
		$pdf->MultiCell(90, 5, sprintf('Detalle: %s', $endoso_cuerpo), 0, 'L', 0, 10);
		
	}
	
	else {
	
		$pdf->wwrite(13, 102, sprintf('Incendio edificio prorrata: $%s %s', $row2['integral_consorcio_inc_edif'], ($row2['integral_consorcio_inc_edif_rep']?' (con cláusula de reposición a nuevo)':'')));
	
		$pdf->wwrite(13, 107, sprintf('Incendio Contenido General - Partes Comunes: $%s', $row2['integral_consorcio_inc_contenido']));
		$pdf->wwrite(13, 112, sprintf('Robo Contenido General Mobiliario / Objetos Específicos – Partes Comunes: $%s', $row2['integral_consorcio_robo_gral']));
		$pdf->wwrite(13, 117, sprintf('Robo Matafuegos: $%s', $row2['integral_consorcio_robo_matafuegos']));
		$pdf->wwrite(13, 122, sprintf('Robo de Luces de Emergencia, Cámaras de Seguridad y Mangueras de Incendio: $%s', $row2['integral_consorcio_robo_lcm']));
		$pdf->wwrite(13, 127, sprintf('RC Comprensiva: $%s', $row2['integral_consorcio_rc_comprensiva']));
		$pdf->wwrite(13, 132, sprintf('Cristales y/o Vidrios y/o Espejos: $%s', $row2['integral_consorcio_cristales']));
		$pdf->wwrite(13, 137, sprintf('Daños por Agua al Contenido de propiedad común: $%s', $row2['integral_consorcio_danios_agua']));
		$pdf->wwrite(13, 142, sprintf('Responsabilidad Civil Garaje – Cubierto o Descubierto - por la guarda y/o depósito de vehículos: $%s', $row2['integral_consorcio_rc_garage']));
		$pdf->wwrite(13, 147, sprintf('Acc. Person. para el Personal que preste serv. al Consorcio sin rel. de depend. laboral en los térm. de la Ley de Contrato de Trabajo: $%s', $row2['integral_consorcio_acc_personales']), 8);
		$pdf->wwrite(13, 152, sprintf('Robo de Dinero de las Expensas en poder del Encargado: $%s', $row2['integral_consorcio_robo_exp']));
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