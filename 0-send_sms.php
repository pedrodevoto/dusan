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
	$mensajes = array();
	
	$sql = "SELECT contacto_telefono2 numero, TRIM(concat_ws(' ', cliente_apellido, cliente_razon_social)) nombre, date_format(cuota_vencimiento, '%d/%m/%y') vencimiento, cuota_nro, cuota_monto, poliza_numero, subtipo_poliza_id, concat_ws(' ', 'Marca', automotor_marca_nombre) marca, if(cuota_vencimiento = date(now()) - interval 1 day,1,null) vencida from cuota join poliza using (poliza_id) join cliente using (cliente_id) join contacto on contacto.cliente_id = cliente.cliente_id and contacto_default = 1 left join automotor using (poliza_id) left join automotor_marca using (automotor_marca_id)
		left join (endoso, endoso_tipo) on (poliza.poliza_id = endoso.poliza_id and endoso.endoso_tipo_id = endoso_tipo.endoso_tipo_id and endoso_tipo_grupo_id = 1)
		where
		poliza_estado_id in (3,4,7) and
		endoso_id is null and
		contacto_telefono2 is not null and contacto_telefono2 <> '' and
		cuota_estado_id = 1 and
		poliza_medio_pago = 'Directo' and
		cliente_sms_deuda = 1 and
		(cuota_vencimiento = date(now()) + interval 1 day or cuota_vencimiento = date(now()) - interval 1 day)";
	
	$res = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_assoc($res)) {
		$i = 0;
		while (true) {
			$nombre = substr($row['nombre'], 0, strlen($row['nombre'])-$i);
			$text = sprintf('Sr/a %s le recordamos que la fecha de vencimiento de su seguro %s el dia %s Cuo.%s x $%s PZA.%s%s. Dusan Aseg', $nombre, $row['vencida']?'fue':'es', $row['vencimiento'], $row['cuota_nro'], $row['cuota_monto'], $row['poliza_numero'], ($row['subtipo_poliza_id']==6?' '.$row['marca']:''));
			if (strlen($text)<=160 or $i==strlen($row['nombre'])) {
				break;
			}
			$i++;
		}
		$mensajes[] = sprintf("11%s\t11%s\t%s", $row['numero'], $row['numero'], $text);
	}

	$sql = "SELECT contacto_telefono2 numero, date_format(cliente_reg_vencimiento, '%d/%m/%y') vencimiento, TRIM(concat_ws(' ', cliente_nombre, cliente_apellido, cliente_razon_social)) nombre from cliente join contacto on contacto.cliente_id = cliente.cliente_id and contacto_default = 1 
		where 
		cliente_reg_vencimiento is not null and 
		contacto_telefono2 is not null and contacto_telefono2 <> '' and
		cliente_sms_registro = 1 and
		(cliente_reg_vencimiento = date(now()) + interval 30 day or cliente_reg_vencimiento = date(now()) + interval 10 day)";
	$res = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_assoc($res)) {
		$i = 0;
		while (true) {
			$nombre = substr($row['nombre'], 0, strlen($row['nombre'])-$i);
			$text = sprintf('Sr/a %s, de acuerdo a lo que figura en nuestra base de datos, su registro de conducir vence el dia %s. Dusan Aseg', $nombre, $row['vencimiento']);
			if (strlen($text)<=160 or $i==strlen($row['nombre'])) {
				break;
			}
			$i++;
		}
		$mensajes[] = sprintf("11%s\t11%s\t%s", $row['numero'], $row['numero'], $text);
	}
	
	$sql = "SELECT contacto_telefono2 numero, TRIM(concat_ws(' ', cliente_nombre, cliente_apellido, cliente_razon_social)) nombre from cliente join contacto on contacto.cliente_id = cliente.cliente_id and contacto_default = 1 
		where 
		cliente_nacimiento is not null and 
		contacto_telefono2 is not null and contacto_telefono2 <> '' and
		cliente_sms_cumpleanos = 1 and
		date_format(cliente_nacimiento, '%d-%m') = date_format(now(), '%d-%m')";
	$res = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_assoc($res)) {
		$i = 0;
		while (true) {
			$nombre = substr($row['nombre'], 0, strlen($row['nombre'])-$i);
			$text = sprintf('Estimado %s, en este dia tan especial Dusan Aseg. le desea Feliz Cumpleaños!!!', $nombre);
			if (strlen($text)<=160 or $i==strlen($row['nombre'])) {
				break;
			}
			$i++;
		}
		$mensajes[] = sprintf("11%s\t11%s\t%s", $row['numero'], $row['numero'], $text);
	}
	
	$fields = array(
		'USUARIO'			=> 'DUSANASEGURADOR',
		'CLAVE'				=> 'DUSANASEGURADOR979',
		'SEPARADORCAMPOS'	=> 'tab',
		'BLOQUE'			=> urlencode(implode("\n", $mensajes)),
		'TEST'				=> 1,
	);
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	// echo $fields_string;
	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL,"http://servicio.smsmasivos.com.ar/enviar_sms_bloque.asp");
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

	$result = curl_exec($ch);
	echo $result;
	curl_close($ch);
?>