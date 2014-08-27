<?php
$pdf = new FPDIW('P','mm',array(210.058,297.18));
$pdf->setSourceFile('siniestros/parana.pdf');

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$pdf->wwrite(154, 15, $siniestro_numero);
$pdf->SetTextColor(255,0,0);
switch((string)$tipo_siniestro) {
	case '1':
	$pdf->wwrite(60, 22, 'DENUNCIA DE SINIESTRO (SIN RECLAMO A TERCEROS)', 10);
	break;
	case '2':
	$pdf->wwrite(60, 22, 'DENUNCIA DE SINIESTRO (CON RECLAMO A TERCEROS)', 10);
	break;
	case '3':
	$pdf->wwrite(60, 22, 'DENUNCIA DE ROBO/DAÑO (CON REPOSICION)', 10);
	break;
	case '4':
	$pdf->wwrite(60, 22, 'DENUNCIA DE ROBO/DAÑO (SIN REPOSICION)', 10);
	break;
}
$pdf->SetTextColor(0,0,0);
$pdf->wwrite(22, 51, $productor_nombre);
$pdf->wwrite(123.5, 51, $poliza_numero);
$pdf->wwrite(65, 58, $asegurado_nombre);
$pdf->wwrite(42, 63.5, $asegurado_calle);
$pdf->wwrite(132, 63.5, $asegurado_altura);
$pdf->wwrite(152, 63.5, $asegurado_piso);
$pdf->wwrite(38, 69, $asegurado_localidad);
$pdf->wwrite(135, 70, $asegurado_provincia, 5);
$pdf->wwrite(165, 69, $asegurado_cp);
$pdf->wwrite(35, 75, $asegurado_tel);
switch ((string)$asegurado_sexo) {
	case '1':
	$pdf->wwrite(167.4, 75, 'X');
	break;
	case '2':
	$pdf->wwrite(175.7, 75, 'X');
	break;
}
switch ((string)$conductor_habitual) {
	case '1':
	$pdf->wwrite(92.4, 80.8, 'X');
	break;
	case '0':
	$pdf->wwrite(103.8, 80.8, 'X');
	break;
}
if (isset($asegurado_fecha_nac)) {
	$age = DateTime::createFromFormat("Y-m-d", $asegurado_fecha_nac, $tz)->diff(new DateTime("now", $tz))->y;
	$pdf->wwrite(135, 80.8, $age);
}
$pdf->wwrite(67, 86, $asegurado_registro);
if (isset($asegurado_registro_venc)) {
	$date = DateTime::createFromFormat("Y-m-d", $asegurado_registro_venc);
	$pdf->wwrite(164, 86, $date->format('d'));
	$pdf->wwrite(175, 86, $date->format('m'));
	$pdf->wwrite(185, 86, $date->format('Y'));
}
$pdf->wwrite(127, 92, $asegurado_cel);
if (!$conductor_asegurado) {
	$pdf->wwrite(47, 98, $conductor_nombre);
	$pdf->wwrite(168, 98, $conductor_doc);
	$pdf->wwrite(42, 103.5, $conductor_calle);
	$pdf->wwrite(132, 103.5, $conductor_altura);
	$pdf->wwrite(38, 109, $conductor_localidad);
	$pdf->wwrite(135, 110, $conductor_provincia, 5);
	$pdf->wwrite(165, 109, $conductor_cp);
	switch ((string)$conductor_sexo) {
		case '1':
		$pdf->wwrite(36, 121.1, 'X');
		break;
		case '2':
		$pdf->wwrite(44.5, 121.1, 'X');
		break;
	}
	if (isset($conductor_fecha_nac)) {
		$age = DateTime::createFromFormat("Y-m-d", $conductor_fecha_nac, $tz)->diff(new DateTime("now", $tz))->y;
		$pdf->wwrite(110, 121.1, $age);
	}
	$pdf->wwrite(155, 121.1, $conductor_tel);
	$pdf->wwrite(60, 138, $conductor_registro);
	if (isset($conductor_registro_venc)) {
		$date = DateTime::createFromFormat("Y-m-d", $conductor_registro_venc);
		$pdf->wwrite(164, 138, $date->format('d'));
		$pdf->wwrite(175, 138, $date->format('m'));
		$pdf->wwrite(185, 138, $date->format('Y'));
	}
}
$pdf->wwrite(35, 143.4, $asegurado_marca);
$pdf->wwrite(108, 143.4, $asegurado_ano);
$pdf->wwrite(137, 144.4, $asegurado_modelo, 5);
$patente = $asegurado_patente_0 . $asegurado_patente_1;
$pdf->wwrite(178, 143.4, $patente);
$pdf->wwrite(35, 149, $asegurado_nro_motor);
$pdf->wwrite(105, 149, $asegurado_nro_chasis);
switch ((string)$asegurado_uso) {
	case '1':
	$pdf->wwrite(106.7, 154.9, 'X');
	break;
	case '2':
	case '3':
	$pdf->wwrite(78.3, 154.9, 'X');
	break;
	case '4':
	$pdf->wwrite(121.6, 154.9, 'X');
	break;
}
if (isset($datos_terceros[0])) {
	$pdf->wwrite(47, 171, $datos_terceros[0]['nombre']);
	$pdf->wwrite(155, 171, $datos_terceros[0]['tel']);
	$pdf->wwrite(44, 176.5, $datos_terceros[0]['calle']);
	$pdf->wwrite(135, 176.5, $datos_terceros[0]['altura']);
	$pdf->wwrite(42, 181.8, $datos_terceros[0]['localidad']);
	$pdf->wwrite(135, 182.8, $datos_terceros[0]['provincia'], 5);
	$pdf->wwrite(168, 181.8, $datos_terceros[0]['cp']);
	$pdf->wwrite(42, 188, $datos_terceros[0]['seguro']);
	$pdf->wwrite(143, 188, $datos_terceros[0]['nro_poliza']);
	$pdf->wwrite(43, 194, $datos_terceros[0]['marca']);
	$pdf->wwrite(118, 194, $datos_terceros[0]['modelo']);
	$patente = $datos_terceros[0]['patente_0'] . $datos_terceros[0]['patente_1'];
	$pdf->wwrite(158, 194, $patente);
	// propietario es asegurado?
	$pdf->wwrite(150, 199.5, $datos_terceros[0]['acompanantes']);
}
$calles = $calle . (!empty($interseccion_1)?' int. '.$interseccion_1:'') . (!empty($interseccion_2)?' int. '.$interseccion_2:'');
$pdf->wwrite(62, 204.5, $calles);
$pdf->wwrite(165, 204.5, $altura);
$pdf->wwrite(45, 210, $localidad);
$pdf->wwrite(112, 210, $provincia);
$pdf->wwrite(163, 210, $cp);
if (isset($fecha)) {
	$date = DateTime::createFromFormat("Y-m-d", $fecha);
	$pdf->wwrite(48, 216, $date->format('d'));
	$pdf->wwrite(55.5, 216, $date->format('m'));
	$pdf->wwrite(60.5, 216, $date->format('Y'));
}
$pdf->wwrite(79, 216, $hora);
if (!empty($diurno)) $pdf->wwrite(101.8, 216, 'X');
if (!empty($nocturno)) $pdf->wwrite(120, 216, 'X');
if (!empty($seco)) $pdf->wwrite(143, 216, 'X');
if (!empty($lluvia) or !empty($granizo) or !empty($nieve)) $pdf->wwrite(156.5, 216, 'X');
if (!empty($niebla)) $pdf->wwrite(170.9, 216, 'X');

