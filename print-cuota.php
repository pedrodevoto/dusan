<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Set locale/timezone
	setlocale(LC_TIME, 'es_AR');
	date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	// Require PDF libraries
	require_once('Classes/fpdf/fpdf.php');
	require_once('Classes/fpdf/fpdi.php');
	require_once('Classes/fpdf/rotation.php');
	// Require PDF functions
	require_once('inc/pdf_functions.php');
	require_once('inc/mail_functions.php');
	class PDF extends PDF_Rotate
	{
		var $rotation = 0;
		function RotatedText($x, $y, $txt, $maxlength)
		{
			$text = iconv('UTF-8', 'windows-1252', $txt);
			$length = $this->GetStringWidth($text);
			while ($length > $maxlength) {
				$text = substr($text, 0, -1);
				$length = $this->GetStringWidth($text);					
			}
		    //Text rotated around its origin
		    $this->Rotate($this->rotation, $x, $y);
		    $this->Text($x, $y, $text);
		    $this->Rotate(0);
		}

		function RotatedImage($file, $x, $y, $w, $h, $angle)
		{
		    //Image rotated around its upper-left corner
		    $this->Rotate($angle, $x, $y);
		    $this->Image($file, $x, $y, $w, $h);
		    $this->Rotate(0);
		}
	}
