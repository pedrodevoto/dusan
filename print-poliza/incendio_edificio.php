<?php
// Recordset: Incendio Edificio
$query_Recordset2 = sprintf("SELECT *  FROM incendio_edificio WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

// If no record found
if ($totalRows_Recordset2 === 0) {
	die("Error: Detalle de Poliza no encontrado.");
}

// Compose Shared Texts
$txt_titular_c1 = array(
	array('maxwidth' => 130, 'text' => "Nombre/Razón Social: ".$row_Recordset1['cliente_nombre']),
	array('maxwidth' => 130, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].(is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso']).(is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'])),
	array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['localidad_nombre']),
	array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
	array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
	array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
);
$txt_titular_c2 = array(
	array('maxwidth' => 47, 'text' => ""),								
	array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
	array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['localidad_cp']),
	array('maxwidth' => 47, 'text' => ""),																
	array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit_0'].$row_Recordset1['cliente_cuit_1'].$row_Recordset1['cliente_cuit_2']),
	array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc'])
);
$txt_poliza = array(
	array('maxwidth' => 55, 'text' => "Tipo de Seguro: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),			
	array('maxwidth' => 55, 'text' => "PÓLIZA Nº: ".$row_Recordset1['poliza_numero']),
	array('maxwidth' => 55, 'text' => "Fecha Solicitud: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_fecha_solicitud']))),
	array('maxwidth' => 55, 'text' => "VIGENCIA DESDE: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_desde']))),
	array('maxwidth' => 55, 'text' => "VIGENCIA HASTA: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_hasta'])))
);
$txt_pago_c1 = "Forma de Pago: ".$row_Recordset1['poliza_medio_pago'];			
$txt_pago_c2 = "Plan de Pago: ".$row_Recordset1['poliza_cant_cuotas'] . ' cuotas';
$txt_pago_c3 = "Detalle de pago: ".$row_Recordset1['poliza_pago_detalle'];			
$txt_imp_c1 = array(
	array('maxwidth' => 95, 'text' => "Prima:"),
	array('maxwidth' => 95, 'text' => "Premio:")
);
$txt_imp_c2 = array(
	array('maxwidth' => 95, 'text' => "$ ".formatNumber($row_Recordset1['poliza_prima'])." "),
	array('maxwidth' => 95, 'text' => "$ ".formatNumber($row_Recordset1['poliza_premio'])." ")
);

switch(substr($_GET['type'], 0, 2)) {
	case 'cc':
		/****************************************
		* CONSTANCIA DE COBERTURA
		*****************************************/

		function newPage($pdf, $first) {
			$pdf->SetAutoPageBreak(false);
			$pdf->AddPage();
			if (isset($_GET['print'])) {
				$pdf->setSourceFile('pdf/cc_dinamica'.(!$first?2:'').'.pdf');
			}
			else {
				$pdf->setSourceFile('pdf/cc_digital_dinamica'.(!$first?2:'').'.pdf');
			}	
			$tplIdx = $pdf->importPage(1);
			$pdf->useTemplate($tplIdx);
			// Fecha y hora
			$txt_date = "FECHA: ".date("d/m/Y")." HORA: ".date("h:i a");
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);										
			$pdf->SetXY(0, 45);
			// printText($txt_date, $pdf, 196, 0, 'R');
		}

		// NEW DOCUMENT
		$pdf = new FPDI('P','mm',array(215.9,297));
		newPage($pdf, true);

		// Compañía / Sucursal
		$txt_compania = "Compañía Aseguradora: ".strtoupper($row_Recordset1['seguro_nombre']);
		$txt_compania.= "  -  Sucursal: ".strtoupper($row_Recordset1['sucursal_nombre']);
		$pdf->SetFont('Arial', 'B', 10);
		$pdf->SetTextColor(0,0,0);										
		$pdf->SetXY(11, 51);
		printText($txt_compania, $pdf, 190, 0);
		// Datos del Titular
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);								
		$pdf->SetXY(11, 61);
		foreach ($txt_titular_c1 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
		$pdf->SetXY(95, 61);
		foreach ($txt_titular_c2 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
		// Datos de la Póliza
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);								
		$pdf->SetXY(147, 61);
		foreach ($txt_poliza as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
						
		$x = 9.8;
		$y = 90;
	
		$pdf->SetFillColor(221,227,237);
		$pdf->SetLineWidth(0.4);
		$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
		$pdf->SetXY(95, $y+0.5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Write(5, 'General');
	
		$pdf->SetLineWidth(0.4);
		$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 33, 1, '1234', 'D');
	
		$y += 9.5;

		$pdf->SetXY($x + 2, $y);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['incendio_edificio_domicilio_calle'], $pdf, 60).' '.$row_Recordset2['incendio_edificio_domicilio_nro']);
		$pdf->SetX($x + 65);
		$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['incendio_edificio_domicilio_piso'].' '.$row_Recordset2['incendio_edificio_domicilio_dpto']);
	
		$pdf->SetX($x + 95);
		$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['incendio_edificio_domicilio_localidad'], $pdf, 60));
		$pdf->SetX($x + 180);
		$pdf->Write(5, 'CP: '.trimText($row_Recordset2['incendio_edificio_domicilio_cp'], $pdf, 60));
	
	
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Barrio cerrado/country: '.$row_Recordset2['incendio_edificio_country']);
		$pdf->SetX($x + 95);
		$pdf->Write(5, 'Lote: '.$row_Recordset2['incendio_edificio_lote']);
	
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Incendio Edificio:                       $'.formatNumber($row_Recordset2['incendio_edificio_inc_edif'], 2));
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Incendio Mobiliario:                   $'.formatNumber($row_Recordset2['incendio_edificio_inc_mob'], 2));
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'RC por Incendio:                       $'.formatNumber($row_Recordset2['incendio_edificio_rc_inc'], 2));
		
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Valor tasado de la propiedad:   $'.formatNumber($row_Recordset2['incendio_edificio_valor_tasado'], 2));
		
		
	
		// Footer
		if ($y > 200) {
			newPage($pdf, false);
		}
		$y = 200;
		$pdf->SetFillColor(229,233,253);
		$pdf->SetDrawColor(138,162,234);
		$pdf->SetLineWidth(0.6);
		$pdf->RoundedRect(10.5, $y, 135+60, 21, 1, '1234', 'DF');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY(95,$y + 1);
		$pdf->Write(5, 'Forma de Pago');
		
		$y += 9;
		
		// Forma de Pago					
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);								
		$pdf->SetXY(12.5, $y);
		printText($txt_pago_c1, $pdf, 55, 3.8);
		printText($txt_pago_c3, $pdf, 100, 3.8);	
		$pdf->SetXY(70, $y);
		$pdf->SetXY(102, $y);
		printText($txt_pago_c2, $pdf, 30, 3.8);
		
		// Firma
		if (isset($_GET['print'])) {
			$pdf->Image('pdf/cc_dinamica_firma.png', 0, 0, 215.9, 279.4);
		}
		else {
			$pdf->Image('pdf/cc_digital_dinamica_firma.png', 0, 0, 215.9, 297);
		}
		break;
	case 'pe':
		/****************************************
		* PEDIDO DE EMISIÓN
		*****************************************/				

	
		function newPage($pdf, $first, $endoso_anulacion=NULL) {
			$pdf->SetAutoPageBreak(false);
			$pdf->AddPage();
			$pdf->setSourceFile('pdf/pe_dinamico'.(!$first?2:'').'.pdf');
			$tplIdx = $pdf->importPage(1);
			$pdf->useTemplate($tplIdx);
			// Fecha y hora
			$txt_date = array(
				array('maxwidth' => 30, 'text' => "FECHA:  ".date("d/m/Y")),
				array('maxwidth' => 30, 'text' => "HORA:    ".date("h:i a"))
			);					
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY(175, 5);
			// foreach ($txt_date as $array) {
			// 	printText($array['text'], $pdf, $array['maxwidth'], 5);
			// }
			// Emitir
			$size_emitir = 44;
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

			$pdf->SetFont('Arial', 'B', $size_emitir);
			$pdf->SetTextColor(0,0,0);										
			$pdf->SetXY(50, 11.5);
			printText($txt_emitir, $pdf, 120, 0);
		}
		// NEW DOCUMENT
		$pdf = new FPDI('P','mm',array(215.9,279.4));
	
		newPage($pdf, true, $endoso['anulacion']);				
		// Compañía
		$txt_compania = strtoupper($row_Recordset1['seguro_nombre']);
		$txt_compania.= ' (' . strtoupper($row_Recordset1['sucursal_nombre']) . ')';
		$pdf->SetFont('Arial', 'B', 28);
		$pdf->SetTextColor(255,0,0);										
		$pdf->SetXY(50, 30);
		printText($txt_compania, $pdf, 155, 0);					
		// Datos del Titular
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);								
		$pdf->SetXY(11, 51.5);
		foreach ($txt_titular_c1 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
		$pdf->SetXY(95, 51.5);
		foreach ($txt_titular_c2 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
		// Datos de la Póliza
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);								
		$pdf->SetXY(147, 51.5);
		foreach ($txt_poliza as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.1);
		}
		$x = 11;
		$y = 78.5;
	
		$pdf->SetFillColor(221,227,237);
		$pdf->SetLineWidth(0.4);
		$pdf->RoundedRect($x - 0.5, $y, 195.5, 6, 1, '1234', 'DF');
		$pdf->SetXY(95, $y+0.5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Write(5, 'General');
	
		$pdf->SetLineWidth(0.4);
		$pdf->RoundedRect($x - 0.5, $y + 7.5, 195.5, 33, 1, '1234', 'D');
	
		$y += 9.5;

		$pdf->SetXY($x + 2, $y);
		$pdf->SetFont('Arial', '', 8);
		$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['incendio_edificio_domicilio_calle'], $pdf, 60).' '.$row_Recordset2['incendio_edificio_domicilio_nro']);
		$pdf->SetX($x + 65);
		$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['incendio_edificio_domicilio_piso'].' '.$row_Recordset2['incendio_edificio_domicilio_dpto']);
	
		$pdf->SetX($x + 95);
		$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['incendio_edificio_domicilio_localidad'], $pdf, 60));
		$pdf->SetX($x + 180);
		$pdf->Write(5, 'CP: '.trimText($row_Recordset2['incendio_edificio_domicilio_cp'], $pdf, 60));
	
	
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Barrio cerrado/country: '.$row_Recordset2['incendio_edificio_country']);
		$pdf->SetX($x + 95);
		$pdf->Write(5, 'Lote: '.$row_Recordset2['incendio_edificio_lote']);
	
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Incendio Edificio:                       $'.formatNumber($row_Recordset2['incendio_edificio_inc_edif'], 2));
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Incendio Mobiliario:                   $'.formatNumber($row_Recordset2['incendio_edificio_inc_mob'], 2));
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'RC por Incendio:                       $'.formatNumber($row_Recordset2['incendio_edificio_rc_inc'], 2));
		
		$y +=5;
		$pdf->SetXY($x + 2, $y);
		$pdf->Write(5, 'Valor tasado de la propiedad:   $'.formatNumber($row_Recordset2['incendio_edificio_valor_tasado'], 2));
		
		$y +=7.5;
		
		if (isset($_GET['en']) && $_GET['en']==1) {
			$pdf->SetFillColor(172,190,219);
			$pdf->SetLineWidth(0.4);
			$pdf->RoundedRect($x - 0.5, $y, 195.5, 6, 1, '1234', 'DF');
			$pdf->SetXY(95, $y+0.5);
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5, 'Endoso');
									
			$y += 7.5;
			$pdf->RoundedRect($x - 0.5, $y, 195.5, 30, 1, '1234', 'D');
			
			$pdf->SetXY($x, $y);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Write(5, "PRODUCTOR: ".strtoupper($row_Recordset1['productor_nombre']));
			$pdf->SetX($x + 60);
			$pdf->Write(5, "CODIGO: ".$row_Recordset1['productor_seguro_codigo']);
			$y += 7;
			
			$pdf->SetXY($x, $y);
			$pdf->SetFont('Arial', '', 8);
			$pdf->Write(5, 'Motivo de endoso: '.$endoso['endoso_tipo_nombre']);
			$y += 7;
			$pdf->SetXY($x, $y);
			$pdf->Write(5, 'Vigencia del endoso: de '. date('d/m/Y') . ' a ' . date('d/m/Y', strtotime($row_Recordset1['poliza_validez_hasta'])));
			$y += 7;
			$pdf->SetXY($x, $y);
			$endoso_cuerpo = iconv('UTF-8', 'windows-1252', $endoso['endoso_cuerpo']);
			$pdf->Write(5, 'Detalle: '.$endoso_cuerpo);
			
			// Firma
			$pdf->SetLineWidth(0.3);
			$pdf->Line(130, 250, 190, 250);
			$pdf->SetXY(146, 252);
			$pdf->SetFont('Arial', '', 9);
			$pdf->Write(5, 'Firma Adherente');
			$pdf->SetXY(146, 257);
			$pdf->Write(5, 'Aclaracion:');
			$pdf->SetXY(146, 262);
			$pdf->Write(5, 'DNI:');
		}
		else {
			// Footer
			if ($y > 238) {
				newPage($pdf, false);
			}
			$pdf->SetFillColor(229,233,253);
			$pdf->SetDrawColor(138,162,234);
			$pdf->SetLineWidth(0.6);
			$pdf->RoundedRect(10.5, 241, 135, 19, 1, '1234', 'DF');
			$pdf->RoundedRect(146, 241, 60, 19, 1, '1234', 'DF');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY(65,242);
			$pdf->Write(5, 'Forma de Pago');
			$pdf->SetXY(168,242);
			$pdf->Write(5, 'Importes');
		
			$pdf->SetLineWidth(0.4);
			$pdf->SetDrawColor(0,0,0);
			$pdf->RoundedRect(10.5, 262, 195.5, 11, 1, '1234', 'D');
		
			// Forma de Pago					
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);								
			$pdf->SetXY(12.5, 250);
			printText($txt_pago_c1, $pdf, 55, 3.8);
			printText($txt_pago_c3, $pdf, 100, 3.8);	
			$pdf->SetXY(70, 250);
			$pdf->SetXY(102, 250);
			printText($txt_pago_c2, $pdf, 30, 3.8);
			// Importes
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);								
			$pdf->SetXY(149, 250);
			foreach ($txt_imp_c1 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 3.8);
			}
			$pdf->SetXY(149, 250);
			foreach ($txt_imp_c2 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
			}
			// Misc
			$txt_misc_c1 = array(
				array('maxwidth' => 95, 'text' => "RECARGO: ".formatNumber($row_Recordset1['poliza_recargo'])." %"),
			);
			$txt_misc_c2 = array(
				array('maxwidth' => 95, 'text' => "PRODUCTOR: ".strtoupper($row_Recordset1['productor_nombre'])),
				array('maxwidth' => 95, 'text' => "CÓDIGO: ".$row_Recordset1['productor_seguro_codigo'])
			);											
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY(12, 263.7);
			foreach ($txt_misc_c1 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 3.8);
			}
			$pdf->SetXY(110, 263.7);
			foreach ($txt_misc_c2 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 3.8);
			}
		}
		break;
	default:
		die ('Documento no definido');
		break;
}
if (isset($_GET['email'])) {
	$cc = explode(',', urldecode($_GET['email']));
	$subject = $_GET['mail-subject'];
	$type = 0;
	switch(substr($_GET['type'], 0, 2)) {
		case 'cc':
			$to = $row_Recordset1['cliente_email'];
			$file_name = 'Constancia de cobertura.pdf';
			$body = TRUE;
			$type = 1;
			break;
		case 'pe':
			$to = $row_Recordset1['seguro_email_patrimoniales_otras'];
			switch(substr($_GET['type'], 2)) {
				case '':
					$file_name = $row_Recordset1['subtipo_poliza_nombre'].' - '.$row_Recordset1['cliente_nombre'].'.pdf';
					$body = FALSE;
					$type = 2;
				break;
				case 'mc':
					$file_name = $row_Recordset1['subtipo_poliza_nombre'].' - '.$row_Recordset1['cliente_nombre'].'.pdf';
					$body = FALSE;
					$type = 3;
				break;
				case 're':
					$file_name = 'Renovacion '.$row_Recordset1['subtipo_poliza_nombre'].' - '.$row_Recordset1['cliente_nombre'].'.pdf';
					$body = FALSE;
					$type = 4;
				break;
				case 'en':
					$to = $row_Recordset1['seguro_email_endosos'];
					$file_name = ($endoso['anulacion']?'Anulacion':'Endoso')." PZA.".$row_Recordset1['poliza_numero'].".pdf";
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