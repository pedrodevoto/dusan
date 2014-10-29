<?php
$pdf = new FPDIW('P','mm',array(215.9,355.6));
$pdf->setSourceFile('siniestros/federal.pdf');
$pdf->SetMargins(0, 0);
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$pdf->wwrite(128, 3.5, $productor_nombre, 10);
$pdf->wwrite(128, 8, $productor_seguro_codigo, 10);
$pdf->SetTextColor(255,0,0);
switch((string)$tipo_siniestro) {
	case '3':
	$pdf->wwrite(60, 23, 'REPOSICION', 10);
	break;
	case '4':
	$pdf->wwrite(60, 23, 'INSPECCIÓN', 10);
	break;
}
$pdf->SetTextColor(0,0,0);
$pdf->wwrite(28, 37.5, $poliza_numero);
$pdf->wwrite(140, 37.5, $siniestro_numero);
if (isset($fecha)) {
	$date = DateTime::createFromFormat("Y-m-d", $fecha);
	$pdf->wwrite(20, 50, $date->format('d'));
	$pdf->wwrite(28, 50, $date->format('m'));
	$pdf->wwrite(34, 50, $date->format('Y'));
}
$pdf->wwrite(54, 50, $hora);
if (!empty($diurno)) $pdf->wwrite(92, 51, 'X');
if (!empty($nocturno)) $pdf->wwrite(112, 51, 'X');
if (!empty($seco)) $pdf->wwrite(127, 51, 'X');
if (!empty($lluvia)) $pdf->wwrite(142, 51, 'X');
if (!empty($granizo)) $pdf->wwrite(158, 51, 'X');
if (!empty($niebla)) $pdf->wwrite(176, 51, 'X');
if (!empty($nieve)) $pdf->wwrite(192, 51, 'X');

$pdf->wwrite(24, 62, $localidad);
$pdf->wwrite(123, 62, $provincia);
$pdf->wwrite(20, 67.5, 'ARGENTINA');
$pdf->wwrite(102, 67.5, $calle);
$pdf->wwrite(173, 67.5, $altura);
$pdf->wwrite(40, 73, $interseccion_1);
$pdf->wwrite(102, 73, $interseccion_2);
if (!empty($cruce_tren)) $pdf->wwrite(25, 85.3, 'X');
switch ((string)$barrera) {
	case '0':
	$pdf->wwrite(75, 85.3, 'X');
	break;
	case '1':
	$pdf->wwrite(60, 85.3, 'X');
	break;
}
switch ((string)$cruce_senalizado) {
	case '0':
	$pdf->wwrite(125, 85.3, 'X');
	break;
	case '1':
	$pdf->wwrite(142, 85.3, 'X');
	break;
}
switch ((string)$semaforo) {
	case '0':
	$pdf->wwrite(52, 91, 'X');
	break;
	case '1':
	$pdf->wwrite(37, 91, 'X');
	break;
}
$pdf->wwrite(162, 90, $semaforo_color);
$pdf->wwrite(34, 96, $tipo_calzada);
$pdf->wwrite(160, 96, $estado_calzada);

