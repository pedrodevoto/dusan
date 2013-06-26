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
	$poliza_id = intval($_GET['id']);
	
	// Recordset: Main
	$query_Recordset1 = sprintf("SELECT * FROM poliza JOIN (subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN (contacto) ON (poliza.cliente_id=contacto.cliente_id AND contacto_default=1)
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
				array('maxwidth' => 47, 'text' => "E-mail: ".$row_Recordset1['cliente_email']),
				array('maxwidth' => 47, 'text' => ""),								
				array('maxwidth' => 47, 'text' => "CP: ".$row_Recordset1['contacto_cp']),
				array('maxwidth' => 47, 'text' => ""),																
				array('maxwidth' => 47, 'text' => "CUIT: ".$row_Recordset1['cliente_cuit']),
				array('maxwidth' => 47, 'text' => $row_Recordset1['cliente_tipo_doc'].": ".$row_Recordset1['cliente_nro_doc'])
			);
			$txt_poliza = array(
				array('maxwidth' => 55, 'text' => "Tipo de Seguro: ".strtoupper($row_Recordset1['subtipo_poliza_nombre'])),			
				array('maxwidth' => 55, 'text' => "PÓLIZA Nº: ".$row_Recordset1['poliza_numero']),
				array('maxwidth' => 55, 'text' => "Renueva Póliza Nº: ".$row_Recordset1['poliza_renueva_num']),
				array('maxwidth' => 55, 'text' => "Fecha Solicitud: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_fecha_solicitud']))),
				array('maxwidth' => 55, 'text' => "VIGENCIA DESDE: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_desde']))),
				array('maxwidth' => 55, 'text' => "VIGENCIA HASTA: ".strftime("%d/%m/%Y", strtotime($row_Recordset1['poliza_validez_hasta'])))
			);
			$txt_marca_modelo = $row_Recordset2['marca']." - ".strtoupper($row_Recordset2['modelo']);
			$txt_datos_vehiculo_c1 = array(
				array('maxwidth' => 57, 'text' => "Tipo Vehículo: ".strtoupper($row_Recordset2['tipo'])),
				array('maxwidth' => 57, 'text' => "0KM: ".formatCB($row_Recordset2['0km'],'W')),
				array('maxwidth' => 57, 'text' => "Año: ".$row_Recordset2['ano']),
				array('maxwidth' => 57, 'text' => "Motor: ".$row_Recordset2['nro_motor']),
				array('maxwidth' => 57, 'text' => "Nº Chasis: ".$row_Recordset2['nro_chasis'])
			);
			$txt_datos_vehiculo_c2 = array(
				array('maxwidth' => 67, 'text' => "Uso: ".$row_Recordset2['uso']),
				array('maxwidth' => 67, 'text' => "Importado: ".formatCB($row_Recordset2['importado'],'W')),
				array('maxwidth' => 67, 'text' => "Accesorios: ".formatCB($row_Recordset2['accesorios'],'W')),
				array('maxwidth' => 67, 'text' => "Zona Riesgo: ".$row_Recordset2['zona_riesgo']),
				array('maxwidth' => 130, 'text' => "Acreedor: ".($row_Recordset2['prendado'] == 1 ? "Prendario (".$row_Recordset2['acreedor_rs']." / CUIT: ".$row_Recordset2['acreedor_cuit'].")" : "No")),
				array('maxwidth' => 67, 'text' => "Tarifa Infoauto: ".formatCB($row_Recordset2['infoauto'],'W'))
			);
			$txt_patente = "Patente: ".$row_Recordset2['patente'];
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
			$txt_cobertura = "Cobertura: ".$row_Recordset2['cobertura_tipo']." | Límite RC: ".$row_Recordset2['limite_rc']." | Franquicia: ".(!is_null($row_Recordset2['franquicia']) ? "$ ".formatNumber($row_Recordset2['franquicia'],0) : "-");
			$txt_observaciones = $row_Recordset2['observaciones'];			
			$txt_pago_c1 = "Forma de Pago: ".$row_Recordset1['poliza_medio_pago'];			
			$txt_pago_c2 = "Cuotas: ".$row_Recordset1['poliza_cant_cuotas'];
			// $txt_pago_c3 = "Cuota Base: $ ".formatNumber($row_Recordset1['poliza_premio'] / $row_Recordset1['poliza_cant_cuotas']);			
			$txt_imp_c1 = array(
				array('maxwidth' => 95, 'text' => "Prima:"),
				array('maxwidth' => 95, 'text' => "Premio:")
			);
			$txt_imp_c2 = array(
				array('maxwidth' => 95, 'text' => "$ ".formatNumber($row_Recordset1['poliza_prima'])." "),
				array('maxwidth' => 95, 'text' => "$ ".formatNumber($row_Recordset1['poliza_premio'])." ")
			);						
			
			// Determine document type
			switch($_GET['type']) {
				case 'cc':
				
					/****************************************
					* CONSTANCIA DE COBERTURA
					*****************************************/
				
					// NEW DOCUMENT
					$pdf = new FPDI('P','mm',array(215.9,279.4));
					$pdf->AddPage();
					$pdf->setSourceFile('pdf/cc.pdf');
					$tplIdx = $pdf->importPage(1);
					$pdf->useTemplate($tplIdx);
					// Fecha y hora
					$txt_date = "FECHA: ".date("d/m/Y")." HORA: ".date("h:i a");
					$pdf->SetFont('Arial', '', 8);
					$pdf->SetTextColor(0,0,0);										
					$pdf->SetXY(0, 45);
					printText($txt_date, $pdf, 196, 0, 'R');
					// Compañía
					$txt_compania = "Compañía Aseguradora: ".strtoupper($row_Recordset1['seguro_nombre']);
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
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(142, 109);
					printText($txt_patente, $pdf, 28, 0);
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
					$pdf->SetXY(70, 205);
					$pdf->SetXY(102, 205);
					printText($txt_pago_c2, $pdf, 30, 3.8);
					// printText($txt_pago_c3, $pdf, 40, 3.8);					
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
					$pdf->Output();
				
					break;
				case 'pe':
				
					/****************************************
					* PEDIDO DE EMISIÓN
					*****************************************/				
				
					// NEW DOCUMENT
					$pdf = new FPDI('P','mm',array(215.9,279.4));
					$pdf->SetAutoPageBreak(false);
					$pdf->AddPage();
					$pdf->setSourceFile('pdf/pe.pdf');
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
					foreach ($txt_date as $array) {
						printText($array['text'], $pdf, $array['maxwidth'], 5);
					}
					// Emitir
					if (isset($_GET['mc']) && $_GET['mc'] === "1") {
						$txt_emitir = "MC";
					} else {
						$txt_emitir = "EMITIR";						
					}
					$pdf->SetFont('Arial', 'B', 44);
					$pdf->SetTextColor(0,0,0);										
					$pdf->SetXY(50, 11.5);
					printText($txt_emitir, $pdf, 120, 0);						
					// Compañía
					$txt_compania = strtoupper($row_Recordset1['seguro_nombre']);
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
					$pdf->SetFont('Arial', 'B', 9);
					$pdf->SetTextColor(0,0,0);								
					$pdf->SetXY(144, 98.5);
					printText($txt_patente, $pdf, 28, 0);
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
						array('maxwidth' => 25, 'text' => "Equipo Rastreo: ".FormatCB($row_Recordset2['equipo_rastreo'],'X')),
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
					$pdf->SetXY(70, 250);
					$pdf->SetXY(102, 250);
					printText($txt_pago_c2, $pdf, 30, 3.8);
					// printText($txt_pago_c3, $pdf, 40, 3.8);	
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
					// OUTPUT
					$pdf->Output();		
				
					break;
				default:
					die("Error: Tipo de documento no definido.");
					break;
			}
			
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