<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
require_once('Connections/connection.php');
require_once('inc/db_functions.php');

$colors = array("#f45b5b", "#8085e9", "#8d4654", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee");

$output = array();

$estado = ($_GET['general_estado'] == 'vigente'?'and poliza_estado_id in (2,3,4,7)':'');


$output['polizas'] = array();
$output['polizas']['bar']['labels'] = array();
$output['polizas']['bar']['data'] = array();
$output['polizas']['pie'] = array();

$sql = sprintf('SELECT seguro_nombre, count(poliza.poliza_id) from poliza join productor_seguro using (productor_seguro_id) join seguro using (seguro_id)
	left join (endoso, endoso_tipo) ON (poliza.poliza_id = endoso.poliza_id AND endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id AND endoso_tipo_grupo_id = 1)
	where endoso_id is null
	%s
	group by seguro_id', $estado);

$res = mysql_query($sql, $connection) or die(mysql_error());
end($colors);
while ($row = mysql_fetch_array($res)) {
	$output['polizas']['bar']['labels'][] = $row[0];
	$output['polizas']['bar']['data'][] = $row[1];
	$color = next($colors) or $color = reset($colors);
	$output['polizas']['pie'][] = array('label'=>$row[0], 'value'=>(int)$row[1], 'color'=>$color, 'highlight'=>$color);
}
echo json_encode($output);

?>