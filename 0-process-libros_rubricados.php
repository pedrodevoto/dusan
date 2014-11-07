<?php
	$MM_authorizedUsers = "master";
?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');
?>
<?php

// Operaciones (ROS)

$items = array();

$sql = 'SELECT p.poliza_id, p.timestamp FROM poliza p LEFT JOIN libros_rubricados_ros lr ON p.poliza_id = lr.entidad_id JOIN (productor_seguro ps, productor pr) ON (p.productor_seguro_id = ps.productor_seguro_id AND ps.productor_id = pr.productor_id) WHERE libros_rubricados_ros_id IS NULL AND productor_exportar_lr = 1 AND poliza_numero <> "" AND poliza_entregada = 1 AND DATE(p.timestamp) < DATE(NOW())';
$sql .= ' AND subtipo_poliza_id = 6';
$res = mysql_query($sql, $connection) or die(mysql_error());
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
	$sql = 'SELECT ps.productor_id as productor_id, p.poliza_id as poliza_id, productor_matricula, productor_cuit, DATE(p.timestamp) as timestamp, cliente_tipo_persona, cliente_tipo_doc, IF(cliente_tipo_persona=1, cliente_nro_doc, CONCAT(cliente_cuit_0, cliente_cuit_1, cliente_cuit_2)) as cliente_nro_doc, IF(cliente_tipo_persona=1, TRIM(CONCAT(IFNULL(cliente_apellido, ""), " ", IFNULL(cliente_nombre, ""))), cliente_razon_social) as cliente_nombre, contacto_domicilio, contacto_nro, contacto_piso, contacto_dpto, localidad_nombre, localidad_cp, contacto_country, contacto_lote, seguro_codigo_lr, poliza_validez_desde, poliza_validez_hasta, poliza_renueva_num, poliza_flota, subtipo_poliza_id FROM poliza p JOIN (productor_seguro ps, seguro s, productor pr, cliente c, contacto co) ON (p.productor_seguro_id = ps.productor_seguro_id AND ps.seguro_id = s.seguro_id AND ps.productor_id = pr.productor_id AND p.cliente_id = c.cliente_id AND c.cliente_id = co.cliente_id AND contacto_default = 1) LEFT JOIN localidad l ON l.localidad_id = co.localidad_id';
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
	$libros_rubricados_ros_asegurado_nro_doc = intval($row['cliente_nro_doc']);
	$libros_rubricados_ros_asegurado_nombre = $row['cliente_nombre'];
	$libros_rubricados_ros_cpa_proponente = $row['contacto_cp'];
	$libros_rubricados_ros_obs_proponente = trim($row['contacto_domicilio'].' '.$row['contacto_nro'].' '.$row['contacto_piso'].' '.$row['contacto_dpto']).', '.trim($row['localidad_nombre'].' '.$row['localidad_cp'].' '.$row['contacto_country'].' '.$row['contacto_lote']);
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

// Cobranzas (RCR)

$sql = 'SELECT COALESCE(MAX(libros_rubricados_log_hasta), "2014-01-01") FROM libros_rubricados_log WHERE libros_rubricados_log_tipo = 2';
$res = mysql_query($sql, $connection);
$row = mysql_fetch_array($res);
$last_rcr = $row[0];

$items = array();

$sql = sprintf('SELECT cl.cuota_id, cuota_log_fecha, SUM(IF(cuota_log_tipo=1,1,-1)) as estado FROM cuota_log cl JOIN (cuota c, poliza p, productor_seguro ps, productor pr) ON (cl.cuota_id = c.cuota_id AND p.poliza_id = c.poliza_id AND p.productor_seguro_id = ps.productor_seguro_id AND ps.productor_id = pr.productor_id) WHERE DATE(cuota_log_fecha) BETWEEN DATE("%s")+INTERVAL 1 DAY AND DATE(NOW()) - INTERVAL 1 DAY AND p.timestamp IS NOT NULL AND productor_exportar_lr = 1 and subtipo_poliza_id = 6 GROUP BY cl.cuota_id HAVING estado<>0', $last_rcr);
$res = mysql_query($sql, $connection) or die(mysql_error());
while ($row = mysql_fetch_array($res)) {
	$items[] = array('type'=>$row[2], 'id'=>$row[0], 'timestamp'=>$row[1]);
}

usort($items, function($a, $b) {
    return strtotime($a['timestamp']) - strtotime($b['timestamp']);
});

