<?php
	// CHECK INTERFACE (avoid being run by browser, either locally or on the live server)
	if (strtolower(php_sapi_name())!="cli") {
		// die("Error: Invalid interface.");
	}
?>
<?php
	// Require connection
	require_once('Connections/connection.php');
	// Require DB functions
	require_once('inc/db_functions.php');	
?>
<?php 
	// Extend execution time
	set_time_limit(600);	
?>
<?php
	
	$sql = "SELECT contacto_telefono2 numero, TRIM(concat_ws(' ', cliente_apellido, cliente_razon_social)) nombre, date_format(cuota_vencimiento, '%d/%m/%y') vencimiento, cuota_nro, cuota_monto, poliza_numero, subtipo_poliza_id, concat_ws(' ', 'Marca', automotor_marca_nombre) marca from cuota join poliza using (poliza_id) join cliente using (cliente_id) join contacto on contacto.cliente_id = cliente.cliente_id and contacto_default = 1 left join automotor using (poliza_id) left join automotor_marca using (automotor_marca_id)
		left join (endoso, endoso_tipo) on (poliza.poliza_id = endoso.poliza_id and endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id and endoso_tipo_grupo_id = 1)
		where
		poliza_estado_id in (3,4,7) and
		endoso_id is null and
		contacto_telefono2 is not null and contacto_telefono2 <> '' and
		cuota_estado_id = 1 and
		cuota_vencimiento = date(now()) + interval 1 day";
	
	$res = mysql_query($sql) or die(mysql_error());
	
	$mensajes = array();
	while ($row=mysql_fetch_assoc($res)) {
		$i = 0;
		while (true) {
			$nombre = substr($row['nombre'], 0, strlen($row['nombre'])-$i);
			$text = sprintf('Sr/a %s le recordamos que la fecha de vencimiento de su seguro es el dia %s Cuo.%s x $%s PZA.%s%s. Dusan Aseg', $nombre, $row['vencimiento'], $row['cuota_nro'], $row['cuota_monto'], $row['poliza_numero'], ($row['subtipo_poliza_id']==6?' '.$row['marca']:''));
			if (strlen($text)<=160) {
				break;
			}
			$i++;
		}
		$mensajes[] = $text;
	}
	echo implode("<br><br>", $mensajes);
?>