?>
<?php

	// Obtain URL parameter
	$cuota_id = intval($_GET['id']);
	
	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT *, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre FROM cuota JOIN (poliza, subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (cuota.poliza_id=poliza.poliza_id AND poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN contacto ON poliza.cliente_id=contacto.cliente_id AND contacto_default=1 LEFT JOIN localidad ON localidad.localidad_id = contacto.localidad_id JOIN sucursal on poliza.sucursal_id = sucursal.sucursal_id LEFT JOIN cliente_cf ON cliente_cf.cliente_cf_id = cliente.cliente_cf_id
									WHERE cuota_recibo IS NOT NULL AND cuota_estado_id=2 AND cuota.cuota_id=%s GROUP BY cuota.cuota_id",
									$cuota_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	// If record not found
	if ($totalRows_Recordset1 !== 1) {
		die("Error: Cuota no encontrada.");
	}	
	// If no contact info found
	if (is_null($row_Recordset1['contacto_id'])) {
		die("Error: El cliente no tiene un contacto primario asignado.");
	}	

	$sql = sprintf('SELECT count(cuota_id) from cuota where poliza_id = (select poliza_id from cuota where cuota_id=%s)', $cuota_id);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$cant_cuotas = $row[0];

	$offset = 0;
	// New document
	if (isset($_GET['print'])) {
		$pdf = new PDF('P','mm',array(210,297));
		$pdf->AddPage();
		$pdf->rotation = 90;
	}
	else {
		$pdf = new PDF('L','mm',array(350,210));
		$pdf->AddPage();
		$pdf->setSourceFile('pdf/cuota_digital.pdf');
		$offset = 5;
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);
	}
	
	// Determine subtype
	switch($row_Recordset1['subtipo_poliza_tabla']) {
			
		case 'automotor':
			// ---------------------------------- AUTOMOTOR ---------------------------------- //
			
			// Recordset: Automotor
			$query_Recordset2 = sprintf("SELECT * FROM automotor JOIN (automotor_tipo, seguro_cobertura_tipo) ON automotor_tipo.automotor_tipo_id = automotor.automotor_tipo_id AND seguro_cobertura_tipo.seguro_cobertura_tipo_id = automotor.seguro_cobertura_tipo_id LEFT JOIN automotor_marca ON automotor.automotor_marca_id = automotor_marca.automotor_marca_id LEFT JOIN automotor_modelo ON automotor.automotor_modelo_id = automotor_modelo.automotor_modelo_id LEFT JOIN automotor_version ON automotor.automotor_version_id = automotor_version.automotor_version_id WHERE automotor.poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);					
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);

			// If no record found
			if ($totalRows_Recordset2 === 0) {
				die("Error: Poliza no encontrada.");
			}
			
			// General variables
			$prox_cuota = getNextPayment($row_Recordset1['poliza_id'], $row_Recordset1['cuota_id']);
			$percent_serv = 0.13045;			
			
			$marca = $row_Recordset2['automotor_marca_nombre'];
			if (empty($row_Recordset2['automotor_version_nombre'])) {
				$modelo = (empty($row_Recordset2['automotor_modelo_nombre'])?$row_Recordset2['modelo']:$row_Recordset2['automotor_modelo_nombre']);
			}
			else {
				$modelo = $row_Recordset2['automotor_version_nombre'];
			}

			// Text 1
			$contacto_piso = is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso'];
			$contacto_dpto = is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'];
			$contacto_telefono1 = is_null($row_Recordset1['contacto_telefono1']) ? "-" : $row_Recordset1['contacto_telefono1'];			
			$txt1 = array(
						array('maxwidth' => 96, 'text' => "Asegurado: ".strtoupper($row_Recordset1['cliente_nombre'])),
						array('maxwidth' => 47, 'text' => "Sección: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),
						array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc']),
						array('maxwidth' => 47, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].$contacto_piso.$contacto_dpto),
						array('maxwidth' => 47, 'text' => "Localidad: ".$row_Recordset1['localidad_nombre']." - ".$row_Recordset1['localidad_cp']),
						array('maxwidth' => 47, 'text' => "Teléfono: ".$contacto_telefono1),
						array('maxwidth' => 47, 'text' => "Cond. IVA: ".$row_Recordset1['cliente_cf_nombre'])
					);


			// Text 2
			$cuota_periodo = ucfirst(strftime("%B/%Y", strtotime($row_Recordset1['cuota_periodo'])));
			$cuota_prox_venc = is_null($prox_cuota) ? date('d/m/Y', strtotime("+1 month", time())) : strftime("%d/%m/%Y", strtotime($prox_cuota['cuota_vencimiento']));
			$poliza_numero = is_null($row_Recordset1['poliza_numero']) ? "-" : $row_Recordset1['poliza_numero'];
			$txt2 = array(
						array('maxwidth' => 1 , 'text' => ''),
						array('maxwidth' => 47, 'text' => "Cía.: ".$row_Recordset1['seguro_nombre']),
						array('maxwidth' => 47, 'text' => "Suc.: ".$row_Recordset1['sucursal_nombre']),
						array('maxwidth' => 47, 'text' => "Póliza: ".$poliza_numero),
						array('maxwidth' => 47, 'text' => "Cuota: ".$row_Recordset1['cuota_nro']."/".$cant_cuotas." - ".$cuota_periodo),
						array('maxwidth' => 47, 'text' => "Próximo Vto: ".$cuota_prox_venc),
						array('maxwidth' => 1 , 'text' => '')
					);						
			
			
			// Text 3
			$cuota_ssn = $row_Recordset1['cuota_monto'] - ($row_Recordset1['cuota_monto'] * $percent_serv);
			$cuota_servicios = $row_Recordset1['cuota_monto'] * $percent_serv;
			$txt3 = array(
						array('maxwidth' => 96, 'text' => "Vehículo: ".$marca." - ".$modelo),
						array('maxwidth' => 47, 'text' => "Tipo: ".strtoupper($row_Recordset2['automotor_tipo_nombre'])),
						array('maxwidth' => 47, 'text' => "Uso: ".$row_Recordset2['uso']),
						array('maxwidth' => 47, 'text' => "SSN: ".formatNumber($cuota_ssn)." Servicios: ".formatNumber($cuota_servicios))
			);
			
			// Text 4
			$txt4 = array(
						array('maxwidth' => 0, 'text' => ""),
						array('maxwidth' => 47, 'text' => "Año: ".$row_Recordset2['ano']." | PATENTE: ".($row_Recordset2['automotor_carroceria_id']==17?'101':'').$row_Recordset2['patente_0'].$row_Recordset2['patente_1']),
						array('maxwidth' => 47, 'text' => "Cobertura: ".$row_Recordset2['seguro_cobertura_tipo_nombre']),
						array('maxwidth' => 47, 'text' => "Total: ".formatNumber($row_Recordset1['cuota_monto']))
			);

			// Close Recordset: Automotor
			mysql_free_result($Recordset2);									
		
			// Break
			break;
			
		case 'accidentes':
			// ---------------------------------- ACCIDENTES ---------------------------------- //
		
			// Recordset: Automotor
			$query_Recordset2 = sprintf("SELECT * FROM accidentes WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);					
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);
			
			// If no record found
			if ($totalRows_Recordset2 === 0) {
				die("Error: Poliza no encontrada.");
			}
		
			$query_Recordset3 = sprintf("SELECT COUNT(accidentes_asegurado_id) as cantidad, SUM(accidentes_asegurado_suma_asegurada) as suma_asegurada, SUM(accidentes_asegurado_gastos_medicos) as gastos_medicos FROM accidentes_asegurado WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$asegurados = mysql_fetch_assoc($Recordset3);
			
			$query_Recordset3 = sprintf("SELECT COUNT(accidentes_clausula_id) as cantidad FROM accidentes_clausula WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$clausulas = mysql_fetch_assoc($Recordset3);
		
			// General variables
			$prox_cuota = getNextPayment($row_Recordset1['poliza_id'], $row_Recordset1['cuota_id']);
			$percent_serv = 0.13045;			

			// Text 1
			$contacto_piso = is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso'];
			$contacto_dpto = is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'];
			$contacto_telefono1 = is_null($row_Recordset1['contacto_telefono1']) ? "-" : $row_Recordset1['contacto_telefono1'];			
			$txt1 = array(
						array('maxwidth' => 96, 'text' => "Asegurado: ".strtoupper($row_Recordset1['cliente_nombre'])),
						array('maxwidth' => 47, 'text' => "Sección: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),
						array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc']),
						array('maxwidth' => 47, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].$contacto_piso.$contacto_dpto),
						array('maxwidth' => 47, 'text' => "Localidad: ".$row_Recordset1['localidad_nombre']." - ".$row_Recordset1['localidad_cp']),
						array('maxwidth' => 47, 'text' => "Teléfono: ".$contacto_telefono1),
						array('maxwidth' => 47, 'text' => "Cond. IVA: ".$row_Recordset1['cliente_cf_nombre'])
					);

			// Text 2
			$cuota_periodo = ucfirst(strftime("%B/%Y", strtotime($row_Recordset1['cuota_periodo'])));
			$cuota_prox_venc = is_null($prox_cuota) ? date('d/m/Y', strtotime("+1 month", time())) : strftime("%d/%m/%Y", strtotime($prox_cuota['cuota_vencimiento']));
			$poliza_numero = is_null($row_Recordset1['poliza_numero']) ? "-" : $row_Recordset1['poliza_numero'];
			$txt2 = array(
						array('maxwidth' => 1 , 'text' => ''),
						array('maxwidth' => 47, 'text' => "Cía.: ".$row_Recordset1['seguro_nombre']),
						array('maxwidth' => 47, 'text' => "Suc.: ".$row_Recordset1['sucursal_nombre']),
						array('maxwidth' => 47, 'text' => "Póliza: ".$poliza_numero),
						array('maxwidth' => 47, 'text' => "Cuota: ".$row_Recordset1['cuota_nro']."/".$cant_cuotas." - ".$cuota_periodo),
						array('maxwidth' => 47, 'text' => "Próximo Vto: ".$cuota_prox_venc),
						array('maxwidth' => 1 , 'text' => '')
					);						
		
			// Text 3
			$txt3 = array(
						array('maxwidth' => 96, 'text' => "Asegurados: ".$asegurados['cantidad']),
						array('maxwidth' => 47, 'text' => "Suma asegurada: $".formatNumber($asegurados['suma_asegurada'], 2)),
						array('maxwidth' => 47, 'text' => "Gastos médicos: $".formatNumber($asegurados['gastos_medicos'], 2)),
			);																			
			
			// Text 4
			$txt4 = array(
						array('maxwidth' => 47, 'text' => "Clausulas de No Repeticion: ".$clausulas['cantidad']),
						array('maxwidth' => 47, 'text' => " "),
						array('maxwidth' => 47, 'text' => "Total: ".formatNumber($row_Recordset1['cuota_monto']))
			);			
			
			break;
		default:
		// General variables
		$prox_cuota = getNextPayment($row_Recordset1['poliza_id'], $row_Recordset1['cuota_id']);
		$percent_serv = 0.13045;			

		// Text 1
		$contacto_piso = is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso'];
		$contacto_dpto = is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'];
		$contacto_telefono1 = is_null($row_Recordset1['contacto_telefono1']) ? "-" : $row_Recordset1['contacto_telefono1'];			
		$txt1 = array(
					array('maxwidth' => 96, 'text' => "Asegurado: ".strtoupper($row_Recordset1['cliente_nombre'])),
					array('maxwidth' => 47, 'text' => "Sección: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),
					array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc']),
					array('maxwidth' => 47, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].$contacto_piso.$contacto_dpto),
					array('maxwidth' => 47, 'text' => "Localidad: ".$row_Recordset1['localidad_nombre']." - ".$row_Recordset1['localidad_cp']),
					array('maxwidth' => 47, 'text' => "Teléfono: ".$contacto_telefono1),
					array('maxwidth' => 47, 'text' => "Cond. IVA: ".$row_Recordset1['cliente_cf_nombre'])
				);

		// Text 2
		$cuota_periodo = ucfirst(strftime("%B/%Y", strtotime($row_Recordset1['cuota_periodo'])));
		$cuota_prox_venc = is_null($prox_cuota) ? date('d/m/Y', strtotime("+1 month", time())) : strftime("%d/%m/%Y", strtotime($prox_cuota['cuota_vencimiento']));
		$poliza_numero = is_null($row_Recordset1['poliza_numero']) ? "-" : $row_Recordset1['poliza_numero'];
		$txt2 = array(
					array('maxwidth' => 1 , 'text' => ''),
					array('maxwidth' => 47, 'text' => "Cía.: ".$row_Recordset1['seguro_nombre']),
					array('maxwidth' => 47, 'text' => "Suc.: ".$row_Recordset1['sucursal_nombre']),
					array('maxwidth' => 47, 'text' => "Póliza: ".$poliza_numero),
					array('maxwidth' => 47, 'text' => "Cuota: ".$row_Recordset1['cuota_nro']."/".$cant_cuotas." - ".$cuota_periodo),
					array('maxwidth' => 47, 'text' => "Próximo Vto: ".$cuota_prox_venc),
					array('maxwidth' => 1 , 'text' => '')
				);						
		// Text 3
		$txt3 = array(
					array('maxwidth' => 47, 'text' => ""),
					array('maxwidth' => 47, 'text' => "Riesgo: ".$row_Recordset1['subtipo_poliza_nombre']),
					array('maxwidth' => 47, 'text' => ""),
		);																			
	
		// Text 4
		$txt4 = array(
					array('maxwidth' => 47, 'text' => ""),
					array('maxwidth' => 47, 'text' => ""),
					array('maxwidth' => 47, 'text' => "Total: ".formatNumber($row_Recordset1['cuota_monto']))
		);			
			break;
	}
	if (isset($_GET['print'])) {
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);
		
		// Header
		$txthead = array(
			array('text'=>iconv('UTF-8', 'windows-1252', date('d/m/Y', strtotime($row_Recordset1['cuota_fe_pago']))."\n")),
			array('text'=>iconv('UTF-8', 'windows-1252', $row_Recordset1['cuota_recibo']))
		);
		
		$y = 56.5;
		foreach ($txthead as $array) {
			$pdf->RotatedText($y, 20, $array['text'], 50);
			$y += 4.5;
		}
		
		$y = 90-4.5;
		foreach ($txt1 as $array) {
			$pdf->RotatedText($y, 101, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 90-4.5;
		foreach ($txt2 as $array) {
			$pdf->RotatedText($y, 50, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 119;
		foreach ($txt3 as $array) {
			$pdf->RotatedText($y, 101, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 119;
		foreach ($txt4 as $array) {
			$pdf->RotatedText($y, 50, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		// Duplicado
		
		$y = 56.5;
		foreach ($txthead as $array) {
			$pdf->RotatedText($y, 20 + 111, $array['text'], 50);
			$y += 4.5;
		}
		
		$y = 90-4.5;
		foreach ($txt1 as $array) {
			$pdf->RotatedText($y, 101 + 111, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 90-4.5;
		foreach ($txt2 as $array) {
			$pdf->RotatedText($y, 50 + 111, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 119;
		foreach ($txt3 as $array) {
			$pdf->RotatedText($y, 101 + 111, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
		
		$y = 119;
		foreach ($txt4 as $array) {
			$pdf->RotatedText($y, 50 + 111, $array['text'], $array['maxwidth']);
			$y += 4.5;
		}
	}
	else {
		// Header
		$txthead = array(
			array('text'=>iconv('UTF-8', 'windows-1252', date('d/m/Y', strtotime($row_Recordset1['cuota_fe_pago']))."\n")),
			array('text'=>iconv('UTF-8', 'windows-1252', $row_Recordset1['cuota_recibo']))
		);
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(175, 53);
		foreach ($txthead as $array) {
			error_log('jara');
			printText($array['text'], $pdf, 10, 4.4);
		}
		
		// Date
		$date = date('d/m/Y', strtotime($row_Recordset1['cuota_fe_pago']));
		$pdf->SetXY(160, 142);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->MultiCell(34, 4.1, $date, 0, 'L');
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->SetTextColor(0,0,0);
		
		$pdf->SetXY(92.5, 87.5);
		foreach ($txt1 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.4);
		}
		
		$pdf->SetXY(140.5, 87.5);
		foreach ($txt2 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.4);
		}
		
		$pdf->SetXY(92.5, 116.5);
		foreach ($txt3 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.4);
		}
		
		$pdf->SetXY(140.5, 116.5);
		foreach ($txt4 as $array) {
			printText($array['text'], $pdf, $array['maxwidth'], 4.4);
		}
	}
	// OUTPUT
	if (isset($_GET['email'])) {
		$cc = explode(',', urldecode($_GET['email']));
		$to = $row_Recordset1['cliente_email'];
		$subject = $_GET['mail-subject'];
		$filename = 'temp/'.md5(microtime()).'.pdf';
		$pdf->Output($filename, 'F');
		$attachments = array();
		$attachments[] = array('file'=>$filename, 'name'=>'Recibo electronico.pdf', 'type'=>'application/pdf');
		echo send_mail(6, $row_Recordset1['poliza_id'], $to, $subject, TRUE, $attachments, $cc, NULL, $row_Recordset1['cuota_recibo']);
	}
	else {
		$pdf->Output();
	}
	
	
	// Free Recordset: Main
	mysql_free_result($Recordset1);			
?>