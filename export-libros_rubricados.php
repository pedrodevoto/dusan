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
if (!empty($_GET['fecha'])) {
	$fecha = mysql_real_escape_string($_GET['fecha']);
}
$files = array();
$zipname = 'temp/'.uniqid().'.zip';
$zip = new ZipArchive();
if($zip->open($zipname, ZIPARCHIVE::CREATE) !== true) {
	die('could not create zip file');
}
$sql = sprintf('SELECT p.productor_id, productor_matricula, productor_cuit, productor_nombre FROM libros_rubricados_ros lr JOIN productor p ON lr.productor_id = p.productor_id WHERE DATE(timestamp) = "%s" GROUP BY lr.productor_id', $fecha);
$res = mysql_query($sql, $connection) or die(mysql_error());

while ($row = mysql_fetch_array($res)) {
	$sql = sprintf('SELECT * FROM libros_rubricados_ros WHERE DATE(timestamp) = "%s" AND productor_id=%s', $fecha, $row[0]);
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
	$filename = 'ROS-'.str_replace(' ', '_', $row[3]).'-'.str_replace('-', '_', $fecha).'.xml';
	$zip->addFromString($filename, $output);
}

$zip->close();

header('Content-type: application/zip, application/octet-stream');
header('Content-Disposition: attachment; filename="Libros_Rubricados-'.str_replace('-', '_', $fecha).'.zip"');
readfile($zipname);
?>