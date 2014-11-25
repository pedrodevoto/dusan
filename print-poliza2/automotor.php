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
	$pdf = new FPDIW('P');
	$pdf->AddPage();
	$pdf->setSourceFile(sprintf('pdf/nuevos/cc_%s_automotor.pdf', isset($_GET['print'])?'print':'email'));
	$tplIdx = $pdf->importPage(1);
	$pdf->useTemplate($tplIdx);
	
	$pdf->wwrite(12, 45, sprintf('Compañía aseguradora: %s - Sucursal: %s', $row['seguro_nombre'], $row['sucursal_nombre']), 12, 'B');
	
	$pdf->wwrite(11, 60, sprintf('Nombre/Razón social: %s', $row['cliente_nombre']));

	$pdf->wwrite(11, 64, sprintf('Domicilio: %s', $row['contacto_domicilio']));
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
	
	$pdf->wwrite(30, 94, $auto_modelo, 8);
	$pdf->wwrite(166, 92, ($row2['automotor_carroceria_id']==17?'101':'').$row2['patente_0'].$row2['patente_1'], 12, 'B');

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
	
	$pdf->wwrite(11, 135, sprintf('Nro. oblea: %s', $row2['nro_oblea']));
	$pdf->wwrite(11, 139, sprintf('Nro. regulador: %s', $row2['nro_regulador']));
	
	$pdf->wwrite(80, 135, sprintf('Marca regulador: %s', $row2['marca_regulador']));
	$pdf->wwrite(80, 139, sprintf('Nro. cilindro: %s', $row2['marca_cilindro']));
	
	$pdf->wwrite(146, 135, sprintf('Venc. oblea: %s', (is_null($row2['venc_oblea']) ? "" : strftime("%d/%m/%Y", strtotime($row2['venc_oblea'])))));
	$pdf->wwrite(146, 139, sprintf('Nro. tubo: %s', $row2['nro_tubo']));
	
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
	
	$pdf->wwrite(11, 202, sprintf('Forma de pago: %s', $row['poliza_medio_pago']));
	$pdf->wwrite(11, 207, sprintf('Detalle de pago: %s', preg_replace('/\n/', ' ', $row['poliza_pago_detalle'])));
	
	$pdf->wwrite(80, 202, sprintf('Plan de pago: %s cuotas', ($row['poliza_cant_cuotas']+$row['cuota_pfc'])));
	
	$pdf->wwrite(11, 215, sprintf('RECARGO: %s%%', formatNumber($row['poliza_recargo'])));
	$pdf->wwrite(11, 219, sprintf('DESCUENTO: %s%%', formatNumber($row['poliza_descuento'])));
	
	$pdf->wwrite(100, 215, sprintf('PRODUCTOR: %s', strtoupper($row['productor_nombre'])));
	$pdf->wwrite(100, 219, sprintf('DESCUENTO: %s', $row['productor_seguro_codigo']));
	
	
	break;
}

// Outpout
$pdf->Output();

?>