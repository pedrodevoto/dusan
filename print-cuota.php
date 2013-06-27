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
	// Require PDF functions
	require_once('inc/pdf_functions.php');
?>
<?php
	// Obtain URL parameter
	$cuota_id = intval($_GET['id']);
	
	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT * FROM cuota JOIN (poliza, subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (cuota.poliza_id=poliza.poliza_id AND poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN (contacto) ON (poliza.cliente_id=contacto.cliente_id AND contacto_default=1)
									WHERE cuota_recibo IS NOT NULL AND cuota_estado='2 - Pagado' AND cuota.cuota_id=%s",
									$cuota_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	// If record not found
	if ($totalRows_Recordset1 !== 1) {
		die("Error: Cuota no encontrada.");
	}	
	// If no contact info found
	if (is_null($row_Recordset1['contacto_domicilio']) || is_null($row_Recordset1['contacto_nro'])) {
		die("Error: El cliente no tiene un contacto primario asignado.");
	}	

	// Determine subtype
	switch($row_Recordset1['subtipo_poliza_tabla']) {
			
		case 'automotor':
			// ---------------------------------- AUTOMOTOR ---------------------------------- //
			
			// Recordset: Automotor
			$query_Recordset2 = sprintf("SELECT * FROM automotor WHERE automotor.poliza_id=%s", $row_Recordset1['poliza_id']);
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
			$offset = 0;
			// New document
			if (isset($_GET['print'])) {
				$pdf = new FPDI('L','mm',array(297,210));
				$pdf->AddPage();
				$pdf->setSourceFile('pdf/cuota.pdf');
			}
			else {
				$pdf = new FPDI('L','mm',array(350,210));
				$pdf->AddPage();
				$pdf->setSourceFile('pdf/cuota_digital.pdf');
				$offset = 5;
			}
			
			$tplIdx = $pdf->importPage(1);
			$pdf->useTemplate($tplIdx);
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetTextColor(0,0,0);
			// Header
			$txthead = date("d/m/Y")."\n".
					   $row_Recordset1['cuota_recibo'];
			$txthead = iconv('UTF-8', 'windows-1252', $txthead);
			$pdf->SetXY(160 + $offset, 53);
			$pdf->MultiCell(34, 4.1, $txthead, 0, 'L');

			// Text 1
			$contacto_piso = is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso'];
			$contacto_dpto = is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'];
			$contacto_telefono1 = is_null($row_Recordset1['contacto_telefono1']) ? "-" : $row_Recordset1['contacto_telefono1'];			
			$txt1 = array(
						array('maxwidth' => 47, 'text' => "Sección: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),
						array('maxwidth' => 96, 'text' => "Señor: ".strtoupper($row_Recordset1['cliente_nombre'])),
						array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc']),
						array('maxwidth' => 47, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].$contacto_piso.$contacto_dpto),
						array('maxwidth' => 47, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']." - ".$row_Recordset1['contacto_cp']),
						array('maxwidth' => 47, 'text' => "Teléfono: ".$contacto_telefono1)
					);
			$pdf->SetXY(92.5, 87.5);
			foreach ($txt1 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 4.4);
			}

			// Text 2
			$cuota_periodo = ucfirst(strftime("%B/%Y", strtotime($row_Recordset1['cuota_periodo'])));
			$cuota_prox_venc = is_null($prox_cuota) ? "-" : strftime("%d/%m/%Y", strtotime($prox_cuota['cuota_vencimiento']));
			$poliza_numero = is_null($row_Recordset1['poliza_numero']) ? "-" : $row_Recordset1['poliza_numero'];
			$txt2 = array(
						array('maxwidth' => 47, 'text' => "Cía.: ".$row_Recordset1['seguro_nombre']),
						array('maxwidth' => 0, 'text' => ""),
						array('maxwidth' => 47, 'text' => "Cond. IVA: ".$row_Recordset1['cliente_cf']),
						array('maxwidth' => 47, 'text' => "Imputado a Póliza: ".$poliza_numero),
						array('maxwidth' => 47, 'text' => "Cuota: ".$row_Recordset1['cuota_nro']."/".$row_Recordset1['poliza_cant_cuotas']." - ".$cuota_periodo),
						array('maxwidth' => 47, 'text' => "Próximo Vto: ".$cuota_prox_venc)
					);						
			$pdf->SetXY(140.5, 87.5);
			foreach ($txt2 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 4.4);
			}
			
			// Text 3
			$cuota_ssn = $row_Recordset1['cuota_monto'] - ($row_Recordset1['cuota_monto'] * $percent_serv);
			$cuota_servicios = $row_Recordset1['cuota_monto'] * $percent_serv;
			$txt3 = array(
						array('maxwidth' => 96, 'text' => "Vehículo: ".$row_Recordset2['marca']." - ".$row_Recordset2['modelo']),
						array('maxwidth' => 47, 'text' => "Tipo: ".strtoupper($row_Recordset2['tipo'])),
						array('maxwidth' => 47, 'text' => "Uso: ".$row_Recordset2['uso']),
						array('maxwidth' => 47, 'text' => "SSN: ".formatNumber($cuota_ssn)." Servicios: ".formatNumber($cuota_servicios))
			);																			
			$pdf->SetXY(92.5, 116.5);
			foreach ($txt3 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 4.4);
			}
			
			// Text 4
			$txt4 = array(
						array('maxwidth' => 0, 'text' => ""),
						array('maxwidth' => 47, 'text' => "Año: ".formatNumber($row_Recordset2['ano'],0)." Patente: ".$row_Recordset2['patente']),
						array('maxwidth' => 47, 'text' => "Cobertura: ".$row_Recordset2['cobertura_tipo']),
						array('maxwidth' => 47, 'text' => "Total: ".formatNumber($row_Recordset1['cuota_monto']))
			);			
			$pdf->SetXY(140.5, 116.5);
			foreach ($txt4 as $array) {
				printText($array['text'], $pdf, $array['maxwidth'], 4.4);
			}
			
			
			if (isset($_GET['print'])) {
				$pdf->SetXY(276, 53);
				$pdf->MultiCell(34, 4.1, $txthead, 0, 'L');
				
				$pdf->SetXY(194, 87.5);			
				foreach ($txt1 as $array) {
					printText($array['text'], $pdf, $array['maxwidth'], 4.4);
				}
				$pdf->SetXY(241.5, 87.5);
				foreach ($txt2 as $array) {
					printText($array['text'], $pdf, $array['maxwidth'], 4.4);
				}
				$pdf->SetXY(194, 116.5);
				foreach ($txt3 as $array) {
					printText($array['text'], $pdf, $array['maxwidth'], 4.4);
				}
				$pdf->SetXY(241.5, 116.5);
				foreach ($txt4 as $array) {
					printText($array['text'], $pdf, $array['maxwidth'], 4.4);
				}
			}
			// Output
			$pdf->Output();								
			
			// Close Recordset: Automotor
			mysql_free_result($Recordset2);									
		
			// Break
			break;
			
		default:
			// ---------------------------------- UNDEFINED ---------------------------------- //		
			die("Error: Subtipo no habilitado.");
			break;
	}
	
	// Free Recordset: Main
	mysql_free_result($Recordset1);			
?>