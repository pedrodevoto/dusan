<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
require_once('Connections/connection.php');
require_once('inc/db_functions.php');

$colors = array("#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee");

$output = array();

$estado = ($_GET['estado'] == 'vigente'?'and poliza_estado_id in (2,3,4,7)':'');

if (!empty($_GET['type']) && $_GET['type']=='altas_bajas' && !empty($_GET['periodo'])) {
	$output['altas_bajas'] = array();
	$output['altas_bajas']['bar']['labels'] = array('Altas', 'Bajas');
	$output['altas_bajas']['bar']['data'] = array();
	$output['altas_bajas']['pie'] = array();
	$sql = sprintf('SELECT count(distinct poliza.poliza_id) from poliza join productor_seguro using (productor_seguro_id) left join (endoso, endoso_tipo) on (poliza.poliza_id = endoso.poliza_id and endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id and endoso_tipo_grupo_id = 1) 
		where date_format(poliza_validez_desde, "%%Y-%%m") = %s
		and subtipo_poliza_id = 6 
		and endoso_id is null
		and poliza_renueva_num is null
		and seguro_id = %s', GetSQLValueString($_GET['periodo'], 'text'), GetSQLValueString($_GET['seguro_id'], 'int'));
	end($colors);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	while ($row = mysql_fetch_array($res)) {
		$output['altas_bajas']['bar']['data'][0] = $row[0];
		$color = next($colors) or $color = reset($colors);
		$output['altas_bajas']['pie'][] = array('label'=>'Altas', 'value'=>(int)$row[0], 'color'=>$color, 'highlight'=>$color);
	}
	$sql = sprintf('SELECT count(distinct poliza.poliza_id) from poliza join productor_seguro using (productor_seguro_id) join (endoso, endoso_tipo) on (poliza.poliza_id = endoso.poliza_id and endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id and endoso_tipo_grupo_id = 1) 
		where date_format(poliza_validez_desde, "%%Y-%%m") = %s
		and subtipo_poliza_id = 6 
		and poliza_renueva_num is null
		and seguro_id = %s', GetSQLValueString($_GET['periodo'], 'text'), GetSQLValueString($_GET['seguro_id'], 'int'));
	$res = mysql_query($sql, $connection) or die(mysql_error());
	while ($row = mysql_fetch_array($res)) {
		$output['altas_bajas']['bar']['data'][1] = $row[0];
		$color = next($colors) or $color = reset($colors);
		$output['altas_bajas']['pie'][] = array('label'=>'Bajas', 'value'=>(int)$row[0], 'color'=>$color, 'highlight'=>$color);
	}
}
else {

	$output['coberturas'] = array();
	$output['coberturas']['bar']['labels'] = array();
	$output['coberturas']['bar']['data'] = array();
	$output['coberturas']['pie'] = array();

	$sql = sprintf('SELECT seguro_cobertura_tipo_nombre letter, count(automotor_id)
		from seguro_cobertura_tipo join automotor using (seguro_cobertura_tipo_id) join poliza using (poliza_id)
		left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1)
		where endoso_id is null
		and seguro_id = %s
		%s
		group by letter', GetSQLValueString($_GET['seguro_id'], 'int'), $estado);

	$res = mysql_query($sql, $connection) or die(mysql_error());
	end($colors);
	while ($row = mysql_fetch_array($res)) {
		$output['coberturas']['bar']['labels'][] = $row[0];
		$output['coberturas']['bar']['data'][] = $row[1];
		$color = next($colors) or $color = reset($colors);
		$output['coberturas']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
	}

	$output['marcas'] = array();
	$output['marcas']['bar']['labels'] = array();
	$output['marcas']['bar']['data'] = array();
	$output['marcas']['pie'] = array();

	$sql = sprintf('SELECT automotor_marca_nombre, count(automotor_id) as cant
		from automotor join automotor_marca using (automotor_marca_id) join poliza using (poliza_id) join productor_seguro using (productor_seguro_id)
		left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1)
		where endoso_id is null
		and seguro_id = %s
		%s
		group by automotor_marca_id
		order by cant desc
		limit 10', GetSQLValueString($_GET['seguro_id'], 'int'), $estado);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	end($colors);
	while ($row = mysql_fetch_array($res)) {
		$output['marcas']['bar']['labels'][] = $row[0];
		$output['marcas']['bar']['data'][] = $row[1];
		$color = next($colors) or $color = reset($colors);
		$output['marcas']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
	}

	$output['castigado'] = array();
	$output['castigado']['bar']['labels'] = array();
	$output['castigado']['bar']['data'] = array();
	$output['castigado']['pie'] = array();

	$sql = sprintf('SELECT IF(castigado=1,"Castigado", "No castigado"), count(automotor_id) as cant
		from automotor join poliza using (poliza_id) join productor_seguro using (productor_seguro_id)
		where seguro_id = %s
		%s
		group by castigado
		order by castigado desc', GetSQLValueString($_GET['seguro_id'], 'int'), $estado);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	end($colors);
	while ($row = mysql_fetch_array($res)) {
		$output['castigado']['bar']['labels'][] = $row[0];
		$output['castigado']['bar']['data'][] = $row[1];
		$color = next($colors) or $color = reset($colors);
		$output['castigado']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
	}

	$output['gnc'] = array();
	$output['gnc']['bar']['labels'] = array();
	$output['gnc']['bar']['data'] = array();
	$output['gnc']['pie'] = array();

	$sql = sprintf('SELECT IF(gnc_flag=1,"Tiene GNC", "No tiene GNC"), count(automotor_id) as cant
		from automotor join poliza using (poliza_id) join productor_seguro using (productor_seguro_id)
		where seguro_id = %s
		%s
		group by gnc_flag
		order by gnc_flag desc', GetSQLValueString($_GET['seguro_id'], 'int'), $estado);
	$res = mysql_query($sql, $connection) or die(mysql_error());
	end($colors);
	while ($row = mysql_fetch_array($res)) {
		$output['gnc']['bar']['labels'][] = $row[0];
		$output['gnc']['bar']['data'][] = $row[1];
		$color = next($colors) or $color = reset($colors);
		$output['gnc']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
	}

	$output['renovadas'] = array();
	$output['renovadas']['bar']['labels'] = array('Renovadas %', 'No renovadas %');
	$output['renovadas']['bar']['data'] = array();
	$output['renovadas']['pie'] = array();

	$sql = sprintf('SELECT SUM(IF(ren.poliza_id is null, 0, 1)) as renovadas, SUM(IF(ren.poliza_id is null, 1, 0)) as no_renovadas
		from poliza p
		join productor_seguro using (productor_seguro_id)
		left join poliza ren on p.poliza_numero = ren.poliza_renueva_num
		where p.poliza_validez_hasta < date(now()) 
		and p.subtipo_poliza_id = 6 
		and seguro_id = %s
		', GetSQLValueString($_GET['seguro_id'], 'int'));
	$res = mysql_query($sql, $connection) or die(mysql_error());
	end($colors);
	while ($row = mysql_fetch_array($res)) {
		$total = $row[0] + $row[1];
		$renovadas = round($row[0] / $total * 100, 2);
		$no_renovadas = round($row[1] / $total * 100, 2);
		$output['renovadas']['bar']['data'][0] = $renovadas;
		$output['renovadas']['bar']['data'][1] = $no_renovadas;
		$color = next($colors) or $color = reset($colors);
		$output['renovadas']['pie'][0] = array('label'=>'Renovadas %', 'value'=>$renovadas, 'color'=>$color, 'highlight'=>$color);
		$color = next($colors) or $color = reset($colors);
		$output['renovadas']['pie'][1] = array('label'=>'No renovadas %', 'value'=>$no_renovadas, 'color'=>$color, 'highlight'=>$color);
	}
}

echo json_encode($output);

?>