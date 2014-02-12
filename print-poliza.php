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
	error_reporting(E_ALL);
	ini_set('display_errors', '0');

	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
	// Require PDF libraries
	require_once('Classes/fpdf/fpdf.php');
	require_once('Classes/fpdf/fpdi.php');
	// Require PDF functions
	require_once('inc/pdf_functions.php');	
	require_once('inc/mail_functions.php');
?>
<?php
	// Obtain URL parameter
	$poliza_id = intval($_GET['id']);
	
	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT *, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ''), ' ', IFNULL(cliente_nombre, ''))), cliente_razon_social) as cliente_nombre FROM poliza JOIN (subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN (contacto) ON (poliza.cliente_id=contacto.cliente_id AND contacto_default=1) JOIN sucursal ON poliza.sucursal_id = sucursal.sucursal_id LEFT JOIN (poliza_plan, poliza_pack) ON poliza.poliza_plan_id = poliza_plan.poliza_plan_id AND poliza.poliza_pack_id = poliza_pack.poliza_pack_id
									WHERE poliza.poliza_id=%s",
									$poliza_id);
	$Recordset1 = mysql_query($query_Recordset1, $connection) or die(mysql_die());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	// If record not found
	if ($totalRows_Recordset1 !== 1) {
		die("Error: Poliza no encontrada.");
	}	
	// If no contact info found
	if (is_null($row_Recordset1['contacto_domicilio']) || is_null($row_Recordset1['contacto_nro'])) {
		die("Error: El cliente no tiene un contacto primario asignado.");
	}
	
	$endoso['anulacion'] = NULL;
	if (isset($_GET['endoso_id']) && $_GET['endoso_id']!='') {
		$endoso_id = mysql_real_escape_string($_GET['endoso_id']);
		$sql = sprintf("SELECT endoso_tipo_nombre, endoso_cuerpo, IF(endoso_tipo_grupo_id=1, 1, 0) AS anulacion, endoso_fecha_pedido FROM endoso JOIN endoso_tipo ON endoso_tipo.endoso_tipo_id = endoso.endoso_tipo_id WHERE endoso_id=%s", $endoso_id);
		$res = mysql_query($sql) or die(mysql_error());
		$endoso = mysql_fetch_assoc($res) or die('No se encontró el endoso.');
	}
	
	$row_Recordset1['poliza_pago_detalle'] = Encryption::decrypt($row_Recordset1['poliza_pago_detalle']);

	// Determine subtype
	switch($row_Recordset1['subtipo_poliza_tabla']) {
			
		case 'automotor':		
			// ---------------------------------- AUTOMOTOR ---------------------------------- //
			
			// Recordset: Automotor
			$query_Recordset2 = sprintf("SELECT * FROM automotor JOIN (automotor_tipo, seguro_cobertura_tipo, automotor_marca, seguro_cobertura_tipo_limite_rc, zona_riesgo) ON automotor.automotor_tipo_id = automotor_tipo.automotor_tipo_id AND automotor.seguro_cobertura_tipo_id = seguro_cobertura_tipo.seguro_cobertura_tipo_id and automotor.automotor_marca_id = automotor_marca.automotor_marca_id AND seguro_cobertura_tipo_limite_rc.seguro_cobertura_tipo_limite_rc_id = automotor.seguro_cobertura_tipo_limite_rc_id AND automotor.zona_riesgo_id = zona_riesgo.zona_riesgo_id WHERE automotor.poliza_id=%s", $row_Recordset1['poliza_id']);
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
				array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']),
				array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
				array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
				array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
			);
			$txt_titular_c2 = array(
				array('maxwidth' => 47, 'text' => ""),
				array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),								
				array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
				array('maxwidth' => 47, 'text' => ""),																
				array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
				array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc'])
			);
			$txt_poliza = array(
				array('maxwidth' => 55, 'text' => "Tipo de Seguro: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),			
				array('maxwidth' => 55, 'text' => "PÓLIZA Nº: ".$row_Recordset1['poliza_numero']),
				array('maxwidth' => 55, 'text' => "Fecha Solicitud: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_fecha_solicitud']))),
				array('maxwidth' => 55, 'text' => "VIGENCIA DESDE: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_desde']))),
				array('maxwidth' => 55, 'text' => "VIGENCIA HASTA: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_hasta'])))
			);
			$txt_marca_modelo = $row_Recordset2['automotor_marca_nombre']." - ".strtoupper($row_Recordset2['modelo']);
			$txt_datos_vehiculo_c1 = array(
				array('maxwidth' => 57, 'text' => "Tipo Vehículo: ".strtoupper($row_Recordset2['automotor_tipo_nombre'])),
				array('maxwidth' => 57, 'text' => "0KM: ".formatCB($row_Recordset2['0km'],'W')),
				array('maxwidth' => 57, 'text' => "Año: ".$row_Recordset2['ano']),
				array('maxwidth' => 57, 'text' => "Motor: ".$row_Recordset2['nro_motor']),
				array('maxwidth' => 57, 'text' => "Nº Chasis: ".$row_Recordset2['nro_chasis'])
			);
			$txt_datos_vehiculo_c2 = array(
				array('maxwidth' => 67, 'text' => "Uso: ".$row_Recordset2['uso']),
				array('maxwidth' => 67, 'text' => "Importado: ".formatCB($row_Recordset2['importado'],'W')),
				array('maxwidth' => 67, 'text' => "Accesorios: ".formatCB($row_Recordset2['accesorios'],'W')),
				array('maxwidth' => 67, 'text' => "Zona Riesgo: ".$row_Recordset2['zona_riesgo_nombre']),
				array('maxwidth' => 130, 'text' => "Acreedor: ".($row_Recordset2['prendado'] == 1 ? "Prendario (".$row_Recordset2['acreedor_rs']." / CUIT: ".$row_Recordset2['acreedor_cuit'].")" : "No"))
			);
			$txt_patente = "Patente: ".($row_Recordset2['automotor_carroceria_id']==17?'101':'').$row_Recordset2['patente_0'].$row_Recordset2['patente_1'];
			$txt_gnc_c1 = array(
				array('maxwidth' => 60, 'text' => "Nro. Oblea: ".$row_Recordset2['nro_oblea']),
				array('maxwidth' => 60, 'text' => "Nro. Regulador: ".$row_Recordset2['nro_regulador'])
			);
			$txt_gnc_c2 = array(
				array('maxwidth' => 68, 'text' => "Marca Regulador: ".$row_Recordset2['marca_regulador']),
				array('maxwidth' => 68, 'text' => "Marca Cilindro: ".$row_Recordset2['marca_cilindro'])
			);
			$txt_gnc_c3 = array(
				array('maxwidth' => 58, 'text' => "Venc. Oblea: ".(is_null($row_Recordset2['venc_oblea']) ? "" : strftime("%d/%m/%Y", strtotime($row_Recordset2['venc_oblea'])))),
				array('maxwidth' => 58, 'text' => "Nro. Tubo: ".$row_Recordset2['nro_tubo'])
			);
			$txt_sumas_c1 = array(
				array('maxwidth' => 95, 'text' => "Suma Asegurada del Vehículo"),
				array('maxwidth' => 95, 'text' => "Equipo GNC"),
				array('maxwidth' => 95, 'text' => "Accesorios"),
				array('maxwidth' => 95, 'text' => ""),
				array('maxwidth' => 95, 'text' => "TOTAL:")
			);
			$txt_sumas_c2 = array(
				array('maxwidth' => 95, 'text' => formatNumber($row_Recordset2['valor_vehiculo'])." "),
				array('maxwidth' => 95, 'text' => formatNumber($row_Recordset2['valor_gnc'])." "),
				array('maxwidth' => 95, 'text' => formatNumber($row_Recordset2['valor_accesorios'])." "),
				array('maxwidth' => 95, 'text' => ""),
				array('maxwidth' => 95, 'text' => formatNumber($row_Recordset2['valor_total'])." ")
			);
			$txt_cobertura = "Cobertura: ".$row_Recordset2['seguro_cobertura_tipo_nombre']." | Límite RC: ".$row_Recordset2['seguro_cobertura_tipo_limite_rc_valor']." | Franquicia: ".(!is_null($row_Recordset2['franquicia']) ? "$ ".formatNumber($row_Recordset2['franquicia'],0) : "-");
			$txt_observaciones = $row_Recordset2['observaciones'];			
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
			
			// Determine document type
			switch(substr($_GET['type'], 0, 2)) {
				case 'cc':
				
					/****************************************
					* CONSTANCIA DE COBERTURA
					*****************************************/
				
					// NEW DOCUMENT
					
					if (isset($_GET['print'])) {
						$pdf = new FPDI('P','mm',array(215.9,279.4));
						$pdf->AddPage();
						$pdf->setSourceFile('pdf/cc.pdf');
					}
					else {
						$pdf = new FPDI('P');
						$pdf->AddPage();
						$pdf->setSourceFile('pdf/cc_digital.pdf');
					}
					$tplIdx = $pdf->importPage(1);
					$pdf->useTemplate($tplIdx);
					// Fecha y hora
					$txt_date = "FECHA: ".date("d/m/Y")." HORA: ".date("h:i a");
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);										
					$pdf->SetXY(0, 45);
					// printText($txt_date, $pdf, 196, 0, 'R');
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
					// Marca - Modelo
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->SetTextColor(255,0,0);										
					$pdf->SetXY(11, 99);
					printText($txt_marca_modelo, $pdf, 190, 0);
					// Datos Vehículo
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(11, 105);
					foreach ($txt_datos_vehiculo_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 4.3);
					}
					$pdf->SetXY(70, 104.7);
					foreach ($txt_datos_vehiculo_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.7);
					}
					// Patente
					$pdf->RoundedRect(11 + 130, 86 + 20, 36, 5, 1, '1234', 'D');
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(142, 109);
					printText($txt_patente, $pdf, 35, 0);
					// GNC
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetXY(12, 137.5);
					foreach ($txt_gnc_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.7);
					}
					$pdf->SetXY(75, 137.5);
					foreach ($txt_gnc_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.7);
					}
					$pdf->SetXY(145, 137.5);
					foreach ($txt_gnc_c3 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.7);
					}					
					// Sumas Seguro
					$pdf->SetFont('Arial', '', 9);
					$pdf->SetTextColor(0,0,0);
					$pdf->SetXY(12, 155.3);
					foreach ($txt_sumas_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8);
					}
					$pdf->SetXY(11, 155.3);
					foreach ($txt_sumas_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
					}
					// Cobertura
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(12, 179);
					printText($txt_cobertura, $pdf, 190, 0);
					// Observaciones
					$pdf->SetFont('Arial', '', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(12, 193);
					printText($txt_observaciones, $pdf, 190, 0);
					// Forma de Pago					
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(12.5, 205);
					printText($txt_pago_c1, $pdf, 55, 3.8);
					printText($txt_pago_c3, $pdf, 100, 3.8);					
					$pdf->SetXY(70, 205);
					$pdf->SetXY(102, 205);
					printText($txt_pago_c2, $pdf, 30, 3.8);
					// Importes
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(149, 205);
					foreach ($txt_imp_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8);
					}
					$pdf->SetXY(149, 205);
					foreach ($txt_imp_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
					}																									
					// OUTPUT
					if (isset($_GET['email'])) {
						$cc = explode(',', urldecode($_GET['email']));
						$to = $row_Recordset1['cliente_email'];
						$filename = 'temp/'.md5(microtime()).'.pdf';
						$pdf->Output($filename, 'F');
						$attachments = array();
						$attachments[] = array('file'=>$filename, 'name'=>'Constancia de cobertura.pdf', 'type'=>'application/pdf');
						echo send_mail(1, $poliza_id, $to, 'Constancia de cobertura', TRUE, $attachments, $cc);
					}
					else {
						$pdf->Output();
					}
					break;
				case 'pe':
				
					/****************************************
					* PEDIDO DE EMISIÓN
					*****************************************/				
				
					// NEW DOCUMENT
					$pdf = new FPDI('P','mm',array(215.9,279.4));
					$pdf->SetAutoPageBreak(false);
					$pdf->AddPage();
					$pdf->setSourceFile('pdf/pe'.(isset($_GET['en'])&&$_GET['en']==1?'_dinamico':'').'.pdf');
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
						if($endoso['anulacion']) {
							$size_emitir = 30;
							$txt_emitir = "ENDOSO - ANULACION";
						}
					} 
					else {
						$txt_emitir = "EMITIR";						
					}
					
					if (isset($_GET['en']) && $_GET['en']==1) {
						// draw rects
						$x = 11;
						$y = 78.5;
					
						$pdf->SetFillColor(172,190,219);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 195.5, 6, 1, '1234', 'DF');
						$pdf->SetXY(95, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'VEHICULOS');
						
						$y += 7.5;
						$pdf->SetFillColor(200,207,231);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 195.5, 6, 1, '1234', 'DF');
						
						
						$y += 7.5;
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 195.5, 25, 1, '1234', 'D');
					
						$y += 26.5;
						$pdf->SetFillColor(172,190,219);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 195.5, 6, 1, '1234', 'DF');
						$pdf->SetXY(95, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Endoso');
												
						$y += 7.5;
						$pdf->RoundedRect($x - 0.5, $y, 195.5, 50, 1, '1234', 'D');
						
						
						$pdf->SetXY($x, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, "PRODUCTOR: ".strtoupper($row_Recordset1['productor_nombre']));
						$pdf->SetX($x + 60);
						$pdf->Write(5, "CODIGO: ".$row_Recordset1['productor_seguro_codigo']);
						$y += 7;

						
						$pdf->SetXY($x, $y);
						$pdf->Write(5, 'Motivo de endoso: '.$endoso['endoso_tipo_nombre']);
						$y += 7;
						$pdf->SetXY($x, $y);
						$pdf->Write(5, 'Vigencia del endoso: de '. date('d/m/Y', strtotime($endoso['endoso_fecha_pedido'])) . ' a ' . date('d/m/Y', strtotime($row_Recordset1['poliza_validez_hasta'])));
						$y += 7;
						$pdf->SetXY($x, $y);
						$endoso_cuerpo = iconv('UTF-8', 'windows-1252', $endoso['endoso_cuerpo']);
						$pdf->Write(5, 'Detalle: '.$endoso_cuerpo);
						
					}
					
					$pdf->SetFont('Arial', 'B', $size_emitir);
					$pdf->SetTextColor(0,0,0);										
					$pdf->SetXY(50, 11.5);
					printText($txt_emitir, $pdf, 120, 0);						
					// Compañía - Sucursal
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
					// Marca - Modelo
					$pdf->SetFont('Arial', 'B', 10);
					$pdf->SetTextColor(255,0,0);										
					$pdf->SetXY(12, 89);
					printText($txt_marca_modelo, $pdf, 190, 0);
					// Datos Vehículo
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(12, 95);
					foreach ($txt_datos_vehiculo_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 4.3);
					}
					$pdf->SetXY(70, 94.7);
					foreach ($txt_datos_vehiculo_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.7);
					}					
					// Patente
					$pdf->RoundedRect(11 + 132, 86 + 10, 36, 5, 1, '1234', 'D');
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(144, 98.5);
					printText($txt_patente, $pdf, 35, 0);
					
					if (isset($_GET['en']) && $_GET['en']==1) {
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
						// Inspecciones (General)
						$txt_ins_gral = array(
							array('maxwidth' => 34, 'text' => "Chapa: ".$row_Recordset2['chapa']),
							array('maxwidth' => 34, 'text' => "Pintura: ".$row_Recordset2['pintura']),
							array('maxwidth' => 34, 'text' => "Tipo de Pintura: ".$row_Recordset2['tipo_pintura']),
							array('maxwidth' => 34, 'text' => "Tapizado: ".$row_Recordset2['tapizado']),
							array('maxwidth' => 34, 'text' => "Combustible: ".$row_Recordset2['combustible']),
							array('maxwidth' => 34, 'text' => "Color: ".$row_Recordset2['color'])
						);
						$pdf->SetFont('Arial', '', 8);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetXY(12.5, 129.5);
						foreach ($txt_ins_gral as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.8);
						}
						// Inspecciones (Equipamiento)
						$txt_ins_eq_c1 = array(
							array('maxwidth' => 25, 'text' => "Alarma: ".FormatCB($row_Recordset2['alarma'],'X')),
							array('maxwidth' => 25, 'text' => "Corta Corriente: ".FormatCB($row_Recordset2['corta_corriente'],'X')),
							array('maxwidth' => 25, 'text' => "Corta Nafta: ".FormatCB($row_Recordset2['corta_nafta'],'X')),
							array('maxwidth' => 25, 'text' => "Traba Volante: ".FormatCB($row_Recordset2['traba_volante'],'X')),
							array('maxwidth' => 25, 'text' => "Matafuego: ".FormatCB($row_Recordset2['matafuego'],'X')),
							array('maxwidth' => 25, 'text' => "Tuercas: ".FormatCB($row_Recordset2['tuercas'],'X')),
							array('maxwidth' => 25, 'text' => "Antena: ".FormatCB($row_Recordset2['antena'],'X')),
							array('maxwidth' => 25, 'text' => "Estéreo: ".FormatCB($row_Recordset2['estereo'],'X'))
						
						);
						$txt_ins_eq_c2 = array(
							array('maxwidth' => 25, 'text' => "Parlantes: ".FormatCB($row_Recordset2['parlantes'],'X')),
							array('maxwidth' => 25, 'text' => "Aire: ".FormatCB($row_Recordset2['aire'],'X')),
							array('maxwidth' => 25, 'text' => "C. Eléctricos: ".FormatCB($row_Recordset2['cristales_electricos'],'X')),
							array('maxwidth' => 25, 'text' => "Faros Adic: ".FormatCB($row_Recordset2['faros_adicionales'],'X')),
							array('maxwidth' => 25, 'text' => "Cierre Sincro: ".FormatCB($row_Recordset2['cierre_sincro'],'X')),
							array('maxwidth' => 25, 'text' => "Techo Corredizo: ".FormatCB($row_Recordset2['techo_corredizo'],'X')),
							array('maxwidth' => 25, 'text' => "Dir. Hidráulica: ".FormatCB($row_Recordset2['direccion_hidraulica'],'X')),
							array('maxwidth' => 25, 'text' => "Frenos ABS: ".FormatCB($row_Recordset2['frenos_abs'],'X'))
						);
						$txt_ins_eq_c3 = array(
							array('maxwidth' => 25, 'text' => "Airbag: ".FormatCB($row_Recordset2['airbag'],'X')),
							array('maxwidth' => 25, 'text' => "C. Tonalizados: ".FormatCB($row_Recordset2['cristales_tonalizados'],'X')),
							array('maxwidth' => 25, 'text' => "Equipo Rastreo: ".FormatCB($row_Recordset2['equipo_rastreo_id']?1:0,'X')),
							array('maxwidth' => 25, 'text' => "Micro Grabado: ".FormatCB($row_Recordset2['micro_grabado'],'X')),
							array('maxwidth' => 25, 'text' => "GPS: ".FormatCB($row_Recordset2['gps'],'X'))												
						);											
						$pdf->SetFont('Arial', '', 8);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetXY(60.5, 133.7);
						foreach ($txt_ins_eq_c1 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.4);
						}
						$pdf->SetXY(91, 133.7);
						foreach ($txt_ins_eq_c2 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.4);
						}
						$pdf->SetXY(121.5, 133.7);
						foreach ($txt_ins_eq_c3 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.4);
						}
						// Inspecciones (Cubiertas)
						$txt_ins_cub = array(
							array('maxwidth' => 34, 'text' => "CUBIERTAS"),
							array('maxwidth' => 34, 'text' => "Medida: ".$row_Recordset2['cubiertas_medidas']),
							array('maxwidth' => 34, 'text' => "Marca: ".$row_Recordset2['cubiertas_marca']),
							array('maxwidth' => 34, 'text' => "Desgaste: "),
							array('maxwidth' => 34, 'text' => "Del. Izq: ".FormatNumber($row_Recordset2['cubiertas_desgaste_di'],0)." %"),
							array('maxwidth' => 34, 'text' => "Del. Der: ".FormatNumber($row_Recordset2['cubiertas_desgaste_dd'],0)." %"),
							array('maxwidth' => 34, 'text' => "Tra. Izq: ".FormatNumber($row_Recordset2['cubiertas_desgaste_ti'],0)." %"),
							array('maxwidth' => 34, 'text' => "Tra. Der: ".FormatNumber($row_Recordset2['cubiertas_desgaste_td'],0)." %"),
							array('maxwidth' => 34, 'text' => "1E Izq: ".(!is_null($row_Recordset2['cubiertas_desgaste_1ei']) ? FormatNumber($row_Recordset2['cubiertas_desgaste_1ei'],0)." %" : "-")),
							array('maxwidth' => 34, 'text' => "1E Der: ".(!is_null($row_Recordset2['cubiertas_desgaste_1ed']) ? FormatNumber($row_Recordset2['cubiertas_desgaste_1ed'],0)." %" : "-")),						
							array('maxwidth' => 34, 'text' => "Auxilio: ".FormatNumber($row_Recordset2['cubiertas_desgaste_auxilio'],0)." %")						
						);
						$pdf->SetFont('Arial', '', 8);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetXY(151, 129.5);
						foreach ($txt_ins_cub as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 2.8);
						}																									
						// GNC
						$pdf->SetFont('Arial', '', 8);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetXY(12, 181.5);
						foreach ($txt_gnc_c1 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.7);
						}
						$pdf->SetXY(75, 181.5);
						foreach ($txt_gnc_c2 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.7);
						}
						$pdf->SetXY(145, 181.5);
						foreach ($txt_gnc_c3 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.7);
						}
						// Sumas Seguro
						$pdf->SetFont('Arial', '', 9);
						$pdf->SetTextColor(0,0,0);
						$pdf->SetXY(12, 199);
						foreach ($txt_sumas_c1 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.8);
						}
						$pdf->SetXY(11, 199);
						foreach ($txt_sumas_c2 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
						}
						// Cobertura
						$pdf->SetFont('Arial', 'B', 9);
						$pdf->SetTextColor(0,0,0);								
						$pdf->SetXY(12, 223);
						printText($txt_cobertura, $pdf, 190, 0);
						// Observaciones
						$pdf->SetFont('Arial', '', 9);
						$pdf->SetTextColor(0,0,0);								
						$pdf->SetXY(12, 237);
						printText($txt_observaciones, $pdf, 190, 0);				
					
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
							array('maxwidth' => 95, 'text' => "AJUSTE: ".formatNumber($row_Recordset1['poliza_ajuste'],0)." %")
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
						}																																				}
					// OUTPUT
					if (isset($_GET['email'])) {
						$cc = explode(',', urldecode($_GET['email']));
						$to = $row_Recordset1['seguro_email_emision'];
						$subject = $_GET['mail-subject'];
						$type = 0;
						switch(substr($_GET['type'], 2)) {
							case '':
								$file_name = ($row_Recordset2['automotor_carroceria_id']==17?'101':'').$row_Recordset2['patente_0'].$row_Recordset2['patente_1'].'.pdf';
								$type = 2;
							break;
							case 'mc':
								$file_name = ($row_Recordset2['automotor_carroceria_id']==17?'101':'').$row_Recordset2['patente_0'].$row_Recordset2['patente_1'].'.pdf';
								$type = 3;
							break;
							case 're':
								$file_name = 'Renovacion '.($row_Recordset2['automotor_carroceria_id']==17?'101':'').$row_Recordset2['patente_0'].$row_Recordset2['patente_1'].'.pdf';
								$type = 4;
							break;
							case 'en':
								$to = $row_Recordset1['seguro_email_endosos'];
								$file_name = ($endoso['anulacion']?'Anulacion':'Endoso')." PZA.".$row_Recordset1['poliza_numero'].".pdf";
								$type = 5;
							break;
						}
						$filename = 'temp/'.md5(microtime()).'.pdf';
						$pdf->Output($filename, 'F');
						$attachments = array();
						$attachments[] = array('file'=>$filename, 'name'=>$file_name, 'type'=>'application/pdf');
						echo send_mail($type, $poliza_id, $to, $subject, FALSE, $attachments, $cc);
					}
					else {
						$pdf->Output();
					}
					
				
					break;
				default:
					die("Error: Tipo de documento no definido.");
					break;
			}
			
			// Close Recordset: Automotor
			mysql_free_result($Recordset2);
		
			// Break
			break;
			
		case 'accidentes':
			// ---------------------------------- ACCIDENTES ---------------------------------- //
		
			// Recordset: Accidentes
			$query_Recordset2 = sprintf("SELECT *  FROM accidentes WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);

			// If no record found
			if ($totalRows_Recordset2 === 0) {
				die("Error: Detalle de Poliza no encontrado.");
			}
		
			$query_Recordset3 = sprintf("SELECT accidentes_asegurado_nombre, accidentes_asegurado_documento, DATE_FORMAT(accidentes_asegurado_nacimiento, '%%d/%%m/%%y') as accidentes_asegurado_nacimiento, asegurado_actividad_nombre, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, IF(accidentes_asegurado_beneficiario<3, 'Si', 'No') AS accidentes_asegurado_legal, accidentes_asegurado_beneficiario_nombre, accidentes_asegurado_beneficiario_documento, accidentes_asegurado_beneficiario_nacimiento, IF(accidentes_asegurado_beneficiario=2, 'Tomador', '') AS accidentes_asegurado_beneficiario_tomador FROM accidentes_asegurado JOIN asegurado_actividad ON asegurado_actividad.asegurado_actividad_id = accidentes_asegurado_actividad WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$asegurados = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<80;$i++) {
					$asegurados[] = $row;
					$asegurados[] = array('beneficiario'=>true);
				// }
				// break;
			}
			
			$query_Recordset3 = sprintf("SELECT * FROM accidentes_clausula WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$clausulas = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<45;$i++) {
					$clausulas[] = $row;
				// }
			}
			
			
			// Compose Shared Texts
			$txt_titular_c1 = array(
				array('maxwidth' => 130, 'text' => "Nombre/Razón Social: ".$row_Recordset1['cliente_nombre']),
				array('maxwidth' => 130, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].(is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso']).(is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'])),
				array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']),
				array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
				array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
				array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
			);
			$txt_titular_c2 = array(
				array('maxwidth' => 47, 'text' => ""),								
				array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
				array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
				array('maxwidth' => 47, 'text' => ""),																
				array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
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
			
			// Determine document type
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
					if (count($asegurados)){
						$asegurados[] = array('total'=>true);
						
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
						$pdf->SetXY(90, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'ASEGURADOS');
						
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 196, (min(count($asegurados), 34) * 5) + 8, 1, '1234', 'D');
						
						$y += 9.5;
						
						// Imprimir asegurados
						$pdf->SetXY($x, $y);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Write(5, 'Nombre');
						$pdf->SetX($x + 45);
						$pdf->Write(5, 'DNI');
						$pdf->SetX($x + 63);
						$pdf->Write(5, 'Nac.');
						$pdf->SetX($x + 79);
						$pdf->Write(5, 'Actividad');
						$pdf->SetX($x + 132);
						$pdf->Write(5, 'Legal');
						$pdf->SetX($x + 145);
						$pdf->Write(5, 'Asegurado');
						$pdf->SetX($x + 170);
						$pdf->Write(5, 'Gastos Farm.');
						$y += 5;
						
						$count_asegurados = 0;
						$count_asegurados_per_page = 0;
						$max_asegurados = 34;
						$total_suma_asegurada = 0;
						$total_gastos_medicos = 0;
						foreach ($asegurados as $asegurado){
							if ($count_asegurados_per_page % $max_asegurados == 0 and $count_asegurados_per_page > 0) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
								$pdf->SetXY(90, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'ASEGURADOS (Cont)');
								
								$y += 7.5;
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x-0.5, $y, 196, (min(count($asegurados)-$count_asegurados, 43) * 5) + 4.5, 1, '1234', 'D');
							
								$y += 2;
								$count_asegurados_per_page = 0;
								$max_asegurados = 43;
							}
							if (isset($asegurado['beneficiario'])) {
								continue;
							}
							if (!isset($asegurado['total'])){
								$pdf->SetXY($x, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48));
								$pdf->SetX($x + 43);
								$pdf->Write(5, $asegurado['accidentes_asegurado_documento']);
								$pdf->SetX($x + 63);
								$pdf->Write(5, $asegurado['accidentes_asegurado_nacimiento']);
								$pdf->SetX($x + 79);
								$pdf->Write(5, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50));
								$pdf->SetX($x + 132);
								$pdf->Write(5, ($asegurado['accidentes_asegurado_beneficiario_tomador']!='' ? $asegurado['accidentes_asegurado_beneficiario_tomador'] : $asegurado['accidentes_asegurado_legal']));
								$pdf->SetX($x + 145);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2));
								
								$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
								$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
								
								$y += 5;
								
								if ($asegurado['accidentes_asegurado_legal']=='No') {
									$pdf->SetXY($x, $y);
									$pdf->SetFont('Arial', 'I', 7);
									$pdf->Write(5, trimText($asegurado['accidentes_asegurado_beneficiario_nombre'], $pdf, 48));
									$pdf->SetX($x + 48);
									$pdf->Write(5, $asegurado['accidentes_asegurado_beneficiario_documento']);
									$pdf->SetX($x + 70);
									$pdf->Write(5, '(Beneficiario)');
									$count_asegurados++;
									$count_asegurados_per_page++;
								}
							}
							else {
								$pdf->SetXY($x, $y);
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Write(5, 'Total: '.$count_asegurados);
								$pdf->SetX($x + 145);
								$pdf->Write(5, '$'.formatNumber($total_suma_asegurada, 2));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($total_gastos_medicos, 2));
							}
							$count_asegurados++;
							$count_asegurados_per_page++;
							$y += 5;
						}
						$y += 4;
					}

					if (count($clausulas)) {
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
						$pdf->SetXY(76, $y + 0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'CLAUSULAS DE NO REPETICION');
						
						// Imprimir clausulas
						$y += 9;
						$pdf->SetXY($x, $y);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Write(5, 'Nombre');
						$pdf->SetX($x + 70);
						$pdf->Write(5, 'CUIT');
						$pdf->SetX($x + 105);
						$pdf->Write(5, 'Domicilio');
						
						$y += 5;
						
						$count_clausulas = 0;
						$count_clausulas_per_page = 0;
						$max_clausulas = count($asegurados)?$max_asegurados - $count_asegurados_per_page -1 :34;
						// echo $max_clausulas;
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y-6, 196, (min(count($clausulas), $max_clausulas) * 5) + 6, 1, '1234', 'D');
						
						foreach ($clausulas as $clausula){
							if ($count_clausulas_per_page % $max_clausulas == 0 and $count_clausulas_per_page > 0) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, 6, 1, '1234', 'DF');
								$pdf->SetXY(78, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'CLAUSULAS DE NO REPETICION (Cont)');
							
								$count_clausulas_per_page = 0;
								$max_clausulas = 43;
								
								$y += 7.5;
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, (min(count($clausulas)-$count_clausulas, $max_clausulas) * 5) + 4.5, 1, '1234', 'D');
								$y += 2;
							}
							$pdf->SetXY($x, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText($clausula['accidentes_clausula_nombre'], $pdf, 70));
							$pdf->SetX($x + 70);
							$pdf->Write(5, $clausula['accidentes_clausula_cuit']);
							$pdf->SetX($x + 105);
							$pdf->Write(5, trimText($clausula['accidentes_clausula_domicilio'], $pdf, 85));
							
							$y += 5;
							$count_clausulas++;
							$count_clausulas_per_page++;
						}
						
					}
					// Footer
					if ($y > 198.5) {
						newPage($pdf, false);
					}
					$y = 200;
					$pdf->SetFillColor(229,233,253);
					$pdf->SetDrawColor(138,162,234);
					$pdf->SetLineWidth(0.6);
					$pdf->RoundedRect(10.5, $y, 135, 19, 1, '1234', 'DF');
					$pdf->RoundedRect(146, $y, 60, 19, 1, '1234', 'DF');
					$pdf->SetFont('Arial','B',10);
					$pdf->SetXY(65,$y + 1);
					$pdf->Write(5, 'Forma de Pago');
					$pdf->SetXY(168,$y + 1);
					$pdf->Write(5, 'Importes');
					
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
					// Importes
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8);
					}
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
					}
					
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
			
					
					function newPage($pdf, $first, $endoso_anulacion = NULL) {
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
						if (count($asegurados)){
							$asegurados[] = array('total'=>true);
						
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect(10.5, $y, 196, 6, 1, '1234', 'DF');
							$pdf->SetXY(92, $y+0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, 'ASEGURADOS');
						
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect(10.5, $y + 7.5, 196, (min(count($asegurados), 36) * 5) + 8, 1, '1234', 'D');
						
							$y += 9.5;
						
							// Imprimir asegurados
							$pdf->SetXY($x, $y);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Write(5, 'Nombre');
							$pdf->SetX($x + 45);
							$pdf->Write(5, 'DNI');
							$pdf->SetX($x + 63);
							$pdf->Write(5, 'Nac.');
							$pdf->SetX($x + 79);
							$pdf->Write(5, 'Actividad');
							$pdf->SetX($x + 132);
							$pdf->Write(5, 'Legal');
							$pdf->SetX($x + 145);
							$pdf->Write(5, 'Asegurado');
							$pdf->SetX($x + 170);
							$pdf->Write(5, 'Gastos Farm.');
							$y += 5;
						
							$count_asegurados = 0;
							$count_asegurados_per_page = 0;
							$max_asegurados = 36;
							$total_suma_asegurada = 0;
							$total_gastos_medicos = 0;
							foreach ($asegurados as $asegurado){
								if ($count_asegurados_per_page % $max_asegurados == 0 and $count_asegurados_per_page > 0) {
									newPage($pdf, false);				
									$pdf->SetFillColor(221,227,237);
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect(10.5, 46, 196, 6, 1, '1234', 'DF');
									$pdf->SetXY(92, 46);
									$pdf->SetFont('Arial','B',10);
									$pdf->Write(5, 'ASEGURADOS (Cont)');
							
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect(10.5, 53.5, 196, (min(count($asegurados)-$count_asegurados, 43) * 5) + 4.5, 1, '1234', 'D');
							
									$y = 55.5;
									$count_asegurados_per_page = 0;
									$max_asegurados = 43;
								}
								if (isset($asegurado['beneficiario'])) {
									$count_asegurados++;
									$count_asegurados_per_page++;
									continue;
								}
								if (!isset($asegurado['total'])){
									$pdf->SetXY($x, $y);
									$pdf->SetFont('Arial', '', 7);
									$pdf->Write(5, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48));
									$pdf->SetX($x + 43);
									$pdf->Write(5, $asegurado['accidentes_asegurado_documento']);
									$pdf->SetX($x + 63);
									$pdf->Write(5, $asegurado['accidentes_asegurado_nacimiento']);
									$pdf->SetX($x + 79);
									$pdf->Write(5, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50));
									$pdf->SetX($x + 132);
									$pdf->Write(5, ($asegurado['accidentes_asegurado_beneficiario_tomador']!='' ? $asegurado['accidentes_asegurado_beneficiario_tomador'] : $asegurado['accidentes_asegurado_legal']));
									$pdf->SetX($x + 145);
									$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2));
									$pdf->SetX($x + 170);
									$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2));
								
									$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
									$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
								
									$y += 5;
									if ($asegurado['accidentes_asegurado_legal']=='No') {
										$pdf->SetXY($x, $y);
										$pdf->SetFont('Arial', 'I', 7);
										$pdf->Write(5, trimText($asegurado['accidentes_asegurado_beneficiario_nombre'], $pdf, 48));
										$pdf->SetX($x + 48);
										$pdf->Write(5, $asegurado['accidentes_asegurado_beneficiario_documento']);
										$pdf->SetX($x + 70);
										$pdf->Write(5, '(Beneficiario)');
										$pdf->SetX($x + 125);
										$pdf->Write(5, $asegurado['accidentes_asegurado_beneficiario_tomador']);
									}
								}
								else {
									$pdf->SetXY($x, $y);
									$pdf->SetFont('Arial', 'B', 8);
									$pdf->Write(5, 'Total: '.$count_asegurados);
									$pdf->SetX($x + 145);
									$pdf->Write(5, '$'.formatNumber($total_suma_asegurada, 2));
									$pdf->SetX($x + 170);
									$pdf->Write(5, '$'.formatNumber($total_gastos_medicos, 2));
								}
								$count_asegurados++;
								$count_asegurados_per_page++;
								$y += 5;
							}
							$y += 4;
						}

						if (count($clausulas)) {
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect(10.5, $y, 196, 6, 1, '1234', 'DF');
							$pdf->SetXY(78, $y + 0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, 'CLAUSULAS DE NO REPETICION');
						
							// Imprimir clausulas
							$x = 11;
							$y += 9;
							$pdf->SetXY($x, $y);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Write(5, 'Nombre');
							$pdf->SetX($x + 70);
							$pdf->Write(5, 'CUIT');
							$pdf->SetX($x + 105);
							$pdf->Write(5, 'Domicilio');
						
							$y += 5;
						
							$count_clausulas = 0;
							$count_clausulas_per_page = 0;
							$max_clausulas = count($asegurados)?$max_asegurados - $count_asegurados_per_page -3 :36;
							// echo $max_clausulas;
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect(10.5, $y-6, 196, (min(count($clausulas), $max_clausulas) * 5) + 6, 1, '1234', 'D');
						
							foreach ($clausulas as $clausula){
								if ($count_clausulas_per_page % $max_clausulas == 0 and $count_clausulas_per_page > 0) {
									newPage($pdf, false);				
									$pdf->SetFillColor(221,227,237);
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect(10.5, 46, 196, 6, 1, '1234', 'DF');
									$pdf->SetXY(78, 46);
									$pdf->SetFont('Arial','B',10);
									$pdf->Write(5, 'CLAUSULAS DE NO REPETICION (Cont)');
							
									$y = 55.5;
									$count_clausulas_per_page = 0;
									$max_clausulas = 43;
								
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect(10.5, 53.5, 196, (min(count($clausulas)-$count_clausulas, $max_clausulas) * 5) + 4.5, 1, '1234', 'D');
								}
								$pdf->SetXY($x, $y);
								$pdf->SetFont('Arial', '', 8);
								$pdf->Write(5, trimText($clausula['accidentes_clausula_nombre'], $pdf, 70));
								$pdf->SetX($x + 70);
								$pdf->Write(5, $clausula['accidentes_clausula_cuit']);
								$pdf->SetX($x + 105);
								$pdf->Write(5, trimText($clausula['accidentes_clausula_domicilio'], $pdf, 85));
							
								$y += 5;
								$count_clausulas++;
								$count_clausulas_per_page++;
							}
						
						}
					
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
							array('maxwidth' => 95, 'text' => "AJUSTE: ".formatNumber($row_Recordset1['poliza_ajuste'],0)." %")
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
					die("Error: Tipo de documento no definido.");
					break;
			}
			// OUTPUT
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
						$to = $row_Recordset1['seguro_email_emision_vida'];
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
			break;
			
		case 'combinado_familiar':
			// ---------------------------------- COMBINADO FAMILIAR ---------------------------------- //
			
			// Recordset: Combinado Familiar
			$query_Recordset2 = sprintf("SELECT *  FROM combinado_familiar WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);

			// If no record found
			if ($totalRows_Recordset2 === 0) {
				die("Error: Detalle de Poliza no encontrado.");
			}
			
			$objects = array(
				array('db'=>'tv_aud_vid', 'desc'=>'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto'),
				array('db'=>'obj_esp_prorrata', 'desc'=>'Robo y/o Hurto de Objetos Especificos y/o Aparatos Electrodomesticos a Prorrata'),
				array('db'=>'equipos_computacion', 'desc'=>'Todo Riesgo Equipos de Computacion en Domicilio a Primer Riesgo Absoluto'),
				array('db'=>'film_foto', 'desc'=>'Robo de Filmadoras y/o Cam. Fotogrficas a Prorrata')
			);
			
			for ($i = 0; $i < count($objects); $i++) {
				$objects[$i]['items'] = array();
				$query_Recordset3 = sprintf('SELECT combinado_familiar_%1$s_cantidad as cantidad, combinado_familiar_%1$s_producto as producto, combinado_familiar_%1$s_marca as marca, combinado_familiar_%1$s_serial as serial_no, combinado_familiar_%1$s_valor as valor FROM combinado_familiar_%1$s WHERE combinado_familiar_id=%2$s', 
					$objects[$i]['db'], 
					$row_Recordset2['combinado_familiar_id']);
				
				$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
				while($row = mysql_fetch_assoc($Recordset3)) {
					// for($j=0;$j<5;$j++) { 
					// 	$row['producto'] .= ' '.$j;
						$objects[$i]['items'][] = $row;
					// }
				}
			}
			
			$detalle_plan = array();
			if ($row_Recordset1['poliza_plan_flag']) {
				$sql = 'SELECT poliza_pack_detalle_cobertura, poliza_pack_detalle_valor FROM poliza_pack_detalle WHERE poliza_pack_id = '.$row_Recordset1['poliza_pack_id'];
				$res = mysql_query($sql, $connection) or die(mysql_error());
				while($row = mysql_fetch_array($res)) {
					$detalle_plan[] = array('cobertura'=>$row[0], 'valor'=>$row[1]);
				}
			}
			
			// Compose Shared Texts
			$txt_titular_c1 = array(
				array('maxwidth' => 130, 'text' => "Nombre/Razón Social: ".$row_Recordset1['cliente_nombre']),
				array('maxwidth' => 130, 'text' => "Domicilio: ".$row_Recordset1['contacto_domicilio']." ".$row_Recordset1['contacto_nro'].(is_null($row_Recordset1['contacto_piso']) ? "" : " P ".$row_Recordset1['contacto_piso']).(is_null($row_Recordset1['contacto_dpto']) ? "" : " Dto. ".$row_Recordset1['contacto_dpto'])),
				array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']),
				array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
				array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
				array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
			);
			$txt_titular_c2 = array(
				array('maxwidth' => 47, 'text' => ""),								
				array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
				array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
				array('maxwidth' => 47, 'text' => ""),																
				array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
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
					$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 23, 1, '1234', 'D');
					
					$y += 9.5;

					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['combinado_familiar_domicilio_calle'], $pdf, 70).' '.$row_Recordset2['combinado_familiar_domicilio_nro']);
					$pdf->SetX($x + 65 + 30);
					$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['combinado_familiar_domicilio_piso'].' '.$row_Recordset2['combinado_familiar_domicilio_dpto']);
					
					$pdf->SetX($x + 95 + 30);
					$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['combinado_familiar_domicilio_localidad'], $pdf, 60));
					$pdf->SetX($x + 180);
					$pdf->Write(5, 'CP: '.trimText($row_Recordset2['combinado_familiar_domicilio_cp'], $pdf, 60));
					
					
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Barrio cerrado/country: '.$row_Recordset2['combinado_familiar_country']);
					$pdf->SetX($x + 95);
					$pdf->Write(5, 'Lote: '.$row_Recordset2['combinado_familiar_lote']);
					
					$y +=5;
					if (!$row_Recordset1['poliza_plan_flag']) {
						$pdf->SetXY($x + 2, $y);
						$pdf->Write(5, 'Incendio Edificio:         $'.formatNumber($row_Recordset2['combinado_familiar_inc_edif'], 2));
						$pdf->SetX($x + 70);
						$pdf->Write(5, 'Incendio Mobiliario: $'.formatNumber($row_Recordset2['combinado_familiar_inc_mob'], 2));
						$pdf->SetX($x + 140);
						$pdf->Write(5, 'Efectos Personales:     $'.formatNumber($row_Recordset2['combinado_familiar_ef_personales'], 2));
					}
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Valor tasado de la propiedad: $'.formatNumber($row_Recordset2['combinado_familiar_valor_tasado'], 2));
					
					$y = 123;
					
					if ($row_Recordset1['poliza_plan_flag']) {
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
						$pdf->SetXY(10, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Plan '.$row_Recordset1['poliza_plan_nombre'].' - Pack '.$row_Recordset1['poliza_pack_nombre'].' - Detalle');
					
						$pdf->SetLineWidth(0.4);

						$y += 9.5;
						foreach ($detalle_plan as $cobertura) {
							if ($y>270) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
								$pdf->SetXY(30, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'Plan '.$row_Recordset1['poliza_plan_nombre'].' - Pack '.$row_Recordset1['poliza_pack_nombre'].' - Detalle');
							
								$y += 7.5;
								$pdf->SetLineWidth(0.4);

								$y += 2;
							
							}
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText($cobertura['cobertura'], $pdf, 200).': '.formatNumber($cobertura['valor'], 2));
							$y += 5;
						}
						$y += 4;
					}
					else {
						foreach ($objects as $object) {
							$items = $object['items'];
							$description = $object['desc'];
						
							if (!count($items)) continue;
							$items[] = array('total'=>true);
												
							if ($y>270) {
								newPage($pdf, false);				
								$y = 48;
							}
						
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
							$pdf->SetXY(35, $y+0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, $object['desc']);
						
							$pdf->SetLineWidth(0.4);

							$y += 9.5;
						
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', 'B', 8);
							$pdf->Write(5, 'Cantidad');
							$pdf->SetX($x + 20);
							$pdf->Write(5, 'Producto');
							$pdf->SetX($x + 110);
							$pdf->Write(5, 'Marca');
							$pdf->SetX($x + 180);
							$pdf->Write(5, 'Valor');
							$y += 5;
												
							$count_items = 0;
							$total_suma_asegurada = 0;
							foreach ($items as $item) {
								if ($y>270) {
									newPage($pdf, false);				
									$y = 48;
									$pdf->SetFillColor(221,227,237);
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
									$pdf->SetXY(30, $y);
									$pdf->SetFont('Arial','B',10);
									$pdf->Write(5, $object['desc']);
								
									$y += 7.5;
									$pdf->SetLineWidth(0.4);

									$y += 2;
								
									$pdf->SetXY($x + 2, $y);
									$pdf->SetFont('Arial', 'B', 8);
									$pdf->Write(5, 'Cantidad');
									$pdf->SetX($x + 20);
									$pdf->Write(5, 'Producto');
									$pdf->SetX($x + 110);
									$pdf->Write(5, 'Marca');
									$pdf->SetX($x + 180);
									$pdf->Write(5, 'Valor');
									$y += 5;
								}
								if (!isset($item['total'])){
									$pdf->SetXY($x + 2, $y);
									$pdf->SetFont('Arial', '', 7);
									$pdf->Write(5, $item['cantidad']);
									$pdf->SetX($x + 20);
									$pdf->Write(5, trimText($item['producto'], $pdf, 85));
									$pdf->SetX($x + 110);
									$pdf->Write(5, trimText($item['marca'], $pdf, 58));
									printText('$'.formatNumber($item['valor'], 2), $pdf, 50, 5, 'R');
								
									$total_suma_asegurada += $item['valor'] * $item['cantidad'];
									$count_items += 1 * $item['cantidad'];
								}
								else {
									$pdf->SetXY($x + 2, $y);
									$pdf->SetFont('Arial', 'B', 8);
									$pdf->Write(5, 'Total: '.$count_items);
									printText('$'.formatNumber($total_suma_asegurada, 2), $pdf, 50, 5, 'R');
									$count_items++;
								}
								$y += 5;
							
							}
							$y += 4;
						}
						if ($y > 255) {
							newPage($pdf, false);
							$y = 48;
						}
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
						$pdf->SetXY(95, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Otros');
					
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 28, 1, '1234', 'D');
					
						$y += 9.5;

						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Cristales a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_cristales'], 2));
					
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'RC Hechos Privados a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_responsabilidad_civil'], 2));
					
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'RC por Incendio - (Excluye cosas de Linderos) - a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_rc_inc'], 2));
					
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText(' Daños por Agua al Mobil. y/o Ef. Pers. (Exc. Edificio) a Primer Riesgo Absoluto: $', $pdf, 120).formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Jugadores de Golf a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
						$y += 5;
					}
					
					// Footer
					if ($y > 200) {
						newPage($pdf, false);
					}
					$y = 200;
					$pdf->SetFillColor(229,233,253);
					$pdf->SetDrawColor(138,162,234);
					$pdf->SetLineWidth(0.6);
					$pdf->RoundedRect(10.5, $y, 135, 21, 1, '1234', 'DF');
					$pdf->RoundedRect(146, $y, 60, 21, 1, '1234', 'DF');
					$pdf->SetFont('Arial','B',10);
					$pdf->SetXY(65,$y + 1);
					$pdf->Write(5, 'Forma de Pago');
					$pdf->SetXY(168,$y + 1);
					$pdf->Write(5, 'Importes');
					
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
					// Importes
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8);
					}
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
					}
					
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
					$pdf->RoundedRect($x - 0.5, $y + 7.5, 195.5, 23, 1, '1234', 'D');
					
					$y += 9.5;

					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['combinado_familiar_domicilio_calle'], $pdf, 70).' '.$row_Recordset2['combinado_familiar_domicilio_nro']);
					$pdf->SetX($x + 65 + 30);
					$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['combinado_familiar_domicilio_piso'].' '.$row_Recordset2['combinado_familiar_domicilio_dpto']);
					
					$pdf->SetX($x + 95 + 30);
					$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['combinado_familiar_domicilio_localidad'], $pdf, 60));
					$pdf->SetX($x + 180);
					$pdf->Write(5, 'CP: '.trimText($row_Recordset2['combinado_familiar_domicilio_cp'], $pdf, 60));
					
					
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Barrio cerrado/country: '.$row_Recordset2['combinado_familiar_country']);
					$pdf->SetX($x + 95);
					$pdf->Write(5, 'Lote: '.$row_Recordset2['combinado_familiar_lote']);
					
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Incendio Edificio:         $'.formatNumber($row_Recordset2['combinado_familiar_inc_edif'], 2));
					$pdf->SetX($x + 70);
					$pdf->Write(5, 'Incendio Mobiliario: $'.formatNumber($row_Recordset2['combinado_familiar_inc_mob'], 2));
					$pdf->SetX($x + 140);
					$pdf->Write(5, 'Efectos Personales:     $'.formatNumber($row_Recordset2['combinado_familiar_ef_personales'], 2));
					
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Valor tasado de la propiedad: $'.formatNumber($row_Recordset2['combinado_familiar_valor_tasado'], 2));
					
					
										
					$y = 111.5;
					
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
						if ($row_Recordset1['poliza_plan_flag']) {
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
							$pdf->SetXY(10, $y+0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, 'Plan '.$row_Recordset1['poliza_plan_nombre'].' - Pack '.$row_Recordset1['poliza_pack_nombre'].' - Detalle');
					
							$pdf->SetLineWidth(0.4);

							$y += 9.5;
							foreach ($detalle_plan as $cobertura) {
								if ($y>270) {
									newPage($pdf, false);				
									$y = 48;
									$pdf->SetFillColor(221,227,237);
									$pdf->SetLineWidth(0.4);
									$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
									$pdf->SetXY(30, $y);
									$pdf->SetFont('Arial','B',10);
									$pdf->Write(5, 'Plan '.$row_Recordset1['poliza_plan_nombre'].' - Pack '.$row_Recordset1['poliza_pack_nombre'].' - Detalle');
							
									$y += 7.5;
									$pdf->SetLineWidth(0.4);

									$y += 2;
							
								}
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', '', 8);
								$pdf->Write(5, trimText($cobertura['cobertura'], $pdf, 200).': '.formatNumber($cobertura['valor'], 2));
								$y += 5;
							}
							$y += 4;
						}
						else {
							foreach ($objects as $object) {
								$items = $object['items'];
								$description = $object['desc'];
						
								if (!count($items)) continue;
								$items[] = array('total'=>true);
												
								if ($y>270) {
									newPage($pdf, false);				
									$y = 48;
								}
						
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
								$pdf->SetXY(35, $y+0.5);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, $object['desc']);
						
								$pdf->SetLineWidth(0.4);

								$y += 9.5;
						
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Write(5, 'Cantidad');
								$pdf->SetX($x + 20);
								$pdf->Write(5, 'Producto');
								$pdf->SetX($x + 110);
								$pdf->Write(5, 'Marca');
								$pdf->SetX($x + 180);
								$pdf->Write(5, 'Valor');
								$y += 5;
												
								$count_items = 0;
								$total_suma_asegurada = 0;
								foreach ($items as $item) {
									if ($y>270) {
										newPage($pdf, false);				
										$y = 48;
										$pdf->SetFillColor(221,227,237);
										$pdf->SetLineWidth(0.4);
										$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
										$pdf->SetXY(30, $y);
										$pdf->SetFont('Arial','B',10);
										$pdf->Write(5, $object['desc']);
								
										$y += 7.5;
										$pdf->SetLineWidth(0.4);

										$y += 2;
								
										$pdf->SetXY($x + 2, $y);
										$pdf->SetFont('Arial', 'B', 8);
										$pdf->Write(5, 'Cantidad');
										$pdf->SetX($x + 20);
										$pdf->Write(5, 'Producto');
										$pdf->SetX($x + 110);
										$pdf->Write(5, 'Marca');
										$pdf->SetX($x + 180);
										$pdf->Write(5, 'Valor');
										$y += 5;
									}
									if (!isset($item['total'])){
										$pdf->SetXY($x + 2, $y);
										$pdf->SetFont('Arial', '', 7);
										$pdf->Write(5, $item['cantidad']);
										$pdf->SetX($x + 20);
										$pdf->Write(5, trimText($item['producto'], $pdf, 85));
										$pdf->SetX($x + 110);
										$pdf->Write(5, trimText($item['marca'], $pdf, 58));
										printText('$'.formatNumber($item['valor'], 2), $pdf, 50, 5, 'R');
								
										$total_suma_asegurada += $item['valor'] * $item['cantidad'];
										$count_items += 1 * $item['cantidad'];
									}
									else {
										$pdf->SetXY($x + 2, $y);
										$pdf->SetFont('Arial', 'B', 8);
										$pdf->Write(5, 'Total: '.$count_items);
										printText('$'.formatNumber($total_suma_asegurada, 2), $pdf, 50, 5, 'R');
										$count_items++;
									}
									$y += 5;
							
								}
								$y += 4;
							}
							if ($y > 255) {
								newPage($pdf, false);
								$y = 48;
							}
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
							$pdf->SetXY(95, $y+0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, 'Otros');
					
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 28, 1, '1234', 'D');
					
							$y += 9.5;

							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Cristales a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_cristales'], 2));
					
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'RC Hechos Privados a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_responsabilidad_civil'], 2));
					
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'RC por Incendio - (Excluye cosas de Linderos) - a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_rc_inc'], 2));
					
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText(' Daños por Agua al Mobil. y/o Ef. Pers. (Exc. Edificio) a Primer Riesgo Absoluto: $', $pdf, 120).formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Jugadores de Golf a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
							$y += 5;			
						
						}
					
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
							array('maxwidth' => 95, 'text' => "AJUSTE: ".formatNumber($row_Recordset1['poliza_ajuste'],0)." %")
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
					die('Certificado no habilitado.');
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
			
			break;
		case 'incendio_edificio':
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
				array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']),
				array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
				array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
				array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
			);
			$txt_titular_c2 = array(
				array('maxwidth' => 47, 'text' => ""),								
				array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
				array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
				array('maxwidth' => 47, 'text' => ""),																
				array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
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
					$pdf->RoundedRect(10.5, $y, 135, 21, 1, '1234', 'DF');
					$pdf->RoundedRect(146, $y, 60, 21, 1, '1234', 'DF');
					$pdf->SetFont('Arial','B',10);
					$pdf->SetXY(65,$y + 1);
					$pdf->Write(5, 'Forma de Pago');
					$pdf->SetXY(168,$y + 1);
					$pdf->Write(5, 'Importes');
					
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
					// Importes
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c1 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8);
					}
					$pdf->SetXY(149, $y);
					foreach ($txt_imp_c2 as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
					}
					
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
							array('maxwidth' => 95, 'text' => "AJUSTE: ".formatNumber($row_Recordset1['poliza_ajuste'],0)." %")
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
			break;
			case 'integral_consorcio':
				// ---------------------------------- INTEGRAL CONSORCIO ---------------------------------- //

				// Recordset: Combinado Familiar
				$query_Recordset2 = sprintf("SELECT *  FROM integral_consorcio WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
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
					array('maxwidth' => 82, 'text' => "Localidad: ".$row_Recordset1['contacto_localidad']),
					array('maxwidth' => 130, 'text' => "Teléfonos: ".$row_Recordset1['contacto_telefono1']." / ".$row_Recordset1['contacto_telefono2']),
					array('maxwidth' => 82, 'text' => "Categoría de IVA: ".$row_Recordset1['cliente_cf']),
					array('maxwidth' => 82, 'text' => "Fecha de Nacimiento: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['cliente_nacimiento'])))
				);
				$txt_titular_c2 = array(
					array('maxwidth' => 47, 'text' => ""),								
					array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
					array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
					array('maxwidth' => 47, 'text' => ""),																
					array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
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
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 23-5, 1, '1234', 'D');

						$y += 9.5;

						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['integral_consorcio_domicilio_calle'], $pdf, 70).' '.$row_Recordset2['integral_consorcio_domicilio_nro']);
						$pdf->SetX($x + 65 + 30);
						$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['integral_consorcio_domicilio_piso'].' '.$row_Recordset2['integral_consorcio_domicilio_dpto']);

						$pdf->SetX($x + 95 + 30);
						$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['integral_consorcio_domicilio_localidad'], $pdf, 60));
						$pdf->SetX($x + 180);
						$pdf->Write(5, 'CP: '.trimText($row_Recordset2['integral_consorcio_domicilio_cp'], $pdf, 60));


						$y +=5;

						$y +=5;
						$pdf->SetXY($x + 2, $y);
						$pdf->Write(5, 'Valor tasado de la propiedad: $'.formatNumber($row_Recordset2['integral_consorcio_valor_tasado'], 2));

						$y += 8.5;


						if ($y > 255) {
							newPage($pdf, false);
							$y = 48;
						}
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
						$pdf->SetXY(95, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Otros');

						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 28 * 2, 1, '1234', 'D');

						$y += 9.5;

						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Incendio Contenido General - Partes Comunes: $'.formatNumber($row_Recordset2['integral_consorcio_inc_contenido'], 2));

						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText('Robo Contenido General Mobiliario / Objetos Específicos – Partes Comunes: $', $pdf, 150).formatNumber($row_Recordset2['integral_consorcio_robo_gral'], 2));

						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Robo Matafuegos: $'.formatNumber($row_Recordset2['integral_consorcio_robo_matafuegos'], 2));

						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText('Robo de Luces de Emergencia, Cámaras de Seguridad y Mangueras de Incendio: $', $pdf, 150).formatNumber($row_Recordset2['integral_consorcio_robo_lcm'], 2));

						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'RC Comprensiva: $'.formatNumber($row_Recordset2['integral_consorcio_rc_comprensiva'], 2));

						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Cristales y/o Vidrios y/o Espejos: $'.formatNumber($row_Recordset2['integral_consorcio_cristales'], 2));
						
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText('Daños por Agua al Contenido de propiedad común: $', $pdf, 120).formatNumber($row_Recordset2['integral_consorcio_danios_agua'], 2));
						
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText('Responsabilidad Civil Garaje – Cubierto o Descubierto - por la guarda y/o depósito de vehículos: $', $pdf, 120).formatNumber($row_Recordset2['integral_consorcio_rc_garage'], 2));
						
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, trimText('Acc. Pers. para el Personal que preste serv. al Consorcio sin rel. de dep. laboral en los términos de la Ley de Contrato de Trabajo: $', $pdf, 200).formatNumber($row_Recordset2['integral_consorcio_acc_personales'], 2));
						
						$y += 5;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Robo de Dinero de las Expensas en poder del Encargado: $'.formatNumber($row_Recordset2['integral_consorcio_robo_exp'], 2));


						$y += 5;

						// Footer
						if ($y > 200) {
							newPage($pdf, false);
						}
						$y = 200;
						$pdf->SetFillColor(229,233,253);
						$pdf->SetDrawColor(138,162,234);
						$pdf->SetLineWidth(0.6);
						$pdf->RoundedRect(10.5, $y, 135, 21, 1, '1234', 'DF');
						$pdf->RoundedRect(146, $y, 60, 21, 1, '1234', 'DF');
						$pdf->SetFont('Arial','B',10);
						$pdf->SetXY(65,$y + 1);
						$pdf->Write(5, 'Forma de Pago');
						$pdf->SetXY(168,$y + 1);
						$pdf->Write(5, 'Importes');

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
						// Importes
						$pdf->SetFont('Arial', '', 8);
						$pdf->SetTextColor(0,0,0);								
						$pdf->SetXY(149, $y);
						foreach ($txt_imp_c1 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.8);
						}
						$pdf->SetXY(149, $y);
						foreach ($txt_imp_c2 as $array) {
							printText($array['text'], $pdf, $array['maxwidth'], 3.8, 'R');
						}

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
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 195.5, 23-5, 1, '1234', 'D');

						$y += 9.5;

						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', '', 8);
						$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['integral_consorcio_domicilio_calle'], $pdf, 70).' '.$row_Recordset2['integral_consorcio_domicilio_nro']);
						$pdf->SetX($x + 65 + 30);
						$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['integral_consorcio_domicilio_piso'].' '.$row_Recordset2['integral_consorcio_domicilio_dpto']);

						$pdf->SetX($x + 95 + 30);
						$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['integral_consorcio_domicilio_localidad'], $pdf, 60));
						$pdf->SetX($x + 180);
						$pdf->Write(5, 'CP: '.trimText($row_Recordset2['integral_consorcio_domicilio_cp'], $pdf, 60));


						$y +=5;


						$y +=5;
						$pdf->SetXY($x + 2, $y);
						$pdf->Write(5, 'Valor tasado de la propiedad: $'.formatNumber($row_Recordset2['integral_consorcio_valor_tasado'], 2));


		
						$y = 106.5;

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
				
							if ($y > 255) {
								newPage($pdf, false);
								$y = 48;
							}
							$pdf->SetFillColor(221,227,237);
							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y, 197, 6, 1, '1234', 'DF');
							$pdf->SetXY(95, $y+0.5);
							$pdf->SetFont('Arial','B',10);
							$pdf->Write(5, 'Otros');

							$pdf->SetLineWidth(0.4);
							$pdf->RoundedRect($x - 0.5, $y + 7.5, 197, 28 * 2, 1, '1234', 'D');

							$y += 9.5;

							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Incendio Contenido General - Partes Comunes: $'.formatNumber($row_Recordset2['integral_consorcio_inc_contenido'], 2));

							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText('Robo Contenido General Mobiliario / Objetos Específicos – Partes Comunes: $', $pdf, 150).formatNumber($row_Recordset2['integral_consorcio_robo_gral'], 2));

							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Robo Matafuegos: $'.formatNumber($row_Recordset2['integral_consorcio_robo_matafuegos'], 2));

							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText('Robo de Luces de Emergencia, Cámaras de Seguridad y Mangueras de Incendio: $', $pdf, 150).formatNumber($row_Recordset2['integral_consorcio_robo_lcm'], 2));

							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'RC Comprensiva: $'.formatNumber($row_Recordset2['integral_consorcio_rc_comprensiva'], 2));

							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Cristales y/o Vidrios y/o Espejos: $'.formatNumber($row_Recordset2['integral_consorcio_cristales'], 2));
						
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText('Daños por Agua al Contenido de propiedad común: $', $pdf, 120).formatNumber($row_Recordset2['integral_consorcio_danios_agua'], 2));
						
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText('Responsabilidad Civil Garaje – Cubierto o Descubierto - por la guarda y/o depósito de vehículos: $', $pdf, 120).formatNumber($row_Recordset2['integral_consorcio_rc_garage'], 2));
						
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, trimText('Acc. Pers. para el Personal que preste serv. al Consorcio sin rel. de dep. laboral en los términos de la Ley de Contrato de Trabajo: $', $pdf, 200).formatNumber($row_Recordset2['integral_consorcio_acc_personales'], 2));
						
							$y += 5;
							$pdf->SetXY($x + 2, $y);
							$pdf->SetFont('Arial', '', 8);
							$pdf->Write(5, 'Robo de Dinero de las Expensas en poder del Encargado: $'.formatNumber($row_Recordset2['integral_consorcio_robo_exp'], 2));

							$y += 5;			

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
								array('maxwidth' => 95, 'text' => "AJUSTE: ".formatNumber($row_Recordset1['poliza_ajuste'],0)." %")
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
						die('Certificado no habilitado.');
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
						$filename = 'temp/'.uniqid().'.pdf';
						$pdf->Output($filename, 'F');
						$attachments = array();
						$attachments[] = array('file'=>$filename, 'name'=>$file_name, 'type'=>'application/pdf');
						echo send_mail($type, $poliza_id, $to, $subject, $body, $attachments, $cc);
					}
					else {
						$pdf->Output();
					}

				break;
		default:
			// ---------------------------------- UNDEFINED ---------------------------------- //		
			die("Error: Subtipo no habilitado.");
			break;
	}
	
	// Free Recordset: Main
	mysql_free_result($Recordset1);			
?>