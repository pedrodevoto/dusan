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
$items = array();

$sql = 'SELECT p.poliza_id, p.timestamp FROM poliza p LEFT JOIN libros_rubricados_ros lr ON p.poliza_id = lr.entidad_id JOIN (productor_seguro ps, productor pr) ON (p.productor_seguro_id = ps.productor_seguro_id AND ps.productor_id = pr.productor_id) WHERE libros_rubricados_ros_id IS NULL AND productor_exportar_lr = 1 AND poliza_numero <> "" AND poliza_entregada = 1 AND DATE(p.timestamp) < DATE(NOW())';
$res = mysql_query($sql, $connection);
while ($row = mysql_fetch_array($res)) {
	$items[] = array('type'=>'poliza', 'id'=>$row[0], 'timestamp'=>$row[1]);
}

$sql = 'SELECT endoso_id, e.timestamp, endoso_tipo_grupo_id FROM endoso e LEFT JOIN libros_rubricados_ros lr ON e.endoso_id = lr.entidad_id JOIN (poliza p, productor_seguro ps, productor pr, endoso_tipo et) ON (e.poliza_id = p.poliza_id AND p.productor_seguro_id = ps.productor_seguro_id AND ps.productor_id = pr.productor_id AND e.endoso_tipo_id = et.endoso_tipo_id) WHERE libros_rubricados_ros_id IS NULL AND productor_exportar_lr = 1 AND endoso_numero <> "" AND endoso_completo = 1 AND DATE(e.timestamp) < DATE(NOW())';
$res = mysql_query($sql, $connection);
while ($row = mysql_fetch_array($res)) {
	$items[] = array('type'=>($row[2]==1?'anulacion':'endoso'), 'id'=>$row[0], 'timestamp'=>$row[1]);
}

usort($items, function($a, $b) {
    return strtotime($a['timestamp']) - strtotime($b['timestamp']);
});