$pdf->wwrite(93, 242.5, $inspeccion_taller);
$pdf->wwrite(30, 248, $inspeccion_calle);
$pdf->wwrite(122, 248, $inspeccion_altura);
$pdf->wwrite(154, 248, $inspeccion_localidad);
$date = DateTime::createFromFormat("Y-m-d", $inspeccion_fecha)->format('d/m/Y');
$pdf->wwrite(32, 254.3, $date);
$pdf->wwrite(74, 254.3, $inspeccion_telefono);

$pdf->AddPage();
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);

if (!empty($asegurado_danios_guardabarro_del_izq)) $pdf->wwrite(88, 23, 'X');
if (!empty($asegurado_danios_guardabarro_del_der)) $pdf->wwrite(96, 23, 'X');
if (!empty($asegurado_danios_faro_del_der)) $pdf->wwrite(88, 26, 'X');
if (!empty($asegurado_danios_faro_del_izq)) $pdf->wwrite(96, 26, 'X');
if (!empty($asegurado_danios_puerta_del_der)) $pdf->wwrite(88, 29.2, 'X');
if (!empty($asegurado_danios_puerta_del_izq)) $pdf->wwrite(96, 29.2, 'X');
if (!empty($asegurado_danios_puerta_tras_der)) $pdf->wwrite(88, 32.4, 'X');
if (!empty($asegurado_danios_puerta_tras_izq)) $pdf->wwrite(96, 32.4, 'X');
if (!empty($asegurado_danios_retrovisor_der)) $pdf->wwrite(88, 35.6, 'X');
if (!empty($asegurado_danios_retrovisor_izq)) $pdf->wwrite(96, 35.6, 'X');
if (!empty($asegurado_danios_guardabarro_tras_der)) $pdf->wwrite(88, 38.8, 'X');
if (!empty($asegurado_danios_guardabarro_tras_izq)) $pdf->wwrite(96, 38.8, 'X');
if (!empty($asegurado_danios_faro_tras_der)) $pdf->wwrite(88, 42, 'X');
if (!empty($asegurado_danios_faro_tras_izq)) $pdf->wwrite(96, 42, 'X');
if (!empty($asegurado_danios_paragolpes_del_der)) $pdf->wwrite(88, 45.2, 'X');
if (!empty($asegurado_danios_paragolpes_del_izq)) $pdf->wwrite(96, 45.2, 'X');
if (!empty($asegurado_danios_paragolpes_tras_der)) $pdf->wwrite(88, 48.4, 'X');
if (!empty($asegurado_danios_paragolpes_tras_izq)) $pdf->wwrite(96, 48.4, 'X');
if (!empty($asegurado_danios_baul_der)) $pdf->wwrite(88, 51.6, 'X');
if (!empty($asegurado_danios_baul_izq)) $pdf->wwrite(96, 51.6, 'X');
if (!empty($asegurado_danios_capot_der)) $pdf->wwrite(88, 54.8, 'X');
if (!empty($asegurado_danios_capot_izq)) $pdf->wwrite(96, 54.8, 'X');
if (!empty($asegurado_danios_techo_der)) $pdf->wwrite(88, 58, 'X');
if (!empty($asegurado_danios_techo_izq)) $pdf->wwrite(96, 58, 'X');
if (isset($datos_terceros[0])) {
	if (!empty($datos_terceros[0]['danios_guardabarro_del_izq'])) $pdf->wwrite(45.5, 23, 'X');
	if (!empty($datos_terceros[0]['danios_guardabarro_del_der'])) $pdf->wwrite(52.5, 23, 'X');
	if (!empty($datos_terceros[0]['danios_faro_del_der'])) $pdf->wwrite(45.5, 26, 'X');
	if (!empty($datos_terceros[0]['danios_faro_del_izq'])) $pdf->wwrite(52.5, 26, 'X');
	if (!empty($datos_terceros[0]['danios_puerta_del_der'])) $pdf->wwrite(45.5, 29.2, 'X');
	if (!empty($datos_terceros[0]['danios_puerta_del_izq'])) $pdf->wwrite(52.5, 29.2, 'X');
	if (!empty($datos_terceros[0]['danios_puerta_tras_der'])) $pdf->wwrite(45.5, 32.4, 'X');
	if (!empty($datos_terceros[0]['danios_puerta_tras_izq'])) $pdf->wwrite(52.5, 32.4, 'X');
	if (!empty($datos_terceros[0]['danios_retrovisor_der'])) $pdf->wwrite(45.5, 35.6, 'X');
	if (!empty($datos_terceros[0]['danios_retrovisor_izq'])) $pdf->wwrite(52.5, 35.6, 'X');
	if (!empty($datos_terceros[0]['danios_guardabarro_tras_der'])) $pdf->wwrite(45.5, 38.8, 'X');
	if (!empty($datos_terceros[0]['danios_guardabarro_tras_izq'])) $pdf->wwrite(52.5, 38.8, 'X');
	if (!empty($datos_terceros[0]['danios_faro_tras_der'])) $pdf->wwrite(45.5, 42, 'X');
	if (!empty($datos_terceros[0]['danios_faro_tras_izq'])) $pdf->wwrite(52.5, 42, 'X');
	if (!empty($datos_terceros[0]['danios_paragolpes_del_der'])) $pdf->wwrite(45.5, 45.2, 'X');
	if (!empty($datos_terceros[0]['danios_paragolpes_del_izq'])) $pdf->wwrite(52.5, 45.2, 'X');
	if (!empty($datos_terceros[0]['danios_paragolpes_tras_der'])) $pdf->wwrite(45.5, 48.4, 'X');
	if (!empty($datos_terceros[0]['danios_paragolpes_tras_izq'])) $pdf->wwrite(52.5, 48.4, 'X');
	if (!empty($datos_terceros[0]['danios_baul_der'])) $pdf->wwrite(45.5, 51.6, 'X');
	if (!empty($datos_terceros[0]['danios_baul_izq'])) $pdf->wwrite(52.5, 51.6, 'X');
	if (!empty($datos_terceros[0]['danios_capot_der'])) $pdf->wwrite(45.5, 54.8, 'X');
	if (!empty($datos_terceros[0]['danios_capot_izq'])) $pdf->wwrite(52.5, 54.8, 'X');
	if (!empty($datos_terceros[0]['danios_techo_der'])) $pdf->wwrite(45.5, 58, 'X');
	if (!empty($datos_terceros[0]['danios_techo_izq'])) $pdf->wwrite(52.5, 58, 'X');
}