foreach ($items as $item) {
	$sql = sprintf('SELECT ps.productor_id as productor_id, productor_matricula, productor_cuit, cuota_nro, cuota_recibo, poliza_cant_cuotas, poliza_numero, seguro_codigo_lr, organizador.organizador_id organizador_id, organizador_matricula, organizador_cuit, cuota_monto FROM cuota c JOIN (poliza p, productor_seguro ps, seguro s, productor pr) ON (p.poliza_id = c.poliza_id AND p.productor_seguro_id = ps.productor_seguro_id AND ps.seguro_id = s.seguro_id AND ps.productor_id = pr.productor_id) LEFT JOIN organizador USING (organizador_id) WHERE cuota_id = %s', $item['id']);
	
	$res = mysql_query($sql, $connection) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	
	$productor_id = $row['productor_id'];
	$cuota_id = $item['id'];
	$libros_rubricados_rcr_version = 1;
	$libros_rubricados_rcr_tipo_persona = 1;
	$libros_rubricados_rcr_matricula = $row['productor_matricula'];
	$libros_rubricados_rcr_cuit = $row['productor_cuit'];
	
	switch ($item['type']) {
		case 1:
		$libros_rubricados_rcr_tipo_registro = 1;
		$libros_rubricados_rcr_anula = '';
		break;
		case -1:
		$libros_rubricados_rcr_tipo_registro = 5;
		$libros_rubricados_rcr_anula = $row['cuota_recibo'];
		break;
	}
	
	$libros_rubricados_rcr_fecha_registro = $item['timestamp'];
	$libros_rubricados_rcr_concepto = sprintf('Cuota No %s/%s. Recibo No %s', $row['cuota_nro'], $row['poliza_cant_cuotas'], $row['cuota_recibo']);
	$libros_rubricados_rcr_polizas = $row['poliza_numero'];
	$libros_rubricados_rcr_cia_id = $row['seguro_codigo_lr'];
	$libros_rubricados_rcr_organizador_flag = (is_null($row['organizador_id'])?0:1);
	$libros_rubricados_rcr_organizador_tipo_persona = 1;
	$libros_rubricados_rcr_organizador_matricula = $row['organizador_matricula'];
	$libros_rubricados_rcr_organizador_cuit = $row['organizador_cuit'];
	$libros_rubricados_rcr_importe = $row['cuota_monto'];
	$libros_rubricados_rcr_importe_tipo = 1;
	
	$sql = sprintf('INSERT INTO libros_rubricados_rcr (productor_id, entidad_id, libros_rubricados_rcr_version, libros_rubricados_rcr_tipo_persona, libros_rubricados_rcr_matricula, libros_rubricados_rcr_cuit, libros_rubricados_rcr_tipo_registro, libros_rubricados_rcr_fecha_registro, libros_rubricados_rcr_concepto, libros_rubricados_rcr_polizas, libros_rubricados_rcr_cia_id, libros_rubricados_rcr_organizador_flag, libros_rubricados_rcr_organizador_tipo_persona, libros_rubricados_rcr_organizador_matricula, libros_rubricados_rcr_organizador_cuit, libros_rubricados_rcr_importe, libros_rubricados_rcr_importe_tipo, libros_rubricados_rcr_anula, libros_rubricados_rcr_rendicion_flag, timestamp)
	VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0, NOW())',
	GetSQLValueString($productor_id, 'int'),
	GetSQLValueString($cuota_id, 'int'),
	GetSQLValueString($libros_rubricados_rcr_version, 'int'),
	GetSQLValueString($libros_rubricados_rcr_tipo_persona, 'int'),
	GetSQLValueString($libros_rubricados_rcr_matricula, 'text'),
	GetSQLValueString($libros_rubricados_rcr_cuit, 'text'),
	GetSQLValueString($libros_rubricados_rcr_tipo_registro, 'int'),
	GetSQLValueString($libros_rubricados_rcr_fecha_registro, 'date'),
	GetSQLValueString($libros_rubricados_rcr_concepto, 'text'),
	GetSQLValueString($libros_rubricados_rcr_polizas, 'text'),
	GetSQLValueString($libros_rubricados_rcr_cia_id, 'int'),
	GetSQLValueString($libros_rubricados_rcr_organizador_flag, 'int'),
	GetSQLValueString($libros_rubricados_rcr_organizador_tipo_persona, 'int'),
	GetSQLValueString($libros_rubricados_rcr_organizador_matricula, 'text'),
	GetSQLValueString($libros_rubricados_rcr_organizador_cuit, 'text'),
	GetSQLValueString($libros_rubricados_rcr_importe, 'double'),
	GetSQLValueString($libros_rubricados_rcr_importe_tipo, 'int'),
	GetSQLValueString($libros_rubricados_rcr_anula, 'text')
	);
	
	mysql_query($sql, $connection) or die(mysql_error());
}