// datos del conductor y asegurado
if (!$conductor_asegurado) {
	$pdf->wwrite(34, 107.5, $conductor_nombre);
	switch ((string)$conductor_sexo) {
		case '2':
		$pdf->wwrite(185, 108, 'X');
		break;
		case '1':
		$pdf->wwrite(195, 108, 'X');
		break;
	}
	$pdf->wwrite(38, 113, $conductor_registro);
	$pdf->wwrite(160, 113, $conductor_tel);
	$pdf->wwrite(25, 119, $conductor_calle . ' ' . $conductor_altura . ' ' . $conductor_piso);
	$pdf->wwrite(172, 119, $conductor_cp);
	$pdf->wwrite(27, 124, $conductor_localidad);
	$pdf->wwrite(105, 124, $conductor_provincia);
	$pdf->wwrite(170, 124, 'Argentina');
	if (isset($conductor_fecha_nac)) {
		$date = DateTime::createFromFormat("Y-m-d", $conductor_fecha_nac);
		$pdf->wwrite(178, 130, $date->format('d'));
		$pdf->wwrite(186, 130, $date->format('m'));
		$pdf->wwrite(194, 130, $date->format('Y'));
	}
	switch ((string)$examen_alcoholemia) {
		case '1':
		$pdf->wwrite(60, 136.6, 'X');
		break;
		case '0':
		$pdf->wwrite(90, 136.6, 'X');
		break;
		case '2':
		$pdf->wwrite(120, 136.6, 'X');
		break;
	}
	switch ((string)$conductor_habitual) {
		case '1':
		$pdf->wwrite(60, 142.5, 'X');
		break;
		case '0':
		$pdf->wwrite(90, 142.5, 'X');
		break;
	}
	$pdf->wwrite(122, 142, $conductor_registro);
	if (isset($conductor_registro_venc)) {
		$date = DateTime::createFromFormat("Y-m-d", $conductor_registro_venc);
		$pdf->wwrite(178, 142, $date->format('d'));
		$pdf->wwrite(186, 142, $date->format('m'));
		$pdf->wwrite(194, 142, $date->format('Y'));
	}
	switch ((string)$conductor_asegurado) {
		case '1':
		$pdf->wwrite(60, 148, 'X');
		break;
		case '0':
		$pdf->wwrite(120, 148, 'X');
		break;
	}

	// datos del asegurado (si no es el conductor)
	$pdf->wwrite(52, 159, $asegurado_nombre);
	$pdf->wwrite(40, 164.5, $asegurado_registro);
	$pdf->wwrite(160, 164.5, $asegurado_tel);
	$pdf->wwrite(25, 170, $asegurado_calle . ' ' . $asegurado_altura . ' ' . $asegurado_piso);
	$pdf->wwrite(174, 170, $asegurado_cp);
	$pdf->wwrite(24, 176, $asegurado_localidad);
	$pdf->wwrite(105, 176, $conductor_provincia);
	$pdf->wwrite(170, 176, 'Argentina');
}
else {
	$pdf->wwrite(34, 107.5, $asegurado_nombre);
	switch ((string)$asegurado_sexo) {
		case '2':
		$pdf->wwrite(185, 108, 'X');
		break;
		case '1':
		$pdf->wwrite(195, 108, 'X');
		break;
	}
	$pdf->wwrite(38, 113, $asegurado_registro);
	$pdf->wwrite(160, 113, $asegurado_tel);
	$pdf->wwrite(25, 119, $asegurado_calle . ' ' . $asegurado_altura . ' ' . $asegurado_piso);
	$pdf->wwrite(172, 119, $asegurado_cp);
	$pdf->wwrite(27, 124, $asegurado_localidad);
	$pdf->wwrite(105, 124, $asegurado_provincia);
	$pdf->wwrite(170, 124, 'Argentina');
	if (isset($asegurado_fecha_nac)) {
		$date = DateTime::createFromFormat("Y-m-d", $asegurado_fecha_nac);
		$pdf->wwrite(178, 130, $date->format('d'));
		$pdf->wwrite(186, 130, $date->format('m'));
		$pdf->wwrite(194, 130, $date->format('Y'));
	}
	switch ((string)$examen_alcoholemia) {
		case '1':
		$pdf->wwrite(60, 136.6, 'X');
		break;
		case '0':
		$pdf->wwrite(90, 136.6, 'X');
		break;
		case '2':
		$pdf->wwrite(120, 136.6, 'X');
		break;
	}
	switch ((string)$conductor_habitual) {
		case '1':
		$pdf->wwrite(60, 142.5, 'X');
		break;
		case '0':
		$pdf->wwrite(90, 142.5, 'X');
		break;
	}
	$pdf->wwrite(122, 142, $asegurado_registro);
	if (isset($asegurado_registro_venc)) {
		$date = DateTime::createFromFormat("Y-m-d", $asegurado_registro_venc);
		$pdf->wwrite(178, 142, $date->format('d'));
		$pdf->wwrite(186, 142, $date->format('m'));
		$pdf->wwrite(194, 142, $date->format('Y'));
	}
	switch ((string)$conductor_asegurado) {
		case '1':
		$pdf->wwrite(60, 148, 'X');
		break;
		case '0':
		$pdf->wwrite(120, 148, 'X');
		break;
	}
}
// datos del vehiculo asegurado
$pdf->wwrite(20, 187.5, $asegurado_marca . ' ' . $asegurado_modelo);
$automotor_tipo = array("1"=>"AUTOMOTOR","2"=>"PICKUP A","3"=>"PICKUP B","7"=>"CAMIÓN","5"=>"ACOPLADO","8"=>"SEMIRREMOLQUE","4"=>"MOTO","9"=>"TRACTOR","10"=>"MAQUINARIA RURAL Y AGRÍCOLA","11"=>"CASA RODANTE","12"=>"TRAILER","6"=>"BANTAM","13"=>"CUATRICICLO");
$pdf->wwrite(170, 187.5, $automotor_tipo[$asegurado_tipo]);
$pdf->wwrite(25, 193, $asegurado_patente_0 . $asegurado_patente_1);
$pdf->wwrite(180, 193, $asegurado_ano);
$pdf->wwrite(25, 199, $asegurado_nro_motor);
$pdf->wwrite(130, 199, $asegurado_nro_chasis);
switch ((string)$asegurado_uso) {
	case '1':
	$pdf->wwrite(45, 205.5, 'X');
	break;
	case '2':
	case '3':
	$pdf->wwrite(76, 205.5, 'X');
	break;
	case '4':
	$pdf->wwrite(100, 205.5, 'X');
	break;
}
// daños
$danios = array();
foreach ($siniestro as $danio_k=>$danio_v) {
	if (!preg_match('/^asegurado_danios_(_observaciones)?/', $danio_k)) continue;
	if ($danio_v == 1) $danios[] = ucwords(preg_replace('/_/', ' ', substr($danio_k, 17)));
}
$text = implode(', ', $danios);
$text = iconv('UTF-8', 'windows-1252', $text);
$text = '                                                   '.$text;
if (!empty($asegurado_danios_observaciones)) $text .= '. Observaciones: '.$asegurado_danios_observaciones;
$pdf->SetXY(12, 216);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(190, 5, $text, 0, 'L', 0, 4);