$lesiones_terceros_txt = array();

foreach ($lesiones_terceros as $lesiones_tercero) {
	$lesiones_tercero_nombre = $lesiones_tercero['nombre'];
	switch ((string)$lesiones_tercero['relacion_asegurado']) {
		case '1':
		$lesiones_tercero_relacion_asegurado = 'conductor otro vehículo';
		break;
		case '2':
		$lesiones_tercero_relacion_asegurado = 'pasajero otro vehículo';
		break;
		case '3':
		$lesiones_tercero_relacion_asegurado = 'pasajero vehículo asegurado';
		break;
		case '4':
		$lesiones_tercero_relacion_asegurado = 'peatón';
		break;
	}
	switch ((string)$lesiones_tercero['tipo_lesiones']) {
		case '1':
		$lesiones_tercero_tipo_lesiones = 'leves';
		break;
		case '2':
		$lesiones_tercero_tipo_lesiones = 'graves';
		break;
		case '3':
		$lesiones_tercero_tipo_lesiones = 'mortal';
		break;
	}
	switch ((string)$lesiones_tercero['examen_alcoholemia']) {
		case '0':
		$lesiones_tercero_examen_alcoholemia = 'no';
		break;
		case '1':
		$lesiones_tercero_examen_alcoholemia = 'sí';
		break;
		case '2':
		$lesiones_tercero_examen_alcoholemia = 'se negó';
		break;
	}
	$lesiones_terceros_txt[] = $lesiones_tercero_nombre . ' (' . $lesiones_tercero_relacion_asegurado . '): ' . $lesiones_tercero_tipo_lesiones;
}