foreach ($items as $item) {
	$sql = 'SELECT ps.productor_id as productor_id, p.poliza_id as poliza_id, productor_matricula, productor_cuit, DATE(p.timestamp) as timestamp, cliente_tipo_persona, cliente_tipo_doc, IF(cliente_tipo_persona=1, cliente_nro_doc, CONCAT(cliente_cuit_0, cliente_cuit_1, cliente_cuit_2)) as cliente_nro_doc, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ""), " ", IFNULL(cliente_nombre, ""))), cliente_razon_social) as cliente_nombre, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, contacto_localidad, contacto_cp, contacto_country, contacto_lote, seguro_codigo_lr, poliza_validez_desde, poliza_validez_hasta, poliza_renueva_num, poliza_flota, subtipo_poliza_id FROM poliza p JOIN (productor_seguro ps, seguro s, productor pr, cliente c, contacto co) ON (p.productor_seguro_id = ps.productor_seguro_id AND ps.seguro_id = s.seguro_id AND ps.productor_id = pr.productor_id AND p.cliente_id = c.cliente_id AND c.cliente_id = co.cliente_id AND contacto_default = 1)';
	switch ($item['type']) {
		case 'poliza':
		$sql .= ' WHERE p.poliza_id = '.$item['id'];
		break;
		case 'endoso':
		case 'anulacion':
		$sql .= ' JOIN endoso e ON p.poliza_id = e.poliza_id WHERE e.endoso_id = '.$item['id'];
		break;
	}
	$res = mysql_query($sql, $connection);
	$row = mysql_fetch_assoc($res);
	
	$productor_id = $row['productor_id'];
	$poliza_id = $row['poliza_id'];
	$entidad_id = $item['id'];
	$libros_rubricados_ros_version = 1;
	$libros_rubricados_ros_tipo_persona = 1;
	$libros_rubricados_ros_matricula = $row['productor_matricula'];
	$libros_rubricados_ros_cuit = $row['productor_cuit'];
	
	// Determinar número de orden
	$sql = 'SELECT COALESCE(MAX(libros_rubricados_ros_nro_orden)+1, productor_lr_numeracion, 1) FROM productor p LEFT JOIN libros_rubricados_ros lr ON lr.productor_id = p.productor_id WHERE p.productor_id = '.$productor_id;
	$res2 = mysql_query($sql, $connection);
	$row2 = mysql_fetch_array($res2);
	
	$libros_rubricados_ros_nro_orden = $row2[0];
	$libros_rubricados_ros_fecha_registro = $item['timestamp'];
	$libros_rubricados_ros_asegurado_tipo = $row['cliente_tipo_persona'];
	$libros_rubricados_ros_asegurado_tipo_doc = ($row['cliente_tipo_persona']==2?2:$row['cliente_tipo_doc']=='DNI'?1:$row['cliente_tipo_doc']=='Pasaporte'?4:$row['cliente_tipo_doc']=='LC'?5:$row['cliente_tipo_doc']=='LE'?6:3);
	$libros_rubricados_ros_asegurado_nro_doc = $row['cliente_nro_doc'];
	$libros_rubricados_ros_asegurado_nombre = $row['cliente_nombre'];
	$libros_rubricados_ros_cpa_proponente = $row['contacto_cp'];
	$libros_rubricados_ros_obs_proponente = trim($row['contacto_domicilio'].' '.$row['contacto_nro'].' '.$row['contacto_piso'].' '.$row['contacto_dpto']).', '.trim($row['contacto_localidad'].' '.$row['contacto_cp'].' '.$row['contacto_country'].' '.$row['contacto_lote']);
	$libros_rubricados_ros_cpa = $row['contacto_cp'];
	$libros_rubricados_ros_cia_id = $row['seguro_codigo_lr'];
	
	switch ($row['subtipo_poliza_id']) {
		case 6:
		$sql = 'SELECT * FROM automotor WHERE poliza_id = '.$row['poliza_id'];
		$res3 = mysql_query($sql, $connection);
		$row3 = mysql_fetch_assoc($res3);
		
		$libros_rubricados_ros_bien_asegurado = $row3['patente_0'].$row3['patente_1'];
		$libros_rubricados_ros_ramo = 32;
		$libros_rubricados_ros_suma_asegurada = $row3['valor_total'];
		break;
		default:
		
		break;
	}
	$libros_rubricados_ros_suma_asegurada_tipo = 1;
	$libros_rubricados_ros_cobertura_desde = $row['poliza_validez_desde'];
	$libros_rubricados_ros_cobertura_hasta = $row['poliza_validez_hasta'];
	switch ($item['type']) {
		case 'poliza':
		if ($row['poliza_renueva_num']>0) {
			$libros_rubricados_ros_tipo = 2;
		}
		else {
			$libros_rubricados_ros_tipo = 1;
		}
		break;
		case 'endoso':
		$libros_rubricados_ros_tipo = 3;
		break;
		case 'anulacion':
		$libros_rubricados_ros_tipo = 4;
		break;
	}
	$libros_rubricados_ros_flota = ($row['poliza_flota']==1?1:0);
	$libros_rubricados_ros_operacion_origen = 1;
	
	$sql = sprintf('INSERT INTO libros_rubricados_ros (productor_id, poliza_id, entidad_id, libros_rubricados_ros_version, libros_rubricados_ros_tipo_persona, libros_rubricados_ros_matricula, libros_rubricados_ros_cuit, libros_rubricados_ros_nro_orden, libros_rubricados_ros_fecha_registro, libros_rubricados_ros_asegurado_tipo, libros_rubricados_ros_asegurado_tipo_doc, libros_rubricados_ros_asegurado_nro_doc, libros_rubricados_ros_asegurado_nombre, libros_rubricados_ros_cpa_proponente, libros_rubricados_ros_obs_proponente, libros_rubricados_ros_cpa, libros_rubricados_ros_cia_id, libros_rubricados_ros_bien_asegurado, libros_rubricados_ros_ramo, libros_rubricados_ros_suma_asegurada, libros_rubricados_ros_suma_asegurada_tipo, libros_rubricados_ros_cobertura_desde, libros_rubricados_ros_cobertura_hasta, libros_rubricados_ros_tipo, libros_rubricados_ros_flota, libros_rubricados_ros_operacion_origen, timestamp)
					VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", NOW())',
	$productor_id,
	$poliza_id,
	$entidad_id,
	$libros_rubricados_ros_version,
	$libros_rubricados_ros_tipo_persona,
	$libros_rubricados_ros_matricula,
	$libros_rubricados_ros_cuit, 
	$libros_rubricados_ros_nro_orden, 
	$libros_rubricados_ros_fecha_registro,
	$libros_rubricados_ros_asegurado_tipo,
	$libros_rubricados_ros_asegurado_tipo_doc,
	$libros_rubricados_ros_asegurado_nro_doc,
	$libros_rubricados_ros_asegurado_nombre,
	$libros_rubricados_ros_cpa_proponente,
	$libros_rubricados_ros_obs_proponente,
	$libros_rubricados_ros_cpa,
	$libros_rubricados_ros_cia_id,
	$libros_rubricados_ros_bien_asegurado,
	$libros_rubricados_ros_ramo,
	$libros_rubricados_ros_suma_asegurada,
	$libros_rubricados_ros_suma_asegurada_tipo,
	$libros_rubricados_ros_cobertura_desde,
	$libros_rubricados_ros_cobertura_hasta,
	$libros_rubricados_ros_tipo,
	$libros_rubricados_ros_flota,
	$libros_rubricados_ros_operacion_origen);

	$res4 = mysql_query($sql, $connection) or die(mysql_error());
}

?>