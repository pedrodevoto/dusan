<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
require_once('Connections/connection.php');
require_once('inc/db_functions.php');

$colors = ["#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"];

$output = array();

$output['coberturas'] = array();
$output['coberturas']['bar']['labels'] = array();
$output['coberturas']['bar']['data'] = array();
$output['coberturas']['pie'] = array();

$sql = sprintf('SELECT if(substring(seguro_cobertura_tipo_nombre, 1, 1) between "a" and "e", substring(seguro_cobertura_tipo_nombre, 1, 1), seguro_cobertura_tipo_nombre) letter, count(automotor_id)
	from seguro_cobertura_tipo join automotor using (seguro_cobertura_tipo_id) join poliza using (poliza_id)
	left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1)
	where poliza_estado_id in (2,3,4,7)
	and endoso_id is null
	and seguro_id = %s
	group by letter', GetSQLValueString($_GET['seguro_id'], 'int'));

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
	where poliza_estado_id in (2,3,4,7)
	and endoso_id is null
	and seguro_id = %s
	group by automotor_marca_id
	order by cant desc
	limit 10', GetSQLValueString($_GET['seguro_id'], 'int'));
$res = mysql_query($sql, $connection) or die(mysql_error());
end($colors);
while ($row = mysql_fetch_array($res)) {
	$output['marcas']['bar']['labels'][] = $row[0];
	$output['marcas']['bar']['data'][] = $row[1];
	$color = next($colors) or $color = reset($colors);
	$output['marcas']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
}

echo json_encode($output);

?>