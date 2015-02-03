<?php
	$MM_authorizedUsers = "master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php
if (!empty($_GET['de']) and !empty($_GET['a'])) {
	$de = mysql_real_escape_string($_GET['de']);
	$a = mysql_real_escape_string($_GET['a']);
}
$files = array();
$zipname = 'temp/'.uniqid().'.zip';
$zip = new ZipArchive();
if($zip->open($zipname, ZIPARCHIVE::CREATE) !== true) {
	die('could not create zip file');
}

// Operaciones (ROS)

$sql = sprintf('SELECT p.productor_id, productor_matricula, productor_cuit, productor_nombre FROM libros_rubricados_ros lr JOIN productor p ON lr.productor_id = p.productor_id WHERE DATE(libros_rubricados_ros_fecha_registro) BETWEEN "%s" AND "%s" %s GROUP BY lr.productor_id', $de, $a, (!empty($_GET['productor'])?sprintf(' AND p.productor_id = "%s"', mysql_real_escape_string($_GET['productor'])):''));
$res = mysql_query($sql, $connection) or die(mysql_error());

while ($row = mysql_fetch_array($res)) {
	$sql = sprintf('SELECT * FROM libros_rubricados_ros WHERE DATE(libros_rubricados_ros_fecha_registro) BETWEEN "%s" AND "%s" AND productor_id=%s', $de, $a, $row[0]);
	$res2 = mysql_query($sql, $connection);
	$registros = mysql_num_rows($res2);
	$output = '<?xml version="1.0" encoding="utf-8"?>
<SSN>
	<Cabecera>
		<Version>1</Version>
		<Productor TipoPersona="1" Matricula="'.$row[1].'" CUIT="'.$row[2].'" />
		<CantidadRegistros>'.$registros.'</CantidadRegistros>
	</Cabecera>
	<Detalle>';
	while ($row2 = mysql_fetch_assoc($res2)) {
		$output .= '
		<Registro>
			<NroOrden>'.$row2['libros_rubricados_ros_nro_orden'].'</NroOrden>
			<FechaRegistro>'.$row2['libros_rubricados_ros_fecha_registro'].'</FechaRegistro>
			<Asegurados>
				<Asegurado TipoAsegurado="'.$row['libros_rubricados_ros_asegurado_tipo'].'" TipoDoc="'.$row['libros_rubricados_ros_asegurado_tipo_doc'].'" NroDoc="'.$row2['libros_rubricados_ros_asegurado_nro_doc'].'" Nombre="'.$row2['libros_rubricados_ros_asegurado_nombre'].'" />
			</Asegurados>
			<CPAProponente>'.$row2['libros_rubricados_ros_cpa_proponente'].'</CPAProponente>
			<ObsProponente>'.$row2['libros_rubricados_ros_obs_proponente'].'</ObsProponente>
			<CPACantidad>1</CPACantidad>
			<CodigosPostales>
				<CPA>'.$row2['libros_rubricados_ros_cpa'].'</CPA>
			</CodigosPostales>
			<CiaID>'.$row2['libros_rubricados_ros_cia_id'].'</CiaID>
			<BienAsegurado>'.$row2['libros_rubricados_ros_bien_asegurado'].'</BienAsegurado>
			<Ramo>'.$row2['libros_rubricados_ros_ramo'].'</Ramo>
			<SumaAsegurada>'.$row2['libros_rubricados_ros_suma_asegurada'].'</SumaAsegurada>
			<SumaAseguradaTipo>'.$row2['libros_rubricados_ros_suma_asegurada_tipo'].'</SumaAseguradaTipo>
			<Cobertura FechaDesde="'.$row2['libros_rubricados_ros_cobertura_desde'].'" FechaHasta="'.$row2['libros_rubricados_ros_cobertura_hasta'].'" />
			<Observacion Tipo="'.$row2['libros_rubricados_ros_tipo'].'" />
			<Flota>'.$row2['libros_rubricados_ros_flota'].'</Flota>
			<OperacionOrigen>'.$row['libros_rubricados_ros_operacion_origen'].'</OperacionOrigen>
		</Registro>';
	}
	$output .= '
	</Detalle>
</SSN>';
	$filename = 'ROS-'.str_replace(' ', '_', $row[3]).'-de-'.str_replace('-', '_', $de).'-a-'.str_replace('-', '_', $a).'.xml';
	$zip->addFromString($filename, $output);
}

