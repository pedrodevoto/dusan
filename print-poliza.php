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
	ini_set('display_errors', '1');

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
	$query_Recordset1 = sprintf("SELECT * FROM poliza JOIN (subtipo_poliza, tipo_poliza, cliente, productor_seguro, productor, seguro) ON (poliza.subtipo_poliza_id=subtipo_poliza.subtipo_poliza_id AND subtipo_poliza.tipo_poliza_id=tipo_poliza.tipo_poliza_id AND poliza.cliente_id=cliente.cliente_id AND poliza.productor_seguro_id=productor_seguro.productor_seguro_id AND productor_seguro.productor_id=productor.productor_id AND productor_seguro.seguro_id=seguro.seguro_id) LEFT JOIN (contacto) ON (poliza.cliente_id=contacto.cliente_id AND contacto_default=1) JOIN sucursal ON poliza.sucursal_id = sucursal.sucursal_id
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
					printText($txt_date, $pdf, 196, 0, 'R');
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
		
			$query_Recordset3 = sprintf("SELECT accidentes_asegurado_nombre, accidentes_asegurado_documento, asegurado_actividad_nombre, accidentes_asegurado_suma_asegurada, accidentes_asegurado_gastos_medicos, IF(accidentes_asegurado_beneficiario=1, 'No', 'Si') AS accidentes_asegurado_legal FROM accidentes_asegurado JOIN asegurado_actividad ON asegurado_actividad.asegurado_actividad_id = accidentes_asegurado_actividad WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$asegurados = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<80;$i++) {
					$asegurados[] = $row;
				// }
				// break;
			}
			
			$query_Recordset3 = sprintf("SELECT * FROM accidentes_clausula WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$clausulas = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<15;$i++) {
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
						printText($txt_date, $pdf, 196, 0, 'R');
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
						$pdf->SetX($x + 50);
						$pdf->Write(5, 'DNI');
						$pdf->SetX($x + 70);
						$pdf->Write(5, 'Actividad');
						$pdf->SetX($x + 125);
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

							if (!isset($asegurado['total'])){
								$pdf->SetXY($x, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48));
								$pdf->SetX($x + 48);
								$pdf->Write(5, $asegurado['accidentes_asegurado_documento']);
								$pdf->SetX($x + 70);
								$pdf->Write(5, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50));
								$pdf->SetX($x + 125);
								$pdf->Write(5, $asegurado['accidentes_asegurado_legal']);
								$pdf->SetX($x + 145);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2));
								
								$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
								$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
								
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
					$pdf->SetXY(70, $y);
					$pdf->SetXY(102, $y);
					printText($txt_pago_c2, $pdf, 30, 3.8);
					// printText($txt_pago_c3, $pdf, 40, 3.8);	
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
			
					
					function newPage($pdf, $first) {
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
					}
					// NEW DOCUMENT
					$pdf = new FPDI('P','mm',array(215.9,279.4));
					
					newPage($pdf, true);				
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
						$pdf->SetX($x + 50);
						$pdf->Write(5, 'DNI');
						$pdf->SetX($x + 70);
						$pdf->Write(5, 'Actividad');
						$pdf->SetX($x + 125);
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

							if (!isset($asegurado['total'])){
								$pdf->SetXY($x, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, trimText($asegurado['accidentes_asegurado_nombre'], $pdf, 48));
								$pdf->SetX($x + 48);
								$pdf->Write(5, $asegurado['accidentes_asegurado_documento']);
								$pdf->SetX($x + 70);
								$pdf->Write(5, trimText($asegurado['asegurado_actividad_nombre'], $pdf, 50));
								$pdf->SetX($x + 125);
								$pdf->Write(5, $asegurado['accidentes_asegurado_legal']);
								$pdf->SetX($x + 145);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_suma_asegurada'], 2));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($asegurado['accidentes_asegurado_gastos_medicos'], 2));
								
								$total_suma_asegurada += $asegurado['accidentes_asegurado_suma_asegurada'];
								$total_gastos_medicos += $asegurado['accidentes_asegurado_gastos_medicos'];
								
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
					
					
					break;
				default:
					die("Error: Tipo de documento no definido.");
					break;
			}
			// OUTPUT
			$pdf->Output();	
			break;
			
		case 'combinado_familiar':
			// Recordset: Combinado Familiar
			$query_Recordset2 = sprintf("SELECT *  FROM combinado_familiar WHERE poliza_id=%s", $row_Recordset1['poliza_id']);
			$Recordset2 = mysql_query($query_Recordset2, $connection) or die(mysql_die());
			$row_Recordset2 = mysql_fetch_assoc($Recordset2);
			$totalRows_Recordset2 = mysql_num_rows($Recordset2);

			// If no record found
			if ($totalRows_Recordset2 === 0) {
				die("Error: Detalle de Poliza no encontrado.");
			}
			
			$query_Recordset3 = sprintf("SELECT * FROM combinado_familiar_tv_aud_vid WHERE combinado_familiar_id=%s", $row_Recordset2['combinado_familiar_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$tv_aud_vids = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<105;$i++) {
					$tv_aud_vids[] = $row;
				// }
			}
			
			$query_Recordset3 = sprintf("SELECT * FROM combinado_familiar_obj_esp_prorrata WHERE combinado_familiar_id=%s", $row_Recordset2['combinado_familiar_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$obj_esp_prorratas = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<105;$i++) {
					$obj_esp_prorratas[] = $row;
				// }
			}
			
			$query_Recordset3 = sprintf("SELECT * FROM combinado_familiar_equipos_computacion WHERE combinado_familiar_id=%s", $row_Recordset2['combinado_familiar_id']);
			$Recordset3 = mysql_query($query_Recordset3, $connection) or die(mysql_die());
			$equipos_comp = array();
			while($row = mysql_fetch_assoc($Recordset3)) {
				// for($i=0;$i<105;$i++) {
					$equipos_comp[] = $row;
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
			
			switch($_GET['type']) {
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
						printText($txt_date, $pdf, 196, 0, 'R');
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
					$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
					$pdf->SetXY(95, $y+0.5);
					$pdf->SetFont('Arial','B',10);
					$pdf->Write(5, 'General');
					
					$pdf->SetLineWidth(0.4);
					$pdf->RoundedRect($x - 0.5, $y + 7.5, 196, 23, 1, '1234', 'D');
					
					$y += 9.5;

					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Direccion: '.trimText($row_Recordset2['combinado_familiar_domicilio_calle'], $pdf, 60).' '.$row_Recordset2['combinado_familiar_domicilio_nro']);
					$pdf->SetX($x + 65);
					$pdf->Write(5, 'Piso/Dpto: '.$row_Recordset2['combinado_familiar_domicilio_piso'].' '.$row_Recordset2['combinado_familiar_domicilio_dpto']);
					
					$pdf->SetX($x + 95);
					$pdf->Write(5, 'Localidad: '.trimText($row_Recordset2['combinado_familiar_domicilio_localidad'], $pdf, 60));
					$pdf->SetX($x + 180);
					$pdf->Write(5, 'CP: '.trimText($row_Recordset2['combinado_familiar_domicilio_cp'], $pdf, 60));
					
					
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Prorrata:                      '.$row_Recordset2['combinado_familiar_prorrata'] .'%');
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'Incendio Edificio:         $'.formatNumber($row_Recordset2['combinado_familiar_inc_edif'], 2));
					$y +=5;
					$pdf->SetXY($x + 2, $y);
					$pdf->Write(5, 'R/C Lind:                     $'.formatNumber($row_Recordset2['combinado_familiar_rc_lind'], 2));
					
					$y = 123;
					
					if (count($tv_aud_vids)){
						$tv_aud_vids[] = array('total'=>true);
						
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
						$pdf->SetXY(35, $y+0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto');
						
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y + 7.5, 196, (min(count($tv_aud_vids), 30) * 5) + 8, 1, '1234', 'D');
						
						$y += 9.5;
						
						// Imprimir tv_aud_vid
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Write(5, 'Cantidad');
						$pdf->SetX($x + 20);
						$pdf->Write(5, 'Producto');
						$pdf->SetX($x + 110);
						$pdf->Write(5, 'Marca');
						$pdf->SetX($x + 170);
						$pdf->Write(5, 'Valor');
						$y += 5;
						
						$count_tv_aud_vids = 0;
						$count_tv_aud_vids_per_page = 0;
						$max_tv_aud_vids = 30;
						$total_suma_asegurada = 0;
						foreach ($tv_aud_vids as $tv_aud_vid){
							if ($count_tv_aud_vids_per_page % $max_tv_aud_vids == 0 and $count_tv_aud_vids_per_page > 0) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
								$pdf->SetXY(30, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'Todo Riesgo Equipos de TV - Audio y Video en Domicilio a Primer Riesgo Absoluto (Cont)');
								
								$y += 7.5;
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect($x-0.5, $y, 196, (min(count($tv_aud_vids)-$count_tv_aud_vids, 46) * 5) + 4.5, 1, '1234', 'D');
							
								$y += 2;
								$count_tv_aud_vids_per_page = 0;
								$max_tv_aud_vids = 46;
							}

							if (!isset($tv_aud_vid['total'])){
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, $tv_aud_vid['combinado_familiar_tv_aud_vid_cantidad']);
								$pdf->SetX($x + 20);
								$pdf->Write(5, trimText($tv_aud_vid['combinado_familiar_tv_aud_vid_producto'], $pdf, 85));
								$pdf->SetX($x + 110);
								$pdf->Write(5, trimText($tv_aud_vid['combinado_familiar_tv_aud_vid_marca'], $pdf, 58));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($tv_aud_vid['combinado_familiar_tv_aud_vid_valor'], 2));
								
								$total_suma_asegurada += $tv_aud_vid['combinado_familiar_tv_aud_vid_valor'];
								
							}
							else {
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Write(5, 'Total: '.$count_tv_aud_vids);
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($total_suma_asegurada, 2));
							}
							$count_tv_aud_vids++;
							$count_tv_aud_vids_per_page++;
							$y += 5;
						}
						$y += 4;
					}
			
					if (count($obj_esp_prorratas)) {
						$obj_esp_prorratas[] = array('total'=>true);
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
						$pdf->SetXY(38, $y + 0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Robo y/o Hurto de Objetos Especificos y/o Aparatos Electrodomesticos a Prorrata');
						
						// Imprimir clausulas
						$y += 9;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Write(5, 'Cantidad');
						$pdf->SetX($x + 20);
						$pdf->Write(5, 'Producto');
						$pdf->SetX($x + 110);
						$pdf->Write(5, 'Marca');
						$pdf->SetX($x + 170);
						$pdf->Write(5, 'Valor');
						
						
						$y += 5;
						
						$count_obj_esp_prorratas = 0;
						$count_obj_esp_prorratas_per_page = 0;
						$max_obj_esp_prorratas = count($tv_aud_vids)?$max_tv_aud_vids - $count_tv_aud_vids_per_page -3 :30;
						$total_suma_asegurada = 0;
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y-6, 196, (min(count($obj_esp_prorratas), $max_obj_esp_prorratas) * 5) + 6, 1, '1234', 'D');

						foreach ($obj_esp_prorratas as $obj_esp_prorrata){
							if ($count_obj_esp_prorratas_per_page % $max_obj_esp_prorratas == 0 and $count_obj_esp_prorratas_per_page > 0) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, 6, 1, '1234', 'DF');
								$pdf->SetXY(35, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'Robo y/o Hurto de Objetos Especificos y/o Aparatos Electrodomesticos a Prorrata (Cont)');
							
								$count_obj_esp_prorratas_per_page = 0;
								$max_obj_esp_prorratas = 46;
								
								$y += 7.5;
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, (min(count($obj_esp_prorratas)-$count_obj_esp_prorratas, $max_obj_esp_prorratas) * 5) + 4.5, 1, '1234', 'D');
								$y += 2;
							}
							
							if (!isset($obj_esp_prorrata['total'])) {
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, $obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_cantidad']);
								$pdf->SetX($x + 20);
								$pdf->Write(5, trimText($obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_producto'], $pdf, 85));
								$pdf->SetX($x + 110);
								$pdf->Write(5, trimText($obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_producto'], $pdf, 58));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_valor'], 2) . ' ($' . formatNumber($row_Recordset2['combinado_familiar_prorrata'] / 100 * $obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_valor'], 2) . ')' );
							
								$total_suma_asegurada += $obj_esp_prorrata['combinado_familiar_obj_esp_prorrata_valor'];
							}							
							else {
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Write(5, 'Total: '.$count_tv_aud_vids);
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($total_suma_asegurada, 2) . ' ($' . formatNumber($row_Recordset2['combinado_familiar_prorrata'] / 100 * $total_suma_asegurada, 2) . ')');
							}
							
							$y += 5;
							$count_obj_esp_prorratas++;
							$count_obj_esp_prorratas_per_page++;
						}
						$y += 3;
					}
					
					if (count($equipos_comp)) {
						$equipos_comp[] = array('total'=>true);
						$pdf->SetFillColor(221,227,237);
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
						$pdf->SetXY(40, $y + 0.5);
						$pdf->SetFont('Arial','B',10);
						$pdf->Write(5, 'Todo Riesgo Equipos de Computacion en Domicilio a Primer Riesgo Absoluto');
						
						// Imprimir clausulas
						$y += 9;
						$pdf->SetXY($x + 2, $y);
						$pdf->SetFont('Arial', 'B', 8);
						$pdf->Write(5, 'Cantidad');
						$pdf->SetX($x + 20);
						$pdf->Write(5, 'Producto');
						$pdf->SetX($x + 110);
						$pdf->Write(5, 'Marca');
						$pdf->SetX($x + 170);
						$pdf->Write(5, 'Valor');
						
						
						$y += 5;
						
						$count_equipos_comp = 0;
						$count_equipos_comp_per_page = 0;
						$max_equipos_comp = count($obj_esp_prorratas)?$max_obj_esp_prorratas - $count_obj_esp_prorratas_per_page -3 : count($tv_aud_vids)?$max_tv_aud_vids - $count_tv_aud_vids_per_page -3 : 30;
						$total_suma_asegurada = 0;
						$pdf->SetLineWidth(0.4);
						$pdf->RoundedRect($x - 0.5, $y-6, 196, (min(count($equipos_comp), $max_equipos_comp) * 5) + 6, 1, '1234', 'D');

						foreach ($equipos_comp as $equipo_comp){
							if ($count_equipos_comp_per_page % $max_equipos_comp == 0 and $count_equipos_comp_per_page > 0) {
								newPage($pdf, false);				
								$y = 48;
								$pdf->SetFillColor(221,227,237);
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, 6, 1, '1234', 'DF');
								$pdf->SetXY(37, $y);
								$pdf->SetFont('Arial','B',10);
								$pdf->Write(5, 'Todo Riesgo Equipos de Computacion en Domicilio a Primer Riesgo Absoluto (Cont)');
							
								$count_equipos_comp_per_page = 0;
								$max_equipos_comp = 46;
								
								$y += 7.5;
								$pdf->SetLineWidth(0.4);
								$pdf->RoundedRect(10.5, $y, 196, (min(count($equipos_comp)-$count_equipos_comp, $max_equipos_comp) * 5) + 4.5, 1, '1234', 'D');
								$y += 2;
							}
							
							if (!isset($equipo_comp['total'])) {
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', '', 7);
								$pdf->Write(5, $equipo_comp['combinado_familiar_equipos_computacion_cantidad']);
								$pdf->SetX($x + 20);
								$pdf->Write(5, trimText($equipo_comp['combinado_familiar_equipos_computacion_producto'], $pdf, 85));
								$pdf->SetX($x + 110);
								$pdf->Write(5, trimText($equipo_comp['combinado_familiar_equipos_computacion_marca'], $pdf, 58));
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($equipo_comp['combinado_familiar_equipos_computacion_valor'], 2));
							
								$total_suma_asegurada += $equipo_comp['combinado_familiar_equipos_computacion_valor'];
							}							
							else {
								$pdf->SetXY($x + 2, $y);
								$pdf->SetFont('Arial', 'B', 8);
								$pdf->Write(5, 'Total: '.$count_equipos_comp);
								$pdf->SetX($x + 170);
								$pdf->Write(5, '$'.formatNumber($total_suma_asegurada, 2));
							}
							
							$y += 5;
							$count_equipos_comp++;
							$count_equipos_comp_per_page++;
						}
						$y += 3;
					}
					
					$pdf->SetFillColor(221,227,237);
					$pdf->SetLineWidth(0.4);
					$pdf->RoundedRect($x - 0.5, $y, 196, 6, 1, '1234', 'DF');
					$pdf->SetXY(95, $y+0.5);
					$pdf->SetFont('Arial','B',10);
					$pdf->Write(5, 'Otros');
					
					$pdf->SetLineWidth(0.4);
					$pdf->RoundedRect($x - 0.5, $y + 7.5, 196, 23, 1, '1234', 'D');
					
					$y += 9.5;

					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Cristales a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_cristales'], 2));
					
					$y += 5;
					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Responsabilidad Civil Hechos Privados a Primer Riesgo Absoluto con Franquicia: $'.formatNumber($row_Recordset2['combinado_familiar_responsabilidad_civil'], 2));
					
					$y += 5;
					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, trimText('Daños por Agua al Mobiliario y/o Efectos Personales a Primer Riesgo Absoluto: $', $pdf, 120).formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
					$y += 5;
					$pdf->SetXY($x + 2, $y);
					$pdf->SetFont('Arial', '', 8);
					$pdf->Write(5, 'Jugadores de Golf a Primer Riesgo Absoluto: $'.formatNumber($row_Recordset2['combinado_familiar_danios_agua'], 2));
					
					$y += 5;
					
					// Footer
					if ($y > 200) {
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
					$pdf->SetXY(70, $y);
					$pdf->SetXY(102, $y);
					printText($txt_pago_c2, $pdf, 30, 3.8);
					// printText($txt_pago_c3, $pdf, 40, 3.8);	
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
				}
				$pdf->Output();
			
			break;
		default:
			// ---------------------------------- UNDEFINED ---------------------------------- //		
			die("Error: Subtipo no habilitado.");
			break;
	}
	
	// Free Recordset: Main
	mysql_free_result($Recordset1);			
?>