// detalle del primer vehiculo tercero
if (isset($datos_terceros[0])) {
	$pdf->wwrite(28, 243, $datos_terceros[0]['nombre']);
	switch ((string)$datos_terceros[0]['sexo']) {
		case '1':
		$pdf->wwrite(194, 244, 'X');
		break;
		case '2':
		$pdf->wwrite(180, 244, 'X');
		break;
	}
	$pdf->wwrite(38, 248.5, $datos_terceros[0]['registro']);
	$pdf->wwrite(160, 248.5, $datos_terceros[0]['tel']);
	$pdf->wwrite(26, 254, $datos_terceros[0]['calle'] . ' ' . $datos_terceros[0]['altura']);
	$pdf->wwrite(174, 254, $datos_terceros[0]['cp']);
	$pdf->wwrite(26, 260, $datos_terceros[0]['localidad']);
	$pdf->wwrite(105, 260, $datos_terceros[0]['provincia']);
	$pdf->wwrite(170, 260, 'Argentina');
	$pdf->wwrite(25, 266, $datos_terceros[0]['marca'].' '.$datos_terceros[0]['modelo']);
	$pdf->wwrite(170, 266, $datos_terceros[0]['tipo']);
	$pdf->wwrite(23, 271.5, $datos_terceros[0]['patente_0'] . $datos_terceros[0]['patente_1']);
	$pdf->wwrite(65, 271.5, $datos_terceros[0]['ano']);
	$pdf->wwrite(95, 271.5, $datos_terceros[0]['nro_motor']);
	$pdf->wwrite(155, 271.5, $datos_terceros[0]['nro_chasis']);
	
	if (!empty($datos_terceros[0]['seguro'])) $pdf->wwrite(74, 283.5, 'X');
	else $pdf->wwrite(46, 283.5, 'X');
	$pdf->wwrite(102, 283, $datos_terceros[0]['seguro']);
	$pdf->wwrite(162, 283, $datos_terceros[0]['nro_poliza']);
	
	$danios = array();
	foreach ($datos_terceros[0] as $danio_k=>$danio_v) {
		if (!preg_match('/^danios_(_observaciones)?/', $danio_k)) continue;
		if ($danio_v == 1) $danios[] = ucwords(preg_replace('/_/', ' ', substr($danio_k, 7)));
	}
	$text = implode(', ', $danios);
	$text = iconv('UTF-8', 'windows-1252', $text);
	$text = '                                                   '.$text;
	if (!empty($datos_terceros[0]['danios_observaciones'])) $text .= '. Observaciones: '.$datos_terceros[0]['danios_observaciones'];
	$pdf->SetXY(12, 289);
	$pdf->SetFont('Arial', '', 7);
	$pdf->MultiCell(190, 5, $text, 0, 'L', 0, 2);
	
	switch ((string)$datos_terceros[0]['conductor_asegurado']) {
		case '1':
		$pdf->wwrite(61, 304.6, 'X');
		break;
		case '0':
		$pdf->wwrite(120, 304.6, 'X');
		break;
	}
	if ((string)$datos_terceros[0]['conductor_asegurado']=='0') {
		$pdf->wwrite(25, 309.6, $datos_terceros[0]['conductor_nombre']);
		switch ((string)$datos_terceros[0]['conductor_sexo']) {
			case '2':
			$pdf->wwrite(183, 310.2, 'X');
			break;
			case '1':
			$pdf->wwrite(193, 310.2, 'X');
			break;
		}
		$pdf->wwrite(38, 315.4, $datos_terceros[0]['conductor_registro']);
		$pdf->wwrite(162, 315.4, $datos_terceros[0]['conductor_tel']);
		$pdf->wwrite(25, 321, $datos_terceros[0]['conductor_calle'] . ' ' . $datos_terceros[0]['conductor_altura']);
		$pdf->wwrite(174, 321, $datos_terceros[0]['conductor_cp']);
		$pdf->wwrite(25, 327, $datos_terceros[0]['conductor_localidad']);
		$pdf->wwrite(105, 327, $datos_terceros[0]['conductor_provincia']);
		$pdf->wwrite(170, 327, 'Argentina');
		if (isset($datos_terceros[0]['conductor_fecha_nac'])) {
			$date = DateTime::createFromFormat("Y-m-d", $datos_terceros[0]['conductor_fecha_nac']);
			$pdf->wwrite(178, 332.5, $date->format('d'));
			$pdf->wwrite(186, 332.5, $date->format('m'));
			$pdf->wwrite(194, 332.5, $date->format('Y'));
		}
		$pdf->wwrite(115, 338, $datos_terceros[0]['conductor_registro']);
		if (isset($datos_terceros[0]['conductor_registro_venc'])) {
			$date = DateTime::createFromFormat("Y-m-d", $datos_terceros[0]['conductor_registro_venc']);
			$pdf->wwrite(178, 338, $date->format('d'));
			$pdf->wwrite(186, 338, $date->format('m'));
			$pdf->wwrite(194, 338, $date->format('Y'));
		}
	}
}