// Cobranzas (RCR)

$sql = sprintf('SELECT p.productor_id, productor_matricula, productor_cuit, productor_nombre FROM libros_rubricados_rcr lr JOIN productor p ON lr.productor_id = p.productor_id WHERE DATE(libros_rubricados_rcr_fecha_registro) BETWEEN "%s" AND "%s" %s GROUP BY lr.productor_id', $de, $a, (!empty($_GET['productor'])?sprintf(' AND p.productor_id = "%s"', mysql_real_escape_string($_GET['productor'])):''));
$res = mysql_query($sql, $connection) or die(mysql_error());

while ($row = mysql_fetch_array($res)) {
	$sql = sprintf('SELECT * FROM libros_rubricados_rcr WHERE DATE(libros_rubricados_rcr_fecha_registro) BETWEEN "%s" AND "%s" AND productor_id=%s ORDER BY libros_rubricados_rcr_fecha_registro ASC', $de, $a, $row[0]);
	$res2 = mysql_query($sql, $connection);
	$registros = mysql_num_rows($res2);

	$output = '<?xml version="1.0" encoding="utf-8" ?>
<SSN>
	<Cabecera>
		<Version>1</Version>
		<Productor TipoPersona="1" Matricula="'.$row[1].'" CUIT="'.$row[2].'" />
		<CantidadRegistros>'.$registros.'</CantidadRegistros>
	</Cabecera>
	<Detalle>';
	while ($row2 = mysql_fetch_assoc($res2)) {
		$output .= '
		<Registro>
			<TipoRegistro>'.$row2['libros_rubricados_rcr_tipo_registro'].'</TipoRegistro>
			<FechaRegistro>'.date('d/m/Y', strtotime($row2['libros_rubricados_rcr_fecha_registro'])).'</FechaRegistro>
			<Concepto>'.$row2['libros_rubricados_rcr_concepto'].'</Concepto>
			<Polizas>';
			foreach (explode(',', $row2['libros_rubricados_rcr_polizas']) as $poliza) {
				$output .= '
				<Poliza>'.$poliza.'</Poliza>';
			}
			$output .= '
			</Polizas>
			<CiaID>'.$row2['libros_rubricados_rcr_cia_id'].'</CiaID>';
			if ($row2['libros_rubricados_rcr_organizador_flag']) {
				$output .= '
			<Organizador TipoPersona="'.$row2['libros_rubricados_rcr_organizador_tipo_persona'].'" Matricula="'.$row2['libros_rubricados_rcr_organizador_matricula'].'" CUIT="'.$row2['libros_rubricados_rcr_organizador_cuit'].'"/>';
			}
			$output .= '
			<Importe>'.number_format($row2['libros_rubricados_rcr_importe'], 2).'</Importe>
			<ImporteTipo>'.$row2['libros_rubricados_rcr_importe_tipo'].'</ImporteTipo>
			<NroRegistroAnulaModifica>'.$row['libros_rubricados_rcr_anula'].'</NroRegistroAnulaModifica>
		</Registro>';
	}
	$output .= '
	</Detalle>
</SSN>';
	$filename = 'RCR-'.str_replace(' ', '_', $row[3]).'-de-'.str_replace('-', '_', $de).'-a-'.str_replace('-', '_', $a).'.xml';
	$zip->addFromString($filename, $output);
}

$zip->close();

header('Content-type: application/zip, application/octet-stream');
header('Content-Disposition: attachment; filename="Libros_Rubricados-de-'.str_replace('-', '_', $de).'-a-'.str_replace('-', '_', $a).'.zip"');
readfile($zipname);
?>