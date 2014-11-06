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
		$sql = sprintf('SELECT CONCAT_WS(" ", cliente_nombre, cliente_apellido, cliente_razon_social) cliente_nombre, date_format(cliente_reg_vencimiento, "%%d/%%m/%%y") as cliente_nacimiento, group_concat(concat_ws(", ", contacto_telefono1, contacto_telefono2)) as telefonos from cliente join poliza using (cliente_id) left join contacto using (cliente_id) where cliente_reg_vencimiento = %s and poliza_estado_id in (3,4,7) group by cliente_id', GetSQLValueString($_GET['date'], 'date'));
		$res = mysql_query($sql, $connection) or die(mysql_error());
		while ($row = mysql_fetch_assoc($res)) {
			$output[] = $row;
		}
	}
	else {
		$sql = sprintf('SELECT cliente_reg_vencimiento, count(cliente_id) from cliente join poliza using (cliente_id) where cliente_reg_vencimiento between %s and %s and poliza_estado_id in (3,4,7) group by cliente_reg_vencimiento', GetSQLValueString($_GET['start'], 'date'), GetSQLValueString($_GET['end'], 'date'));
		$res = mysql_query($sql, $connection) or die(mysql_die());
		while ($row = mysql_fetch_array($res)) {
			$output[] = array('title'=>sprintf('Venc. registros (%s)', $row[1]), 'start'=>$row[0], 'id'=>'vencimientos_registro', 'titlePrefix'=>'Vencimientos de registros
				');
		}
	}
	echo json_encode($output);
	
	// Close Recordset: Main	
	mysql_free_result($res);
?>