$pdf->AddPage();
$tplIdx = $pdf->importPage(2);
$pdf->useTemplate($tplIdx);

// detalle del primer vehiculo tercero
if (isset($datos_terceros[1])) {
	$pdf->wwrite(28, 15, $datos_terceros[1]['nombre']);
	switch ((string)$datos_terceros[1]['sexo']) {
		case '1':
		$pdf->wwrite(194, 15, 'X');
		break;
		case '2':
		$pdf->wwrite(180, 15, 'X');
		break;
	}
	$pdf->wwrite(38, 20.5, $datos_terceros[1]['registro']);
	$pdf->wwrite(160, 20.5, $datos_terceros[1]['tel']);
	$pdf->wwrite(26, 26, $datos_terceros[1]['calle'] . ' ' . $datos_terceros[1]['altura']);
	$pdf->wwrite(174, 26, $datos_terceros[1]['cp']);
	$pdf->wwrite(26, 32, $datos_terceros[1]['localidad']);
	$pdf->wwrite(105, 32, $datos_terceros[1]['provincia']);
	$pdf->wwrite(170, 32, 'Argentina');
	$pdf->wwrite(25, 37, $datos_terceros[1]['marca'].' '.$datos_terceros[1]['modelo']);
	$pdf->wwrite(170, 37, $datos_terceros[1]['tipo']);
	$pdf->wwrite(23, 43, $datos_terceros[1]['patente_0'] . $datos_terceros[1]['patente_1']);
	$pdf->wwrite(65, 43, $datos_terceros[1]['ano']);
	$pdf->wwrite(95, 43, $datos_terceros[1]['nro_motor']);
	$pdf->wwrite(155, 43, $datos_terceros[1]['nro_chasis']);
	
	if (!empty($datos_terceros[1]['seguro'])) $pdf->wwrite(74, 55, 'X');
	else $pdf->wwrite(46, 55, 'X');
	$pdf->wwrite(102, 54.5, $datos_terceros[1]['seguro']);
	$pdf->wwrite(162, 54.5, $datos_terceros[1]['nro_poliza']);
	
	$danios = array();
	foreach ($datos_terceros[1] as $danio_k=>$danio_v) {
		if (!preg_match('/^danios_(_observaciones)?/', $danio_k)) continue;
		if ($danio_v == 1) $danios[] = ucwords(preg_replace('/_/', ' ', substr($danio_k, 7)));
	}
	$text = implode(', ', $danios);
	$text = iconv('UTF-8', 'windows-1252', $text);
	$text = '                                                   '.$text;
	if (!empty($datos_terceros[1]['danios_observaciones'])) $text .= '. Observaciones: '.$datos_terceros[1]['danios_observaciones'];
	$pdf->SetXY(12, 60.5);
	$pdf->SetFont('Arial', '', 7);
	$pdf->MultiCell(190, 5, $text, 0, 'L', 0, 2);
	
	switch ((string)$datos_terceros[1]['conductor_asegurado']) {
		case '1':
		$pdf->wwrite(61, 76.1, 'X');
		break;
		case '0':
		$pdf->wwrite(120, 76.1, 'X');
		break;
	}
	if ((string)$datos_terceros[1]['conductor_asegurado']=='0') {
		$pdf->wwrite(25, 81, $datos_terceros[1]['conductor_nombre']);
		switch ((string)$datos_terceros[1]['conductor_sexo']) {
			case '2':
			$pdf->wwrite(183, 81.7, 'X');
			break;
			case '1':
			$pdf->wwrite(193, 81.7, 'X');
			break;
		}
		$pdf->wwrite(38, 86.9, $datos_terceros[1]['conductor_registro']);
		$pdf->wwrite(162, 86.9, $datos_terceros[1]['conductor_tel']);
		$pdf->wwrite(25, 92.5, $datos_terceros[1]['conductor_calle'] . ' ' . $datos_terceros[1]['conductor_altura']);
		$pdf->wwrite(174, 92.5, $datos_terceros[1]['conductor_cp']);
		$pdf->wwrite(25, 98.2, $datos_terceros[1]['conductor_localidad']);
		$pdf->wwrite(105, 98.2, $datos_terceros[1]['conductor_provincia']);
		$pdf->wwrite(170, 98.2, 'Argentina');
		if (isset($datos_terceros[1]['conductor_fecha_nac'])) {
			$date = DateTime::createFromFormat("Y-m-d", $datos_terceros[1]['conductor_fecha_nac']);
			$pdf->wwrite(178, 104, $date->format('d'));
			$pdf->wwrite(186, 104, $date->format('m'));
			$pdf->wwrite(194, 104, $date->format('Y'));
		}
		$pdf->wwrite(115, 109.5, $datos_terceros[1]['conductor_registro']);
		if (isset($datos_terceros[1]['conductor_registro_venc'])) {
			$date = DateTime::createFromFormat("Y-m-d", $datos_terceros[1]['conductor_registro_venc']);
			$pdf->wwrite(178, 109.5, $date->format('d'));
			$pdf->wwrite(186, 109.5, $date->format('m'));
			$pdf->wwrite(194, 109.5, $date->format('Y'));
		}
	}
}