$text = implode(', ', $lesiones_terceros_txt);
$text = iconv('UTF-8', 'windows-1252', $text);
$pdf->SetXY(103, 24);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(90, 5, $text, 0, 'L', 0, 10);

// croquis
$data = substr($siniestro['croquis_img-noupper'], 22);
if (base64_encode(base64_decode($data, true))===$data) {
	$pdf->Image($siniestro['croquis_img-noupper'], 26, 202, 40, 36, 'png');
}


if (isset($datos_terceros[1])) {
	$pdf->wwrite(62, 148.5, $datos_terceros[1]['nombre']);
	$pdf->wwrite(50, 154.5, $datos_terceros[1]['registro']);
	if (isset($datos_terceros[1]['registro_venc'])) {
		$date = DateTime::createFromFormat("Y-m-d", $datos_terceros[1]['registro_venc']);
		$pdf->wwrite(129.5, 154.5, $date->format('d'));
		$pdf->wwrite(138, 154.5, $date->format('m'));
		$pdf->wwrite(145, 154.5, $date->format('Y'));
	}
	$pdf->wwrite(44, 160, $datos_terceros[1]['calle']);
	$pdf->wwrite(105, 160, $datos_terceros[1]['altura']);
	$pdf->wwrite(36, 166, $datos_terceros[1]['cp']);
	$pdf->wwrite(82, 166, $datos_terceros[1]['localidad']);
	$pdf->wwrite(132, 167, $datos_terceros[1]['provincia'], 5);
	$pdf->wwrite(43, 172, $datos_terceros[1]['seguro']);
	$pdf->wwrite(115, 172, $datos_terceros[1]['nro_poliza']);
	$pdf->wwrite(104, 177.5, $datos_terceros[1]['marca']);
	$pdf->wwrite(134, 178.5, $datos_terceros[1]['modelo'], 5);
	$patente = $datos_terceros[1]['patente_0'] . $datos_terceros[1]['patente_1'];
	$pdf->wwrite(38, 183, $patente);
	$pdf->wwrite(130, 183, $datos_terceros[1]['uso']);
	// propietario es asegurado?
	
	if (!empty($datos_terceros[1]['danios_guardabarro_del_izq'])) $pdf->wwrite(182.7, 151, 'X');
	if (!empty($datos_terceros[1]['danios_guardabarro_del_der'])) $pdf->wwrite(189.7, 151, 'X');
	if (!empty($datos_terceros[1]['danios_faro_del_der'])) $pdf->wwrite(182.7, 154.4, 'X');
	if (!empty($datos_terceros[1]['danios_faro_del_izq'])) $pdf->wwrite(189.7, 154.4, 'X');
	if (!empty($datos_terceros[1]['danios_puerta_del_der'])) $pdf->wwrite(182.7, 157.7, 'X');
	if (!empty($datos_terceros[1]['danios_puerta_del_izq'])) $pdf->wwrite(189.7, 157.7, 'X');
	if (!empty($datos_terceros[1]['danios_puerta_tras_der'])) $pdf->wwrite(182.7, 160.9, 'X');
	if (!empty($datos_terceros[1]['danios_puerta_tras_izq'])) $pdf->wwrite(189.7, 160.9, 'X');
	if (!empty($datos_terceros[1]['danios_retrovisor_der'])) $pdf->wwrite(182.7, 164.4, 'X');
	if (!empty($datos_terceros[1]['danios_retrovisor_izq'])) $pdf->wwrite(189.7, 164.4, 'X');
	if (!empty($datos_terceros[1]['danios_guardabarro_tras_der'])) $pdf->wwrite(182.7, 167.6, 'X');
	if (!empty($datos_terceros[1]['danios_guardabarro_tras_izq'])) $pdf->wwrite(189.7, 167.6, 'X');
	if (!empty($datos_terceros[1]['danios_faro_tras_der'])) $pdf->wwrite(182.7, 170.8, 'X');
	if (!empty($datos_terceros[1]['danios_faro_tras_izq'])) $pdf->wwrite(189.7, 170.8, 'X');
	if (!empty($datos_terceros[1]['danios_paragolpes_del_der'])) $pdf->wwrite(182.7, 174.2, 'X');
	if (!empty($datos_terceros[1]['danios_paragolpes_del_izq'])) $pdf->wwrite(189.7, 174.2, 'X');
	if (!empty($datos_terceros[1]['danios_paragolpes_tras_der'])) $pdf->wwrite(182.7, 177.8, 'X');
	if (!empty($datos_terceros[1]['danios_paragolpes_tras_izq'])) $pdf->wwrite(189.7, 177.8, 'X');
	if (!empty($datos_terceros[1]['danios_baul_der'])) $pdf->wwrite(182.7, 181, 'X');
	if (!empty($datos_terceros[1]['danios_baul_izq'])) $pdf->wwrite(189.7, 181, 'X');
	if (!empty($datos_terceros[1]['danios_capot_der'])) $pdf->wwrite(182.7, 184.4, 'X');
	if (!empty($datos_terceros[1]['danios_capot_izq'])) $pdf->wwrite(189.7, 184.4, 'X');
	if (!empty($datos_terceros[1]['danios_techo_der'])) $pdf->wwrite(182.7, 187.6, 'X');
	if (!empty($datos_terceros[1]['danios_techo_izq'])) $pdf->wwrite(189.7, 187.6, 'X');
}

