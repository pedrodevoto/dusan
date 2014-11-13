<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-html.php'); ?>
<?php
require_once('Connections/connection.php');
require_once('inc/db_functions.php');

if (!empty($_GET['cliente_id'])) {
	$sql = sprintf('SELECT concat_ws(" ", cliente_apellido, cliente_nombre, cliente_razon_social, "-", "DNI:", cliente_nro_doc) cliente_nombre, date_format((select min(poliza_validez_desde) from poliza where poliza.cliente_id = cliente.cliente_id), "%%d/%%m/%%Y") inicio_cliente, sum(if(subtipo_poliza_id=6,1,0)) automotor, sum(if(subtipo_poliza_id not in(6,14),1,0)) otros_riesgos, sum(if(subtipo_poliza_id=14,1,0)) personas, timestampdiff(year, cliente_nacimiento, curdate()) edad, (select count(siniestros.id) from siniestros where siniestros.cliente_id = cliente.cliente_id) siniestros from cliente left join poliza using (cliente_id) where cliente_id = %s', GetSQLValueString($_GET['cliente_id'], 'int'));
}
$res = mysql_query($sql) or die(mysql_error());
$output = array();
if ($row=mysql_fetch_assoc($res)) {
	$output['nombre'] = $row['cliente_nombre'];
	$output['inicio'] = $row['inicio_cliente'];
	$output['cant_automotor'] = $row['automotor'];
	$output['cant_otros'] = $row['otros_riesgos'];
	$output['cant_personas'] = $row['personas'];
	$output['edad'] = $row['edad'];
	$output['cant_siniestros'] = $row['siniestros'];
}

// Timeline
$output['timeline'] = array();
$i = 0;
// Altas de poliza
$sql = sprintf('SELECT poliza_validez_desde, poliza_validez_hasta, poliza_numero, poliza_renueva_num from poliza where cliente_id = %s', GetSQLValueString($_GET['cliente_id'], 'int'));
$res = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($res)) {
	$i++;
	$output['timeline'][] = array('id'=>$i, 'content'=>'Alta PZA '.$row[2].(is_null($row[3])?'':' (Renueva PZA '.$row[3].')'), 'start'=>$row[0]);
	$i++;
	$output['timeline'][] = array('id'=>$i, 'content'=>'Vence PZA '.$row[2], 'start'=>$row[1]);
}
// Endosos
$sql = sprintf('SELECT endoso_fecha_pedido, poliza_numero, endoso_tipo_nombre, endoso_tipo_grupo_id from endoso join poliza using (poliza_id) join endoso_tipo using (endoso_tipo_id) where cliente_id = %s group by endoso_id', GetSQLValueString($_GET['cliente_id'], 'int'));
$res = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($res)) {
	$i++;
	$output['timeline'][] = array('id'=>$i, 'content'=>sprintf('%s (%s) PZA %s', ($row[3]==1?'Anulacion':'Endoso'), $row[2], $row[1]), 'start'=>$row[0]);
}
// Siniestros
$sql = sprintf('SELECT siniestros.`timestamp`, poliza_numero from siniestros join automotor using (automotor_id) join poliza using (poliza_id) where siniestros.cliente_id = %s', GetSQLValueString($_GET['cliente_id'], 'int'));
$res = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($res)) {
	$i++;
	$output['timeline'][] = array('id'=>$i, 'content'=>sprintf('Siniestro PZA %s', $row[1]), 'start'=>$row[0]);
}
echo json_encode($output);
?>