// detalle del siniestro

if (!empty($detalle_tipo_frontal)) $pdf->wwrite(50, 170.5, 'X');
if (!empty($detalle_tipo_posterior)) $pdf->wwrite(78, 170.5, 'X');
if (!empty($detalle_tipo_lateral)) $pdf->wwrite(108, 170.5, 'X');
if (!empty($detalle_tipo_en_cadena)) $pdf->wwrite(135, 170.5, 'X');
if (!empty($detalle_tipo_vuelco)) $pdf->wwrite(160, 170.5, 'X');
if (!empty($detalle_tipo_desplazamiento)) $pdf->wwrite(195, 170.5, 'X');
if (!empty($detalle_tipo_inmersion)) $pdf->wwrite(50, 175.8, 'X');
if (!empty($detalle_tipo_incendio)) $pdf->wwrite(78, 175.8, 'X');
if (!empty($detalle_tipo_explosion)) $pdf->wwrite(108, 175.8, 'X');
if (!empty($detalle_tipo_danio_carga)) $pdf->wwrite(142, 175.8, 'X');

if (!empty($detalle_autopista)) $pdf->wwrite(27, 184.3, 'X');
if (!empty($detalle_calle)) $pdf->wwrite(50, 184.3, 'X');
if (!empty($detalle_avenida)) $pdf->wwrite(72, 184.3, 'X');
if (!empty($detalle_curva)) $pdf->wwrite(92, 184.3, 'X');
if (!empty($detalle_pendiente)) $pdf->wwrite(118, 184.3, 'X');
if (!empty($detalle_tunel)) $pdf->wwrite(136, 184.3, 'X');
if (!empty($detalle_sobre_puente)) $pdf->wwrite(162, 184.3, 'X');

