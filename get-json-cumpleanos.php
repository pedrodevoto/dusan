<?php
"-- select concat_ws(' ', cliente_apellido, cliente_nombre), cliente_nacimiento, group_concat(concat_ws(',', contacto_telefono1, contacto_telefono2)) from cliente left join contacto using (cliente_id) where date_format(cliente_nacimiento, '%m-%d') = date_format(now(), '%m-%d') group by cliente_id order by cliente_id, contacto_default desc;

select count(cliente_id), cliente_nacimiento from cliente where date_format(cliente_nacimiento, '%m-%d') between '09-01' and '09-23' group by date_format(cliente_nacimiento, '%m-%d');"	
?>
<?php
	$MM_authorizedUsers = "administrativo,master";
?>
<?php require_once('inc/security-ajax.php'); ?>
<?php
	// Require connection
	require_once('Connections/connection.php');	
	require_once('inc/db_functions.php');	
?>
<?php
	// Recordset: Main	
	$output = array();
	if (!empty($_GET['date'])) {
		$sql = sprintf('SELECT CONCAT_WS(" ", cliente_nombre, cliente_apellido, cliente_razon_social) cliente_nombre, date_format(cliente_nacimiento, "%%d/%%m/%%y") as cliente_nacimiento, group_concat(concat_ws(", ", contacto_telefono1, contacto_telefono2)) as telefonos from cliente join poliza using (cliente_id) left join contacto using (cliente_id) where date_format(cliente_nacimiento, "%%m-%%d") = date_format(%s, "%%m-%%d") and poliza_estado_id in (3,4,7) group by cliente_id', GetSQLValueString($_GET['date'], 'date'));
		error_log($sql);
		$res = mysql_query($sql, $connection) or die(mysql_error());
		while ($row = mysql_fetch_assoc($res)) {
			$output[] = $row;
		}
	}
	else {
		$sql = sprintf('SELECT date_format(cliente_nacimiento, concat_ws("-", date_format(%s, "%%Y"), "%%m-%%d")), count(cliente_id) from cliente join poliza using (cliente_id) where date_format(cliente_nacimiento, "%%m-%%d") between date_format(%s, "%%m-%%d") and date_format(%s, "%%m-%%d") and poliza_estado_id in (3,4,7) group by date_format(cliente_nacimiento, "%%m-%%d")', GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['end'], 'date'));
		$res = mysql_query($sql, $connection) or die(mysql_die());
		while ($row = mysql_fetch_array($res)) {
			$output[] = array('title'=>sprintf('Cumpleaños (%s)', $row[1]), 'start'=>$row[0], 'id'=>'cumpleanos', 'titlePrefix'=>'Cumpleaños');
		}
	}
	echo json_encode($output);
	
	// Close Recordset: Main	
	mysql_free_result($res);
?>