// Rendiciones (RCR)

$sql = 'SELECT * FROM libros_rubricados_rcr WHERE date(libros_rubricados_rcr_fecha_registro) < date(now()) and ((extract(day from now()) >= 25 and (extract(day from libros_rubricados_rcr_fecha_registro) <= 25 or extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now()))) or (extract(day from now() < 25 and extract(day from libros_rubricados_rcr_fecha_registro) < 25 and extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now())))) and libros_rubricados_rcr_rendicion_flag = 0';
$res = mysql_query($sql, $connection) or die(mysql_error());

$sql = 'INSERT INTO libros_rubricados_rcr (productor_id, entidad_id, libros_rubricados_rcr_version, libros_rubricados_rcr_tipo_persona, libros_rubricados_rcr_matricula, libros_rubricados_rcr_cuit, libros_rubricados_rcr_tipo_registro, libros_rubricados_rcr_fecha_registro, libros_rubricados_rcr_concepto, libros_rubricados_rcr_polizas, libros_rubricados_rcr_cia_id, libros_rubricados_rcr_organizador_flag, libros_rubricados_rcr_organizador_tipo_persona, libros_rubricados_rcr_organizador_matricula, libros_rubricados_rcr_organizador_cuit, libros_rubricados_rcr_importe, libros_rubricados_rcr_importe_tipo, libros_rubricados_rcr_anula, libros_rubricados_rcr_rendicion_flag, timestamp)
	SELECT productor_id, entidad_id, libros_rubricados_rcr_version, libros_rubricados_rcr_tipo_persona, libros_rubricados_rcr_matricula, libros_rubricados_rcr_cuit, 2, date_format(now() - interval IF(extract(day from now())<25,1,0) month, "%Y-%m-25"), libros_rubricados_rcr_concepto, libros_rubricados_rcr_polizas, libros_rubricados_rcr_cia_id, libros_rubricados_rcr_organizador_flag, libros_rubricados_rcr_organizador_tipo_persona, libros_rubricados_rcr_organizador_matricula, libros_rubricados_rcr_organizador_cuit, libros_rubricados_rcr_importe, libros_rubricados_rcr_importe_tipo, libros_rubricados_rcr_anula, libros_rubricados_rcr_rendicion_flag, NOW() FROM libros_rubricados_rcr WHERE date(libros_rubricados_rcr_fecha_registro) < date(now()) and ((extract(day from now()) >= 25 and (extract(day from libros_rubricados_rcr_fecha_registro) <= 25 or extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now()))) or (extract(day from now() < 25 and extract(day from libros_rubricados_rcr_fecha_registro) < 25 and extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now())))) and libros_rubricados_rcr_rendicion_flag = 0 and libros_rubricados_rcr_tipo_registro = 1';
mysql_query($sql, $connection) or die(mysql_error());

$sql = 'UPDATE libros_rubricados_rcr SET libros_rubricados_rcr_rendicion_flag = 1 WHERE date(libros_rubricados_rcr_fecha_registro) < date(now()) and ((extract(day from now()) >= 25 and (extract(day from libros_rubricados_rcr_fecha_registro) <= 25 or extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now()))) or (extract(day from now() < 25 and extract(day from libros_rubricados_rcr_fecha_registro) < 25 and extract(month from libros_rubricados_rcr_fecha_registro) < extract(month from now())))) and libros_rubricados_rcr_rendicion_flag = 0 and libros_rubricados_rcr_tipo_registro = 1';
mysql_query($sql, $connection) or die(mysql_error());

$sql = 'INSERT INTO libros_rubricados_log (libros_rubricados_log_tipo, libros_rubricados_log_hasta, timestamp) VALUES (1, DATE(NOW()-INTERVAL 1 DAY), NOW()), (2, DATE(NOW()-INTERVAL 1 DAY), NOW())';
mysql_query($sql, $connection) or die(mysql_error());

?>