if (!empty($detalle_colision_peaton)) $pdf->wwrite(50, 189.8, 'X');
if (!empty($detalle_colision_vehiculo)) $pdf->wwrite(72, 189.8, 'X');
if (!empty($detalle_colision_trans_publico)) $pdf->wwrite(100, 189.8, 'X');
if (!empty($detalle_colision_edificio)) $pdf->wwrite(120, 189.8, 'X');
if (!empty($detalle_colision_columna)) $pdf->wwrite(142, 189.8, 'X');
if (!empty($detalle_colision_animal)) $pdf->wwrite(162, 189.8, 'X');

// croquis
$data = substr($siniestro['croquis_img-noupper'], 22);
if (base64_encode(base64_decode($data, true))===$data && imagecreatefrompng($siniestro['croquis_img-noupper'])) {
	$pdf->Image($siniestro['croquis_img-noupper'], 26, 202, 40, 36, 'png');
}

$text = iconv('UTF-8', 'windows-1252', $siniestro_detalle);
$pdf->SetXY(90, 203.6);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(115, 4.9, $text, 0, 'L', 0, 7);

$pdf->wwrite(20, 298, $lugar_denuncia);
if (isset($fecha_denuncia)) {
	$date = DateTime::createFromFormat("Y-m-d", $fecha_denuncia);
	$pdf->wwrite(90, 298, $date->format('d'));
	$pdf->wwrite(98, 298, $date->format('m'));
	$pdf->wwrite(106, 298, $date->format('Y'));
}
$pdf->wwrite(132, 298, $hora_denuncia);