$text = iconv('UTF-8', 'windows-1252', $siniestro_detalle);
$pdf->SetXY(22, 201);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(172, 7, $text, 0, 'L', 0, 6);

$date = DateTime::createFromFormat("Y-m-d", $fecha_denuncia)->format('d/m/Y');
$text = implode(', ', array($lugar_denuncia, $date));
$pdf->wwrite(44, 265.5, $text);

$text = '';
if (count($siniestro['datos_terceros'])>2) {
	for ($i=2;$i<count($siniestro['datos_terceros']);$i++) {
		$datos_tercero = $siniestro['datos_terceros'][$i];
		$text .= "DETALLE DEL OTRO VEHÍCULO (".($i+1).")\n";
		$text .= 'Nombre: '.$datos_tercero['nombre'];
		$text .= ', '.($datos_tercero['sexo']==1?'masculino':'femenino').'. ';
		$text .= 'Registro: '.$datos_tercero['registro'].'. ';
		$text .= 'Tel: '.$datos_tercero['tel'].'. ';
		$text .= 'Domicilio: '.implode(', ', array_filter(array($datos_tercero['calle'].' '.$datos_tercero['altura'], $datos_tercero['cp'], $datos_tercero['localidad'], $datos_tercero['provincia']))).'. ';
		$text .= 'Marca: '.$datos_tercero['marca'].'. ';
		$text .= 'Modelo: '.$datos_tercero['modelo'].'. ';
		$text .= 'Tipo: '.$datos_tercero['tipo'].'. ';
		$text .= 'Patente: '.$datos_tercero['patente_0'].$datos_tercero['patente_1'].'. ';
		$text .= 'Año: '.$datos_tercero['ano'].'. ';
		$text .= 'Nro motor: '.$datos_tercero['nro_motor'].'. ';
		$text .= 'Nro chasis: '.$datos_tercero['nro_chasis'].'. ';
		$text .= 'Seguro: '.$datos_tercero['seguro'].'. ';
		$text .= 'Póliza: '.$datos_tercero['nro_poliza'].'. ';
		
		$danios = array();
		foreach ($datos_tercero as $danio_k=>$danio_v) {
			if (!preg_match('/^danios_(_observaciones)?/', $danio_k)) continue;
			if ($danio_v == 1) $danios[] = ucwords(preg_replace('/_/', ' ', substr($danio_k, 7)));
		}
		$text .= 'Daños: '.implode(', ', $danios).'. ';
		if (!empty($datos_tercero['danios_observaciones'])) $text .= 'Observaciones: '.$datos_tercero['danios_observaciones'].'. ';
		
		$text .= 'El conductor '.((string)$datos_tercero['conductor_asegurado']=='0'?'no ':'').'es el propio asegurado. ';

		if ((string)$datos_tercero['conductor_asegurado']=='0') {
			$text .= 'Datos del conductor: ';
			$text .= 'nombre: '.$datos_tercero['conductor_nombre'];
			$text .= ', '.($datos_tercero['conductor_sexo']==1?'masculino':'femenino').'. ';
			$text .= 'Registro: '.$datos_tercero['conductor_registro'].'. ';
			if (isset($datos_tercero['conductor_registro_venc'])) {
				$date = DateTime::createFromFormat("Y-m-d", $datos_tercero['conductor_registro_venc']);
				$text .= 'Vencimiento: '.$date->format('d/m/Y').'. ';
			}
			$text .= 'Teléfono: '.$datos_tercero['conductor_tel'].'. ';
			$text .= 'Domicilio: '.implode(', ', array_filter(array($datos_tercero['conductor_calle'].' '.$datos_tercero['conductor_altura'], $datos_tercero['conductor_cp'], $datos_tercero['conductor_localidad'], $datos_tercero['conductor_provincia']))).'. ';			
			
			if (isset($datos_tercero['conductor_fecha_nac'])) {
				$date = DateTime::createFromFormat("Y-m-d", $datos_tercero['conductor_fecha_nac']);
				$text .= 'Fecha de nacimiento: '.$date->format('d/m/Y').'. ';
			}
		}
		$text .= "\n\n";
	}
}
if (count($siniestro['lesiones_terceros'])>4) {
	for ($i=4;$i<count($siniestro['lesiones_terceros']);$i++) {
		$lesiones_tercero = $siniestro['lesiones_terceros'][$i];
		$text .= "LESIONES A TERCEROS (".($i+1).")\n";
		$text .= 'Nombre: '.$lesiones_tercero['nombre'];
		$text .= ', '.($lesiones_tercero['sexo']==1?'masculino':'femenino').'. ';
		$text .= 'Número de documento: '.$lesiones_tercero['nro_doc'].'. ';
		$text .= 'Teléfono '.$lesiones_tercero['tel'].'. ';
		$text .= 'Domicilio: '.implode(', ', array_filter(array($lesiones_tercero['calle'].' '.$lesiones_tercero['altura'], $lesiones_tercero['cp'], $lesiones_tercero['localidad'], $lesiones_tercero['provincia']))).'. ';
		$text .= 'Estado civil: '.$lesiones_tercero['estado_civil'].'. ';
		if (isset($lesiones_tercero['fecha_nac'])) {
			$date = DateTime::createFromFormat("Y-m-d", $lesiones_tercero['fecha_nac']);
			$text .= 'Fecha de nacimiento: '.$date->format('d/m/Y').'. ';
		}
		switch((string)$lesiones_tercero['relacion_asegurado']) {
			case 1:
			$text .= 'Relación con asegurado: conductor otro vehículo. ';
			break;
			case 2:
			$text .= 'Relación con asegurado: pasajero otro vehículo. ';
			break;
			case 3:
			$text .= 'Relación con asegurado: pasajero vehículo asegurado. ';
			break;
			case 4:
			$text .= 'Relación con asegurado: peatón. ';
			break;
		}
		switch((string)$lesiones_tercero['tipo_lesiones']) {
			case 1:
			$text .= 'Tipo de lesiones: leves. ';
			break;
			case 2:
			$text .= 'Tipo de lesiones: graves (con internación). ';
			break;
			case 3:
			$text .= 'Tipo de lesiones: mortal. ';
			break;
		}
		switch ((string)$lesiones_tercero['examen_alcoholemia']) {
			case '1':
			$text .= 'Exámen de alcoholemia: sí. ';
			break;
			case '0':
			$text .= 'Exámen de alcoholemia: no. ';
			break;
			case '2':
			$text .= 'Exámen de alcoholemia: se negó. ';
			break;
		}
		$text .= 'Centro asistencial: '.$lesiones_tercero['centro_asistencial'];
		$text .= "\n\n";
	}
}

if (!empty($text)) {
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true, 10);
	$pdf->SetMargins(10, 10);

	$text = iconv('UTF-8', 'windows-1252', $text);
	$pdf->SetXY(10, 10);
	$pdf->SetFont('Arial', '', 7);
	$pdf->MultiCell(200, 5, $text);
}
?>