$pdf->wwrite(141, 326.5, $asegurado_nombre);
$pdf->wwrite(141, 330, $asegurado_registro);

$pdf->AddPage();
$tplIdx = $pdf->importPage(3);
$pdf->useTemplate($tplIdx);

$pdf->wwrite(28, 36.5, $poliza_numero);
$pdf->wwrite(140, 36.5, $siniestro_numero);

for ($i = 0; $i < min(4, count($siniestro['lesiones_terceros'])); $i++) {
	$o = $i * 64.65;
	$lesiones_tercero = $siniestro['lesiones_terceros'][$i];
	
	$pdf->wwrite(35, 49.1+$o, $lesiones_tercero['nombre']);
	switch((string)$lesiones_tercero['sexo']) {
		case 1:
		$pdf->wwrite(195, 50+$o, 'X');
		break;
		case 2:
		$pdf->wwrite(185, 50+$o, 'X');
		break;
	}
	$pdf->wwrite(37, 55+$o, $lesiones_tercero['nro_doc']);
	$pdf->wwrite(160, 55+$o, $lesiones_tercero['tel']);
	$pdf->wwrite(25, 60.8+$o, $lesiones_tercero['calle'] . ' ' . $lesiones_tercero['altura']);
	$pdf->wwrite(174, 60.8+$o, $lesiones_tercero['cp']);
	$pdf->wwrite(25, 66.5+$o, $lesiones_tercero['localidad']);
	$pdf->wwrite(106, 66.5+$o, $lesiones_tercero['provincia']);
	$pdf->wwrite(30, 72+$o, $lesiones_tercero['estado_civil']);
	if (isset($lesiones_tercero['fecha_nac'])) {
		$date = DateTime::createFromFormat("Y-m-d", $lesiones_tercero['fecha_nac']);
		$pdf->wwrite(178, 72+$o, $date->format('d'));
		$pdf->wwrite(186, 72+$o, $date->format('m'));
		$pdf->wwrite(194, 72+$o, $date->format('Y'));
	}
	switch((string)$lesiones_tercero['relacion_asegurado']) {
		case 1:
		$pdf->wwrite(80, 78.6+$o, 'X');
		break;
		case 2:
		$pdf->wwrite(80, 84.2+$o, 'X');
		break;
		case 3:
		$pdf->wwrite(130, 78.6+$o, 'X');
		break;
		case 4:
		$pdf->wwrite(105, 84.2+$o, 'X');
		break;
	}
	switch((string)$lesiones_tercero['tipo_lesiones']) {
		case 1:
		$pdf->wwrite(56, 89.8+$o, 'X');
		break;
		case 2:
		$pdf->wwrite(106, 89.8+$o, 'X');
		break;
		case 3:
		$pdf->wwrite(135, 89.8+$o, 'X');
		break;
	}
	switch ((string)$lesiones_tercero['examen_alcoholemia']) {
		case '1':
		$pdf->wwrite(55, 95.6+$o, 'X');
		break;
		case '0':
		$pdf->wwrite(80, 95.6+$o, 'X');
		break;
		case '2':
		$pdf->wwrite(110, 95.6+$o, 'X');
		break;
	}
	$pdf->wwrite(34, 100.8+$o, $lesiones_tercero['centro_asistencial']);
}

$pdf->wwrite(20, 308, $lugar_denuncia);
if (isset($fecha_denuncia)) {
	$date = DateTime::createFromFormat("Y-m-d", $fecha_denuncia);
	$pdf->wwrite(90, 308, $date->format('d'));
	$pdf->wwrite(98, 308, $date->format('m'));
	$pdf->wwrite(106, 308, $date->format('Y'));
}
$pdf->wwrite(132, 308, $hora_denuncia);

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